<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatTestController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\RegisterController;


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;

// Route::get('/', [ChatTestController::class, 'login']);
Route::get('/chat', [ChatTestController::class, 'index']);
Route::post('/test-broadcast', [ChatTestController::class, 'broadcast']);


Route::get('/test-broadcast', function () {
    broadcast(new \App\Events\MessageSent('Sebas', 'Test message via GET route'));
    return 'Test broadcasted!';
});

# Landing Page
Route::get('/lending', fn() => view('index'))->name('index');

# multi-purpose website
Route::get('/', [BlogPostController::class, 'landingView']);
Route::get('/jewelry', [BlogPostController::class, 'landingJewelry']);
Route::get('/hub', [BlogPostController::class, 'landingHub']);
Route::get('/travel-and-tours', [BlogPostController::class, 'landingTAT']);

#index page routes - Lending website
Route::get('/login', fn() => view('admin.pages.main.index'))->name('admin.pages.main.index');

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

Route::post('/register', [RegisterController::class, 'register'])->name('register');


Route::post('/upload', [BlogPostController::class, 'upload']);

//AUTH
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('borrower.pages.home');
Route::get('/verify-otp', [OtpVerificationController::class, 'showForm'])->name('otp.form');
Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
//AJAX
Route::post('/register-auth-send', [OtpVerificationController::class, 'regauthsend'])->name('reg.auth.send');
Route::post('/register-auth-check', [OtpVerificationController::class, 'regauthcheck'])->name('reg.auth.check');
Route::post('/forgot-auth-send', [OtpVerificationController::class, 'forgotauthsend'])->name('forgot.auth.send');
Route::post('/forgot-auth-changepass', [OtpVerificationController::class, 'forgotchangepass'])->name('forgot.change.pass');

# borrower routes
Route::get('/apply-loan', [App\Http\Controllers\HomeController::class, 'loanApply'])->name('loan.apply');

# message pages
Route::get('/loan-success', fn() => view('borrower.layouts.message'))->name('borrower.layouts.message');

# active loan
Route::get('/active-loan', function () {
    return view('borrower.pages.active-loan');
})->name('loan.active');

Route::get('/payment', function () {
    return view('borrower.pages.payment');
})->name('loan.payment');

# loan application backend functions
Route::get('/borrower/fetch-income/{id}', [HomeController::class, 'fetchIncome']);
Route::post('/borrower/save-precheck', [HomeController::class, 'savePrecheck']);
Route::post('/borrower/update-loan-details', [HomeController::class, 'updateLoanDetails']);
Route::post('/borrower/final-submit', [HomeController::class, 'finalSubmit']);


# profile page

Route::get('/profile', [ProfileController::class, 'index'])->name('borrower.pages.profile');
Route::post('/update-profile', [ProfileController::class, 'update'])->name('profile.update');

# change password
Route::middleware(['auth'])->prefix('borrower')->name('borrower.')->group(function () {
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('change-password.update');
});
