<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_all_public_portal_pages_render_without_external_cdns(): void
    {
        $routes = [
            '/',
            '/services',
            '/pricelist',
            '/about',
            '/advisories',
            '/careers',
            '/contact',
            '/privacy-notice',
        ];

        foreach ($routes as $uri) {
            $response = $this->get($uri);
            $response->assertOk();

            $html = $response->getContent();
            $this->assertStringNotContainsString('fonts.googleapis.com', $html);
            $this->assertStringNotContainsString('fonts.bunny.net', $html);
            $this->assertStringNotContainsString('cdn.tailwindcss.com', $html);
            $this->assertStringNotContainsString('wixsite.com', $html);
        }
    }

    public function test_home_and_about_display_official_cmc_branding(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Capiz Medical Center')
            ->assertSee('Pag-atipan nga may gugma')
            ->assertSee('(036) 621-0030')
            ->assertSee('(036) 621-0823');

        $this->get('/about')
            ->assertOk()
            ->assertSee('147 Beds')
            ->assertSee('A beacon of hope and healing');
    }

    public function test_services_directory_contains_all_updated_divisions(): void
    {
        $this->get('/services')
            ->assertOk()
            ->assertSee('Internal Medicine')
            ->assertSee('Physician Residency Training Program')
            ->assertSee('Obstetrics and Gynecology')
            ->assertSee('Out-Patient Department (OPD)')
            ->assertSee('Gugma para sa Capiz Dialysis Center')
            ->assertSee('Tertiary Level Clinical Laboratory');
    }

    public function test_pricelist_pdf_endpoints_return_pdf_documents(): void
    {
        $allPdf = $this->get('/pricelist/pdf');
        $allPdf->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $allPdf->headers->get('content-type'));

        $labPdf = $this->get('/pricelist/laboratory/pdf');
        $labPdf->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $labPdf->headers->get('content-type'));
    }

    public function test_contact_inquiry_requires_ra_10173_consent_and_saves_locally(): void
    {
        $this->post('/contact', [
            'name' => 'Maria Santos',
            'contact_info' => '09171234567',
            'subject' => 'Lab Schedule Inquiry',
            'message' => 'What time does the outpatient ultrasound open?',
        ])->assertSessionHasErrors('privacy_consented');

        $this->post('/contact', [
            'name' => 'Maria Santos',
            'contact_info' => '09171234567',
            'subject' => 'Lab Schedule Inquiry',
            'message' => 'What time does the outpatient ultrasound open?',
            'privacy_consented' => '1',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas('inquiries', [
            'name' => 'Maria Santos',
            'subject' => 'Lab Schedule Inquiry',
            'privacy_consented' => true,
            'status' => Inquiry::STATUS_NEW,
        ]);
    }
}
