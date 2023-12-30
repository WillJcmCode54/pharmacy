<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LendController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');

Route::get('/dashboard',[DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {

    Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
    // Perfil
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Clientes
        Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
        Route::post('/customer/create', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('/customer/view/{id}', [CustomerController::class, 'show'])->name('customer.view');
        Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
        Route::post('/customer/edit/{id}', [CustomerController::class, 'update'])->name('customer.update');
        Route::delete('/customer/delete/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
    
    // Estanterias
        Route::get('/shelf', [ShelfController::class, 'index'])->name('shelf.index');
        Route::get('/shelf/create', [ShelfController::class, 'create'])->name('shelf.create');
        Route::post('/shelf/create', [ShelfController::class, 'store'])->name('shelf.store');
        Route::get('/shelf/view/{id}', [ShelfController::class, 'show'])->name('shelf.view');
        Route::get('/shelf/edit/{id}', [ShelfController::class, 'edit'])->name('shelf.edit');
        Route::post('/shelf/edit/{id}', [ShelfController::class, 'update'])->name('shelf.update');
        Route::delete('/shelf/delete/{id}', [ShelfController::class, 'destroy'])->name('shelf.destroy');

    // Libros
        Route::get('/book', [BookController::class, 'index'])->name('book.index');
        Route::get('/book/create', [BookController::class, 'create'])->name('book.create');
        Route::post('/book/create', [BookController::class, 'store'])->name('book.store');
        Route::get('/book/view/{id}', [BookController::class, 'show'])->name('book.view');
        Route::get('/book/check/{id}', [BookController::class, 'check'])->name('book.check');
        Route::get('/book/edit/{id}', [BookController::class, 'edit'])->name('book.edit');
        Route::post('/book/edit/{id}', [BookController::class, 'update'])->name('book.update');
        Route::delete('/book/delete/{id}', [BookController::class, 'destroy'])->name('book.destroy');

    // Movimeintos
        Route::get('/movement', [MovementController::class, 'index'])->name('movement.index');
        Route::get('/movement/create', [MovementController::class, 'create'])->name('movement.create');
        Route::post('/movement/create', [MovementController::class, 'store'])->name('movement.store');
        Route::get('/movement/view/{id}', [MovementController::class, 'show'])->name('movement.view');
        Route::get('/movement/edit/{id}', [MovementController::class, 'edit'])->name('movement.edit');
        Route::post('/movement/edit/{id}', [MovementController::class, 'update'])->name('movement.update');
        Route::delete('/movement/delete/{id}', [MovementController::class, 'destroy'])->name('movement.destroy');
        Route::post('/movement/status/{id}', [MovementController::class, 'changeStatus'])->name('movement.status');
        
    //Prestamos
        Route::get('/lend', [LendController::class, 'index'])->name('lend.index');
        Route::get('/lend/create', [LendController::class, 'create'])->name('lend.create');
        Route::post('/lend/create', [LendController::class, 'store'])->name('lend.store');
        Route::get('/lend/view/{id}', [LendController::class, 'show'])->name('lend.view');
        Route::get('/lend/edit/{id}', [LendController::class, 'edit'])->name('lend.edit');
        Route::post('/lend/edit/{id}', [LendController::class, 'update'])->name('lend.update');
        Route::delete('/lend/delete/{id}', [LendController::class, 'destroy'])->name('lend.destroy');
        Route::post('/lend/status/{id}', [LendController::class, 'changeStatus'])->name('lend.status');
        
    //Devoluciones
        Route::get('/return', [ReturnController::class, 'index'])->name('return.index');
        Route::get('/return/view/{id}', [ReturnController::class, 'show'])->name('return.view');
        Route::get('/return/edit/{id}', [ReturnController::class, 'edit'])->name('return.edit');
        Route::post('/return/edit/{id}', [ReturnController::class, 'update'])->name('return.update');
        Route::post('/return/status/{id}', [ReturnController::class, 'changeStatus'])->name('return.status');
});

require __DIR__.'/auth.php';
