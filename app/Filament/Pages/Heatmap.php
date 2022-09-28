<?php

namespace App\Filament\Pages;

use App\Models\Click;
use App\Models\Site;
use Filament\Pages\Page;

class Heatmap extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.heatmap';

    protected static ?string $slug = 'heatmap/{site}';

    protected static bool $shouldRegisterNavigation = false;

    public $clicks;

    public $size = 'lgAndXl';

    public function mount($site)
    {
        $this->clicks = Site::findOrFail($site)
            ->clicks()
            ->{$this->size}()
            ->get()
            ->map(function (Click $click) {
                return collect($click->data)->map(function ($dataPoint) use ($click) {
                    $originalScaleWidth = (1200 - $click->width) / 2;

                    return [
                        'x' => floor($dataPoint['x'] + $originalScaleWidth),
                        'y' => floor($dataPoint['y']),
                    ];
                });
            })
            ->flatten(1);

    }
}
