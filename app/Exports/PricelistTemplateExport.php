<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PricelistTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'item_code',
            'name',
            'unit',
            'price',
            'is_available',
            'notes',
        ];
    }

    public function array(): array
    {
        return [
            ['LAB-CBC', 'Complete Blood Count (CBC) with Platelet', 'Per Test', 220.00, 'Yes', 'Results within 1-2 hours (24/7)'],
            ['LAB-FBS', 'Fasting Blood Sugar (FBS)', 'Per Test', 160.00, 'Yes', '8-10 hours fasting required'],
        ];
    }
}
