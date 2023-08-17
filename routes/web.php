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

Route::post("/HandleSearchStaff", 'App\Http\Controllers\StaffController@HandleSearchStaff');

Route::get('/Show_order_list', 'App\Http\Controllers\ProjectController@Show_order_list');

Route::post('/Delete_order', 'App\Http\Controllers\ProjectController@Delete_order');

Route::post('/Order_Create', 'App\Http\Controllers\ProjectController@store');

Route::post('/Order_Edit_Detail', 'App\Http\Controllers\ProjectController@Order_Edit_Detail');

Route::post('/GetOrderByID','App\Http\Controllers\ProjectController@Get_Order_By_ID');

Route::post('/HandleSearchOrder', 'App\Http\Controllers\ProjectController@HandleSearchOrder');

Route::post('/GetStaffID', 'App\Http\Controllers\StaffController@GetStaffById');

Route::post("/Actual_Plan", 'App\Http\Controllers\T_project_Controller@Actual_Plan');
