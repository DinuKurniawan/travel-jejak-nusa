<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traveler extends Model
{
    protected $fillable = ['user_id', 'name', 'id_number', 'phone', 'tag'];
}
