<?php

namespace App\Filament\Resources\PricelistItemResource\Pages;

use App\Filament\Resources\PricelistItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePricelistItem extends CreateRecord
{
    protected static string $resource = PricelistItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['updated_by'] = auth()->id();
        $data['item_code'] = strtoupper(trim((string) $data['item_code']));

        return $data;
    }
}
