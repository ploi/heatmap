<?php

namespace App\Http\Controllers;

use App\Services\JsObfuscator;
use Illuminate\Http\Request;
use JShrink\Minifier;

class JavascriptTrackerController extends Controller
{
    public function __invoke(Request $request, $hash)
    {
        $js = view('js', [
            'baseUrl' => config('app.url'),
            'url' => route('track'),
            'clicks' => true,
            'movement' => false,
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
}
