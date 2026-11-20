<?php

namespace App\Filament\Resources\PricelistItemResource\Pages;

use App\Filament\Pages\BulkPricelistImport;
use App\Filament\Resources\PricelistItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPricelistItems extends ListRecords
{
    protected static string $resource = PricelistItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('bulk_import')
                ->label('Bulk Import (.xlsx / .csv)')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('info')
                ->url(BulkPricelistImport::getUrl()),
            Actions\CreateAction::make(),
        ];
    }
}
