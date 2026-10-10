<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'kind', 'read_at'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }
}
