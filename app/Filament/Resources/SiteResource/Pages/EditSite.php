<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Pages\Heatmap;
use App\Filament\Resources\SiteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSite extends EditRecord
{
    protected static string $resource = SiteResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['tracker_code']);

        return $data;
    }

    protected function getActions(): array
    {
        return [
            Actions\Action::make('view_heatmap')
                ->url(Heatmap::getUrl(['site' => $this->record->id])),
            Actions\DeleteAction::make(),
        ];
    }
}
