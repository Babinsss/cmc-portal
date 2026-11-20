<?php

namespace App\Filament\Resources\PricelistItemResource\Pages;

use App\Filament\Resources\PricelistItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPricelistItem extends EditRecord
{
    protected static string $resource = PricelistItemResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();
        $data['item_code'] = strtoupper(trim((string) $data['item_code']));

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
