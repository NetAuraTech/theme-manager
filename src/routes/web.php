<?php

use Illuminate\Support\Facades\Route;
use NetAuraTech\ThemeManager\Http\Controllers\AssetController;

Route::get('/assets/themes/{path}', [AssetController::class, 'show'])
    ->where('path', '.*')
    ->name('themes.assets');