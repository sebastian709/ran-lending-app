<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ChatTestController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Borrower\PaymentController;

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
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('borrower.pages.home');
Route::get('/verify-otp', [OtpVerificationController::class, 'showForm'])->name('otp.form');
Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
//AJAX
Route::post('/register-auth-send', [OtpVerificationController::class, 'regauthsend'])->name('reg.auth.send');
Route::post('/register-auth-check', [OtpVerificationController::class, 'regauthcheck'])->name('reg.auth.check');
Route::post('/forgot-auth-send', [OtpVerificationController::class, 'forgotauthsend'])->name('forgot.auth.send');
Route::post('/forgot-auth-changepass', [OtpVerificationController::class, 'forgotchangepass'])->name('forgot.change.pass');

// borrower routes
Route::get('/apply-loan', function () {
    return view('borrower.pages.loan-apply');
})->name('loan.apply');

# message pages
Route::get('/loan-success', fn() => view('borrower.layouts.message'))->name('borrower.layouts.message');

Route::get('/active-loan', function () {
    return view('borrower.pages.active-loan');
})->name('loan.active');

//Payment
Route::name('loan.')->group(function () {
    // Route::get('/payment', function () {
    //     return view('borrower.pages.payments.payment');
    // })->name('payment');
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::post('/payment/submit', [PaymentController::class, 'submit']);

    Route::get('/confirm-payment', function () {
        return view('borrower.pages.payments.confirm-payment');
    })->name('payment-confirm');

    Route::get('/payment-success', function () {
        return view('borrower.layouts.payment_success');
    })->name('payment-success');

    Route::get('/payment-history', function () {
        return view('borrower.pages.payments.payment-history');
    })->name('payment-history');

    Route::get('/no_loan', function () {
        return view('borrower.layouts.payment-state');
    })->name('no_loan');
});

//My loans
Route::get('/repayment-schedule', function () {
    return view('borrower.pages.repayment-schedule');
})->name('my-loan.repayment-schedule');

# loan application backend functions
Route::get('/borrower/fetch-income/{id}', [App\Http\Controllers\HomeController::class, 'fetchIncome']);
Route::post('/borrower/save-precheck', [App\Http\Controllers\HomeController::class, 'savePrecheck']);
Route::post('/borrower/update-loan-details', [App\Http\Controllers\HomeController::class, 'updateLoanDetails']);
Route::post('/borrower/final-submit', [App\Http\Controllers\HomeController::class, 'finalSubmit']);


