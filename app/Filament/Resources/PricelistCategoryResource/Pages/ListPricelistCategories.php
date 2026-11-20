<?php

namespace App\Filament\Resources\PricelistCategoryResource\Pages;

use App\Filament\Resources\PricelistCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPricelistCategories extends ListRecords
{
    protected static string $resource = PricelistCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
