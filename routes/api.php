<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\SalesController;

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

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');
//Books endpoints BookController
Route::get('/books', [BookController::class, 'getAllBooks'])->middleware('auth:api');
Route::get('/books/{id}', [BookController::class, 'getById'])->middleware('auth:api');
//Borrow endpoints BorrowedBooksController
Route::post('/borrowed-books', [BorrowController::class, 'borrowBook'])->middleware('auth:api');
Route::get('/borrowed-books', [BorrowController::class, 'getAllBorrowedBooks'])->middleware('auth:api');
Route::put('/borrowed-books/{id}/return', [BorrowController::class, 'returnBook'])->middleware('auth:api');
//Sales endpoints SalesController
Route::post('/sales', [SalesController::class, 'newSale'])->middleware('auth:api');
Route::get('/sales', [SalesController::class, 'getSalesByUser'])->middleware('auth:api');
Route::get('/sales/{id}', [SalesController::class, 'getSaleById'])->middleware('auth:api');