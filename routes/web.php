<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PlaceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

// User authentication
Route::get('/dang-nhap', [AuthController::class, 'showUserLoginForm'])->name('login');
Route::post('/dang-nhap', [AuthController::class, 'userLogin']);
Route::get('/dang-ky', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/dang-ky', [AuthController::class, 'register']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// User routes (protected)
Route::middleware(['auth', 'user.auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'adminLogin']);

    Route::middleware(['auth', 'admin.auth'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::prefix('places')->name('places.')->group(function () {
            Route::get('/', [PlaceController::class, 'index'])->name('index');  // Danh sách địa điểm
            Route::get('/create', [PlaceController::class, 'create'])->name('create'); // Tạo địa điểm mới
            Route::post('/', [PlaceController::class, 'store'])->name('store'); // Lưu địa điểm mới
            Route::get('/{id}/edit', [PlaceController::class, 'edit'])->name('edit'); // Sửa địa điểm
            Route::put('/{id}', [PlaceController::class, 'update'])->name('update'); // Cập nhật địa điểm
            Route::delete('/{id}', [PlaceController::class, 'destroy'])->name('destroy'); // Xóa địa điểm
            Route::delete('/images/{id}', [PlaceController::class, 'deleteImage'])->name('image.delete');
        });
    });
});
