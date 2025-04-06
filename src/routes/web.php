<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;

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

Route::get("/", [ItemController::class, "index"]);
Route::get("/item/{item}", [ItemController::class, "item"]);
Route::get("/search", [ItemController::class, "search"]);


Route::middleware('auth')->group(function () {
    Route::post("/address", [UserController::class, "store"]);
    Route::get('/mypage/profile', [UserController::class, "profile"]);
    Route::get("/sell", [ItemController::class, "sell"]);
    Route::post("/sell_register", [ItemController::class, "store"]);
    Route::get("/mypage/mypage", [ItemController::class, "mypage"]);
    Route::post("/comment", [ItemController::class, "comment"]);
    Route::get("/purchase/{item}", [ItemController::class, "purchase"]);
    Route::get("/purchase/address/{item}", [UserController::class, "updateAddress"]);
    Route::patch("/updateaddress", [UserController::class, "update"]);
    Route::post("/transaction", [ItemController::class, "transaction"]);
    Route::delete("/favoritedelete", [ItemController::class, "destroy"]);
    Route::post("/favorite", [ItemController::class, "favorite"]);
});

// Auth::routes(['verify' => true]);
// Route::middleware('verified')->group(function(){
//     Route::get('mypage/profile',function(){
//         return view('profile');
//     });
// });


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
