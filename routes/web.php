<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckEmailUniqueMiddleware;
use App\Http\Middleware\CheckUsernameUniqueMiddleware;
use App\Models\Account;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

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
        Route::post('/login', [AccountController::class, 'login'])->middleware('login');

        //account verification
        Route::post('/verificate', [AccountController::class, 'verificate']);
    });

Route::prefix('/profile')
    ->group(function () {

        Route::prefix('/followers')
            ->group(function () {

                Route::get('/count', [ProfileController::class, 'count']);

                Route::post('/follow', [ProfileController::class, 'follow'])->middleware('follow');

                Route::post('/accept', [ProfileController::class, 'accept']);

                Route::post('/decline', [ProfileController::class, 'decline']);

                Route::get('/requests', [ProfileController::class, 'requests']);
            });
    });
