<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Inquiry;
use App\Models\JobPosting;
use App\Models\PricelistCategory;
use App\Models\PricelistItem;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\User;
use App\Policies\AnnouncementPolicy;
use App\Policies\InquiryPolicy;
use App\Policies\JobPostingPolicy;
use App\Policies\PricelistCategoryPolicy;
use App\Policies\PricelistItemPolicy;
use App\Policies\ServicePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(PricelistCategory::class, PricelistCategoryPolicy::class);
        Gate::policy(PricelistItem::class, PricelistItemPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);
        Gate::policy(Announcement::class, AnnouncementPolicy::class);
        Gate::policy(JobPosting::class, JobPostingPolicy::class);
        Gate::policy(Inquiry::class, InquiryPolicy::class);

        View::composer(['layouts.*', 'portal.*', 'pdf.*'], function ($view): void {
            try {
                $settings = Schema::hasTable('site_settings')
                    ? SiteSetting::allMap()
                    : SiteSetting::defaults();
            } catch (Throwable) {
                $settings = SiteSetting::defaults();
            }

            $view->with('settings', $settings);
        });
    }
}
