<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
