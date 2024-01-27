<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'discount_id',
        'product_id',
        'discount_percent',
        'fixed_amount'
    ];

    protected $dates = ['deleted_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }
}
