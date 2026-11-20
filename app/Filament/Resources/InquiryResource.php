<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InquiryResource\Pages;
use App\Models\Inquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'Public Information';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Local Inquiries';

    public static function getNavigationBadge(): ?string
    {
        $count = Inquiry::query()->where('status', Inquiry::STATUS_NEW)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Submitted Inquiry (RA 10173 Consented)')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')->disabled(),
                    Forms\Components\TextInput::make('contact_info')->label('Contact Details')->disabled(),
                    Forms\Components\TextInput::make('subject')->disabled()->columnSpanFull(),
                    Forms\Components\Textarea::make('message')->rows(5)->disabled()->columnSpanFull(),
                    Forms\Components\Toggle::make('privacy_consented')
                        ->label('RA 10173 Privacy Consent Given')
                        ->disabled(),
                ]),

            Forms\Components\Section::make('Assistance Desk Handling')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options(Inquiry::STATUSES)
                        ->required(),
                    Forms\Components\Textarea::make('staff_notes')
                        ->label('Internal Resolution Notes')
                        ->rows(3),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('contact_info')
                    ->label('Contact')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Inquiry::STATUSES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Inquiry::STATUS_NEW => 'warning',
                        Inquiry::STATUS_IN_PROGRESS => 'info',
                        Inquiry::STATUS_RESOLVED => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(Inquiry::STATUSES),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Review'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInquiries::route('/'),
            'edit' => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }
}
