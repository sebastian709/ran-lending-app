<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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

# Landing Page
Route::get('/', fn() => view('index'))->name('index');

# multi-purpose website
Route::get('/main', [BlogPostController::class, 'landingView']);
Route::get('/jewelry', [BlogPostController::class, 'landingJewelry']);
Route::get('/hub', [BlogPostController::class, 'landingHub']);
Route::get('/travel-and-tours', [BlogPostController::class, 'landingTAT']);




// admin routes
Route::prefix('admin')->group(function () {
    // dashboard
    Route::get('/', fn() => view('admin.pages.main.index'))->name('admin.pages.main.index');

    // testing only
    Route::get('/blankpage', fn() => view('admin.testing-only.blankpage'))->name('admin.testing-only.blankpage');

    // blogpost
    Route::prefix('blogpost')->group(function () {
        Route::get('/', [BlogPostController::class, 'index']);
        Route::get('/filter', [BlogPostController::class, 'index']);
        Route::post('/store', [BlogPostController::class, 'store']);
        Route::get('/createBlogpost', fn() => view('admin.pages.blogpost.createBlogpost'))->name('admin.pages.blogpost.createBlogpost');

        Route::get('/view/{id}', [BlogPostController::class, 'view']);
        Route::get('/fetch/{id}', [BlogPostController::class, 'fetch']);
        Route::post('/update', [BlogPostController::class, 'update']);
        Route::post('/update-status', [BlogPostController::class, 'updateStatus']);

        
    });
});






Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/verify-otp', [OtpVerificationController::class, 'showForm'])->name('otp.form');
Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');

// borrower routes
Route::get('/apply-loan', function () {
    return view('borrower.loan-apply');
})->name('loan.apply');


