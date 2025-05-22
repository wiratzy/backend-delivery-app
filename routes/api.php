<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Owner\RestaurantController;
use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\ItemCategoryController;
use App\Http\Controllers\UserController\HomeController;
use App\Http\Controllers\Admin\AdminRestaurantController;

Route::post('/register', [AuthController::class, 'register'])->middleware('guest')->name('register');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //get restaurant-categories
    Route::get('/restaurant-categories', [CategoryController::class, 'indexRestaurantCategories']);
    Route::get('/restaurant-categories/{id}', [CategoryController::class, 'showRestaurantCategory']);
    //get restaurant-categories

    Route::get('/user', function (Request $request) {
        return response()->json([
            'message' => 'Data Berhasil Diterima!',
            'success' => true,
            'user' => $request->user(),
        ]);
    });
    Route::put('/user', [UserController::class, 'update']);
    Route::post('/user/photo', [UserController::class, 'uploadPhoto']);
    // Routes untuk Customer
    Route::middleware('role:customer')->group(function () {
        Route::get('/user/home', [HomeController::class,'fetchHomeData']);
        // Route::get('/user/home/categories', [HomeController::class, 'getCategories']);
        Route::get('/user/items/categories', [HomeController::class, 'getAllCategories']);
        Route::get('/user/items/category/{category_id}', [ItemController::class,'getItemsByCategory']);
        Route::get('user/item/{item_id}', [ItemController::class, 'show']);
        // Route::get('/user/popular-restaurants', [HomeController::class, 'getPopularRestaurants']);
        // Route::get('/user/most-popular-restaurants', [HomeController::class, 'getMostPopularRestaurants']);
        // Route::get('/user/recent-items', [ItemController::class, 'recent']);
        Route::get('/user/restaurants', [RestaurantController::class, 'index']);
        Route::get('/user/popular', [RestaurantController::class, 'popular']);
        Route::get('/user/most-popular', [RestaurantController::class, 'mostPopular']);
        Route::get('/user/recent', [ItemController::class, 'recent']);
        Route::apiResource('cart', CartController::class)->except(['show']);
        Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'show']);
    });

    // Routes untuk Admin
    Route::middleware('role:admin')->group(function () {
        Route::prefix('restaurant-categories')->group(function () {
            Route::post('/', [CategoryController::class, 'storeRestaurantCategory']);
            Route::put('/{id}', [CategoryController::class, 'updateRestaurantCategory']);
            Route::delete('/{id}', [CategoryController::class, 'destroyRestaurantCategory']);
        });
        Route::apiResource('admin-item-categories', ItemCategoryController::class);
        Route::get('/users/customer', [AdminAccountController::class,'getAllCustomer']);
        Route::get('/users', [AdminAccountController::class,'index']);
        Route::get('/admin/restaurants', [AdminRestaurantController::class,'index']);
        Route::prefix('admin-restaurant-owner')->group(function () {
            Route::get('/', [AdminRestaurantController::class, 'index']);
            Route::get('/{id}', [AdminRestaurantController::class, 'showRestaurantOwner']);
            Route::post('/', [AdminAccountController::class, 'storeRestaurantOwner']);
            Route::put('/{id}', [AdminAccountController::class, 'UpdateRestaurantOwner']);
            Route::delete('/{id}', [AdminAccountController::class, 'DestroyRestaurantOwner']);
        });
        Route::prefix('admin-items')->group(function () {
            Route::get('/', [ItemController::class,'index']);
            Route::get('/{id}', [ItemController::class,'show']);
            Route::post('/{id}', [ItemController::class,'storeForAdmin']);
            Route::put('/{id}', [ItemController::class,'update']);
        });
        // Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
        Route::put('/orders/{id}/assign-driver', [OrderController::class, 'assignDriver']);
    });

    // Routes untuk Restaurant Owner
    Route::middleware('role:restaurant_owner')->group(function () {
        Route::prefix('restaurants')->group(function () {
            Route::get('/', [RestaurantController::class, 'index']);
            Route::get('/{id}', [RestaurantController::class, 'showOwnerRestaurant']);
            Route::post('/', [RestaurantController::class, 'storeRestaurant']);
            Route::put('/{id}', [RestaurantController::class, 'update']);
            Route::delete('/{id}', [RestaurantController::class, 'destroy']);
        });
        Route::prefix('restaurants-items')->group(function () {
            Route::get('/{id}', [ItemController::class,'index']);
            Route::get('/detail/{itemId}', [ItemController::class,'show']);
            Route::post('/', [ItemController::class,'store']);
        });
        Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    });

    // Routes untuk Driver
    Route::middleware('role:driver')->group(function () {
        Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    });

    // Routes untuk Notifikasi (semua role)
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});
