<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'description',
        'discount_percent',
        'fixed_amount',
        'expire_date',
        'capacity',
        'for_user',
        'is_active'
    ];

    public function usedCount()
    {
        return Order::where(['discount_id' => $this->id, 'status' => OrderStatus::PAYMENT_SUCCESSFUL->value])->count();
    }
}
