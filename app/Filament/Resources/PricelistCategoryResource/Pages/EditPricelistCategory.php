<?php

namespace App\Filament\Resources\PricelistCategoryResource\Pages;

use App\Filament\Resources\PricelistCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPricelistCategory extends EditRecord
{
    protected static string $resource = PricelistCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
