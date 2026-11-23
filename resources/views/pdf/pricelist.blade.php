<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $settings['hospital_name'] ?? 'Capiz Medical Center' }} — Citizen's Charter Pricelist</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 20px;
        }
        .header {
            border-bottom: 2px solid #0f2d52;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .hospital-name {
            font-size: 16px;
            font-weight: bold;
            color: #0f2d52;
            margin: 0;
        }
        .subtitle {
            font-size: 10px;
            color: #0e4a86;
            margin: 2px 0 0 0;
        }
        .meta {
            font-size: 8.5px;
            color: #475569;
            margin-top: 4px;
        }
        .category-block {
            margin-bottom: 16px;
        }
        .category-title {
            background-color: #0f2d52;
            color: #ffffff;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: bold;
            margin: 0;
            page-break-after: avoid;
        }
        tr {
            page-break-inside: avoid;
        }
        .category-desc {
            background-color: #f1f5f9;
            padding: 4px 10px;
            font-size: 8.5px;
            color: #475569;
            border-left: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        th {
            background-color: #e2e8f0;
            color: #0f2d52;
            text-align: left;
            padding: 5px 7px;
            font-size: 8.5px;
            border: 1px solid #cbd5e1;
        }
        td {
            padding: 5px 7px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .price {
            font-weight: bold;
            color: #0f2d52;
            white-space: nowrap;
        }
        .notes {
            font-size: 8px;
            color: #64748b;
            margin-top: 2px;
        }
        .badge-avail {
            color: #047857;
            font-weight: bold;
        }
        .badge-unavail {
            color: #be123c;
            font-weight: bold;
        }
        .footer {
            margin-top: 16px;
            padding-top: 8px;
            border-top: 1px solid #cbd5e1;
            font-size: 8px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="hospital-name">{{ $settings['hospital_name'] ?? 'Capiz Medical Center' }}</p>
        <p class="subtitle">
            Citizen's Charter Schedule of Fees
            @if ($singleCategory)
                — {{ $singleCategory->name }}
            @else
                — Complete Hospital Pricelist (Sections 1–8)
            @endif
        </p>
        <p class="meta">
            {{ $settings['location'] ?? 'Brgy. Lanot, Roxas City, Capiz 5800, Philippines' }}
            &nbsp;|&nbsp; Trunklines: {{ $settings['hotline_1'] ?? '(036) 621-0030' }} / {{ $settings['hotline_2'] ?? '(036) 621-0823' }}
            &nbsp;|&nbsp; Generated: {{ $generatedAt }}
        </p>
    </div>

    @foreach ($categories as $category)
        <div class="category-block">
            <h2 class="category-title">{{ $category->name }} ({{ $category->items->count() }} items)</h2>
            @if ($category->description)
                <div class="category-desc">{{ $category->description }}</div>
            @endif
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">Item Code</th>
                        <th style="width: 39%;">Description</th>
                        <th style="width: 15%;">Unit / Spec</th>
                        <th style="width: 13%;" class="text-right">Rate (PHP)</th>
                        <th style="width: 10%;" class="text-center">Status</th>
                        <th style="width: 8%;">Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($category->items as $item)
                        <tr>
                            <td>{{ $item->item_code }}</td>
                            <td>
                                <strong>{{ $item->name }}</strong>
                                @if ($item->notes)
                                    <div class="notes">{{ $item->notes }}</div>
                                @endif
                            </td>
                            <td>{{ $item->unit ?: '—' }}</td>
                            <td class="text-right price">PHP {{ number_format((float) $item->price, 2) }}</td>
                            <td class="text-center">
                                <span class="{{ $item->is_available ? 'badge-avail' : 'badge-unavail' }}">
                                    {{ $item->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>
                            <td>{{ $item->updated_at?->format('Y-m-d') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No items encoded in this category.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        {{ $settings['hospital_name'] ?? 'Capiz Medical Center' }} &bull;
        "{{ $settings['tagline'] ?? 'Pag-atipan nga may gugma' }}" &bull;
        Official Citizen's Charter Document. Qualified indigent/sponsored PhilHealth members are eligible for No Balance Billing (NBB) in service wards.
    </div>
</body>
</html>
