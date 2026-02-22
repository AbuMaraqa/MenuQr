<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPage extends Model
{
    protected $fillable = [
        'name',
        'sort_order',
        'is_active'
    ];
}
