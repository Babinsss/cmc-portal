<?php

namespace Tests\Feature;

use App\Filament\Pages\BulkPricelistImport;
use App\Filament\Pages\ManageSiteSettings;
use App\Filament\Resources\AnnouncementResource;
use App\Filament\Resources\PricelistItemResource;
use App\Filament\Resources\UserResource;
use App\Models\PricelistCategory;
use App\Models\PricelistItem;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_super_admin_can_access_admin_panel_and_system_resources(): void
    {
        $admin = User::query()->where('email', 'admin@cmc.local')->firstOrFail();

        $this->actingAs($admin);

        $this->get('/admin')->assertOk();
        $this->assertTrue(ManageSiteSettings::canAccess());
        $this->assertTrue(BulkPricelistImport::canAccess());
        $this->assertTrue(UserResource::canViewAny());
        $this->assertTrue(PricelistItemResource::canViewAny());
        $this->assertTrue(AnnouncementResource::canViewAny());
    }

    public function test_pricelist_encoder_is_scoped_to_pricelist_and_assigned_categories(): void
    {
        $lab = PricelistCategory::query()->where('slug', 'laboratory')->firstOrFail();
        $pharmacy = PricelistCategory::query()->where('slug', 'pharmacy')->firstOrFail();

        $encoder = User::query()->where('email', 'encoder@cmc.local')->firstOrFail();
        $encoder->update(['assigned_categories' => [$lab->id]]);

        $this->actingAs($encoder);

        $this->get('/admin')->assertOk();
        $this->assertTrue(BulkPricelistImport::canAccess());
        $this->assertTrue(PricelistItemResource::canViewAny());
        $this->assertFalse(ManageSiteSettings::canAccess());
        $this->assertFalse(UserResource::canViewAny());
        $this->assertFalse(AnnouncementResource::canViewAny());

        $labItem = PricelistItem::query()->where('category_id', $lab->id)->firstOrFail();
        $pharmacyItem = PricelistItem::query()->where('category_id', $pharmacy->id)->firstOrFail();

        $this->assertTrue($encoder->can('update', $labItem));
        $this->assertFalse($encoder->can('update', $pharmacyItem));
    }

    public function test_public_info_officer_cannot_access_pricelist_or_user_management(): void
    {
        $pio = User::query()->where('email', 'pio@cmc.local')->firstOrFail();

        $this->actingAs($pio);

        $this->get('/admin')->assertOk();
        $this->assertTrue(AnnouncementResource::canViewAny());
        $this->assertFalse(PricelistItemResource::canViewAny());
        $this->assertFalse(BulkPricelistImport::canAccess());
        $this->assertFalse(UserResource::canViewAny());
        $this->assertFalse(ManageSiteSettings::canAccess());
    }
}
