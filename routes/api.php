<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\RestoOrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\RestaurantItemController;
use App\Http\Controllers\Admin\AdminRestaurantController;
use App\Http\Controllers\Owner\RestaurantController;
use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\ItemCategoryController;
use App\Http\Controllers\UserController\HomeController;
use App\Http\Controllers\UserController\UserOrderController;

Route::post('/register', [AuthController::class, 'register'])->middleware('guest')->name('register');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //get restaurant-categories
    Route::get('/restaurant-categories', [CategoryController::class, 'indexRestaurantCategories']);
    Route::get('/restaurant-categories/{id}', [CategoryController::class, 'showRestaurantCategory']);
    //get restaurant-categories

    Route::get('user/items-categories', [ItemCategoryController::class, 'getItemsCategories']);
    Route::get('/user', function (Request $request) {

        $user = $request->user();
        return response()->json([
            'message' => 'Data Berhasil Diterima!',
            'success' => true,
            'user' => $user,
        ]);
    });
    Route::put('/user/update', [UserController::class, 'update']);
    Route::post('/user/photo', [UserController::class, 'uploadPhoto']);
    // Routes untuk Customer
    Route::middleware('role:customer')->group(function () {
        Route::get('/user/home', [HomeController::class, 'fetchHomeData']);
        // Route::get('/user/home/categories', [HomeController::class, 'getCategories']);
        Route::get('user/items', [ItemController::class, 'getAllItems']);
        Route::get('/user/items/categories', [HomeController::class, 'getAllCategories']);
        Route::get('/user/items/category/{category_id}', [ItemController::class, 'getItemsByCategory']);
        Route::get('user/item/{item_id}', [ItemController::class, 'show']);
        // Route::get('/user/popular-restaurants', [HomeController::class, 'getPopularRestaurants']);
        // Route::get('/user/most-popular-restaurants', [HomeController::class, 'getMostPopularRestaurants']);
        // Route::get('/user/recent-items', [ItemController::class, 'recent']);
        Route::get('/user/restaurants', [RestaurantController::class, 'indexForCustomer']);
        Route::get('/user/restaurants/{id}', [RestaurantController::class, 'showForCustomer']);

        Route::get('/user/cart', [CartController::class, 'index']);
        Route::post('/user/cart', [CartController::class, 'store']);
        Route::delete('/user/cart', [CartController::class, 'destroy']);
        Route::post('/user/cart/increase', [CartController::class, 'increase']);
        Route::post('/user/cart/decrease', [CartController::class, 'decrease']);

        Route::get('/user/popular', [RestaurantController::class, 'popular']);
        Route::get('/user/most-popular', [RestaurantController::class, 'mostPopular']);
        Route::get('/user/recent', [ItemController::class, 'recent']);
        // Route::apiResource('cart', CartController::class)->except(['show']);

        //alur checkout
        Route::post('/checkout', [RestoOrderController::class, 'checkout']);
        Route::get('/user/my-orders', [UserOrderController::class, 'userOrders']);
        Route::get('/user/orders/{id}', [UserOrderController::class, 'show']);
        Route::put('/user/orders/{id}/status', [UserOrderController::class, 'updateStatus']);
    });

    // Routes untuk Admin
    Route::middleware('role:admin')->group(function () {
        Route::prefix('restaurant-categories')->group(function () {
            Route::post('/', [CategoryController::class, 'storeRestaurantCategory']);
            Route::put('/{id}', [CategoryController::class, 'updateRestaurantCategory']);
            Route::delete('/{id}', [CategoryController::class, 'destroyRestaurantCategory']);
        });
        Route::apiResource('admin-item-categories', ItemCategoryController::class);
        Route::get('/users/customer', [AdminAccountController::class, 'getAllCustomer']);
        Route::put('/users/customer/{id}', [AdminAccountController::class, 'update']);
        Route::delete('/users/customer/{id}', [AdminAccountController::class, 'delete']);
        Route::get('/users', [AdminAccountController::class, 'index']);
        Route::get('/admin/restaurants', [AdminRestaurantController::class, 'index']);
        Route::prefix('/admin')->group(function () {
            Route::get('/restaurants', [AdminRestaurantController::class, 'index']);
            Route::post('/restaurants', [AdminRestaurantController::class, 'store']);
            Route::get('/restaurants/{id}', [AdminRestaurantController::class, 'show']);
            Route::put('/restaurants/{id}', [AdminRestaurantController::class, 'update']);
            Route::delete('/restaurants/{id}', [AdminRestaurantController::class, 'destroy']);
        });
        Route::prefix('admin-items')->group(function () {
            Route::get('/', [ItemController::class, 'index']);
            Route::get('/{id}', [ItemController::class, 'show']);
            Route::post('/{id}', [ItemController::class, 'storeForAdmin']);
            Route::put('/{id}', [ItemController::class, 'update']);
        });
        // Route::apiResource('orders', RestoOrderController::class)->only(['index', 'show']);
        // Route::put('/orders/{id}/assign-driver', [RestoOrderController::class, 'assignDriver']);
    });

    // Routes untuk Restaurant Owner
    Route::middleware('role:restaurant_owner')->group(function () {
        Route::prefix('restaurants')->group(function () {
            Route::get('/drivers', [DriverController::class, 'index']);
            Route::post('/drivers', [DriverController::class, 'store']);
            Route::get('/drivers/{id}', [DriverController::class, 'show']);
            Route::put('/drivers/{id}', [DriverController::class, 'update']);
            Route::delete('/drivers/{id}', [DriverController::class, 'destroy']);
            Route::get('/', [RestaurantController::class, 'index']);
            Route::get('/{id}', [RestaurantController::class, 'showOwnerRestaurant']);
            Route::post('/', [RestaurantController::class, 'storeRestaurant']);
            Route::put('/', [RestaurantController::class, 'update']);
            Route::delete('/{id}', [RestaurantController::class, 'destroy']);


        });
        Route::prefix('restaurants-items')->group(function () {
            Route::get('/', [RestaurantItemController::class, 'index']);
            Route::get('/detail/{itemId}', [RestaurantItemController::class, 'show']);
            Route::post('/', [RestaurantItemController::class, 'store']);
            Route::put('/{id}', [RestaurantItemController::class, 'update']);
            Route::delete('/{id}', [RestaurantItemController::class, 'destroy']);
        });
        Route::get('/get-orders', [RestoOrderController::class, 'restoOrders']);
        Route::get('/restaurants/orders/{id}', [RestoOrderController::class, 'show']);
        Route::put('/restaurants/orders/{id}/status', [RestoOrderController::class, 'updateStatus']);
        Route::post('/restaurants/orders/{id}/assign-driver', [RestoOrderController::class, 'assignDriver']);
        Route::get('/restausrants/drivers/available', [RestoOrderController::class, 'getAvailableDrivers']);



        // Route::apiResource('orders', RestoOrderController::class)->only(['index', 'show']);

    });

    // Routes untuk Driver
    Route::middleware('role:driver')->group(function () {
        Route::apiResource('orders', RestoOrderController::class)->only(['index', 'show']);
    });

    // Routes untuk Notifikasi (semua role)
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});
