<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailsBookMovement extends Model
{
    use HasFactory;

    /* Tabla De movimientos de libros
    * Vision detallada
    */

    public $table = "details_book_movements";
    public $fillable = [
        'id',
        'book_movement_id',
        'type_movement',
        'book_id',
        'quantity'
    ];
}
