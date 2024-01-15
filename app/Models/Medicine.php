<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    public $table = "medicines";

    protected $fillable = [
        "name",
        "img",
        "decription",
        "amount",
        "expiration_date",
        "shelf_id",
        "category_id",
    ];
}
