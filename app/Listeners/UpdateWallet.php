<?php

namespace App\Listeners;

use App\Enums\TransactionType;
use App\Events\TransactionCreated;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateWallet
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionCreated $event): void
    {
        if ($event->transaction->user_id) {
            $user = User::find($event->transaction->user_id);

            $currentWallet = $user->wallet ?? 0;
            if ($event->transaction->type == TransactionType::INCREASE->value) {
                $user->wallet = $currentWallet + $event->transaction->amount;
            } else if ($event->transaction->type == TransactionType::DECREASE->value) {
                $user->wallet = $currentWallet - $event->transaction->amount;
            }
            
            $user->save();
        }
    }
}
