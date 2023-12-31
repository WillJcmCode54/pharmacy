<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Shelf;
use App\Models\Category;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicines = Medicine::select('medicines.*','shelfs.name AS shelf')
                        ->join('shelfs','medicines.shelf_id',"=",'shelfs.id')
                        ->join('categories','medicines.category_id',"=",'categories.id')
                        ->get();
        return view("medicine.index", compact("medicines"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shelfs = Shelf::all();
        $categories = Category::all();
        return view("medicine.create",compact("shelfs","categories"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            "name"=> 'required',
            "expiration_date"=> 'required',
            'category_id' => 'required',
            'shelf_id' => 'required',
            'amount' => 'required',
            'decription' => 'required',
        ]);

        if(!is_numeric($request->shelf_id)){
            throw ValidationException::withMessages([
                'shelf_id'=> 'por favor selecciones una estanteria'
            ]);
        }
        if(!is_numeric($request->category_id)){
            throw ValidationException::withMessages([
                'category_id'=> 'por favor selecciones una categoria'
            ]);
        }

        $date = Carbon::parse($request->expiration_date);
        $date = $date->format('Y-m-d');

        $medicine = Medicine::create([
            "name"=> $request->name,
            'expiration_date' => $date,
            'category_id' => $request->category_id,
            'shelf_id' => $request->shelf_id,
            'amount' => $request->amount,
            'decription' => $request->decription,
        ]);
        Warehouse::updateOrInsert(
            [
            'medicine_id'=> $medicine->id,
            'amount'=> $medicine->id,
            ],
            ['actual_quantity'=> 0]
        );
        if($medicine){
            return redirect()->route('medicine.index')->with('success','Guardado con Exitoso');
        }else{
            return redirect()->back()->with('error','Se ha producido un error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $medicine = Medicine::select('medicines.*','shelfs.name AS shelf')
                    ->join('shelfs', 'medicines.shelf_id',"=",'shelfs.id')
                    ->join('categories','medicines.category_id',"=",'categories.id')
                    ->find($id);
        return view('medicine.view', compact('medicine'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $medicine = Medicine::find($id);
        $shelfs = Shelf::all();
        $categories = Category::all();
        return view('medicine.edit', compact('medicine','shelfs','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            "name"=> 'required',
            "expiration_date"=> 'required',
            'category_id' => 'required',
            'shelf_id' => 'required',
            'amount' => 'required',
            'decription' => 'required',
        ]);

        if(!is_numeric($request->shelf_id)){
            throw ValidationException::withMessages([
                'shelf_id'=> 'por favor selecciones una estanteria'
            ]);
        }
        if(!is_numeric($request->category_id)){
            throw ValidationException::withMessages([
                'category_id'=> 'por favor selecciones una categoria'
            ]);
        }

        $medicine = Medicine::find($id);
        $date = Carbon::parse($request->date);
        $date = $date->format('Y-m-d');

        $status = $medicine->update([
            "name"=> $request->name,
            'expiration_date' => $date,
            'category_id' => $request->category_id,
            'shelf_id' => $request->shelf_id,
            'amount' => $request->amount,
            'decription' => $request->decription,
        ]);
        if($status){
            return redirect()->route('medicine.index')->with('success','Editado con Exitoso');
        }else{
            return redirect()->back()->with('error','Se ha producido un error');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $medicine = Medicine::find($id);
        $name = $medicine->name;
        $medicine->delete();
        return redirect()->route('medicine.index')->with('error','Se ha eliminado a '.$name );
    }
    
    /* funciones para los json() */
    public function check(string $id)
    {
        $medicine = Medicine::select('medicines.*','shelfs.name AS shelfs','warehouse.actual_quantity AS quantity')
                    ->join('warehouse', 'medicines.id',"=",'warehouse.medicine_id')
                    ->join('shelfs', 'medicines.shelf_id',"=",'shelfs.id')
                    ->find($id);
        return response()->json($medicine, 200);
    }
}
