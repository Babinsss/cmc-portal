<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Public Information';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Advisories & Notices';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Advisory Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\Select::make('category')
                        ->options([
                            'Advisory' => 'Hospital Advisory',
                            'Public Health' => 'Public Health Notice',
                            'Training & Education' => 'Training & Education',
                            'Memorandum' => 'Official Memorandum',
                        ])
                        ->default('Advisory')
                        ->required(),
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\Toggle::make('is_pinned')
                                ->label('Pin on Home Page')
                                ->default(false),
                            Forms\Components\Toggle::make('is_published')
                                ->label('Published')
                                ->default(true),
                        ]),
                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Publish At')
                        ->default(now()),
                    Forms\Components\DateTimePicker::make('expires_at')
                        ->label('Expires At (Optional)'),
                    Forms\Components\Textarea::make('summary')
                        ->rows(2)
                        ->maxLength(400)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('body')
                        ->required()
                        ->rows(8)
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('image_path')
                        ->label('Announcement Poster / Image')
                        ->image()
                        ->disk('public')
                        ->directory('announcements')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_pinned')
                    ->label('Pinned')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published Date')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
