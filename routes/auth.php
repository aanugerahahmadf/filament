<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('login', 'auth.login')
        ->name('login');

    Volt::route('register', 'auth.register')
        ->name('register');

    Volt::route('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');

    Volt::route('otp-verify', 'otp-verify')
        ->name('otp.verify');

    Volt::route('sms-whatsapp-otp-verify', 'sms-whatsapp-otp-verify')
        ->name('sms.whatsapp.otp.verify');

    Volt::route('whatsapp-otp-verify', 'whatsapp-otp-verify')
        ->name('whatsapp.otp.verify');

    Volt::route('email-otp-verify', 'email-otp-verify')
        ->name('email.otp.verify');

    Volt::route('otp-challenge', 'otp-challenge')
        ->name('otp.challenge');

});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
});

Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
