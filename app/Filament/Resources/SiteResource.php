<?php

namespace App\Filament\Resources;

use App\Filament\Pages\Heatmap;
use App\Filament\Resources\SiteResource\Pages;
use App\Filament\Resources\SiteResource\RelationManagers;
use App\Models\Site;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('domain'),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->sortable()->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('tracker_code')
                    ->action(function () {

                    })
                    ->requiresConfirmation()
                    ->modalHeading('Tracker code')
                    ->modalSubheading('Get your tracker code here, put this snippet between your <head></head.> tags.')
                    ->modalActions([])
                    ->form([
                        Forms\Components\Textarea::make('tracker_code')->afterStateHydrated(function ($component, $state, $record, \Closure $set) {
                            $set('tracker_code', '<script src="' . route('heatmap.js') . '"></script>');
                        })
                    ]),
                Tables\Actions\Action::make('heatmap')->url(fn($record) => Heatmap::getUrl(['site' => $record->id])),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }
}
