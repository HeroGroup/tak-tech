<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPriceHistory;
use App\Models\Service;
use Illuminate\Http\Request;

require_once app_path('Helpers/utils.php');

class ProductController extends Controller
{
    private $base_product_image_path = 'resources/assets/img/products/';

    public function index()
    {
        try {
            $products = Product::all();
            $categories = Category::where('is_active', 1)->get()->pluck('title','id')->toArray();
            return view('admin.products', compact('products', 'categories'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function store(Request $request)
    {
        // TODO: sanitize
        // TODO: validation
        try {
            $product = Product::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'period' => $request->period,
                'iType' => $request->iType,
                'allowed_traffic' => $request->allowed_traffic,
                'maximum_connections' => $request->maximum_connections,
            ]);

            ProductPriceHistory::create([
                'product_id' => $product->id,
                'price' => $request->price,
            ]);

            if($request->has('categories')) {
                $categories = $request->categories;
                foreach($categories as $category) {
                    ProductCategory::create([
                        'product_id' => $product->id,
                        'category_id' => $category
                    ]);
                }
            }

            if($request->hasFile('photo')) {
                $document = $request->photo;
                $fileName = time() . '-' . $document->getClientOriginalName();
                $document->move($this->base_product_image_path, $fileName);
                $photoUrl = '/' . $this->base_product_image_path . $fileName;

                $product->image_url = $photoUrl;
                $product->save();
            }

            return back()->with('message', 'New product created successfully.')->with('type', 'success');
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // TODO: sanitize
            // TODO: validation
            $product = Product::find($id);

            if ($product) {
                $priceHasChanged = $product->price != $request->price ? true : false;

                $product->title = $request->title;
                $product->description = $request->description;
                $product->price = $request->price;
                $product->is_featured = $request->input('is_featured', 'off') == 'on' ? 1 : 0;
                $product->is_active = $request->input('is_active', 'off') == 'on' ? 1 : 0;
                $product->period = $request->period;
                $product->iType = $request->iType;
                $product->allowed_traffic = $request->allowed_traffic;
                $product->maximum_connections = $request->maximum_connections;

                $product->save();

                if ($priceHasChanged) {
                    ProductPriceHistory::create([
                        'product_id' => $product->id,
                        'price' => $request->price,
                    ]);
                }

                if($request->has('categories')) {
                    ProductCategory::where('product_id', $product->id)->delete();
                    
                    $categories = $request->categories;
                    foreach($categories as $category) {
                        ProductCategory::create([
                            'product_id' => $product->id,
                            'category_id' => $category
                        ]);
                    }
                }

                if($request->hasFile('photo')) {
                    $document = $request->photo;
                    $fileName = time() . '-' . $document->getClientOriginalName();
                    $document->move($this->base_product_image_path, $fileName);
                    $product->image_url = '/' . $this->base_product_image_path . $fileName;

                    $product->save();
                }

                return back()->with('message', 'Product updated successfully.')->with('type', 'success');
            } else {
                return back()->with('message', 'invalid product')->with('type', 'danger');
            }
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function destroy($id)
    {
        try {
            Product::destroy($id);
            return $this->success('Product removed successfully.');
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function importServices($id) // get
    {
        return view('admin.importServices', compact('id'));
    }

    public function importServicesAction(Request $request) // post
    {
        try {
            if (! $request->id) {
                return back()->with('message', 'invalid product id')->with('type', 'danger');
            }
            if (! $request->hasFile('csvFile')) {
                return back()->with('message', 'invalid data file')->with('type', 'danger');
            }
            if (! $request->hasFile('zipFile')) {
                return back()->with('message', 'invalid config files')->with('type', 'danger');
            }

            $unzipResult = unzipFiles($request->zipFile, resource_path('/confs'));
            if ($unzipResult['status'] == -1) {
                return back()->with('message', $unzipResult['message'])->with('type', 'danger');
            }

            $csv = $request->csvFile;
            // proccess csv file
            $count = 0;
            if (($handle = fopen($csv, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $qr_name = basename($data[4]);
                    $conf_name = basename($data[5]);
                    if (Service::where('panel_peer_id', $data[0])->count() == 0) {
                        Service::create([
                            'product_id' => $request->id,
                            'panel_peer_id' => $data[0],
                            'is_enabled' => $data[1],
                            'note' => $data[2],
                            'expire_days' => $data[3],
                            'qr_file' => resource_path("/confs/$qr_name"),
                            'conf_file' => resource_path("/confs/$conf_name"),
                        ]);
                        $count++;
                    }
                }
                fclose($handle);
            }

            return back()->with('message', "$count Services imported successfully!")->with('type', 'success');
        } catch (\Exception $excpetion) {
            return back()->with('message', $excpetion->getLine() . ': ' . $excpetion->getMessage())->with('type', 'danger');
        }
    }
}
