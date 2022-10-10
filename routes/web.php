<?php

use App\Http\Controllers\JavascriptTrackerController;
use App\Http\Controllers\TrackController;
use Illuminate\Support\Facades\Route;

Route::post('track', TrackController::class)->name('track');
Route::get('{hash}/heatmap.js', JavascriptTrackerController::class)->name('heatmap.js');
