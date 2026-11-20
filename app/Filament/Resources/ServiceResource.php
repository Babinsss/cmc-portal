<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Public Information';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Medical Services';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Division Overview')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('Division Code (Roman Numeral)')
                        ->placeholder('e.g., I, II, III')
                        ->required()
                        ->maxLength(20),
                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->required(),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('schedule_label')
                        ->label('Operating Hours Badge')
                        ->placeholder('24/7 or 8:00 AM - 5:00 PM, Monday - Friday')
                        ->required()
                        ->maxLength(160),
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\Toggle::make('is_24_7')
                                ->label('24/7 Service')
                                ->default(true),
                            Forms\Components\Toggle::make('is_highlighted')
                                ->label('Highlight Card')
                                ->default(false),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),
                        ]),
                    Forms\Components\Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Subspecialties & Units')
                ->schema([
                    Forms\Components\Repeater::make('subspecialties')
                        ->relationship()
                        ->orderColumn('sort_order')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('schedule_note')
                                ->label('Schedule Note (Optional)')
                                ->placeholder('e.g., 24/7 or Mon–Fri')
                                ->maxLength(120),
                        ])
                        ->defaultItems(0)
                        ->addActionLabel('Add Subspecialty / Unit'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('schedule_label')
                    ->label('Schedule'),
                Tables\Columns\IconColumn::make('is_24_7')
                    ->label('24/7')
                    ->boolean(),
                Tables\Columns\TextColumn::make('subspecialties_count')
                    ->counts('subspecialties')
                    ->label('Units'),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
