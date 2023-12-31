<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailedMovement extends Model
{
    use HasFactory;

    public $table = "detailed_movements";

    protected $fillable = [
        'movement_id',
        'type_movement',
        'medicine_id',
        'amount',
        'quantity',
        'subtotal',
    ];
    
}
