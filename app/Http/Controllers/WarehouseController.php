<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(){
        $books = Book::select('books.*','warehouse.actual_quantity AS quantity', 'shelfs.name AS shelf')
                     ->join('shelfs', 'shelfs.id', '=', 'books.shelf_id')
                    ->join('warehouse','warehouse.book_id','=','books.id')
                    ->orderBy("id","desc")->get();
        return view("warehouse.index",compact("books"));
    }
}
