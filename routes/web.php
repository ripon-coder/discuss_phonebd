<?php

use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DiscussionController::class, 'index'])->name('home');
Route::get('/search', [DiscussionController::class, 'search'])->name('search');

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Catch-all Discussion routes (Must be at the bottom)
Route::get('/{slug}', [DiscussionController::class, 'show'])->name('discussions.show');
Route::post('/{slug}', [DiscussionController::class, 'store'])->name('discussions.store');
Route::post('/{slug}/discussions/{discussion}/reply', [DiscussionController::class, 'storeReply'])->name('discussions.reply');
Route::post('/{slug}/discussions/{discussion}/vote', [DiscussionController::class, 'vote'])->name('discussions.vote');
Route::post('/{slug}/discussions/{discussion}/report', [DiscussionController::class, 'report'])->name('discussions.report');


