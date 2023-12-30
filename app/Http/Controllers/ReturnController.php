<?php

namespace App\Http\Controllers;

use App\Models\BookMovement;
use App\Models\Book;
use App\Models\Customer;
use App\Models\DetailsBookMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returns = BookMovement::select('book_movements.*', 'users.name AS user', 'customers.name AS customer', 'customers.last_name AS last_name')
                            ->join('users','book_movements.user_id','=','users.id')
                            ->leftjoin('customers','book_movements.customer_id','=','customers.id')
                            ->whereIn('book_movements.type_movement',['lend', 'return'])
                            ->where('book_movements.status', 'locked')
                            ->orderBy("book_movements.created_at","desc")->get();

        return view("return.index", compact("returns"));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $returns = BookMovement::select('book_movements.*',  'users.name AS user', 'customers.name AS customer', 'customers.last_name AS last_name')
                            ->join('users','book_movements.user_id','=','users.id')
                            ->join('customers','book_movements.customer_id','=','customers.id')
                            ->find($id);
        $movementsDetails = DetailsBookMovement::
                            select('details_book_movements.*','books.title', 'books.author','books.publication_year')
                            ->join('books','books.id','=','details_book_movements.book_id')
                            ->where('details_book_movements.book_movement_id',$id)->get();
        return view("return.view", compact("returns","movementsDetails"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $books = Book::all();
        $customers = Customer::all();
        $returns = BookMovement::find($id);
        $movementsDetails = DetailsBookMovement::
            select('details_book_movements.*','books.title', 'books.author','books.publication_year')
            ->join('books', 'details_book_movements.book_id','=','books.id')
            ->where('details_book_movements.book_movement_id',$returns->id)->get();
        return view("return.edit", compact("books","customers", "returns","movementsDetails"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(is_null($request->date_real)){
            throw ValidationException::withMessages([
                'date_real'=> 'por favor seleccione fechas'
            ]);
        }
        $movement = BookMovement::find($id);
        $movement->update([
            'real_date' => $request->date_real,
        ]);

        if($movement){
            return redirect()->route('return.index')->with('success','Guardado con Exito');
        }else{
            return redirect()->back()->with('error','Se ha producido un error');
        }
    }
   
    /**
     * Change the status for the specified resource from storage.
     */
    public function changeStatus(string $id)
    {
        $movement = BookMovement::find($id);
        $movement->type_movement = "return";
        $movement->save();

        /*cargar en almacen */
        $movementDetails = DetailsBookMovement::where('book_movement_id', $id)->get();
        foreach ($movementDetails as $key => $detail) {
            $warehouse = Warehouse::find($detail->book_id);
            $oldQuantity = (is_null($warehouse->actual_quantity)) ? 0 : $warehouse->actual_quantity ;
            $newQuantity = $oldQuantity + ($detail->quantity * -1);
            $warehouse->actual_quantity = $newQuantity;
            $warehouse->save();
        }
        return redirect()->route('return.index')->with('warning','Registro'.$movement->code.' Asentado');

    }
}
