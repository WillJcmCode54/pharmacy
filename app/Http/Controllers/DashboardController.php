<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookMovement;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){

        $books = Book::get()->count();
        $customers = Customer::get()->count();
        $lends = BookMovement::where('type_movement', '=', 'lend')->get()->count();
        $returns = BookMovement::where('type_movement', '=', 'return')->get()->count();

        $startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        $defaulters = BookMovement::select('book_movements.*', 'customers.name AS customer', 'customers.last_name AS last_name', 'books.title AS book','books.publication_year AS date')
                    ->leftjoin('customers','book_movements.customer_id','=','customers.id')
                    ->join('details_book_movements','book_movements.id','=','details_book_movements.book_movement_id')
                    ->join('books','details_book_movements.book_id','=','books.id')
                    ->where('book_movements.type_movement','=','lend')
                    ->where('book_movements.status', 'locked')
                    ->whereBetween('book_movements.return_date', [$startDate, $endDate])
                    ->orderBy("book_movements.return_date","desc")->get();

        return view("dashboard", compact("books","customers","lends", 'returns', 'defaulters'));
    }
}
