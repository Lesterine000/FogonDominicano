<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'dish_id',
        'quantity',
        'reserved',
        'available_at'
    ];


    public function dish() {
        return $this->belongsTo(Dish::class);
    }
}