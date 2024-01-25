<?php

namespace App\Listeners;

use App\Enums\TransactionType;
use App\Events\TransactionCreated;
use App\Models\Mailbox;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserForTransaction
{
    public function __construct()
    {
        //
    }

    public function handle(TransactionCreated $event): void
    {
        if ($event->transaction?->user_id && $event->transaction?->type == TransactionType::INCREASE->value) {
            // Notify User in Mailbox
            Mailbox::create([
                'user_id' => $event->transaction->user_id,
                'subject' => 'افزایش اعتبار',
                'description' => $event->transaction->description . ' به مبلغ ' . number_format($event->transaction->amount),
                'route' => '/customer/transactions',
            ]);
        }
    }
}
