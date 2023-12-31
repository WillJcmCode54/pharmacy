<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

    // Almacen
        Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
    
    // Perfil
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Clientes
        Route::resource('/customer', CustomerController::class);
    
    // Estanterias
        Route::resource('/shelf', ShelfController::class);
    
    // Categorias
        Route::resource('/category', CategoryController::class);

    // Libros
        Route::resource('/medicine', MedicineController::class);

    // Movimeintos
        Route::resource('/movement', MovementController::class);
        Route::post('/movement/status/{id}', [MovementController::class, 'changeStatus'])->name('movement.status');
});

require __DIR__.'/auth.php';
