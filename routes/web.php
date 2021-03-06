<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
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
Route::softDeletes('users', 'UserController');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/', [UserController::class, 'index'])->name('users.index');

Route::resource('/users', UserController::class)->except('index')->middleware('auth');




