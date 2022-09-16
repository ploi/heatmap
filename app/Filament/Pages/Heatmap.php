<?php

namespace App\Filament\Pages;

use App\Models\Site;
use Filament\Pages\Page;

class Heatmap extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.heatmap';

    public $clicks;

    public function mount()
    {
        $this->clicks = Site::first()->clicks()->pluck('data')->flatten(1);
    }
}
