<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(){
        $medicines = Medicine::select('medicines.*','warehouse.actual_quantity AS quantity', 'shelfs.name AS shelf', 'categories.name AS category')
                     ->join('shelfs', 'shelfs.id', '=', 'medicines.shelf_id')
                    ->join('warehouse','warehouse.medicine_id','=','medicines.id')
                    ->join('categories','medicines.category_id',"=",'categories.id')
                    ->orderBy("id","desc")->get();
        return view("warehouse.index",compact("medicines"));
    }
}
