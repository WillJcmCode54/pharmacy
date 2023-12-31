<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    public $table = "movements";

    protected $fillable = [
        'code',
        'type_movement',
        'status',
        'user_id',
        'customer_id',
        'total',
    ];
}
