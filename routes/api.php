<?php

use App\Http\Controllers\Api\AppointmentApiController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\MailController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Api\DegreeController as ApiDegreeController;
use App\Http\Controllers\Api\ServiceRegistrationApiController;

Route::get('/degrees', [ApiDegreeController::class, 'index']);

Route::apiResource('menu', MenuController::class)->only(['index', 'show']);
Route::apiResource('category', CategoryController::class)->only(['index', 'show']);
Route::apiResource('post', PostController::class)->only(['index', 'show']);
Route::get('post/{id}/related', [PostController::class, 'relatedPosts']);
Route::apiResource('setting', SettingController::class)->only(['index']);

Route::post('/register-service', [ServiceRegistrationApiController::class, 'store']);


Route::get('/comments', [CommentController::class, 'index']);
Route::post('/comments', [CommentController::class, 'store']);


//appointment
Route::post('/appointments', [AppointmentApiController::class, 'store']);

// routes/api.php
Route::post('/sendmail', [MailController::class, 'sendMail']);

// sitemap
Route::get('post_sitemap', [PostController::class, 'sitemap']);


Route::post('/contact', [ContactController::class, 'store']);


