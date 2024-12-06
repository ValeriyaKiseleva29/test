<?php
    use App\Http\Controllers\Company\Admin\DashboardController;
    use App\Http\Controllers\Company\Admin\ProjectController;
    use App\Http\Controllers\Company\Admin\TaskController;
    use App\Http\Controllers\Company\Admin\UserController;
    use App\Http\Controllers\Company\CompanyController;
    use App\Http\Controllers\Company\Lists\ProjectListController;
    use App\Http\Controllers\Company\Lists\TaskListController;
    use App\Http\Controllers\Company\Lists\UserListController;
    use Illuminate\Support\Facades\Route;
    use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

    Route::redirect('/', '/company');


    Route::middleware(['web'])->group(function () {
    Route::prefix('company')->as('company.')->group(function () {
    Route::get('/', [CompanyController::class, 'index'])->name('index');
    Route::post('/', [CompanyController::class, 'store'])->name('store');
    });

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth')->prefix('dashboard')->as('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::prefix('projects')->as('projects.')->group(function () {
    Route::get('/create', [ProjectController::class, 'create'])->name('create');
    Route::post('/', [ProjectController::class, 'store'])->name('store');
    Route::get('/list', [ProjectListController::class, 'index'])->name('list');
    Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [ProjectController::class, 'update'])->name('update');
    Route::delete('/files/{file}', [ProjectController::class, 'destroyFile'])->name('files.destroy');
    Route::delete('/destroy/{id}', [ProjectController::class, 'destroy'])->name('destroy');
        Route::get('/show/{id}', [ProjectController::class, 'bladeShow'])->name('show');


    });


    Route::prefix('users')->as('users.')->group(function () {
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/list', [UserListController::class, 'index'])->name('list');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
    Route::get('/{id}', [UserController::class, 'show'])->name('show');

    });

    Route::prefix('tasks')->as('tasks.')->group(function () {
    Route::get('/create', [TaskController::class, 'create'])->name('create');
    Route::post('/store', [TaskController::class, 'store'])->name('store');
    Route::get('/list', [TaskListController::class, 'index'])->name('list');
    Route::get('/edit/{id}', [TaskController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [TaskController::class, 'update'])->name('update');
    Route::delete('/files/{file}', [TaskController::class, 'destroyFile'])->name('files.destroy');
    Route::delete('/destroy/{id}', [TaskController::class, 'destroy'])->name('destroy');
    Route::get('/show/{id}', [TaskController::class, 'show'])->name('show');

    });

    Route::prefix('company')->as('company.')->group(function () {
    Route::get('/{id}', [DashboardController::class, 'index'])->name('show');
    Route::delete('/{id}', [CompanyController::class, 'destroy'])->name('destroy');
    });
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

