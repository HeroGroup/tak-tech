<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPrivilege extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
