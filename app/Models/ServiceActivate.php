<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceActivate extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'api_call_status',
        'api_call_message',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
