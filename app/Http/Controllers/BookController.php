<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Shelf;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::select('books.*','shelfs.name AS shelf')
                        ->join('shelfs', 'books.shelf_id',"=",'shelfs.id')
                        ->get();
        return view("book.index", compact("books"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shelfs = Shelf::all();
        return view("book.create",compact("shelfs"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            "title"=> 'required',
            "author"=> 'required',
            'editorial' => 'required',
            'decription' => 'required',
            'publication_year' => 'required',
            'genre' => 'required',
            'shelf_id' => 'required',
        ]);

        if(!is_numeric($request->shelf_id)){
            throw ValidationException::withMessages([
                'shelf_id'=> 'por favor selecciones una estanteria'
            ]);
        }

        $date = Carbon::parse($request->date);
        $date = $date->format('Y-m-d');

        $book = Book::create([
            "title"=> $request->title,
            "author"=> $request->author,
            'editorial' => $request->editorial,
            'decription' => $request->decription,
            'publication_year' => $date,
            'genre' => $request->genre,
            'shelf_id' => $request->shelf_id,
        ]);
        Warehouse::create([
            'book_id'=> $book->id,
            'actual_quantity'=> 0
        ]);
        if($book){
            return redirect()->route('book.index')->with('success','Guardado con Exitoso');
        }else{
            return redirect()->back()->with('error','Se ha producido un error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::select('books.*','shelfs.name AS shelf')
                    ->join('shelfs', 'books.shelf_id',"=",'shelfs.id')
                    ->find($id);
        return view('book.view', compact('book'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::find($id);
        $shelfs = Shelf::all();
        return view('book.edit', compact('book','shelfs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "title"=> 'required',
            "author"=> 'required',
            'editorial' => 'required',
            'decription' => 'required',
            'publication_year' => 'required',
            'genre' => 'required',
            'shelf_id' => 'required',
        ]);

        if(!is_numeric($request->shelf_id)){
            throw ValidationException::withMessages([
                'shelf_id'=> 'por favor selecciones una estanteria'
            ]);
        }

        $book = Book::find($id);
        $date = Carbon::parse($request->date);
        $date = $date->format('Y-m-d');

        $status = $book->update([
            "title"=> $request->title,
            "author"=> $request->author,
            'editorial' => $request->editorial,
            'decription' => $request->decription,
            'publication_year' => $date,
            'genre' => $request->genre,
            'shelf_id' => $request->shelf_id,
        ]);
        if($status){
            return redirect()->route('book.index')->with('success','Editado con Exitoso');
        }else{
            return redirect()->back()->with('error','Se ha producido un error');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);
        $name = $book->name;
        $book->delete();
        return redirect()->route('book.index')->with('error','Se ha eliminado a '.$name );
    }
    
    /* funciones para los json() */
    public function check(string $id)
    {
        $book = Book::select('books.*','shelfs.name AS shelfs','warehouse.actual_quantity AS quantity')
                    ->join('warehouse', 'books.id',"=",'warehouse.book_id')
                    ->join('shelfs', 'books.shelf_id',"=",'shelfs.id')
                    ->find($id);
        return response()->json($book, 200);
    }
}
