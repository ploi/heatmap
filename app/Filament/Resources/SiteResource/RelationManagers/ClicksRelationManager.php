<?php

namespace App\Filament\Resources\SiteResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClicksRelationManager extends RelationManager
{
    protected static string $relationship = 'clicks';

    protected static ?string $recordTitleAttribute = 'width';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('client.country')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.country')->label('Country'),
                Tables\Columns\TextColumn::make('width')->label('Width'),
                Tables\Columns\TextColumn::make('height')->label('Height'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
