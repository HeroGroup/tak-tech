<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'product_id',
        'panel_peer_id',
        'conf_file',
        'qr_file',
        'is_enabled',
        'is_sold',
        'sold_at',
        'order_detail_id',
        'owner',
        'activated_at',
        'expire_days'
    ];

    protected $dates = ['deleted_at'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner', 'id');
    }
}
