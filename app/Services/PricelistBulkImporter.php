<?php

namespace App\Services;

use App\Imports\PricelistSpreadsheetImport;
use App\Models\PricelistCategory;
use App\Models\PricelistImportLog;
use App\Models\PricelistItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PricelistBulkImporter
{
    /**
     * Validate spreadsheet headers, parse rows, and preview create/update diffs.
     *
     * @return array{
     *   header_error: ?string,
     *   valid_rows: array<int, array<string, mixed>>,
     *   invalid_rows: array<int, array<string, mixed>>,
     *   summary: array{total_rows: int, to_create: int, to_update: int, invalid_count: int}
     * }
     */
    public function analyze(string $filePath, PricelistCategory $category): array
    {
        $sheets = Excel::toArray(new PricelistSpreadsheetImport(), $filePath);
        $rawRows = $sheets[0] ?? [];

        if ($rawRows === []) {
            return [
                'header_error' => 'The uploaded spreadsheet is empty or has no data rows beneath the header.',
                'valid_rows' => [],
                'invalid_rows' => [],
                'summary' => ['total_rows' => 0, 'to_create' => 0, 'to_update' => 0, 'invalid_count' => 0],
            ];
        }

        $firstRowKeys = array_keys($rawRows[0]);
        $hasCode = $this->hasAnyKey($firstRowKeys, ['item_code', 'code', 'service_code']);
        $hasName = $this->hasAnyKey($firstRowKeys, ['name', 'description', 'item_name', 'service']);
        $hasPrice = $this->hasAnyKey($firstRowKeys, ['price', 'rate', 'rate_php', 'amount']);

        if (! $hasCode || ! $hasName || ! $hasPrice) {
            return [
                'header_error' => 'Missing required columns. Expected headers: item_code, name (or description), and price (or rate). Optional: unit, is_available, notes.',
                'valid_rows' => [],
                'invalid_rows' => [],
                'summary' => ['total_rows' => 0, 'to_create' => 0, 'to_update' => 0, 'invalid_count' => 0],
            ];
        }

        $existingByCode = PricelistItem::query()
            ->where('category_id', $category->id)
            ->get()
            ->keyBy(fn (PricelistItem $item): string => strtoupper(trim($item->item_code)));

        $validRows = [];
        $invalidRows = [];
        $seenCodes = [];
        $toCreate = 0;
        $toUpdate = 0;

        foreach ($rawRows as $index => $row) {
            $rowNumber = $index + 2; // +1 for 1-based, +1 for header row

            // Skip completely blank trailing lines
            if ($this->isRowBlank($row)) {
                continue;
            }

            $itemCode = strtoupper(trim((string) $this->pick($row, ['item_code', 'code', 'service_code'], '')));
            $name = trim((string) $this->pick($row, ['name', 'description', 'item_name', 'service'], ''));
            $unit = trim((string) $this->pick($row, ['unit', 'specification', 'unit_spec', 'spec'], ''));
            $rawPrice = $this->pick($row, ['price', 'rate', 'rate_php', 'amount'], null);
            $rawAvailable = $this->pick($row, ['is_available', 'available', 'availability', 'status'], true);
            $notes = trim((string) $this->pick($row, ['notes', 'remarks'], ''));

            $errors = [];

            if ($itemCode === '') {
                $errors[] = 'item_code is required';
            } elseif (strlen($itemCode) > 64) {
                $errors[] = 'item_code exceeds 64 characters';
            } elseif (isset($seenCodes[$itemCode])) {
                $errors[] = "Duplicate item_code '{$itemCode}' in spreadsheet";
            }

            if ($name === '') {
                $errors[] = 'name/description is required';
            }

            $parsedPrice = $this->parseNumericPrice($rawPrice);
            if ($parsedPrice === null || $parsedPrice < 0) {
                $errors[] = 'price must be a valid non-negative number';
            }

            if ($errors !== []) {
                $invalidRows[] = [
                    'row_number' => $rowNumber,
                    'item_code' => $itemCode ?: '—',
                    'name' => $name ?: '—',
                    'errors' => implode('; ', $errors),
                ];

                continue;
            }

            $seenCodes[$itemCode] = true;
            $isAvailable = $this->parseBoolean($rawAvailable);

            /** @var ?PricelistItem $existing */
            $existing = $existingByCode->get($itemCode);
            $action = $existing ? 'update' : 'create';

            if ($action === 'create') {
                $toCreate++;
            } else {
                $toUpdate++;
            }

            $validRows[] = [
                'row_number' => $rowNumber,
                'action' => $action,
                'item_code' => $itemCode,
                'name' => $name,
                'unit' => $unit !== '' ? $unit : null,
                'price' => $parsedPrice,
                'old_price' => $existing ? (float) $existing->price : null,
                'is_available' => $isAvailable,
                'notes' => $notes !== '' ? $notes : null,
            ];
        }

        return [
            'header_error' => null,
            'valid_rows' => $validRows,
            'invalid_rows' => $invalidRows,
            'summary' => [
                'total_rows' => count($validRows) + count($invalidRows),
                'to_create' => $toCreate,
                'to_update' => $toUpdate,
                'invalid_count' => count($invalidRows),
            ],
        ];
    }

    /**
     * Atomically apply validated pricelist changes and record an audit trail log.
     *
     * @param  array<int, array<string, mixed>>  $validRows
     */
    public function commit(
        array $validRows,
        PricelistCategory $category,
        User $user,
        string $filename,
        int $invalidCount = 0,
    ): PricelistImportLog {
        return DB::transaction(function () use ($validRows, $category, $user, $filename, $invalidCount): PricelistImportLog {
            $created = 0;
            $updated = 0;

            foreach ($validRows as $row) {
                $existing = PricelistItem::query()
                    ->where('category_id', $category->id)
                    ->where('item_code', $row['item_code'])
                    ->first();

                $attributes = [
                    'name' => $row['name'],
                    'unit' => $row['unit'],
                    'price' => $row['price'],
                    'notes' => $row['notes'],
                    'is_available' => $row['is_available'],
                    'updated_by' => $user->id,
                ];

                if ($existing) {
                    $existing->update($attributes);
                    $updated++;
                } else {
                    PricelistItem::query()->create(array_merge($attributes, [
                        'category_id' => $category->id,
                        'item_code' => $row['item_code'],
                    ]));
                    $created++;
                }
            }

            $category->update(['last_updated_at' => now()]);

            return PricelistImportLog::query()->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'filename' => $filename,
                'rows_created' => $created,
                'rows_updated' => $updated,
                'rows_skipped' => $invalidCount,
                'notes' => "Imported by {$user->name} ({$user->roleLabel()})",
                'created_at' => now(),
            ]);
        });
    }

    private function hasAnyKey(array $keys, array $candidates): bool
    {
        foreach ($candidates as $candidate) {
            if (in_array($candidate, $keys, true)) {
                return true;
            }
        }

        return false;
    }

    private function pick(array $row, array $candidates, mixed $default = null): mixed
    {
        foreach ($candidates as $key) {
            if (array_key_exists($key, $row) && $row[$key] !== null && $row[$key] !== '') {
                return $row[$key];
            }
        }

        return $default;
    }

    private function isRowBlank(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function parseNumericPrice(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return round((float) $value, 2);
        }

        $cleaned = str_replace(['₱', 'PHP', 'php', ',', ' '], '', (string) $value);

        return is_numeric($cleaned) ? round((float) $cleaned, 2) : null;
    }

    private function parseBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $normalized = strtolower(trim((string) $value));

        return ! in_array($normalized, ['0', 'false', 'no', 'n', 'unavailable', 'inactive'], true);
    }
}
