<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Api\ActivityApplicationController;

Route::get('/', [PageController::class, 'landing']);
Route::get('/login', [PageController::class, 'login']);
Route::get('/dashboard', [PageController::class, 'dashboard']);
Route::get('/campus-map', [PageController::class, 'campusMap']);
Route::get('/module/{name}', [PageController::class, 'module']);

// Activity application downloads
Route::get('/activity-applications/{id}/word', [ActivityApplicationController::class, 'word']);
Route::get('/activity-applications/{id}/pdf', [ActivityApplicationController::class, 'pdf']);
