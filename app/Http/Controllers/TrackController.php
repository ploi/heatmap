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
        $site = Site::where('hash', $request->input('hash'))->firstOrFail();

        if(! $site->active) {
            return [];
        }

        $client = Client::updateOrCreate([
            'identifier' => $request->anonymizedIdentifier(),
        ], [
            'country' => Location::get()->countryCode ?? null,
            'width' => $request->input('width'),
            'height' => $request->input('height'),
            'last_seen_at' => now(),
        ]);

        if ($request->input('clicks')) {
            $this->handleClicks($request, $site, $client);
        }

        if ($request->input('movements')) {
            $this->handleMovements($request, $site, $client);
        }

        return [];
    }

    protected function handleClicks(Request $request, Site $site, Client $client)
    {
        $site->clicks()->create([
            'data' => $request->input('clicks'),
            'width' => $request->input('width'),
            'height' => $request->input('height'),
            'path' => $request->input('path'),
            'client_id' => $client->id,
        ]);
    }

    protected function handleMovements(Request $request, Site $site, Client $client)
    {
        $site->movements()->create([
            'data' => $request->input('movements'),
            'width' => $request->input('width'),
            'height' => $request->input('height'),
            'path' => $request->input('path'),
            'client_id' => $client->id,
        ]);
    }
}
