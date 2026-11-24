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
            ->assertSee('(036) 621-0823')
            ->assertSee('Brgy. Lanot, Roxas City, Capiz 5800, Philippines')
            ->assertDontSee('Sitio Nasagud');

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

    public function test_contact_page_embeds_official_google_map_and_offline_guide(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('https://www.google.com/maps/embed?pb=', false)
            ->assertSee('Offline Campus Guide')
            ->assertSee('id="campus-map"', false);
    }

    public function test_pharmacy_pricelist_contains_official_august_2025_rates(): void
    {
        $this->get('/pricelist?category=pharmacy')
            ->assertOk()
            ->assertSee('PARACETAMOL 500mg TABLET')
            ->assertSee('MEROPENEM 1g VIAL')
            ->assertSee('SEVOFLURANE 100% X 250ml BOTTLE')
            ->assertSee('Consigned Stocks')
            ->assertSee('Non-Consigned')
            ->assertDontSee('PHM-PARA500');

        $pdf = $this->get('/pricelist/pharmacy/pdf');
        $pdf->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdf->headers->get('content-type'));
    }

    public function test_internal_medicine_residency_announcement_displays_details_and_poster(): void
    {
        $this->get('/advisories')
            ->assertOk()
            ->assertSee('Department of Internal Medicine')
            ->assertSee('3-Year PCP-Accredited Residency Training Program')
            ->assertSee('internal-medicine-residency.jpg');

        $this->get('/advisories/pcp-accredited-internal-medicine-residency-open')
            ->assertOk()
            ->assertSee('Dr. Ma. Camille Belasoto')
            ->assertSee('09175066702')
            ->assertSee('dim.rmph0234@gmail.com')
            ->assertSee('(036) 6210-0030 Loc. 160')
            ->assertSee('internal-medicine-residency.jpg');
    }

    public function test_general_surgery_residency_announcement_displays_details_and_poster(): void
    {
        $this->get('/advisories')
            ->assertOk()
            ->assertSee('Department of Surgery')
            ->assertSee('5-Year PSGS-Accredited General Surgery Residency Training Program')
            ->assertSee('general-surgery-residency.jpg');

        $this->get('/advisories/psgs-accredited-general-surgery-residency-open')
            ->assertOk()
            ->assertSee('Philippine Society of General Surgeons')
            ->assertSee('Jheimar B. Francisco')
            ->assertSee('Roy Jason P. Montecillo')
            ->assertSee('(036) 6210-030 local 178')
            ->assertSee('rmphrxcapiz@yahoo.com')
            ->assertSee('general-surgery-residency.jpg');
    }

    public function test_ct_scan_ultrasound_and_lab_rates_match_january_2026_schedules(): void
    {
        $this->get('/pricelist?category=laboratory')
            ->assertOk()
            ->assertSee('24 HR, URINE COLLECTION CLEARANCE')
            ->assertSee('CULTURE SENSITIVITY = BLOOD')
            ->assertSee('PROCALCITONIN')
            ->assertSee('DRUG TESTING')
            ->assertDontSee('LAB-CBC');

        $this->get('/pricelist?category=radiology')
            ->assertOk()
            ->assertSee('SKULL APL')
            ->assertSee('CHEST BUCKY')
            ->assertSee('BARIUM ENEMA')
            ->assertSee('PORTABLE MACHINE (ADDITIONAL CHARGE)')
            ->assertSee('CRANIAL CT PLAIN')
            ->assertSee('WHOLE ABDOMEN TRIPLE WITH CONTRAST')
            ->assertSee('CT ANGIOGRAM')
            ->assertDontSee('RAD-CXR-PA')
            ->assertDontSee('RAD-CT-CRAN');

        $this->get('/pricelist?category=ultrasound')
            ->assertOk()
            ->assertSee('Whole Abdomen Ultrasound')
            ->assertSee('KUBP Ultrasound')
            ->assertSee('Dr. Emmanuel M. Palomata')
            ->assertDontSee('UTS-WABD');
    }

    public function test_philhealth_pricelist_contains_updated_annex_case_rates(): void
    {
        $this->get('/pricelist?category=philhealth')
            ->assertOk()
            ->assertSee('No Balance Billing (NBB)')
            ->assertSee('CHOLERA DUE TO VIBRIO CHOLERAE 01, BIOVAR CHOLERAE')
            ->assertSee('CAESARIAN SECTION, PRIMARY')
            ->assertSee('HEMODIALYSIS PROCEDURE')
            ->assertSee('SEVERE DENGUE')
            ->assertSee('PHILHEALTH KONSULTA PACKAGE')
            ->assertSee('EXPANDED Z MORPH LUMBOSACRAL')
            ->assertDontSee('PHIC-CAP')
            ->assertDontSee('PHIC-HD');

        $pdf = $this->get('/pricelist/philhealth/pdf');
        $pdf->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdf->headers->get('content-type'));
    }

    public function test_csr_pricelist_contains_official_october_2026_rates(): void
    {
        $this->get('/pricelist?category=central-supply-room')
            ->assertOk()
            ->assertSee('3 WAY FOLEY CATHETER FR.18')
            ->assertSee('ETHILON 3.0 CUTTING')
            ->assertSee('HEMODIALYSIS ACID CONCENTRATE')
            ->assertSee('VENTILATOR TUBING SET, ADULT')
            ->assertSee('Supplier: JOSMEF')
            ->assertDontSee('CSR-IVC20');

        $pdf = $this->get('/pricelist/central-supply-room/pdf');
        $pdf->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdf->headers->get('content-type'));
    }

    public function test_cardio_pulmonary_pricelist_contains_official_rates(): void
    {
        $this->get('/pricelist?category=cardio-pulmonary-unit')
            ->assertOk()
            ->assertSee('ABG (Arterial Blood Gas)')
            ->assertSee('ECG (Electrocardiogram)')
            ->assertSee('Oxygen Therapy')
            ->assertSee('OECB: PHP 70.00')
            ->assertSee('1st 24 Hours')
            ->assertSee('Transport Vent. Circuit')
            ->assertSee('900.00')
            ->assertSee('Flow Sensor (SV300 PRO)')
            ->assertDontSee('Pedia Circuit')
            ->assertDontSee('Neonatal Circuit')
            ->assertDontSee('CPU-ECG12')
            ->assertDontSee('CPU-2DECHO');

        $pdf = $this->get('/pricelist/cardio-pulmonary-unit/pdf');
        $pdf->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdf->headers->get('content-type'));
    }

    public function test_room_rates_pricelist_contains_june_2026_ordinance_schedule(): void
    {
        $this->get('/pricelist?category=room-rates')
            ->assertOk()
            ->assertSee('Emergency Room')
            ->assertSee('Veterans Room (Air Conditioned)')
            ->assertSee('Private Room (Air Conditioned)')
            ->assertSee('ICU (From Private Accommodation)')
            ->assertSee('NICU (From Ward Accommodation)')
            ->assertSee('Operating Room Fee (Major Operation)')
            ->assertDontSee('RM-SEMI')
            ->assertDontSee('Deluxe Private Room');

        $pdf = $this->get('/pricelist/room-rates/pdf');
        $pdf->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdf->headers->get('content-type'));
    }
}
