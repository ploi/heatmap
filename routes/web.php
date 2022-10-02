<?php

use App\Http\Controllers\TrackController;
use Illuminate\Support\Facades\Route;

Route::post('track', TrackController::class);

Route::get('heatmap.js', function () {
    return response()
        ->view('js', [
            'baseUrl' => config('app.url'),
            'url' => url()->to('/track'),
            'clicks' => true,
            'movement' => false,
        ])
        ->header('Content-Type', 'application/javascript');
})->name('heatmap.js');
