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

    public $url = '/test/Document.html';

    public $size = 'lgAndXl';

    public $site;

    protected $listeners = ['urlChanged' => 'changeUrl'];

    public function changeUrl($url)
    {
        $this->url = $url;
        $this->getClicks();
    }

    public function mount($site)
    {
        $this->getSite($site);
        $this->getClicks();
    }

    public function getSite($site)
    {
        $this->site = Site::findOrFail($site);
    }

    public function getClicks()
    {
        ray($this->url);
        ray()->showQueries();
        $this->clicks = $this->site
            ->clicks()
            ->{$this->size}()
            ->where('path', $this->url)
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

        ray($this->clicks);
    }
}
