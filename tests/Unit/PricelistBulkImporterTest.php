<?php

namespace Tests\Unit;

use App\Models\PricelistCategory;
use App\Models\PricelistItem;
use App\Models\User;
use App\Services\PricelistBulkImporter;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricelistBulkImporterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_bulk_importer_previews_and_commits_atomically_with_audit_log(): void
    {
        $lab = PricelistCategory::query()->where('slug', 'laboratory')->firstOrFail();
        $encoder = User::query()->where('email', 'encoder@cmc.local')->firstOrFail();

        $csvPath = tempnam(sys_get_temp_dir(), 'cmc_import_') . '.csv';
        file_put_contents($csvPath, implode("\n", [
            'item_code,name,unit,price,is_available,notes',
            'LAB-CBC,Complete Blood Count (CBC) with Platelet,Per Test,250.00,Yes,Updated rate',
            'LAB-HBA1C,Glycated Hemoglobin (HbA1c),Per Test,620.00,Yes,New test',
            ',Missing Code Row,Per Test,-10,Yes,Invalid row',
        ]));

        try {
            $importer = app(PricelistBulkImporter::class);
            $analysis = $importer->analyze($csvPath, $lab);

            $this->assertNull($analysis['header_error']);
            $this->assertSame(1, $analysis['summary']['to_create']);
            $this->assertSame(1, $analysis['summary']['to_update']);
            $this->assertSame(1, $analysis['summary']['invalid_count']);

            $log = $importer->commit(
                $analysis['valid_rows'],
                $lab,
                $encoder,
                'lab-rates.csv',
                $analysis['summary']['invalid_count'],
            );

            $this->assertSame(1, $log->rows_created);
            $this->assertSame(1, $log->rows_updated);
            $this->assertSame(1, $log->rows_skipped);

            $updatedCbc = PricelistItem::query()
                ->where('category_id', $lab->id)
                ->where('item_code', 'LAB-CBC')
                ->firstOrFail();
            $this->assertSame('250.00', (string) $updatedCbc->price);

            $createdHba1c = PricelistItem::query()
                ->where('category_id', $lab->id)
                ->where('item_code', 'LAB-HBA1C')
                ->firstOrFail();
            $this->assertSame('620.00', (string) $createdHba1c->price);
            $this->assertSame($encoder->id, $createdHba1c->updated_by);
        } finally {
            @unlink($csvPath);
        }
    }
}
