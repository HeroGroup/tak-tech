<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\TransactionType;
use App\Enums\TransactionReason;
use App\Enums\TransactionStatus;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserCart;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Hash;

require_once __DIR__.'/../../Helpers/utils.php';

class SiteController extends Controller
{
    public function index() {
        try {
            $products = Product::where('is_active', 1)->get();
            $cart = "{}";

            if (auth()->user()) {
                $userCart = UserCart::where('user_id', auth()->user()->id)->first(['cart']);
                if ($userCart) {
                    $cart = $userCart->cart;
                }
            }
            
            return view('site.index', compact('products', 'cart'));
        } catch (\Exeption $exception) {
            return $this->fail($exception->getMessage());
        }
        
    }

    public function addToCart(Request $request) {
        try {
            $userId = auth()->user()?->id;
            if ($userId) {
                // $cart = json_decode($request->cart, true);
                $userCart = UserCart::updateOrCreate(
                    ['user_id' => $userId],
                    ['cart' => $request->cart]
                );

                return $this->success('cart updated successfully.');
            } else {
                return $this->fail('invalid user');
            }
        } catch (\Exeption $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function submitOrder(Request $request) {
        $uid = generateUID();
        $status = 'fail';
        $order = Order::create([
            'uid' => $uid
        ]);

        try {
            $basePriceSum = 0;
            $finalPriceSum = 0;
            
            $cart = json_decode($request->cart);

            foreach($cart as $key => $value) {
                // $key is product_id
                $product = Product::find($key);
                $count = $value->count;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'count' => $count,
                    'product_title' => $product->title,
                    'product_description' => $product->description,
                    'product_base_price' => $product->price,
                    'product_final_price' => $product->price,
                ]);

                $basePriceSum += $product->price * $count;
                // if discount code exists, 
                // from discount code details find corresponding discount fee
                // and update product_final_price
                $finalPriceSum += $product->price * $count;
            }
            
            // unreal transactions
            $chargeTransaction = new Transaction;
            $chargeTransaction->title = 'شارژ کیف پول';
            $chargeTransaction->amount = $finalPriceSum;
            $chargeTransaction->type = TransactionType::INCREASE->value;
            $chargeTransaction->reason = TransactionReason::CHARGE->value;
            $chargeTransaction->status = TransactionStatus::PAYMENT_SUCCESSFUL->value;

            $payTransaction = new Transaction;
            $payTransaction->title = 'پرداخت سفارش '.$order->uid;
            $payTransaction->amount = $finalPriceSum;
            $payTransaction->type = TransactionType::DECREASE->value;
            $payTransaction->reason = TransactionReason::PAYMENT->value;
            $payTransaction->status = TransactionStatus::PAYMENT_SUCCESSFUL->value;

            if (auth()->user()) {
                $userId = auth()->user()->id;

                $order->user_id = $userId;
                $chargeTransaction->user_id = $userId;
                $payTransaction->user_id = $userId;
                
                // delete cart from db
                UserCart::where('user_id', $userId)->delete();

                // check if first purchase and user has invitee
                if (auth()->user()->invitee) {
                    $numberOfOrders = Order::where(['user_id' => $userId, 'status' => OrderStatus::PAYMENT_SUCCESSFUL->value])->count();
                    if ($numberOfOrders == 0) {
                        // find invitee and give reward
                        $invitee = User::find(auth()->user()->invitee);
                        // ToDo: do the reward action
                        Transaction::create([
                            'user_id' => $invitee->id,
                            'title' => 'شارژ کیف پول',
                            'description' => 'شارژ کیف پول بابت دعوت از دوستان',
                            'amount' => env('INVITE_CHARGE_AMOUNT'),
                            'type' => TransactionType::INCREASE->value,
                            'reason' => TransactionReason::CHARGE->value,
                            'status' => TransactionStatus::PAYMENT_SUCCESSFUL->value,
                            'is_reward' => 1
                        ]);
                    }
                }
            }
            
            $chargeTransaction->save();
            $payTransaction->save();
            
            $order->base_price = $basePriceSum;
            // if discount code exists, 
            // from discount code details find corresponding discount fee
            // and update order final_price
            $order->final_price = $finalPriceSum;
            $order->status = OrderStatus::PAYMENT_SUCCESSFUL->value;
            $order->transaction_id = $payTransaction->id;
            $order->save();
            
            $status = 'success';
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            // rollback everything
            if ($order && $order->id) {
                // delete from order items
                OrderDetail::where('order_id', $order->id)->delete();

                // delete order
                $order->delete();
            }
        } finally {
            return view('site.final', compact('status'));
        }
    }
}
