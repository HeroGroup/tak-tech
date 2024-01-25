<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\DiscountDetail;
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
        return view('admin.discount.create');
    }

    public function store(Request $request)
    {
        try {
            Discount::create([
                'code' => generateDiscountCode(),
                'title' => $request->title,
                'description' => $request->description,
                'discount_percent' => $request->discount_percent,
                'fixed_amount' => $request->fixed_amount,
                'expire_date' => $request->expire_date,
                'capacity' => $request->capacity,
                'for_user' => $request->for_user,
            ]);

            return back()->with('message', 'Discount created successfully.')->with('type', 'success');
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function show(Discount $discount)
    {
        return view('admin.discount.show', compact('discount'));
    }

    public function edit(Discount $discount)
    {
        return view('admin.discount.edit', compact('discount'));
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
            $discount->is_active = $request->is_active;
            $discount->save();

            return back()->with('message', 'Discount updated successfully.')->with('type', 'success');
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function destroy(Discount $discount)
    {
        try {
            $discount->delete();

            return back()->with('message', 'Discount deleted successfully.')->with('type', 'success');
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }
}
