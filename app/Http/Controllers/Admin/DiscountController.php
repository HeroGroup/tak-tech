<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;

use App\Http\Controllers\Controller;

use App\Models\Discount;
use App\Models\DiscountDetail;
use App\Models\Order;
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

    public function checkDiscountCode($code, $returnType='json') {
        try {
            if (! auth()->user()) {
                if ($returnType == 'array') {
                    return ['status' => -1];
                }
                return $this->fail('جهت استفاده از کد تخفیف، ابتدا لاگین کنید.');
            }

            $discount = Discount::where('code', $code)->first();
            if ($discount) {
                // check if discount is active
                if (! $discount->is_active) {
                    if ($returnType == 'array') {
                        return ['status' => -1];
                    }
                    return $this->fail('کد تخفیف نامعتبر است.', ['is_active' => $discount->is_active]);
                }

                // check if user has used this code before
                $hasUsed = Order::where(['user_id' => auth()->user()->id, 'discount_id' => $discount->id, 'status' => OrderStatus::PAYMENT_SUCCESSFUL->value])->count();
                if ($hasUsed > 0) {
                    if ($returnType == 'array') {
                        return ['status' => -1];
                    }
                    return $this->fail('از این کد قبلا استفاده کرده اید.');
                }
                
                // check date
                if ($discount->expire_date && $discount->expire_date < now()) {
                    if ($returnType == 'array') {
                        return ['status' => -1];
                    }
                    return $this->fail('اعتبار این کد به پایان رسیده است.', ['expires' => $discount->expire_date]);
                }
                
                // check capacity
                if ($discount->capacity && $discount->used >= $discount->capacity) {
                    if ($returnType == 'array') {
                        return ['status' => -1];
                    }
                    return $this->fail('اعتبار این کد به پایان رسیده است.', ['capacity' => $discount->capacity, 'used' => $discount->used]);
                }
                
                // check for appropriate user
                if ($discount->for_user && $discount->for_user != auth()->user()->id) {
                    if ($returnType == 'array') {
                        return ['status' => -1];
                    }
                    return $this->fail('کد تخفیف نامعتبر است.', ['for_user' => $discount->for_user]);
                }

                $returnData = ['discount' => $discount];

                $discountDetails = DiscountDetail::where('discount_id', $discount->id)->get();

                if (count($discountDetails) > 0) {
                    $returnData['discountDetails'] = $discountDetails;
                }

                if ($returnType == 'array') {
                    return ['status' => 1, 'data' => $returnData];
                }
                return $this->success('کد تخفیف با موفقیت اعمال شد.', $returnData);
            } else {
                if ($returnType == 'array') {
                    return ['status' => -1];
                }
                return $this->fail('کد تخفیف نامعتبر است.', ['code' => $code]);
            }
        } catch (\Exception $exception) {
            if ($returnType == 'array') {
                return ['status' => -1];
            }
            return $this->fail($exception->getMessage());
        }
    }
}
