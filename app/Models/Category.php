<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'image_url',
        'is_active'
    ];

    protected $dates = ['deleted_at'];

    public function products(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }
}
