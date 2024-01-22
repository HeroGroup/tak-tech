<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Mailbox;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUser
{
    public function __construct()
    {
        //
    }

    public function handle(OrderCreated $event): void
    {
        if ($event->order->user_id) {
            // Notify User in Mailbox
            $description = 'خرید شما به تعداد x سرویس و قیمت y تومان ثبت شد.';
            $mailbox = Mailbox::create([
                'user_id' => $event->order->user_id,
                'subject' => 'سفارش شما ثبت شد',
                'description' => str_replace('y', number_format($event->order->final_price), str_replace('x', $event->order->orderDetails->count() , $description)),
                'route' => '/customer/orders/'.$event->order->uid,
            ]);
        }
    }
}
