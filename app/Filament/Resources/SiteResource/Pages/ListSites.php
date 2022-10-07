<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Pages\Heatmap;
use App\Filament\Resources\SiteResource;
use App\Models\Site;
use Closure;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSites extends ListRecords
{
    protected static string $resource = SiteResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn (Site $record): string => Heatmap::getUrl(['site' => $record->id]);
    }
}
