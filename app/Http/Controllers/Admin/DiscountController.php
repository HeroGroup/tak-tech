<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\DiscountDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

require_once app_path('/Helpers/utils.php');

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();

        return view('admin.discount.index', compact('discounts'));
    }

    public function create()
    {
        $code = generateDiscountCode();
        $users = User::where('is_active', 1)->pluck('email', 'id')->toArray();
        $products = Product::where('is_active', 1)->pluck('title', 'id')->toArray();

        return view('admin.discount.create', compact('code', 'users', 'products'));
    }

    public function createDiscountDetailsFromRequest($discount_id, $request_product_id, $request_product_discount_percent, $request_product_fixed_amount)
    {
        $request_product_id_count = count($request_product_id);
        if ($request_product_id_count > 0) {
            for($i=0; $i<$request_product_id_count; $i++) {
                if ($request_product_id[$i] && 
                    ($request_product_discount_percent[$i] || 
                    $request_product_fixed_amount[$i])) {
                    // 
                    DiscountDetail::create([
                        'discount_id' => $discount_id,
                        'product_id' => $request_product_id[$i],
                        'discount_percent' => $request_product_discount_percent[$i],
                        'fixed_amount' => $request_product_fixed_amount[$i]
                    ]);
                }
            }
        }
    }

    public function store(Request $request)
    {
        try {
            $discount = Discount::create([
                'code' => $request->code,
                'title' => $request->title,
                'description' => $request->description,
                'discount_percent' => $request->discount_percent,
                'fixed_amount' => $request->fixed_amount,
                'expire_date' => $request->expire_date,
                'capacity' => $request->capacity,
                'for_user' => $request->for_user,
            ]);

            $this->createDiscountDetailsFromRequest(
                $discount->id,
                $request->product_id,
                $request->product_discount_percent,
                $request->product_fixed_amount
            );

            return back()->with('message', 'Discount created successfully.')->with('type', 'success');
        } catch (\Exception $exception) {
            return back()->withInput()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function edit(Discount $discount)
    {
        $discountDetails = DiscountDetail::where('discount_id', $discount->id)->get();
        $users = User::where('is_active', 1)->pluck('email', 'id')->toArray();
        $products = Product::where('is_active', 1)->pluck('title', 'id')->toArray();

        return view('admin.discount.edit', compact('discount', 'discountDetails', 'users', 'products'));
    }

    public function update(Request $request, Discount $discount)
    {
        try {
            $discount->title = $request->title;
            $discount->description = $request->description;
            $discount->discount_percent = $request->discount_percent;
            $discount->fixed_amount = $request->fixed_amount;
            $discount->expire_date = $request->expire_date;
            $discount->capacity = $request->capacity;
            $discount->for_user = $request->for_user;
            $discount->is_active = $request->is_active == "on" ? 1 : 0;
            $discount->save();
            
            DiscountDetail::where('discount_id', $discount->id)->delete();
            $this->createDiscountDetailsFromRequest(
                $discount->id,
                $request->product_id,
                $request->product_discount_percent,
                $request->product_fixed_amount
            );

            return back()->with('message', 'Discount updated successfully.')->with('type', 'success');
        } catch (\Exception $exception) {
            return back()->withInput()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function destroy(Discount $discount)
    {
        try {
            DiscountDetail::where('discount_id', $discount->id)->delete();
            $discount->delete();

            return $this->success('Discount deleted successfully.');
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }
}
