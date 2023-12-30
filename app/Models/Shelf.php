<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;

    /* Tabla de estanterias */

    public $table = "shelfs";
    public $fillable = [
        'id',
        'name',
        'decription'
    ];
}
