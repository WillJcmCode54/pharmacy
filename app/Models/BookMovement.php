<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookMovement extends Model
{
    use HasFactory;

    /* Tabla para almacenar los movimientos 
     * que se hagan en el almacen 
     * Vision general
     */

    public $table = "book_movements";

    public $fillable = [
        'id',
        'code',
        'status',
        'type_movement',
        'loan_date',
        'return_date',
        'real_date',
        'user_id',
        'customer_id'
    ];
}
