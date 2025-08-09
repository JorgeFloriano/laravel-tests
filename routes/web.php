<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;

Route::get(uri:'/', action:[HomeController::class, 'index'])->name(name: 'home');

Route::get(uri:'/login', action:[LoginController::class, 'index'])->name(name: 'login');
Route::post(uri:'/login', action:[LoginController::class, 'store'])->name(name: 'login.store');
