<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantApplicationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn() => view('landing'));
Route::post('/restaurant-application', [RestaurantApplicationController::class, 'store'])->name('restaurant-application.store');

require __DIR__.'/auth.php';
