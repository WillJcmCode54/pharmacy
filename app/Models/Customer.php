<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    /* Table de Clientes */

    public $table = "customers";
    public $fillable = [
        'id',
        'name',
        'last_name',
        'number_id',
        'phone',
        'email',
        'address'
    ];
}
