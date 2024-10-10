<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/', function (Request $request) {return "API";});
Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class,'register']);
Route::get('display', [AuthController::class,'display']);
Route::get('laundycateg', [AuthController::class,'laundycateg']);

Route::post('addtrans', [AuthController::class, 'addtrans']);
Route::get('cancelTrans/{id}', [AuthController::class, 'cancelTrans']);
