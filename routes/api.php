<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('tasks', [TaskController::class, 'index']);
Route::prefix('/task')->group(function() {
    Route::post('/store', [TaskController::class, 'store']);
    Route::put('/{task_id}', [TaskController::class, 'update']);
    Route::delete('/{task_id}', [TaskController::class, 'destroy']);
    Route::post('/retrieve/{task_id}', [TaskController::class, 'retrieve']);

    Route::prefix('/{task_id}/subTask')->group(function() {
        Route::post('/store', [SubTaskController::class, 'store']);
        Route::put('/{subTask_id}', [SubTaskController::class, 'update']);
        Route::delete('/{subTask_id}', [SubTaskController::class, 'destroy']);
        Route::post('/{subTask_id}/markAsComplete', [SubTaskController::class, 'markAsComplete']);
        Route::post('/{subTask_id}/markAsIncomplete', [SubTaskController::class, 'markAsIncomplete']);
    });
});

Route::get('/categories', [CategoryController::class, 'index']);
Route::prefix('/category')->group(function() {
    Route::post('/store', [CategoryController::class, 'store']);
});
