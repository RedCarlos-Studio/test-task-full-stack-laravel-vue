<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('tasks', TaskController::class)->only([
    'index', 'show', 'store', 'update', 'destroy'
]);
Route::get('tasks/{task}/set-as-completed', [TaskController::class, 'setAsCompleted']);
Route::get('tasks/{task}/set-as-open', [TaskController::class, 'setAsOpen']);
Route::get('tasks/{task}/set-as-deleted', [TaskController::class, 'setAsDeleted']);
Route::get('tasks/{task}/set-as-not-deleted', [TaskController::class, 'setAsNotDeleted']);
