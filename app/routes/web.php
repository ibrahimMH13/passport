<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SSO\LoginController;
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

Route::get('/', [HomeController::class,'index'])->name('home')->middleware('refresh.token');

Route::get("/sso",[LoginController::class,'index'])->name("sso.login");
Route::get("sso/oauth/callback",[LoginController::class,'callback'])->name("sso.callback");
Route::get("sso/oauth/authUser",[LoginController::class,'authUser'])->name("sso.user");
Route::get("sso/oauth/refresh",[LoginController::class,'refresh'])->name("sso.refresh");
