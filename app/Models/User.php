<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as BaseUser;

class User extends BaseUser
{
    // protected $table = 'users'; автоматически определяет имя таблицы исходы из названия класса (User).
    // Если нужно записать в другую таблицу, нужно просто указать с какой таблицей работаем
    protected $fillable = ['name', 'email', 'password'];
    public function userProducts()
    {
        return $this->hasMany(UserProduct::class);
    }
    public function products()
    {
        return $this->hasManyThrough(Product::class, UserProduct::class, 'user_id', 'id', 'id', 'product_id');
    }
}
