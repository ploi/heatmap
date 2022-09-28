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
    if (!$request->input('clicks')) {
        return [];
    }

    $site = \App\Models\Site::first();

    $client = \App\Models\Client::firstOrCreate([
        'identifier' => $request->anonymizedIdentifier()
    ], [
        'width' => $request->input('width'),
        'height' => $request->input('height'),
    ]);

    $site->clicks()->create([
        'data' => $request->input('clicks'),
        'width' => $request->input('width'),
        'height' => $request->input('height'),
        'path' => $request->input('path'),
        'client_id' => $client->id
    ]);
    return [];
});

Route::get('heatmap.js', function () {
    return response()
        ->view('js', [
            'baseUrl' => config('app.url'),
            'url' => url()->to('/track'),
            'clicks' => true,
            'movement' => false
        ])
        ->header('Content-Type', 'application/javascript');
})->name('heatmap.js');
