<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use App\Models\Inquiry;
use App\Models\JobPosting;
use App\Models\PricelistItem;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PortalStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("Citizen's Charter Rates", PricelistItem::query()->count())
                ->description('Across 8 hospital sections')
                ->color('primary'),
            Stat::make('Active Medical Divisions', Service::query()->where('is_active', true)->count())
                ->description('Clinical & diagnostic services')
                ->color('success'),
            Stat::make('Published Advisories', Announcement::query()->where('is_published', true)->count())
                ->description('Hospital public notices')
                ->color('info'),
            Stat::make('Pending Inquiries', Inquiry::query()->where('status', Inquiry::STATUS_NEW)->count())
                ->description(JobPosting::query()->where('is_open', true)->count() . ' open job postings')
                ->color('warning'),
        ];
    }
}
