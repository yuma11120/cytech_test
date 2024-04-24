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



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\ProductController::class, 'index'])->name('product.index')->middleware('auth');
Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('product.index')->middleware('auth');
Route::post('/', [App\Http\Controllers\ProductController::class, 'index'])->name('product.index')->middleware('auth');
Route::get('/index', [App\Http\Controllers\ProductController::class, 'index'])->name('product.index')->middleware('auth');
Route::post('/index', [App\Http\Controllers\ProductController::class, 'index'])->name('product.index')->middleware('auth');

Route::get('/create', [App\Http\Controllers\ProductController::class, 'store'])->name('product.create')->middleware('auth');
Route::post('/product/new', [App\Http\Controllers\ProductController::class, 'create'])->name('product.new')->middleware('auth');

Route::get('/show/{id}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show')->middleware('auth');
Route::get('/edit/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('product.edit')->middleware('auth');

Route::put('/Product/update/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('product.update')->middleware('auth');
Route::post('/destroy{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('product.destroy');


