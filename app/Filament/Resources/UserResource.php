<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\PricelistCategory;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'System & Administration';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Staff Accounts (RBAC)';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Staff Account & Role')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\Select::make('role')
                        ->options(User::ROLES)
                        ->required()
                        ->live(),
                    Forms\Components\TextInput::make('department')
                        ->maxLength(255),
                    Forms\Components\CheckboxList::make('assigned_categories')
                        ->label('Assigned Pricelist Categories (for Pricelist Encoders)')
                        ->helperText('Leave unchecked to allow editing all 8 pricelist sections.')
                        ->options(fn () => PricelistCategory::query()->orderBy('sort_order')->pluck('name', 'id')->all())
                        ->visible(fn (Forms\Get $get): bool => $get('role') === User::ROLE_PRICELIST_ENCODER)
                        ->columns(2)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->maxLength(255),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active Account')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => User::ROLES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        User::ROLE_SUPER_ADMIN => 'danger',
                        User::ROLE_PRICELIST_ENCODER => 'info',
                        User::ROLE_PUBLIC_INFO_OFFICER => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('department')
                    ->wrap(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
