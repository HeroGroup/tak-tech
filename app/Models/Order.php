<?php

namespace App\Models;

use App\Events\OrderCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'user_id',
        'discount_id',
        'status',
        'base_price',
        'final_price',
    ];
    
    protected $dispatchesEvents = [
        'saved' => OrderCreated::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
