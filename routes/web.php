<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::post("/Login", "App\Http\Controllers\UserController@Login");

Route::get("/Show_Staff_Screen", 'App\Http\Controllers\StaffController@Show_Staff_Screen');

Route::post("/Delete", "App\Http\Controllers\StaffController@Delete");

Route::post("/Staff_Create", "App\Http\Controllers\StaffController@Staff_Create");

Route::post("Staff_Detail_Edit", "App\Http\Controllers\StaffController@Staff_Detail_Edit");