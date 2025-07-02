<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatTestController;
use App\Http\Controllers\BlogPostController;

Route::get('/', [ChatTestController::class, 'welcome']);
Route::get('/chat', [ChatTestController::class, 'index']);
Route::post('/test-broadcast', [ChatTestController::class, 'broadcast']);


Route::get('/test-broadcast', function () {
    broadcast(new \App\Events\MessageSent('Sebas', 'Test message via GET route'));
    return 'Test broadcasted!';
});

// admin routes
Route::prefix('admin')->group(function () {
    Route::get('/', fn () => view('admin.home'))->name('admin.home');
    Route::get('/dashboard', fn () => view('admin.home'))->name('admin.home');
    Route::get('/about', fn () => view('admin.about'))->name('admin.about');

    Route::prefix('blogpost')->group(function () {
        Route::get('/', [BlogPostController::class, 'index']);
        Route::get('/createBlogpost', fn () => view('admin.blogpost.createBlogpost'))->name('admin.blogpost.createBlogpost');
    });
});


Route::post('/admin/blogpost/store', [BlogPostController::class, 'store']);
