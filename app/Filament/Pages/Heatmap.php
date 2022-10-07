<?php

namespace App\Filament\Pages;

use App\Models\Click;
use App\Models\Site;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Pages\Actions\Action;

class Heatmap extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.heatmap';

    protected static ?string $slug = 'sites/{site}/heatmap';

    protected static bool $shouldRegisterNavigation = false;

    public $clicks;
    public $initialUrl = null;
    public $url = null;
    public $path = '/';
    public $size = 'mdAndLg';
    public $site;
    public $frameWidth = Click::LG_BREAKPOINT - 1;
    public $frameHeight = 2500;
    public $sizeCounts = [];
    public Carbon|null $date = null;
    public Carbon|null $endDate = null;

    protected $listeners = ['urlChanged' => 'changeUrl'];

    protected function getTitle(): string
    {
        return 'Heatmap - ' . $this->path;
    }

    public function mount($site)
    {
        $this->setDate(now());
        $this->getSite($site);
        $this->getClicks();
        $this->getClickCounts();

        $this->emit('heatmapNeedsRendering');
    }

    public function changeUrl($url)
    {
        $parse = parse_url($url);

        $this->url = $parse['scheme'] . '://' . $parse['host'];
        $this->path = $parse['path'] ?? '/';

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

        $parse = parse_url($this->site->domain);

        $this->initialUrl = $parse['scheme'] . '://' . $parse['host'];
        $this->url = $parse['scheme'] . '://' . $parse['host'];
        $this->path = $parse['path'] ?? '/';
    }

    public function getFullUrl(): string
    {
        return $this->initialUrl . $this->path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getClicks()
    {
        $this->clicks = $this->site
            ->clicks()
            ->{$this->size}()
            ->where('path', $this->getPath())
            ->when($this->endDate, function ($query) {
                return $query
                    ->where('created_at', '>=', $this->date)
                    ->where('created_at', '<=', $this->endDate);
            }, function ($query) {
                return $query->whereDate('created_at', $this->date->format('Y-m-d'));
            })
            ->get()
            ->map(function (Click $click) {
                return collect($click->data)
                    ->map(function ($dataPoint) use ($click) {
                        $originalScaleWidth = 0;

                        // We only calculate if the breakpoint is higher than mobile
                        if ($click->width > Click::SM_BREAKPOINT - 1) {
                            $originalScaleWidth = ($this->frameWidth - $click->width) / 2;
                        }

                        return [
                            'x' => floor($dataPoint['x'] + $originalScaleWidth),
                            'y' => floor($dataPoint['y']),
                        ];
                    });
            })
            ->flatten(1);
    }

    public function getClickCounts()
    {
        $this->sizeCounts = [
            'smAndLower' => $this->site->clicks()->whereDate('created_at', $this->date->format('Y-m-d'))->smAndLower()->count(),
            'smAndMd' => $this->site->clicks()->whereDate('created_at', $this->date->format('Y-m-d'))->smAndMd()->count(),
            'mdAndLg' => $this->site->clicks()->whereDate('created_at', $this->date->format('Y-m-d'))->mdAndLg()->count(),
            'lgAndXl' => $this->site->clicks()->whereDate('created_at', $this->date->format('Y-m-d'))->lgAndXl()->count(),
            'xlAndXxl' => $this->site->clicks()->whereDate('created_at', $this->date->format('Y-m-d'))->xlAndXxl()->count(),
            'xxlAndHigher' => $this->site->clicks()->whereDate('created_at', $this->date->format('Y-m-d'))->xxlAndHigher()->count(),
        ];
    }

    public function setFrameSize()
    {
        $this->frameWidth = match ($this->size) {
            'smAndLower' => Click::SM_BREAKPOINT - 1,
            'smAndMd' => Click::MD_BREAKPOINT - 1,
            'mdAndLg' => Click::LG_BREAKPOINT - 1,
            'lgAndXl' => Click::XL_BREAKPOINT - 1,
            'xlAndXxl' => Click::XXL_BREAKPOINT - 1,
            'xxlAndHigher' => Click::XXL_BREAKPOINT - 1,
            default => Click::XL_BREAKPOINT - 1,
        };
    }

    public function setDate(Carbon $date, Carbon|null $endDate = null)
    {
        $this->date = $date;
        $this->endDate = $endDate;
    }

    protected function getActions(): array
    {
        return [
            Action::make('this_week')
                ->color('secondary')
                ->action(function () {
                    $this->setDate(now()->endOfDay(), now()->subWeek()->startOfDay());

                    $this->getClicks();
                    $this->getClickCounts();

                    $this->emit('heatmapNeedsRendering');

                    Notification::make()->success()->title('Filter')->body('Showing data from this week')->send();
                }),

            Action::make('yesterday')
                ->color('secondary')
                ->disabled(function () {
                    return $this->date->isYesterday();
                })
                ->action(function () {
                    $this->setDate(now()->subDay());

                    $this->getClicks();
                    $this->getClickCounts();

                    $this->emit('heatmapNeedsRendering');

                    Notification::make()->success()->title('Filter')->body('Showing data from yesterday')->send();
                }),

            Action::make('today')
                ->color('secondary')
                ->disabled(function () {
                    return $this->date->isToday();
                })
                ->action(function () {
                    $this->setDate(now());

                    $this->getClicks();
                    $this->getClickCounts();

                    $this->emit('heatmapNeedsRendering');

                    Notification::make()->success()->title('Filter')->body('Showing data from today')->send();
                }),
        ];
    }
}
