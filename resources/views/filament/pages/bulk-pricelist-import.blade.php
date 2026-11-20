<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
            <div class="text-sm text-gray-600 dark:text-gray-300">
                Need a starting spreadsheet? Download the official template with required column headers (<code class="font-mono text-xs">item_code, name, unit, price, is_available, notes</code>).
            </div>
            <x-filament::button wire:click="downloadTemplate" color="gray" icon="heroicon-o-arrow-down-tray">
                Download Sample Template (.xlsx)
            </x-filament::button>
        </div>

        <form wire:submit="previewImport" class="space-y-4">
            {{ $this->form }}

            <div class="flex items-center gap-3">
                <x-filament::button type="submit" icon="heroicon-o-magnifying-glass">
                    Validate &amp; Preview Changes
                </x-filament::button>
            </div>
        </form>

        @if ($analysis)
            <div class="rounded-xl border border-teal-300 bg-white p-6 shadow-sm dark:border-teal-700 dark:bg-gray-900 space-y-5">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Import Preview</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            File: <strong>{{ $previewFilename }}</strong> &bull;
                            New: <strong class="text-emerald-600">{{ $analysis['summary']['to_create'] }}</strong> &bull;
                            Updates: <strong class="text-amber-600">{{ $analysis['summary']['to_update'] }}</strong> &bull;
                            Skipped/Errors: <strong class="text-rose-600">{{ $analysis['summary']['invalid_count'] }}</strong>
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-filament::button wire:click="cancelPreview" color="gray">
                            Cancel
                        </x-filament::button>
                        @if (count($analysis['valid_rows']) > 0)
                            <x-filament::button wire:click="commitImport" color="success" icon="heroicon-o-check-circle">
                                Confirm &amp; Commit Atomic Update
                            </x-filament::button>
                        @endif
                    </div>
                </div>

                @if (count($analysis['invalid_rows']) > 0)
                    <div class="rounded-lg border border-rose-200 bg-rose-50 p-4 dark:border-rose-800 dark:bg-rose-950/30">
                        <h4 class="text-sm font-bold text-rose-800 dark:text-rose-300 mb-2">Rows with Validation Errors (Will Be Skipped)</h4>
                        <ul class="space-y-1 text-xs text-rose-700 dark:text-rose-200">
                            @foreach ($analysis['invalid_rows'] as $err)
                                <li>Row {{ $err['row_number'] }} ({{ $err['item_code'] }} — {{ $err['name'] }}): {{ $err['errors'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (count($analysis['valid_rows']) > 0)
                    <div class="overflow-x-auto border border-gray-200 rounded-lg dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                            <thead class="bg-gray-50 dark:bg-gray-800 text-left font-semibold text-gray-600 dark:text-gray-300">
                                <tr>
                                    <th class="px-3 py-2.5">Row</th>
                                    <th class="px-3 py-2.5">Action</th>
                                    <th class="px-3 py-2.5">Code</th>
                                    <th class="px-3 py-2.5">Description</th>
                                    <th class="px-3 py-2.5">Unit</th>
                                    <th class="px-3 py-2.5 text-right">Old Rate</th>
                                    <th class="px-3 py-2.5 text-right">New Rate</th>
                                    <th class="px-3 py-2.5 text-center">Available</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach ($analysis['valid_rows'] as $row)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-500">{{ $row['row_number'] }}</td>
                                        <td class="px-3 py-2">
                                            @if ($row['action'] === 'create')
                                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold">CREATE</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-semibold">UPDATE</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 font-mono font-semibold">{{ $row['item_code'] }}</td>
                                        <td class="px-3 py-2">{{ $row['name'] }}</td>
                                        <td class="px-3 py-2 text-gray-500">{{ $row['unit'] ?? '—' }}</td>
                                        <td class="px-3 py-2 text-right text-gray-500">
                                            {{ $row['old_price'] !== null ? '₱' . number_format($row['old_price'], 2) : '—' }}
                                        </td>
                                        <td class="px-3 py-2 text-right font-bold text-gray-900 dark:text-white">
                                            ₱{{ number_format($row['price'], 2) }}
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            {{ $row['is_available'] ? 'Yes' : 'No' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif

        {{-- Audit Trail --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Recent Bulk Import Audit Trail</h3>
            @if ($this->recentLogs->isEmpty())
                <p class="text-sm text-gray-500">No bulk spreadsheet imports have been recorded yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                        <thead class="bg-gray-50 dark:bg-gray-800 text-left font-semibold text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-3 py-2">Timestamp</th>
                                <th class="px-3 py-2">Staff</th>
                                <th class="px-3 py-2">Section</th>
                                <th class="px-3 py-2">Filename</th>
                                <th class="px-3 py-2 text-right">Created</th>
                                <th class="px-3 py-2 text-right">Updated</th>
                                <th class="px-3 py-2 text-right">Skipped</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($this->recentLogs as $log)
                                <tr>
                                    <td class="px-3 py-2 text-gray-500">{{ $log->created_at?->format('M d, Y h:i A') }}</td>
                                    <td class="px-3 py-2 font-medium">{{ $log->user?->name ?? 'System' }}</td>
                                    <td class="px-3 py-2">{{ $log->category?->name ?? '—' }}</td>
                                    <td class="px-3 py-2 font-mono">{{ $log->filename }}</td>
                                    <td class="px-3 py-2 text-right text-emerald-600 font-semibold">{{ $log->rows_created }}</td>
                                    <td class="px-3 py-2 text-right text-amber-600 font-semibold">{{ $log->rows_updated }}</td>
                                    <td class="px-3 py-2 text-right text-rose-600">{{ $log->rows_skipped }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
