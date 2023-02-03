<?php

use App\Http\Controllers\AccountController;
use App\Http\Middleware\CheckEmailUniqueMiddleware;
use App\Http\Middleware\CheckUsernameUniqueMiddleware;
use App\Models\Account;
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


Route::prefix('/account')
    ->group(function () {

        //account register route
        Route::post('/register', [AccountController::class, 'register'])->middleware('register');

        //account login route
        Route::post('/login', [AccountController::class, 'login']);
    });
