<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \Laravel\Passport\Http\Controllers\AccessTokenController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\AuthorController;
use App\Http\Controllers\API\PatronController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/oauth/token', [AccessTokenController::class, 'issueToken']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('patrons/{patron}/borrow/{book}', [PatronController::class, 'borrowBook'])->middleware('auth:api');;
Route::post('patrons/{patron}/return/{book}', [PatronController::class, 'returnBook'])->middleware('auth:api');;
Route::get('/books/searchs', [BookController::class,'search'])->middleware('auth:api');
Route::get('/authors/{author}/books',  [AuthorController::class, 'showBooks'])->middleware('auth:api');;
Route::resource('/books', BookController::class)->middleware('auth:api');
Route::resource('/patrons', PatronController::class)->middleware('auth:api');
Route::resource('/authors',AuthorController::class)->middleware('auth:api');;
Route::post('/authors/{author}/assign-books', [AuthorController::class,'assignAuthor'])->middleware('auth:api');

