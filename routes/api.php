<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\AdminController;





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// For Brand
Route::get('/', [BrandController::class, 'index']);
Route::post('/addbrands', [BrandController::class, 'add']);
Route::delete('/delete/{id}', [BrandController::class, 'delete']);
// Route::get('/edit/{id}', [BrandController::class, 'edit']);
Route::post('/edit/{id}', [BrandController::class, 'update']);
Route::get('/brand/{id}', [BrandController::class, 'show']);


// For Customer
Route::get('/customer', [CustomerController::class, 'index']);
Route::post('/addCustomer', [CustomerController::class, 'add']);
Route::delete('/deleteCustomer/{id}', [CustomerController::class, 'delete']);
// Route::get('/editCustomer/{id}', [CustomerController::class, 'edit']);
Route::post('/editCustomer/{id}', [CustomerController::class, 'update']);
Route::get('/customer/{id}', [CustomerController::class, 'show']);


// For User
Route::get('/users', [UserController::class, 'index']);
Route::post('/auth/register', [UserController::class, 'createUser']);
Route::post('/auth/login', [UserController::class, 'loginUser']);
Route::delete('/deleteUser/{id}', [UserController::class, 'delete']);
Route::post('/editUser/{id}', [UserController::class, 'update']);
Route::get('/users/{id}', [UserController::class, 'show']);


// For Dealer
Route::get('/dealer', [DealerController::class, 'index']);
Route::post('/adddealer', [DealerController::class, 'store']);
Route::delete('/deleteDealer/{id}', [DealerController::class, 'delete']);
Route::post('/editdealer/{id}', [DealerController::class, 'update']);
Route::get('/dealer/{id}', [DealerController::class, 'show']);



// For Super Admin
Route::post('/superAdmin', [SuperAdminController::class, 'createSuperAdmin']);
Route::post('/editSuperAdmin/{id}', [SuperAdminController::class, 'update']);
Route::delete('/deleteSuperAdmin/{id}', [SuperAdminController::class, 'delete']);
Route::get('/superAdmin', [SuperAdminController::class, 'index']);
Route::get('/superAdmin/{id}', [SuperAdminController::class, 'show']);



// For Admin
Route::post('/admin/register', [AdminController::class, 'createUser']);
Route::post('/admin/login', [AdminController::class, 'loginUser']);
Route::get('/admin', [AdminController::class, 'index']);
Route::delete('/deleteAdmin/{id}', [AdminController::class, 'delete']);
Route::post('/editAdmin/{id}', [AdminController::class, 'update']);
Route::get('/admin/{id}', [AdminController::class, 'show']);





Route::post('/customerOrder', [CustomerOrderController::class, 'store']);













