<?php

use App\Http\Controllers\Company\Admin\ProjectController;
use App\Http\Controllers\Company\Admin\TaskController;
use App\Http\Controllers\Company\Admin\UserController;
use App\Http\Controllers\Company\CompanyController;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

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
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !password_verify($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'role' => $user->role,
    ]);
});
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/mine', [ProjectController::class, 'myProjects']);
//    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::get('/projects/{id}', [ProjectController::class, 'apiShow']);


    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/mine', [TaskController::class, 'myTasks']);
    Route::get('/tasks/{id}', [TaskController::class, 'apiShow'])->name('apiShow');


    Route::get('/workers', [UserController::class, 'getAllWorkers']);

    Route::get('/projects/files/download/{id}', function ($id) {
        $file = File::findOrFail($id);
        $filePath = storage_path('app/public/' . $file->path);

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'Файл не найден'], 404);
        }
        return response()->download($filePath, $file->name);
    })->middleware('auth:sanctum')->name('projects.files.download');

    Route::get('/tasks/files/download/{id}', function ($id) {
        $file = \App\Models\File::findOrFail($id);
        $filePath = storage_path('app/public/' . $file->path);
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'Файл не найден'], 404);
        }
        return response()->download($filePath, $file->name);
    })->middleware('auth:sanctum')->name('tasks.files.download');


});
