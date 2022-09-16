<?php

namespace App\Filament\Pages;

use App\Models\Site;
use Filament\Pages\Page;

class Heatmap extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.heatmap';

    protected static ?string $slug = 'heatmap/{site}';

    protected static bool $shouldRegisterNavigation = false;

    public $clicks;

    public function mount($site)
    {
        $this->clicks = Site::findOrFail($site)->clicks()->pluck('data')->flatten(1);
    }
}
