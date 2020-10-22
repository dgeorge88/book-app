<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Book;
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

Route::resource('books', 'BookController');

//custom routes
Route::get('book/author/{author}', 'BookController@searchAuthor');
Route::get('book/category/{category}', 'BookController@searchCategory');
Route::get('book/categories', 'BookController@categories');
Route::get('book/search/{author}/{category}', 'BookController@searchMultiple');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
