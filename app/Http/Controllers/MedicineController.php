<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Shelf;
use App\Models\Category;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicines = Medicine::select('medicines.*','shelfs.name AS shelf','categories.name AS category')
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
            'img'=> 'image|max:5120',
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
        $path = ($request->hasFile('img')) ?
            $request->file('img')->storeAs('public/img', Carbon::now()->format('Y-m-d')."_".mb_strtoupper($request->name).".png")
        :
            $path = "img/medicine.png";
        
        $url = Storage::url($path);

        $date = Carbon::parse($request->expiration_date);
        $date = $date->format('Y-m-d');

        $medicine = Medicine::create([
            "name"=> $request->name,
            "img"=> $url,
            'expiration_date' => $date,
            'category_id' => $request->category_id,
            'shelf_id' => $request->shelf_id,
            'amount' => $request->amount,
            'decription' => $request->decription,
        ]);
        Warehouse::create([
            'medicine_id'=> $medicine->id,
            'amount'=> $medicine->amount,
            'actual_quantity'=> 0
        ]);
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
        $medicine = Medicine::select('medicines.*','shelfs.name AS shelf', 'categories.name AS category')
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
            'img'=> 'image|max:5120',
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

        //se elimina la imagen
        // $img = explode('/', $request->old_img);
        // if ($img[3] != "medicine.png" ) {
        //     Storage::disk('img')->delete($img[3]);
        // }
         if($request->hasFile('img')) {
            $path =$request->file('img')->storeAs('public/img', Carbon::now()->format('Y-m-d')."_".mb_strtoupper($request->name).".png");
            $url =  Storage::url($path);
        }else{
            $url =$request->old_img;
        }

        $medicine = Medicine::find($id);
        $date = Carbon::parse($request->expiration_date);
        $date = $date->format('Y-m-d');

        $status = $medicine->update([
            "name"=> $request->name,
            'expiration_date' => $date,
            'category_id' => $request->category_id,
            'shelf_id' => $request->shelf_id,
            'amount' => $request->amount,
            'decription' => $request->decription,
            'img' => $url
        ]);

        Warehouse::where('medicine_id', $request->id)->update([ 'amount' => $request->amount]);
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
        $img = explode('/', $medicine->img);
        if ($img[3] != "medicine.png" ) {
            Storage::disk('img')->delete($img[3]);
        }
        Storage::disk('img')->delete($img);
        $medicine->delete();
        Warehouse::where('medicine_id', $id)->delete();
        return redirect()->route('medicine.index')->with('error','Se ha eliminado a '.$name );
    }
    
    /* funciones para los json() */
    public function check(string $id)
    {
        $medicine = Medicine::select('medicines.*','shelfs.name AS shelf','categories.name AS category','warehouse.actual_quantity AS quantity')
                    ->join('warehouse', 'medicines.id',"=",'warehouse.medicine_id')
                    ->join('shelfs', 'medicines.shelf_id',"=",'shelfs.id')
                    ->join('categories','medicines.category_id',"=",'categories.id')
                    ->find($id);
        return response()->json($medicine, 200);
    }
}
