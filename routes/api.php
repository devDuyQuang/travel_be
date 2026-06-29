<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\CustomerPasswordController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\MailController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServiceRegistrationApiController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::apiResource('menu', MenuController::class)->only(['index', 'show']);
Route::apiResource('category', CategoryController::class)->only(['index', 'show']);
Route::apiResource('post', PostController::class)->only(['index', 'show']);
Route::get('/tags', [TagController::class, 'index'])->name('api.tags.index');
Route::get('/team-members', [TeamMemberController::class, 'index'])->name('api.team-members.index');
Route::get('/team-members/{slug}', [TeamMemberController::class, 'show'])->name('api.team-members.show');
Route::get('/faqs', [FaqController::class, 'index'])->name('api.faqs.index');
Route::get('/resolve/{slug}', [PostController::class, 'resolve'])
    ->where('slug', '[A-Za-z0-9\-]+');

Route::get('/product', [ProductController::class, 'index'])->name('api.product.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('api.product.show');

Route::get('post/{id}/related', [PostController::class, 'relatedPosts']);
Route::apiResource('setting', SettingController::class)->only(['index']);

Route::post('/register-service', [ServiceRegistrationApiController::class, 'store']);
Route::post('/bookings', [BookingController::class, 'store'])->middleware('throttle:transaction-create');
Route::post('/orders', [OrderController::class, 'store'])->middleware('throttle:transaction-create');
Route::post('/customer/password/reset', [CustomerPasswordController::class, 'reset'])
    ->middleware('throttle:6,1');

Route::middleware('web')->prefix('customer')->group(function () {
    Route::post('/login', [CustomerAuthController::class, 'login'])
        ->middleware('throttle:5,1');
    Route::get('/me', [CustomerAuthController::class, 'me'])
        ->middleware('auth:customer');
    Route::post('/logout', [CustomerAuthController::class, 'logout'])
        ->middleware('auth:customer');
});

Route::get('/comments', [CommentController::class, 'index']);
Route::post('/comments', [CommentController::class, 'store']);

Route::post('/sendmail', [MailController::class, 'sendMail']);

Route::get('post_sitemap', [PostController::class, 'sitemap']);

Route::post('/contact', [ContactController::class, 'store']);
