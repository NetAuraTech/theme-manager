<?php

use Illuminate\Support\Facades\Route;
use Netauratech\ThemeManager\Http\Controllers\Admin\ThemeController;

/**
 * Themes
 */
Route::get('/theme', [ThemeController::class, 'index'])->name('themes.index');
Route::post('/theme', [ThemeController::class, 'upload'])->name('themes.upload');
Route::post('/theme/{theme}', [ThemeController::class, 'define'])->name('themes.define');
Route::delete('/theme/{theme}', [ThemeController::class, 'destroy'])->name('themes.destroy');
Route::get('/theme/{theme}/compile', [ThemeController::class, 'compile'])->name('themes.compile');