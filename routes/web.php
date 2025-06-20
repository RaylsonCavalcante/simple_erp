<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [SalesController::class, 'create'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



// IMPLEMENTAÇÃO DAS TELAS DOS TESTE

// Clientes
Route::resource('clients', ClientController::class);

// Produtos
Route::resource('products', ProductController::class);

// PDF
Route::get('/sales/{sale}/pdf', [SalesController::class, 'generatePdf'])->name('sales.pdf');

// Vendas
Route::resource('sales', SalesController::class);