<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Services\JsObfuscator;
use Illuminate\Http\Request;
use JShrink\Minifier;

class JavascriptTrackerController extends Controller
{
    public function __invoke(Request $request, $hash)
    {
        $site = $this->getSite($hash);

        $js = view('js', [
            'debug' => config('heatmap.tracker.debug'),
            'baseUrl' => config('app.url'),
            'url' => route('track'),
            'hash' => $hash,
            'clicks' => $site->track_clicks,
            'movement' => $site->track_movements,
            'clickThreshold' => 10,
            'movementsThreshold' => 10,
            'movementDebounce' => 75
        ])->render();

        if (config('heatmap.tracker.obfuscate')) {
            $obfuscator = new JsObfuscator($js);

            $js = $obfuscator->Obfuscate();
        } else {
            $js = Minifier::minify($js);
        }

        return response($js)
            ->header('Content-Type', 'application/javascript');
    }

    protected function getSite($hash): Site
    {
        return cache()->remember('site-' . $hash, now()->addDay(), function () use ($hash) {
            return Site::where('hash', $hash)->firstOrFail();
        });
    }
}
