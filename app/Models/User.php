<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Privileges;
use App\Enums\UserType;
use App\Models\UserPrivilege;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'google_id',
        'google_token',
        'google_refresh_token',
        'apple_id',
        'apple_token',
        'apple_refresh_token',
        'user_type', // ['superadmin', 'admin', 'reseller', 'customer']
        'wallet',
        'invite_code',
        'invitee',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_superadmin' => 'boolean',
        'category_access' => 'boolean',
        'product_access' => 'boolean',
        'service_access' => 'boolean',
        'order_access' => 'boolean',
        'transaction_access' => 'boolean',
        'discount_access' => 'boolean',
        'user_access' => 'boolean',
    ];

    protected function isSuperadmin(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user_type == UserType::SUPERADMIN->value,
        );
    }

    protected function categoryAccess(): Attribute
    {
        return Attribute::make(
            get: fn () => UserPrivilege::where('user_id', $this->id)->where('action', Privileges::CATEGORIES->value)->count() > 0,
        );
    }

    protected function productAccess(): Attribute
    {
        return Attribute::make(
            get: fn () => UserPrivilege::where('user_id', $this->id)->where('action', Privileges::PRODUCTS->value)->count() > 0,
        );
    }

    protected function serviceAccess(): Attribute
    {
        return Attribute::make(
            get: fn () => UserPrivilege::where('user_id', $this->id)->where('action', Privileges::SERVICES->value)->count() > 0,
        );
    }

    protected function orderAccess(): Attribute
    {
        return Attribute::make(
            get: fn () => UserPrivilege::where('user_id', $this->id)->where('action', Privileges::ORDERS->value)->count() > 0,
        );
    }

    protected function transactionAccess(): Attribute
    {
        return Attribute::make(
            get: fn () => UserPrivilege::where('user_id', $this->id)->where('action', Privileges::TRANSACTIONS->value)->count() > 0,
        );
    }

    protected function discountAccess(): Attribute
    {
        return Attribute::make(
            get: fn () => UserPrivilege::where('user_id', $this->id)->where('action', Privileges::DISCOUNTS->value)->count() > 0,
        );
    }

    protected function userAccess(): Attribute
    {
        return Attribute::make(
            get: fn () => UserPrivilege::where('user_id', $this->id)->where('action', Privileges::USERS->value)->count() > 0,
        );
    }
}
