<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    /* tabla de almacen */
    public $table = "warehouse";

    public $fillable = [
        'id',
        'book_id',
        'actual_quantity'
    ];
}
