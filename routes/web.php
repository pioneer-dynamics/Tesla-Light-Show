<?php

use App\Http\Controllers\BackgroundUploadsController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\LightShowController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('light-shows/my', [LightShowController::class, 'my'])->name('light-shows.my');
    Route::get('light-shows/{light_show}/audio', [LightShowController::class, 'downloadAudio'])->name('light-shows.audio');
    Route::get('light-shows/{light_show}/seq', [LightShowController::class, 'downloadSequence'])->name('light-shows.seq');
    Route::resource('light-shows', LightShowController::class);

    Route::post('bg-uploads/process', [BackgroundUploadsController::class, 'process'])->name('bg-uploads.process');
    Route::delete('bg-uploads/revert', [BackgroundUploadsController::class, 'revert'])->name('bg-uploads.revert');
});
