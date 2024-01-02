<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailedMovement;
use App\Models\Medicine;
use App\Models\Movement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){

        $medicines = Medicine::get()->count();
        $customers = Customer::get()->count();
        $load = Movement::where('type_movement', '=', 'load')->get()->count();
        $download = Movement::where('type_movement', '=', 'download')->get()->count();

        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d h:i:s');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d h:i:s');

        $topMediciesLoad = Movement::selectRaw("`movements`.*,
                                                `medicines`.`name` AS `medicine`,
                                                `categories`.`name` AS `category`,
                                                SUM(`detailed_movements`.`quantity`) AS `subtotal`")
                    ->join('detailed_movements','movements.id','=','detailed_movements.movement_id')
                    ->join('medicines','detailed_movements.medicine_id','=','medicines.id')
                    ->join('categories','medicines.category_id','=','categories.id')
                    ->where('movements.type_movement','=','load')
                    ->where('movements.status', 'locked')
                    ->whereBetween('movements.updated_at', [$startDate, $endDate])
                    ->groupBy('detailed_movements.medicine_id')
                    ->orderByRaw("SUM(`detailed_movements`.`quantity`)  DESC")->limit(5)->get();

        $topMediciesDownload = Movement::selectRaw("`movements`.*,
                                                `medicines`.`name` AS `medicine`,
                                                `categories`.`name` AS `category`,
                                                SUM(`detailed_movements`.`quantity`) AS `subtotal`")
                    ->join('detailed_movements','movements.id','=','detailed_movements.movement_id')
                    ->join('medicines','detailed_movements.medicine_id','=','medicines.id')
                    ->join('categories','medicines.category_id','=','categories.id')
                    ->where('movements.type_movement','=','download')
                    ->where('movements.status', 'locked')
                    ->whereBetween('movements.updated_at', [$startDate, $endDate])
                    ->groupBy('detailed_movements.medicine_id')
                    ->orderByRaw("SUM(`detailed_movements`.`quantity`) DESC")->limit(5)->get();

        return view("dashboard", compact("medicines","customers","load", 'download', 'topMediciesDownload','topMediciesLoad'));
    }
}
