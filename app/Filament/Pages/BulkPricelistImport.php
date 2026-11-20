<?php

namespace App\Filament\Pages;

use App\Exports\PricelistTemplateExport;
use App\Models\PricelistCategory;
use App\Models\PricelistImportLog;
use App\Services\PricelistBulkImporter;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BulkPricelistImport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?string $navigationGroup = "Citizen's Charter Pricelist";

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Bulk Import (.xlsx / .csv)';

    protected static ?string $title = "Bulk Citizen's Charter Pricelist Importer";

    protected static string $view = 'filament.pages.bulk-pricelist-import';

    public ?array $data = [];

    public ?array $analysis = null;

    public ?int $previewCategoryId = null;

    public ?string $previewFilename = null;

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null && ($user->isSuperAdmin() || $user->isPricelistEncoder());
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('Upload Rate Sheet (.xlsx / .csv)')
                    ->description('Step 1: Select a hospital section and upload a spreadsheet with columns: item_code, name, unit, price, is_available, notes.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Target Hospital Section')
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
                        Forms\Components\FileUpload::make('spreadsheet')
                            ->label('Excel or CSV File')
                            ->disk('local')
                            ->directory('pricelist-imports')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-excel',
                                'text/csv',
                                'text/plain',
                                '.xlsx',
                                '.xls',
                                '.csv',
                            ])
                            ->maxSize(5120)
                            ->required(),
                    ]),
            ]);
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(new PricelistTemplateExport(), 'cmc-pricelist-import-template.xlsx');
    }

    public function previewImport(PricelistBulkImporter $importer): void
    {
        $state = $this->form->getState();
        $category = PricelistCategory::query()->findOrFail($state['category_id']);
        $user = auth()->user();

        abort_unless($user && $user->canManageCategory($category->id), 403);

        $storedRelativePath = $state['spreadsheet'];
        $fullPath = Storage::disk('local')->path($storedRelativePath);

        $result = $importer->analyze($fullPath, $category);

        if ($result['header_error'] !== null) {
            $this->analysis = null;

            Notification::make()
                ->title('Invalid spreadsheet headers')
                ->body($result['header_error'])
                ->danger()
                ->send();

            return;
        }

        $this->analysis = $result;
        $this->previewCategoryId = $category->id;
        $this->previewFilename = basename((string) $storedRelativePath);

        Notification::make()
            ->title('Spreadsheet validated')
            ->body("Preview ready: {$result['summary']['to_create']} new, {$result['summary']['to_update']} updates, {$result['summary']['invalid_count']} skipped.")
            ->info()
            ->send();
    }

    public function commitImport(PricelistBulkImporter $importer): void
    {
        if (! $this->analysis || ! $this->previewCategoryId) {
            return;
        }

        $category = PricelistCategory::query()->findOrFail($this->previewCategoryId);
        $user = auth()->user();

        abort_unless($user && $user->canManageCategory($category->id), 403);

        $validRows = $this->analysis['valid_rows'] ?? [];
        if ($validRows === []) {
            Notification::make()
                ->title('Nothing to import')
                ->body('No valid rows were found in the preview.')
                ->warning()
                ->send();

            return;
        }

        $log = $importer->commit(
            $validRows,
            $category,
            $user,
            $this->previewFilename ?? 'upload.xlsx',
            (int) ($this->analysis['summary']['invalid_count'] ?? 0),
        );

        // Clean up temporary uploaded file after successful commit
        if (! empty($this->data['spreadsheet'])) {
            Storage::disk('local')->delete($this->data['spreadsheet']);
        }

        $this->analysis = null;
        $this->previewCategoryId = null;
        $this->previewFilename = null;
        $this->form->fill();

        Notification::make()
            ->title("Pricelist updated for {$category->name}")
            ->body("Created {$log->rows_created}, updated {$log->rows_updated}, skipped {$log->rows_skipped}.")
            ->success()
            ->send();
    }

    public function cancelPreview(): void
    {
        $this->analysis = null;
        $this->previewCategoryId = null;
        $this->previewFilename = null;
    }

    public function getRecentLogsProperty()
    {
        return PricelistImportLog::query()
            ->with(['user:id,name', 'category:id,name'])
            ->latest('created_at')
            ->limit(10)
            ->get();
    }
}
