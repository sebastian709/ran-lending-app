<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatTestController;

Route::get('/', [ChatTestController::class, 'welcome']);
Route::get('/chat', [ChatTestController::class, 'index']);
Route::post('/test-broadcast', [ChatTestController::class, 'broadcast']);

Route::get('/test-broadcast', function () {
    broadcast(new \App\Events\MessageSent('Sebas', 'Test message via GET route'));
    return 'Test broadcasted!';
});


Route::prefix('admin')->group(function () {
    Route::get('/', fn () => view('admin.home'))->name('admin.dashboard');
    Route::get('/home', fn () => view('admin.home'))->name('admin.home');
    Route::get('/about', fn () => view('admin.about'))->name('admin.about');
});