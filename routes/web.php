<?php

use App\Http\Controllers\AccountRecoveryController;
use App\Http\Controllers\EmailVerificationCodeController;
use App\Http\Controllers\ManagedRiderContactController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('creeaza-cont', 'auth-portal')->middleware('guest')->name('account.choose');
Route::livewire('creeaza-cont/tutore', 'pages::auth.register-guardian')->middleware('guest')->name('guardian.register');
Route::view('centre', 'centers')->name('centers.index');
Route::view('monitori', 'professionals')->name('professionals.index');
Route::view('federatie', 'federation')->name('federation.index');

Route::livewire('aplica/centru', 'pages::centers.apply')->name('centers.apply');
Route::livewire('aplica/monitor', 'pages::professionals.apply')->name('professionals.apply');

Route::post('email/verify-code', EmailVerificationCodeController::class)
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.code');

Route::get('ai-uitat-parola', [AccountRecoveryController::class, 'request'])->name('recovery.request');
Route::post('ai-uitat-parola/cod', [AccountRecoveryController::class, 'send'])->middleware('throttle:6,1')->name('recovery.send');
Route::get('ai-uitat-parola/verifica', [AccountRecoveryController::class, 'verifyForm'])->name('recovery.verify');
Route::post('ai-uitat-parola/verifica', [AccountRecoveryController::class, 'verify'])->middleware('throttle:10,1')->name('recovery.verify.store');
Route::get('ai-uitat-parola/parola-noua', [AccountRecoveryController::class, 'resetForm'])->name('recovery.reset');
Route::post('ai-uitat-parola/parola-noua', [AccountRecoveryController::class, 'reset'])->name('recovery.reset.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('securitate/trimite-cod', [AccountRecoveryController::class, 'sendSecurityCode'])->middleware('throttle:6,1')->name('security.code.send');
    Route::post('securitate/confirma-cod', [AccountRecoveryController::class, 'confirmSecurityCode'])->middleware('throttle:10,1')->name('security.code.confirm');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard-selector')->name('dashboard');
    Route::view('responsabilitati-membri-frte', 'frte-responsibilities')->name('frte.responsibilities');
    Route::livewire('federatie/cereri', 'pages::federation.applications')
        ->middleware('can:access-federation')
        ->name('federation.applications');
    Route::livewire('federatie/cereri/{type}/{applicationId}', 'pages::federation.review')
        ->middleware('can:access-federation')
        ->whereIn('type', ['centru', 'monitor'])
        ->name('federation.review');
    Route::view('centru/panou', 'dashboards.center')->middleware('can:access-center')->name('center.dashboard');
    Route::view('monitor/panou', 'dashboards.monitor')->middleware('can:access-monitor')->name('monitor.dashboard');
    Route::view('calaret/panou', 'dashboards.rider')->middleware('can:access-rider')->name('rider.dashboard');
    Route::livewire('calaret/sesiuni', 'pages::riders.sessions')->middleware('can:access-rider')->name('rider.sessions');
    Route::view('tutore/panou', 'dashboards.guardian')->middleware('can:access-guardian')->name('guardian.dashboard');
    Route::get('calareti/{riderProfile}/profil', [ManagedRiderContactController::class, 'edit'])
        ->name('riders.profile.edit');
    Route::patch('calareti/{riderProfile}/profil', [ManagedRiderContactController::class, 'update'])
        ->name('riders.profile.update');
});

require __DIR__.'/settings.php';
