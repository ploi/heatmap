<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Site;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function __invoke(Request $request)
    {
        if (! $request->input('clicks')) {
            return [];
        }

        // TODO:
        $site = Site::first();

        $client = Client::firstOrCreate([
            'identifier' => $request->anonymizedIdentifier(),
        ], [
            'width' => $request->input('width'),
            'height' => $request->input('height'),
        ]);

        $site->clicks()->create([
            'data' => $request->input('clicks'),
            'width' => $request->input('width'),
            'height' => $request->input('height'),
            'path' => $request->input('path'),
            'client_id' => $client->id,
        ]);

        return [];
    }
}
