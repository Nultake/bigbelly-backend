<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ReportController;
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

        Route::get('/search', [ProfileController::class, 'search']);

        Route::prefix('/{id}')
            ->group(function () {

                Route::get('/', [ProfileController::class, 'info']);

                Route::get('/requests', [ProfileController::class, 'requests']);

                Route::get('/followers', [ProfileController::class, 'followers']);
                Route::get('/followeds', [ProfileController::class, 'followeds']);

                Route::get('/posts', [ProfileController::class, 'posts']);
                Route::get('/home-page-posts', [ProfileController::class, 'homePagePosts']);

                Route::post('/edit', [ProfileController::class, 'edit']);

                Route::post('/editProfile', [ProfileController::class, 'editProfile']);
            });

        Route::prefix('/followers')
            ->group(function () {

                Route::post('/unfollow', [ProfileController::class, 'unfollow']);

                Route::post('/cancel-follow-request', [ProfileController::class, 'cancelFollowRequest']);

                Route::post('/follow', [ProfileController::class, 'follow'])->middleware('follow');

                Route::post('/accept', [ProfileController::class, 'accept']);

                Route::post('/decline', [ProfileController::class, 'decline']);
            });
    });

Route::prefix('/post')
    ->group(function () {
        Route::post('/create', [PostController::class, 'create']);

        Route::post('/{id}/image', [PostController::class, 'addImage']);

        Route::post('/{id}/archive', [PostController::class, 'archive']);
        Route::post('/{id}/dearchive', [PostController::class, 'dearchive']);
        Route::get('/get-archiveds', [PostController::class, 'getArchiveds']);

        Route::post('/{id}/recipe', [RecipeController::class, 'recipe']);
        Route::post('/{id}/derecipe', [RecipeController::class, 'derecipe']);
        Route::get('/get-recipes', [RecipeController::class, 'getRecipes']);

        Route::get('/ingredients', [IngredientController::class, 'all']);

        Route::post('/like', [PostController::class, 'like']);
        Route::post('/unlike', [PostController::class, 'unlike']);
        Route::post('/comment', [PostController::class, 'comment']);

        Route::get('/{id}/comments', [PostController::class, 'getComments']);
        Route::get('/{id}/image', [PostController::class, 'getImage']);

        Route::get('/search-by-tag', [PostController::class, 'searchByTag']);
    });

Route::prefix('/collection')
    ->group(function () {
        Route::post('/create', [CollectionController::class, 'create']);

        Route::get('/get', [CollectionController::class, 'get']);

        Route::prefix('/{id}')
            ->group(function () {
                Route::post('/delete', [CollectionController::class, 'delete']);

                Route::get('/getPosts', [CollectionController::class, 'getPosts']);

                Route::post('/addPost', [CollectionController::class, 'addPost']);
                Route::post('/deletePost', [CollectionController::class, 'deletePost']);
            });
    });

Route::prefix('/report')
    ->group(function () {
        Route::post('/comment', [ReportController::class, 'comment']);
        Route::post('/post', [ReportController::class, 'post']);
    });

Route::prefix('/recommendation')
    ->group(function () {
        Route::get('/', [RecommendationController::class, 'recommendation']);
        Route::get('/group', [RecommendationController::class, 'groupRecommendation']);
        Route::get('/history', [RecommendationController::class, 'history']);
    });
