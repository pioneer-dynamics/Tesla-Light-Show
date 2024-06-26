<?php

use Inertia\Inertia;
use App\Models\LightShow;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\LightShowController;
use App\Http\Controllers\BackgroundUploadsController;

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
        $total_light_shows = LightShow::count();
        return Inertia::render('Dashboard', compact('total_light_shows'));
    })->name('dashboard');

    Route::get('light-shows/my', [LightShowController::class, 'my'])->name('light-shows.my');
    Route::get('light-shows/{light_show}/{filename}.fseq', [LightShowController::class, 'downloadSequence'])->name('light-shows.seq');
    Route::get('light-shows/{light_show}/{filename}.zip', [LightShowController::class, 'downloadZip'])->name('light-shows.zip');
    Route::get('light-shows/{light_show}/{filename}.{ext}', [LightShowController::class, 'downloadAudio'])->where('ext', '.*')->name('light-shows.audio');
    Route::resource('light-shows', LightShowController::class);

    Route::post('bg-uploads/process', [BackgroundUploadsController::class, 'process'])->name('bg-uploads.process');
    Route::delete('bg-uploads/revert', [BackgroundUploadsController::class, 'revert'])->name('bg-uploads.revert');
});
