<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/tasks', [TaskController::class, 'create']);
Route::get('/tasks/{clientId}', [TaskController::class, 'poll']);
Route::patch('/tasks/{id}', [TaskController::class, 'updateStatus']);
Route::post('/upload', [UploadController::class, 'store']);