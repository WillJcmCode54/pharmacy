<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookMovement AS Movement;
use App\Models\DetailsBookMovement AS MovementDetails;
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
        $movements = Movement::select('book_movements.*', 'users.name AS user', 'customers.name AS customer')
                    ->join('users','book_movements.user_id','=','users.id')
                    ->leftjoin('customers','book_movements.customer_id','=','customers.id')
                    ->orderBy("book_movements.created_at","desc")->get();

        return view("book_movement.index", compact("movements"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $books = Book::all();
        $customers = Customer::all();

        $lastReference =  Movement::select('code')->orderByDesc('code')->first();
        $realReference = (is_null($lastReference)) ? "M0000000000" : $lastReference->code;
        $realReference = preg_replace("/[^0-9-.]/", "", $realReference);
        $reference = (int) $realReference + 1;
        $digit = (strlen($reference) == 10 ) ? strlen($reference) + 1 : 10;

        $newReferece = 'M'.substr(str_repeat(0, $digit).$reference, -1 * ($digit));
        $type_movement = $request->type;

        return view("book_movement.create", compact("books","customers",'newReferece', 'type_movement'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(is_null($request->quantity)){
            throw ValidationException::withMessages([
                'book_id'=> 'por favor seleccione un libro'
            ]);
        }

        $movement = Movement::create([
            'code' => $request->code,
            'status' => "saved",
            'type_movement' => $request->type_movement,
            'user_id'=> Auth::user()->id
        ]);

        foreach ($request->book_id as $key => $book) {
            $quantity = ($request->type_movement == "load") ? $request->quantity[$book] : $request->quantity[$book] * -1;
            MovementDetails::create([
                'book_movement_id' => $movement->id,
                'type_movement' => $request->type_movement,
                'book_id' => $book,
                'quantity'=> $quantity
            ]);
        }

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
                            select('details_book_movements.*','books.title', 'books.author','books.publication_year')
                            ->join('books', 'details_book_movements.book_id','=','books.id')
                            ->where('details_book_movements.book_movement_id',$id)->get();
        return view("book_movement.view", compact("movements","movementsDetails"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $books = Book::all();
        $customers = Customer::all();
        $movements = Movement::find($id);
        $movementsDetails = MovementDetails::
            select('details_book_movements.*','books.title', 'books.author','books.publication_year')
            ->join('books', 'details_book_movements.book_id','=','books.id')
            ->where('details_book_movements.book_movement_id',$movements->id)->get();
        return view("book_movement.edit", compact("books","customers", "movements","movementsDetails"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(is_null($request->quantity)){
            throw ValidationException::withMessages([
                'book_id'=> 'por favor seleccione un libro'
            ]);
        }

        $movementDetails = MovementDetails::where('book_movement_id', $id)->get();


        /** Primero eliminamos los libros que ya no estan  */ 
        foreach ($movementDetails as $key => $detail) {
            if (array_search($detail->book_id,$request->book_id) == null) {
                MovementDetails::where('id', '=', $detail->id)->delete();
            }
        }

        $movement = Movement::find($id);
        foreach ($request->book_id as $key => $book) {
            $quantity = ($request->type_movement == "load") ? $request->quantity[$book] : $request->quantity[$book] * -1;
            $movementDetails = MovementDetails::updateOrCreate(
                [
                    'book_movement_id' => $movement->id,
                    'book_id' => $book,
                ],
                [
                'type_movement' => $request->type_movement,
                'quantity'=> $quantity
                ]
            );
        }

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

        MovementDetails::where('book_movement_id','=', $id)->delete();

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
        $movementDetails = MovementDetails::where('book_movement_id', $id)->get();
        foreach ($movementDetails as $key => $detail) {
            $warehouse = Warehouse::find($detail->book_id);
            $oldQuantity = (is_null($warehouse->actual_quantity)) ? 0 : $warehouse->actual_quantity ;
            $newQuantity = $oldQuantity + $detail->quantity;
            $warehouse->actual_quantity = $newQuantity;
            $warehouse->save();
        }

        return redirect()->route('movement.index')->with('warning','Registro'.$movement->code.' Asentado');

    }
}
