<?php

namespace App\Filament\Resources;

use App\Filament\Pages\Heatmap;
use App\Filament\Resources\SiteResource\Pages;
use App\Filament\Resources\SiteResource\RelationManagers\ClicksRelationManager;
use App\Models\Site;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Notifications\Notification;
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
                    Forms\Components\TextInput::make('domain')
                        ->required()
                        ->url()
                        ->string(),
                ])->columnSpan(1),
                Forms\Components\Card::make([
                    Forms\Components\Fieldset::make('Tracking')
                        ->columnSpan(1)
                        ->schema([
                            Forms\Components\Checkbox::make('track_clicks')->default(true),
                            Forms\Components\Checkbox::make('track_movements')->helperText('Be aware that tracking movements can accumulate data very quickly.'),

                            Forms\Components\Textarea::make('tracker_code')
                                ->visibleOn('edit')
                                ->columnSpan(2)
                                ->afterStateHydrated(function ($component, $state, $record, \Closure $set) {
                                    if ($record) {
                                        $set('tracker_code', $record->getTrackerCodeAsHtml());
                                    }
                                })
                                ->helperText(new HtmlString('This is your tracker code, put this snippet between your <b>&lt;head&gt; .. &lt;/head&gt;</b> tags.'))
                        ]),
                ])->columnSpan(1)
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
                Tables\Columns\TextColumn::make('movements_count')->counts('movements')->label('Movements')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->sortable()->dateTime('Y-m-d H:i:s'),
                Tables\Columns\ToggleColumn::make('active')->label('Active')->sortable();
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('heatmap')
                    ->icon('heroicon-o-desktop-computer')
                    ->url(fn($record) => Heatmap::getUrl(['site' => $record->id])),

                Tables\Actions\Action::make('tracker_code')
                    ->action(function () {
                        return null;
                    })
                    ->icon('heroicon-o-code')
                    ->requiresConfirmation()
                    ->modalHeading('Tracker code')
                    ->modalSubheading(new HtmlString('This is your tracker code, put this snippet between your <b>&lt;head&gt; .. &lt;/head&gt;</b> tags.'))
                    ->modalActions([])
                    ->form([
                        Forms\Components\Textarea::make('tracker_code')->afterStateHydrated(function ($component, $state, $record, \Closure $set) {
                            $set('tracker_code', $record->getTrackerCodeAsHtml());
                        }),
                    ]),

                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('purge')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalSubheading('Select the types you want to purge the data of.')
                    ->form([
                        Forms\Components\DatePicker::make('before')->helperText('Optionally select a date you want to purge the data before.'),
                        Forms\Components\CheckboxList::make('purge')
                            ->options([
                                'clicks' => 'Clicks',
                                'movements' => 'Movements'
                            ])
                            ->default(['clicks'])
                    ])
                    ->action(function (array $data, $record) {
                        if (in_array('clicks', $data['purge'])) {
                            $record->clicks()
                                ->when($data['before'], function ($query) use ($data) {
                                    return $query->where('created_at', '<', Carbon::parse($data['before']));
                                })
                                ->delete();
                        }

                        if (in_array('movements', $data['purge'])) {
                            $record->movements()
                                ->when($data['before'], function ($query) use ($data) {
                                    return $query->where('created_at', '<', Carbon::parse($data['before']));
                                })
                                ->delete();
                        }

                        Notification::make()->success()
                            ->title('Purge')
                            ->body('Data has been cleared')->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ClicksRelationManager::class
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
