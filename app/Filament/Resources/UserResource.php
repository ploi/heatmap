<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = -2;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->required(),

                    TextInput::make('email')
                        ->unique('users', 'email', ignoreRecord: true)
                        ->email()
                        ->required(),

                    TextInput::make('password')
                        ->label('Password')
                        ->lazy()
                        ->dehydrated(fn($state) => filled($state))
                        ->dehydrateStateUsing(fn($state) => bcrypt($state))
                        ->helperText(function ($record) {
                            return $record ? 'Leave empty to keep current password' : 'Leave empty to generate a password.';
                        })
                        ->rules([
                            'confirmed'
                        ])
                        ->password(),
                    TextInput::make('password_confirmation')
                        ->label('Password confirmation')
                        ->dehydrated(fn($state) => filled($state))
                        ->dehydrateStateUsing(fn($state) => bcrypt($state))
                        ->visible(function ($get) {
                            return filled($get('password'));
                        })
                        ->required()
                        ->password(),

                    Placeholder::make('created_at')
                        ->label('Created Date')
                        ->visibleOn('edit')
                        ->content(fn(?User $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                    Placeholder::make('updated_at')
                        ->label('Last Modified Date')
                        ->visibleOn('edit')
                        ->content(fn(?User $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                ])->columns()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->sortable()
                    ->dateTime('Y-m-d H:i:s'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
