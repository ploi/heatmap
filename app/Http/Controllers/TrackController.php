<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Site;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class TrackController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$request->input('clicks')) {
            return [];
        }

        $site = Site::where('hash', $request->input('hash'))->firstOrFail();

        $client = Client::updateOrCreate([
            'identifier' => $request->anonymizedIdentifier(),
        ], [
            'country' => Location::get()->countryCode ?? null,
            'width' => $request->input('width'),
            'height' => $request->input('height'),
            'last_seen_at' => now()
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
