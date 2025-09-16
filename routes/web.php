<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatTestController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Borrower\PaymentController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReferralCodeController;
use App\Http\Controllers\LimitLoanSettingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NoAuthController;


// Route::get('/', [ChatTestController::class, 'login']);
Route::get('/chat', [ChatTestController::class, 'index']);
Route::post('/test-broadcast', [ChatTestController::class, 'broadcast']);

Route::get('/testingNotif', [NotificationController::class, 'testNotif']);
Route::post('/send-notification', [NotificationController::class, 'send']);
Route::get('/get-notification-data', [NotificationController::class, 'getNotificationData']);
Route::post('/mark-all-read', [NotificationController::class, 'markAllRead']);
Route::post('/clear-all-notifications', [NotificationController::class, 'clearAllNotifications']);





Route::get('/test-broadcast', function () {
    broadcast(new \App\Events\MessageSent('Sebas', 'Test message via GET route'));
    return 'Test broadcasted!';
});

# Landing Page
Route::get('/lending', fn() => view('index'))->name('index');

# multi-purpose website
Route::get('/', [NoAuthController::class, 'landingView']);
Route::get('/jewelry', [NoAuthController::class, 'landingJewelry']);
Route::get('/hub', [NoAuthController::class, 'landingHub']);
Route::get('/travel-and-tours', [NoAuthController::class, 'landingTAT']);

#index page routes - Lending website
Route::get('/login', fn() => view('admin.pages.main.index'))->name('admin.pages.main.index');
Route::post('/register', [RegisterController::class, 'register'])->name('register');


//AUTH
Auth::routes();

// admin routes
Route::prefix('admin')->group(function () {
    // dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.pages.main.index');

    // testing only
    Route::get('/blankpage', [AdminController::class, 'blankTesting'])->name('admin.testing-only.blankpage');

    // admin profile
    Route::get('/profile', [ProfileController::class, 'adminIndex'])->name('admin.pages.profile.index');
    Route::post('/update-profile', [ProfileController::class, 'adminUpdate'])->name('admin.profile.update');

    Route::middleware(['auth'])->prefix('profile')->group(function () {
        Route::get('/change-password', [ProfileController::class, 'adminChangePassword'])->name('admin.pages.change-password');
        Route::post('/change-password', [ProfileController::class, 'adminUpdatePassword'])->name('admin.pages.change-password.update');
    
    
        // referral management
        Route::get('/referral-management', [ReferralCodeController::class, 'index']);
    });

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

    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.pages.settings.index');
    Route::post('/update-loan-settings', [AdminController::class, 'updateLoanSettings']);

    Route::prefix('settings')->group(function () {
        Route::get('/loan-limit-settings', [LimitLoanSettingsController::class, 'index']);
    });

    // page loader
    Route::get('/loan-request', [AdminController::class, 'viewLoanRequest']);

    // get datas
    Route::prefix('loan-request')->group(function () {
        Route::get('data', [AdminController::class, 'getLoanRequests']);
        Route::post('get-loan-data', [AdminController::class, 'getBorrowersApplication']);
        Route::post('/loan/{id}/approve', [AdminController::class, 'approve'])->name('loan.approve');
        Route::post('/loan/{id}/reject', [AdminController::class, 'reject'])->name('loan.reject');
        Route::post('/loan/{id}/reject-field', [AdminController::class, 'rejectField'])->name('loan.rejectField');
        Route::post('/update-loan-status', [AdminController::class, 'updateLoanStatus'])->name('loan.updateStatus');
        Route::post('/get-bank-details', [AdminController::class, 'getBankDetails'])->name('loan.getBankDetails');
        Route::post('/transfer-money', [AdminController::class, 'transferMoeny'])->name('loan.transferMoney');
    });

    

    Route::prefix('referral-code')->group(function () {
        Route::post('save', [ReferralCodeController::class, 'store']);

        // table 
        Route::get('list', [ReferralCodeController::class, 'list']);
        Route::post('update-status', [ReferralCodeController::class, 'updateStatus']);
        Route::post('delete', [ReferralCodeController::class, 'delete']);
    });

    Route::get('/notification-page', [NotificationController::class, 'AdminViewPage']);
    
});
Route::post('check-referral-code', [ReferralCodeController::class, 'checkReferralCode']);

Route::post('/upload', [BlogPostController::class, 'upload']);



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
// Route::get('/repayment-schedule', function () {
//     return view('borrower.pages.repayment-schedule');
// })->name('my-loan.repayment-schedule');

# loan application backend functions
Route::get('/borrower/fetch-income/{id}', [HomeController::class, 'fetchIncome']);
Route::post('/borrower/save-precheck', [HomeController::class, 'savePrecheck']);
Route::post('/borrower/update-loan-details', [HomeController::class, 'updateLoanDetails']);
Route::post('/borrower/final-submit', [HomeController::class, 'finalSubmit']);
Route::get('/notification-page', [NotificationController::class, 'BorrowerViewPage']);

Route::get('/repayment-schedule', [HomeController::class, 'repayment_schedule'])->name('my-loan.repayment-schedule');
# profile page

Route::get('/profile', [ProfileController::class, 'index'])->name('borrower.pages.profile');
Route::post('/update-profile', [ProfileController::class, 'update'])->name('profile.update');

# change password
Route::middleware(['auth'])->prefix('borrower')->name('borrower.')->group(function () {
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('change-password.update');
});

Route::get('/loan-list', [ProfileController::class, 'loanList'])->name('borrower.pages.loan-list');
Route::get('/update-application-info', [App\Http\Controllers\HomeController::class, 'updateInformation'])->name('update-information');
Route::post('/borrower/resubmit-loan-info', [HomeController::class, 'resubmitLoanInfo']);
Route::post('/borrower/resubmit-loan-documents', [HomeController::class, 'resubmitLoanDocuments']);