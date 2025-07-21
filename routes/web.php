<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\DisplayController;


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

Route::get('/', function () {
    return redirect('/home');
});

Route::post('/profileName/edit', [UserController::class, 'editName']);
Route::post('/profilePhoto/edit', [UserController::class, 'editPhoto']);
Route::post('/password/changes', [UserController::class, 'changePassword']);
Route::post('/recoverPhone/add', [UserController::class, 'addPhone']);
Route::post('/recoverEmail/add', [UserController::class, 'addEmail']);
Route::post('/incomeAmount/added', [BalanceController::class, 'added']);
Route::post('/amount/used', [BalanceController::class, 'used']);
Route::post('/password/confirmed', [UserController::class, 'confirmPassword']);

Route::get('wallet', [WalletController::class, 'wallet']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
