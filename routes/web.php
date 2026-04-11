<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'landing']);
Route::get('/login', [PageController::class, 'login']);
Route::get('/dashboard', [PageController::class, 'dashboard']);
Route::get('/campus-map', [PageController::class, 'campusMap']);
Route::get('/module/{name}', [PageController::class, 'module']);
