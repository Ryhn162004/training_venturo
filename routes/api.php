<?php

use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\RoleController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Master\CustomerController;
use App\Http\Controllers\Api\Master\ItemController;
use App\Http\Controllers\Api\Master\PromoController;
use App\Http\Controllers\Api\Master\DiskonController;
use App\Http\Controllers\Api\Master\VoucherController;
use App\Http\Controllers\Api\Report\OrderController;
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

Route::prefix('v1')->group(function () {
    /**
     * CRUD user
     */
    Route::get('/users', [UserController::class, 'index'])->middleware(['web', 'auth.api:user_view']);
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware(['web', 'auth.api:user_view']);
    Route::post('/users', [UserController::class, 'store'])->middleware(['web', 'auth.api:user_create']);
    Route::put('/users', [UserController::class, 'update'])->middleware(['web', 'auth.api:user_update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware(['web', 'auth.api:user_delete']);

    /**
     * CRUD role / hak akses
     */
    Route::get('/roles', [RoleController::class, 'index'])->middleware(['web', 'auth.api:roles_view']);
    Route::get('/roles/{id}', [RoleController::class, 'show'])->middleware(['web', 'auth.api:roles_view']);
    Route::post('/roles', [RoleController::class, 'store'])->middleware(['web', 'auth.api:roles_create']);
    Route::put('/roles', [RoleController::class, 'update'])->middleware(['web', 'auth.api:roles_update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->middleware(['web', 'auth.api:roles_delete']);

     /**
     * CRUD customer
     */
    Route::get('/customers', [CustomerController::class, 'index'])->middleware(['web', 'auth.api:customer_view']);
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->middleware(['web', 'auth.api:customer_view']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::put('/customers', [CustomerController::class, 'update'])->middleware(['web', 'auth.api:customer_update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->middleware(['web', 'auth.api:customer_delete']);

     /**
     * CRUD items / produk
     */
    Route::get('/items', [ItemController::class, 'index'])->middleware(['web', 'auth.api:item_view']);
    Route::get('/items/{id}', [ItemController::class, 'show'])->middleware(['web', 'auth.api:item_view']);
    Route::post('/items', [ItemController::class, 'store'])->middleware(['web', 'auth.api:item_create']);
    Route::put('/items', [ItemController::class, 'update'])->middleware(['web', 'auth.api:item_update']);
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->middleware(['web', 'auth.api:item_delete']);

    /**
     * CRUD promo
     */
    Route::get('/promo', [PromoController::class, 'index'])->middleware(['web', 'auth.api:promo_view']);
    Route::get('/promo_table', [PromoController::class, 'index_table'])->middleware(['web', 'auth.api:promo_view']);
    Route::get('/promo/{id}', [PromoController::class, 'show'])->middleware(['web', 'auth.api:promo_view']);
    Route::post('/promo', [PromoController::class, 'store'])->middleware(['web', 'auth.api:promo_create']);
    Route::put('/promo', [PromoController::class, 'update'])->middleware(['web', 'auth.api:promo_update']);
    Route::delete('/promo/{id}', [PromoController::class, 'destroy'])->middleware(['web', 'auth.api:promo_delete']);

    /**
     * CRUD diskon
     */
    Route::get('/diskon', [DiskonController::class, 'index'])->middleware(['web', 'auth.api:diskon_view']);
    Route::get('/diskon/{id}', [DiskonController::class, 'show'])->middleware(['web', 'auth.api:diskon_view']);
    Route::post('/diskon', [DiskonController::class, 'store']);
    Route::put('/diskon', [DiskonController::class, 'update'])->middleware(['web', 'auth.api:diskon_update']);
    Route::delete('/diskon/{id}', [DiskonController::class, 'destroy'])->middleware(['web', 'auth.api:diskon_delete']);

    /**
     * CRUD voucher
     */
    Route::get('/voucher', [VoucherController::class, 'index'])->middleware(['web', 'auth.api:voucher_view']);
    Route::get('/voucher/{id}', [VoucherController::class, 'show'])->middleware(['web', 'auth.api:voucher_view']);
    Route::post('/voucher', [VoucherController::class, 'store'])->middleware(['web', 'auth.api:voucher_create']);
    Route::put('/voucher', [VoucherController::class, 'update'])->middleware(['web', 'auth.api:voucher_update']);
    Route::delete('/voucher/{id}', [VoucherController::class, 'destroy'])->middleware(['web', 'auth.api:voucher_delete']);

    /**
     * Get Report Penjualan
     */
    Route::get('/report', [OrderController::class, 'index']);
    Route::get('/report_menu', [OrderController::class, 'getAllPenjualanMenu']);

    /**
     * Route khusus authentifikasi
     */
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile'])->middleware(['auth.api']);
        Route::get('/csrf', [AuthController::class, 'csrf'])->middleware(['web']);
    });
});

Route::get('/', function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});

/**
 * Jika Frontend meminta request endpoint API yang tidak terdaftar
 * maka akan menampilkan HTTP 404
 */
Route::fallback(function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});
