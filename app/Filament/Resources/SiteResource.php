<?php

namespace App\Filament\Resources;

use App\Filament\Pages\Heatmap;
use App\Filament\Resources\SiteResource\Pages;
use App\Models\Site;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\HtmlString;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('domain')->required()->string()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('favIcon')
                    ->label('')
                    ->width(32)->height(32)->extraImgAttributes(['class' => 'drop-shadow-lg rounded']),
                Tables\Columns\TextColumn::make('domain')->searchable(),
                Tables\Columns\TextColumn::make('clicks_count')->counts('clicks')->label('Clicks')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->sortable()->dateTime('Y-m-d H:i:s'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('heatmap')
                    ->icon('heroicon-o-desktop-computer')
                    ->url(fn ($record) => Heatmap::getUrl(['site' => $record->id])),

                Tables\Actions\Action::make('tracker_code')
                    ->action(function () {
                        return null;
                    })
                    ->icon('heroicon-o-code')
                    ->requiresConfirmation()
                    ->modalHeading('Tracker code')
                    ->modalSubheading(new HtmlString('This is your tracker code, put this snippet between your <head></head> tags.'))
                    ->modalActions([])
                    ->form([
                        Forms\Components\Textarea::make('tracker_code')->afterStateHydrated(function ($component, $state, $record, \Closure $set) {
                            $set('tracker_code', '<script src="'.route('heatmap.js').'"></script>');
                        }),
                    ]),

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
