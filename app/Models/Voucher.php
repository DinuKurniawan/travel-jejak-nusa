<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = ['code', 'title', 'discount', 'min_spend', 'expires_at', 'is_active'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
            'is_active' => 'boolean',
            'discount' => 'integer',
            'min_spend' => 'integer',
        ];
    }
}
