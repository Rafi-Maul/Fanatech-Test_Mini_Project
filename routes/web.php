<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;

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

Route::controller(AuthController::class)->group(function() {
    Route::get('login','login')->middleware('guest')->name('login');
    Route::post('login','loginAction')->middleware('guest')->name('login.action');
    Route::post('logout', 'logout')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function() {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');

    // route resource
    Route::resource('inventory', InventoryController::class)->middleware('role:SuperAdmin')->except('create, show, update');
    Route::resource('sales', SalesController::class)->middleware('role:SuperAdmin,Sales')->except('create, show, update');
    Route::resource('purchases', PurchaseController::class)->middleware('role:SuperAdmin,Purchase')->except('create, show, edit');
});
