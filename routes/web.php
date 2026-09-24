<?php

use App\Http\Controllers\ManagedRiderContactController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::livewire('aplica/centru', 'pages::centers.apply')->name('centers.apply');
Route::livewire('aplica/monitor', 'pages::professionals.apply')->name('professionals.apply');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard-selector')->name('dashboard');
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
    Route::view('tutore/panou', 'dashboards.guardian')->middleware('can:access-guardian')->name('guardian.dashboard');
    Route::get('calareti/{riderProfile}/profil', [ManagedRiderContactController::class, 'edit'])
        ->name('riders.profile.edit');
    Route::patch('calareti/{riderProfile}/profil', [ManagedRiderContactController::class, 'update'])
        ->name('riders.profile.update');
});

require __DIR__.'/settings.php';
