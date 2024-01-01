<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Movement;
use App\Models\DetailedMovement AS MovementDetails;
use App\Models\Customer;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movements = Movement::select('movements.*', 'users.name AS user', 'customers.name AS customer')
                    ->join('users','movements.user_id','=','users.id')
                    ->leftjoin('customers','movements.customer_id','=','customers.id')
                    ->orderBy("movements.created_at","desc")->get();

        return view("movement.index", compact("movements"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $medicines = Medicine::all();
        $customers = Customer::all();

        $lastReference =  Movement::select('code')->orderByDesc('code')->first();
        $realReference = (is_null($lastReference)) ? "M0000000000" : $lastReference->code;
        $realReference = preg_replace("/[^0-9-.]/", "", $realReference);
        $reference = (int) $realReference + 1;
        $digit = (strlen($reference) == 10 ) ? strlen($reference) + 1 : 10;

        $newReferece = 'M'.substr(str_repeat(0, $digit).$reference, -1 * ($digit));
        $type_movement = $request->type;

        return view("movement.create", compact("medicines","customers",'newReferece', 'type_movement'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(is_null($request->quantity)){
            throw ValidationException::withMessages([
                'medicine_id'=> 'por favor seleccione un libro'
            ]);
        }

        if(!is_numeric($request->customer_id)){
            throw ValidationException::withMessages([
                'customer_id'=> 'por favor selecciones un Cliente'
            ]);
        }
       
        $movement = Movement::create([
            'code' => $request->code,
            'status' => "saved",
            'type_movement' => $request->type_movement,
            'user_id'=> Auth::user()->id,
            'customer_id'=> $request->customer_id,
        ]);
        
        $total = 0;
        foreach ($request->medicine_id as $key => $medicine) {
            $quantity = ($request->type_movement == "load") ? $request->quantity[$medicine] : $request->quantity[$medicine] * -1;
            $subtotal =  $quantity * $request->amount[$medicine];
        
            // dd($request->all(),$request->quantity[$medicine], $request->amount[$medicine], $subtotal);
            MovementDetails::create([
                'movement_id' => $movement->id,
                'type_movement' => $request->type_movement,
                'medicine_id' => $medicine,
                'quantity'=> $quantity,
                'amount'=> $request->amount[$medicine],
                'subtotal'=> $subtotal,
            ]);

            $total =+ (float) $subtotal;
        }

        $movement->update(['total' => $total]);

        if($movement){
            return redirect()->route('movement.index')->with('success','Guardado con Exito');
        }else{
            return redirect()->back()->with('error','Se ha producido un error');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $movements = Movement::find($id);
        $movementsDetails = MovementDetails::
                            select('detailed_movements.*','medicines.name', 'shelfs.name AS shelf')
                            ->join('medicines', 'detailed_movements.medicine_id','=','medicines.id')
                            ->join('shelfs', 'medicines.shelf_id','=','shelfs.id')
                            ->where('detailed_movements.movement_id',$id)->get();
        return view("movement.view", compact("movements","movementsDetails"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $medicines = Medicine::all();
        $customers = Customer::all();
        $movements = Movement::find($id);
        $movementsDetails = MovementDetails::
            select('detailed_movements.*','medicines.name AS medicine', 'shelfs.name AS shelf')
            ->join('medicines', 'detailed_movements.medicine_id','=','medicines.id')
            ->join('shelfs', 'medicines.shelf_id','=','shelfs.id')
            ->where('detailed_movements.movement_id',$movements->id)->get();
        return view("movement.edit", compact("medicines","customers", "movements","movementsDetails"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(is_null($request->quantity)){
            throw ValidationException::withMessages([
                'medicine_id'=> 'por favor seleccione un libro'
            ]);
        }

        $movementDetails = MovementDetails::where('movement_id', $id)->get();


        /** Primero eliminamos los libros que ya no estan  */ 
        foreach ($movementDetails as $key => $detail) {
            if (array_search($detail->medicine_id,$request->medicine_id) == null) {
                MovementDetails::where('id', '=', $detail->id)->delete();
            }
        }

        $total = 0;
        $movement = Movement::find($id);
        foreach ($request->medicine_id as $key => $medicine) {
            $quantity = ($request->type_movement == "load") ? $request->quantity[$medicine] : $request->quantity[$medicine] * -1;
            $subtotal =  $quantity * $request->amount[$medicine];
            $movementDetails = MovementDetails::updateOrCreate(
                [
                    'movement_id' => $movement->id,
                    'medicine_id' => $medicine,
                ],
                [
                    'type_movement' => $request->type_movement,
                    'quantity'=> $quantity,
                    'amount'=> $request->amount[$medicine],
                    'subtotal'=> $subtotal,
                ]
            );
            $total =+ $subtotal;
        }

        $movement->total = $total;
        $movement->save();

        if($movementDetails){
            return redirect()->route('movement.index')->with('success','Guardado con Exito');
        }else{
            return redirect()->back()->with('error','Se ha producido un error');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movement = Movement::find($id);
        $code = $movement->code;
        $movement->delete();

        MovementDetails::where('movement_id','=', $id)->delete();

        return redirect()->route('movement.index')->with('error','Se ha eliminado a '.$code);
    }
   
   
    /**
     * Change the status for the specified resource from storage.
     */
    public function changeStatus(string $id)
    {
        $movement = Movement::find($id);
        $movement->status = "locked";
        $movement->save();


        /*cargar en almacen */
        $movementDetails = MovementDetails::where('movement_id', $id)->get();
        foreach ($movementDetails as $key => $detail) {
            $warehouse = Warehouse::find($detail->medicine_id);
            $oldQuantity = (is_null($warehouse->actual_quantity)) ? 0 : $warehouse->actual_quantity ;
            $newQuantity = $oldQuantity + $detail->quantity;
            $warehouse->actual_quantity = $newQuantity;
            $warehouse->amount = $detail->amount;
            $warehouse->save();
        }

        return redirect()->route('movement.index')->with('warning','Registro'.$movement->code.' Asentado');

    }
}
