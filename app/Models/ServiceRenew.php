<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRenew extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'service_price',
        'discount_id',
        'add_days',
        'payment_status',
        'api_call_status',
        'api_call_message',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
