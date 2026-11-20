<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PricelistItemResource\Pages;
use App\Models\PricelistCategory;
use App\Models\PricelistItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;

class PricelistItemResource extends Resource
{
    protected static ?string $model = PricelistItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = "Citizen's Charter Pricelist";

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Pricelist Items';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['category', 'updatedByUser']);
        $user = auth()->user();

        if ($user && $user->isPricelistEncoder() && ! empty($user->assigned_categories)) {
            $query->whereIn('category_id', array_map('intval', $user->assigned_categories));
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Rate Details')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('category_id')
                        ->label('Hospital Section')
                        ->options(function (): array {
                            $query = PricelistCategory::query()->orderBy('sort_order');
                            $user = auth()->user();

                            if ($user && $user->isPricelistEncoder() && ! empty($user->assigned_categories)) {
                                $query->whereIn('id', array_map('intval', $user->assigned_categories));
                            }

                            return $query->pluck('name', 'id')->all();
                        })
                        ->required()
                        ->searchable(),
                    Forms\Components\TextInput::make('item_code')
                        ->label('Item / Service Code')
                        ->required()
                        ->maxLength(64)
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: fn (Unique $rule, Forms\Get $get) => $rule->where('category_id', $get('category_id')),
                        ),
                    Forms\Components\TextInput::make('name')
                        ->label('Item / Procedure Description')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('unit')
                        ->label('Unit / Specification')
                        ->placeholder('e.g., Per Test, Per Day, Per Tablet')
                        ->maxLength(120),
                    Forms\Components\TextInput::make('price')
                        ->label('Rate (PHP)')
                        ->numeric()
                        ->prefix('₱')
                        ->minValue(0)
                        ->step('0.01')
                        ->required(),
                    Forms\Components\Toggle::make('is_available')
                        ->label('Available')
                        ->default(true),
                    Forms\Components\Textarea::make('notes')
                        ->label('Preparation / Billing Notes')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Section')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Description')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('unit')
                    ->label('Unit')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Rate')
                    ->money('PHP')
                    ->sortable()
                    ->alignRight(),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Available')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updatedByUser.name')
                    ->label('Updated By')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Section')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Availability'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPricelistItems::route('/'),
            'create' => Pages\CreatePricelistItem::route('/create'),
            'edit' => Pages\EditPricelistItem::route('/{record}/edit'),
        ];
    }
}
