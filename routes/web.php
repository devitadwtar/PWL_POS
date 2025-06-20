<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Support\Facades\Auth;

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/register', [AuthController::class, 'register'])->name('register.form');
Route::post('/register', [AuthController::class, 'store_user'])->name('register.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [WelcomeController::class, 'index']);
    Route::middleware(['authorize:ADM'])->prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/list', [UserController::class, 'list']);
    Route::get('/create', [UserController::class, 'create']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/create_ajax', [UserController::class, 'create_ajax']);
    Route::post('/ajax', [UserController::class, 'store_ajax']);
    Route::get('/import', [UserController::class, 'import']);
    Route::post('/import_ajax', [UserController::class, 'import_ajax']);
    Route::get('/export_excel', [UserController::class, 'export_excel']);
    Route::get('/export_pdf', [UserController::class, 'export_pdf']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/{id}/edit', [UserController::class, 'edit']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});
    
Route::middleware(['authorize:ADM'])->prefix('level')->group(function () {
    Route::get('/', [LevelController::class, 'index']);
    Route::post('/list', [LevelController::class, 'list']);
    Route::get('/create', [LevelController::class, 'create']);
    Route::post('/', [LevelController::class, 'store']);
    Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
    Route::post('/ajax', [LevelController::class, 'store_ajax']);
    Route::get('/import', [LevelController::class, 'import']);           // tampilkan form import
    Route::post('/import_ajax', [LevelController::class, 'import_ajax']); // proses import via AJAX
    Route::get('/export_excel', [LevelController::class, 'export_excel']);
    Route::get('/export_pdf', [LevelController::class, 'export_pdf']);
    Route::get('/{id}', [LevelController::class, 'show']);
    Route::get('/{id}/edit', [LevelController::class, 'edit']);
    Route::put('/{id}', [LevelController::class, 'update']);
    Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
    Route::delete('/{id}', [LevelController::class, 'destroy']);
});

Route::middleware(['authorize:ADM,KSR'])->prefix('kategori')->group(function () {
    Route::get('/', [KategoriController::class, 'index']);
    Route::get('/import', [KategoriController::class, 'import']);
    Route::post('/import_ajax', [KategoriController::class, 'import_ajax']);
    Route::get('/export_excel', [KategoriController::class, 'export_excel']);
    Route::get('/export_pdf', [KategoriController::class, 'export_pdf']);
    Route::get('/create', [KategoriController::class, 'create']);
    Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
    Route::post('/ajax', [KategoriController::class, 'store_ajax']);
    Route::post('/', [KategoriController::class, 'store']);
    Route::post('/list', [KategoriController::class, 'list']);
    Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);
    Route::get('/{id}/edit', [KategoriController::class, 'edit']);
    Route::put('/{id}', [KategoriController::class, 'update']);
    Route::delete('/{id}', [KategoriController::class, 'destroy']);
    Route::get('/{id}', [KategoriController::class, 'show']);
});

Route::middleware(['authorize:ADM,MNG'])->prefix('barang')->group(function () {
    Route::get('/', [BarangController::class, 'index']); 
    Route::get('/import', [BarangController::class, 'import']);
    Route::post('/import_ajax', [BarangController::class, 'import_ajax']);
    Route::get('/export_excel', [BarangController::class, 'export_excel']);
    Route::get('/export_pdf', [BarangController::class, 'export_pdf']);
    Route::get('/create', [BarangController::class, 'create']);
    Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
    Route::post('/ajax', [BarangController::class, 'store_ajax']);
    Route::post('/', [BarangController::class, 'store']);
    Route::post('/list', [BarangController::class, 'list']);
    Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
    Route::get('/{id}/edit', [BarangController::class, 'edit']);
    Route::put('/{id}', [BarangController::class, 'update']);
    Route::delete('/{id}', [BarangController::class, 'destroy']);
    Route::get('/{id}', [BarangController::class, 'show']);
});


Route::middleware(['authorize:ADM,MNG'])->prefix('supplier')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
    Route::post('/list', [SupplierController::class, 'list'])->name('supplier.list');
    Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::get('/create_ajax', [SupplierController::class, 'create_ajax'])->name('supplier.create_ajax');
    Route::post('/ajax', [SupplierController::class, 'store_ajax'])->name('supplier.store_ajax');
    Route::post('/', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('/import', [SupplierController::class, 'import'])->name('supplier.import');  // Jika ada fitur import
    Route::post('/import_ajax', [SupplierController::class, 'import_ajax'])->name('supplier.import_ajax'); // Jika ada fitur import AJAX
    Route::get('/export_excel', [SupplierController::class, 'export_excel']);
    Route::get('/export_pdf', [SupplierController::class, 'export_pdf']);
    Route::get('/{id}', [SupplierController::class, 'show'])->name('supplier.show');
    Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax'])->name('supplier.edit_ajax');
    Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax'])->name('supplier.update_ajax');
    Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax'])->name('supplier.confirm_ajax');
    Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax'])->name('supplier.delete_ajax');
    Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
});


    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');

});
