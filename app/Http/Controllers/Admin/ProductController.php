<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPriceHistory;
use Illuminate\Http\Request;

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
                // 'image_url',
                'price' => $request->price,
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

            $priceHasChanged = $product->price != $request->price ? true : false;

            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->is_featured = $request->input('is_featured', 'off') == 'on' ? 1 : 0;
            $product->is_active = $request->input('is_active', 'off') == 'on' ? 1 : 0;

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
}
