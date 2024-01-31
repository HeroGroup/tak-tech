<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'price',
        'period',
        'is_featured',
        'is_active'
    ];

    protected $dates = ['deleted_at'];

    public function categories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }
}
