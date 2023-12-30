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

class LendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lends = BookMovement::select('book_movements.*', 'users.name AS user', 'customers.name AS customer', 'customers.last_name AS last_name')
                            ->join('users','book_movements.user_id','=','users.id')
                            ->leftjoin('customers','book_movements.customer_id','=','customers.id')
                            ->where('book_movements.type_movement', 'lend')
                            ->orderBy("book_movements.created_at","desc")->get();

        return view("lend.index", compact("lends"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $books = Book::all();
        $customers = Customer::all();

        $lastReference =  BookMovement::select('code')->orderByDesc('code')->first();
        $realReference = (is_null($lastReference)) ? "M0000000000" : $lastReference->code;
        $realReference = preg_replace("/[^0-9-.]/", "", $realReference);
        $reference = (int) $realReference + 1;
        $digit = (strlen($reference) == 10 ) ? strlen($reference) + 1 : 10;

        $newReferece = 'M'.substr(str_repeat(0, $digit).$reference, -1 * ($digit));
        $type_movement = $request->type;

        return view("lend.create", compact("books","customers",'newReferece', 'type_movement'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!is_numeric($request->customer_id)){
            throw ValidationException::withMessages([
                'customer_id'=> 'por favor seleccione un cliente'
            ]);
        }

        if(is_null($request->quantity)){
            throw ValidationException::withMessages([
                'book_id'=> 'por favor seleccione un libro'
            ]);
        }
       
        if(is_null($request->dateRange)){
            throw ValidationException::withMessages([
                'dateRange'=> 'por favor seleccione fechas'
            ]);
        }
        $date = explode("-", $request->dateRange);
        $lends = BookMovement::create([
            'code' => $request->code,
            'status' => "saved",
            'type_movement' => 'lend',
            'loan_date' => $date[0],
            'return_date' => $date[1],
            'user_id'=> Auth::user()->id,
            'customer_id'=> $request->customer_id
        ]);

        foreach ($request->book_id as $key => $book) {
            DetailsBookMovement::create([
                'book_movement_id' => $lends->id,
                'type_movement' => 'lend',
                'book_id' => $book,
                'quantity'=> $request->quantity[$book] * -1
            ]);
        }

        if($lends){
            return redirect()->route('lend.index')->with('success','Guardado con Exito');
        }else{
            return redirect()->back()->with('error','Se ha producido un error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lends = BookMovement::select('book_movements.*',  'users.name AS user', 'customers.name AS customer', 'customers.last_name AS last_name')
                            ->join('users','book_movements.user_id','=','users.id')
                            ->join('customers','book_movements.customer_id','=','customers.id')
                            ->find($id);
        $movementsDetails = DetailsBookMovement::
                            select('details_book_movements.*','books.title', 'books.author','books.publication_year')
                            ->join('books','books.id','=','details_book_movements.book_id')
                            ->where('details_book_movements.book_movement_id',$id)->get();
        return view("lend.view", compact("lends","movementsDetails"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $books = Book::all();
        $customers = Customer::all();
        $lends = BookMovement::find($id);
        $movementsDetails = DetailsBookMovement::
            select('details_book_movements.*','books.title', 'books.author','books.publication_year')
            ->join('books', 'details_book_movements.book_id','=','books.id')
            ->where('details_book_movements.book_movement_id',$lends->id)->get();
        return view("lend.edit", compact("books","customers", "lends","movementsDetails"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(!is_numeric($request->customer_id)){
            throw ValidationException::withMessages([
                'customer_id'=> 'por favor seleccione un cliente'
            ]);
        }

        if(is_null($request->quantity)){
            throw ValidationException::withMessages([
                'book_id'=> 'por favor seleccione un libro'
            ]);
        }
       
        if(is_null($request->dateRange)){
            throw ValidationException::withMessages([
                'dateRange'=> 'por favor seleccione fechas'
            ]);
        }
        $date = explode("-", $request->dateRange);
        $movement = BookMovement::find($id);
        $movement->update([
            'status' => "saved",
            'loan_date' => $date[0],
            'return_date' => $date[1],
            'user_id'=> Auth::user()->id,
            'customer_id'=> $request->customer_id
        ]);

        $movementDetails = DetailsBookMovement::where('book_movement_id', $id)->get();

        /** Primero eliminamos los libros que ya no estan  */ 
        foreach ($movementDetails as $key => $detail) {
            if (array_search($detail->book_id,$request->book_id) == null) {
                DetailsBookMovement::where('id', '=', $detail->id)->delete();
            }
        }

        foreach ($request->book_id as $key => $book) {
            $movementDetails = DetailsBookMovement::updateOrCreate(
                [
                    'book_movement_id' => $movement->id,
                    'book_id' => $book,
                ],
                [
                'type_movement' => 'lend',
                'quantity'=> $request->quantity[$book] * -1
                ]
            );
        }

        if($movementDetails){
            return redirect()->route('lend.index')->with('success','Guardado con Exito');
        }else{
            return redirect()->back()->with('error','Se ha producido un error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movement = BookMovement::find($id);
        $code = $movement->code;
        $movement->delete();

        DetailsBookMovement::where('book_movement_id','=', $id)->delete();

        return redirect()->route('lend.index')->with('error','Se ha eliminado a '.$code);
    }
   
   
    /**
     * Change the status for the specified resource from storage.
     */
    public function changeStatus(string $id)
    {
        $movement = BookMovement::find($id);
        $movement->status = "locked";
        $movement->save();

        /*cargar en almacen */
        $movementDetails = DetailsBookMovement::where('book_movement_id', $id)->get();
        foreach ($movementDetails as $key => $detail) {
            $warehouse = Warehouse::find($detail->book_id);
            $oldQuantity = (is_null($warehouse->actual_quantity)) ? 0 : $warehouse->actual_quantity ;
            $newQuantity = $oldQuantity + $detail->quantity;
            $warehouse->actual_quantity = $newQuantity;
            $warehouse->save();
        }
        return redirect()->route('lend.index')->with('warning','Registro'.$movement->code.' Asentado');

    }
}
