<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'booking_id', 'booking_code', 'rating', 'review'];

    protected function casts(): array
    {
        return ['rating' => 'integer'];
    }
}
