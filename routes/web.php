<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/admin');
});

Route::post('track', function (\Illuminate\Http\Request $request) {
    ray($request->all());
});

Route::get('heatmap.js', function () {
    return response()
        ->view('js', [
            'url' => 'http://heatmap.test/track',
            'clicks' => true,
            'movement' => false
        ])
        ->header('Content-Type', 'application/javascript');
});
