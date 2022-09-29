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

    public $frameWidth = Click::XL_BREAKPOINT - 1;

    public $sizeCounts = [];

    protected $listeners = ['urlChanged' => 'changeUrl'];

    public function mount($site)
    {
        $this->sizeCounts = [
            'smAndLower' => Click::query()->smAndLower()->count(),
            'smAndMd' => Click::query()->smAndMd()->count(),
            'mdAndLg' => Click::query()->mdAndLg()->count(),
            'lgAndXl' => Click::query()->lgAndXl()->count(),
            'xlAndXxl' => Click::query()->xlAndXxl()->count(),
            'xxlAndHigher' => Click::query()->xxlAndHigher()->count(),
        ];

        $this->getSite($site);
        $this->getClicks();

        $this->emit('heatmapNeedsRendering');
    }

    public function changeUrl($url)
    {
        $this->url = $url;
        $this->getClicks();

        $this->emit('heatmapNeedsRendering');
    }

    public function changeSize($size)
    {
        $this->size = $size;

        $this->setFrameSize();
        $this->getClicks();

        $this->emit('heatmapNeedsRendering');
    }

    public function getSite($site)
    {
        $this->site = Site::findOrFail($site);
    }

    public function getClicks()
    {
        $this->clicks = $this->site
            ->clicks()
            ->{$this->size}()
            ->where('path', $this->url)
            ->get()
            ->map(function (Click $click) {
                return collect($click->data)
                    ->map(function ($dataPoint) use ($click) {
                        $originalScaleWidth = ($this->frameWidth - $click->width) / 2;
                        return [
                            'x' => floor($dataPoint['x'] + $originalScaleWidth),
                            'y' => floor($dataPoint['y']),
                        ];
                    });
            })
            ->flatten(1);
    }

    public function setFrameSize()
    {
        $this->frameWidth = match($this->size){
            'smAndLower' => Click::SM_BREAKPOINT - 1,
            'smAndMd' => Click::MD_BREAKPOINT - 1,
            'mdAndLg' => Click::LG_BREAKPOINT - 1,
            'lgAndXl' => Click::XL_BREAKPOINT - 1 ,
            'xlAndXxl' => Click::XXL_BREAKPOINT - 1,
            'xxlAndHigher' => Click::XXL_BREAKPOINT - 1,
            default => Click::XL_BREAKPOINT - 1,
        };
    }
}
