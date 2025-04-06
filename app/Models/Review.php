<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $product_id
 * @property int $user_id
 * @property string $date
 * @property int $grade
 * @property string $comment
 * @property User $user
 */
class Review extends Model
{
    protected $fillable = ['product_id', 'user_id', 'date', 'grade', 'comment', 'user'];
}
