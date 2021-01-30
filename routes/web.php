<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::group(['middleware' => ['auth:web']], function () {
    Route::get('/admin', [ \App\Http\Controllers\Admin\dashboardController::class , 'index']);
    Route::get('/scrapping-page', [ \App\Http\Controllers\Admin\dashboardController::class , 'jobDetails']);
});

Route::get('/search',[ \App\Http\Controllers\SearchController::class,'search' ])->name('search');
