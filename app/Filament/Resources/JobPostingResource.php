<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobPostingResource\Pages;
use App\Models\JobPosting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JobPostingResource extends Resource
{
    protected static ?string $model = JobPosting::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Public Information';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Job Postings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Vacancy Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('department')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('employment_type')
                        ->options([
                            'Permanent' => 'Permanent (Plantilla)',
                            'Contractual' => 'Contractual',
                            'Job Order' => 'Job Order',
                            'Residency Training' => 'Residency Training',
                        ])
                        ->default('Permanent')
                        ->required(),
                    Forms\Components\TextInput::make('salary_grade')
                        ->placeholder('e.g., SG-16')
                        ->maxLength(64),
                    Forms\Components\TextInput::make('vacancies_count')
                        ->label('Available Slots')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->required(),
                    Forms\Components\DatePicker::make('deadline')
                        ->label('Application Deadline'),
                    Forms\Components\Toggle::make('is_open')
                        ->label('Accepting Applications')
                        ->default(true),
                    Forms\Components\Textarea::make('description')
                        ->rows(4)
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('qualifications')
                        ->rows(5)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('department')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employment_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('vacancies_count')
                    ->label('Slots'),
                Tables\Columns\TextColumn::make('deadline')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_open')
                    ->label('Open')
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
            'index' => Pages\ListJobPostings::route('/'),
            'create' => Pages\CreateJobPosting::route('/create'),
            'edit' => Pages\EditJobPosting::route('/{record}/edit'),
        ];
    }
}
