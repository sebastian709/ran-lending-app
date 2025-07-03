<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatTestController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\Auth\OtpVerificationController;

// Route::get('/', [ChatTestController::class, 'login']);
Route::get('/chat', [ChatTestController::class, 'index']);
Route::post('/test-broadcast', [ChatTestController::class, 'broadcast']);


Route::get('/test-broadcast', function () {
    broadcast(new \App\Events\MessageSent('Sebas', 'Test message via GET route'));
    return 'Test broadcasted!';
});

// admin routes
Route::prefix('admin')->group(function () {
    Route::get('/', fn() => view('admin.home'))->name('admin.home');
    Route::get('/dashboard', fn() => view('admin.home'))->name('admin.home');
    Route::get('/blankpage', fn() => view('admin.blankpage'))->name('admin.blankpage');

    Route::prefix('blogpost')->group(function () {
        Route::get('/', [BlogPostController::class, 'index']);
        Route::get('/filter', [BlogPostController::class, 'index']); // shared lang to
        Route::post('/store', [BlogPostController::class, 'store']);
        Route::get('/createBlogpost', fn() => view('admin.blogpost.createBlogpost'))->name('admin.blogpost.createBlogpost');

    });
});




Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/verify-otp', [OtpVerificationController::class, 'showForm'])->name('otp.form');
Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
