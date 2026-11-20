<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $pio = User::query()->where('email', 'pio@cmc.local')->first()
            ?? User::query()->first();

        $announcements = [
            [
                'title' => 'Capiz Medical Center Launches Official Intranet Portal & Searchable Citizen\'s Charter',
                'slug' => 'cmc-launches-official-portal-and-citizens-charter',
                'category' => 'Advisory',
                'summary' => 'Patients, watchers, and hospital staff can now look up transparent DOH/PhilHealth service fees across all 8 hospital sections instantaneously.',
                'body' => "Capiz Medical Center has launched its new local web portal to make hospital services, operating hours, and Citizen's Charter rate schedules transparent and accessible across all hospital kiosks and wards.\n\nVisit the Citizen's Charter Pricelist section to search Laboratory, Pharmacy, Central Supply Room, Radiology, Ultrasound, Cardio Pulmonary Unit, PhilHealth, and Room Rates, or print a PDF summary directly at the assistance desk.",
                'published_at' => now()->subDays(2),
                'expires_at' => null,
                'is_pinned' => true,
                'is_published' => true,
                'created_by' => $pio?->id,
            ],
            [
                'title' => 'PCP-Accredited Internal Medicine Residency Training — Open for Applications',
                'slug' => 'pcp-accredited-internal-medicine-residency-open',
                'category' => 'Training & Education',
                'summary' => 'Applications are open for licensed physicians interested in joining CMC\'s 3-year Philippine College of Physicians (PCP) accredited training program.',
                'body' => "The Department of Internal Medicine invites dedicated medical graduates to apply for our 24/7 clinical residency training program.\n\nProspective applicants may coordinate with the Office of the Chief of Clinics or visit the Careers page for documentary requirements and interview schedules.",
                'published_at' => now()->subDays(5),
                'expires_at' => now()->addMonths(3),
                'is_pinned' => true,
                'is_published' => true,
                'created_by' => $pio?->id,
            ],
            [
                'title' => 'Outpatient Physical & Rehabilitative Medicine Schedule Reminder',
                'slug' => 'physical-rehabilitative-medicine-schedule',
                'category' => 'Public Health',
                'summary' => 'Outpatient physical therapy consultations and sessions operate Monday to Friday, 8:00 AM to 5:00 PM (excluding legal holidays).',
                'body' => "Please be advised that the Physical and Rehabilitative Medicine Unit accepts outpatient referrals and therapy sessions from Monday to Friday, 8:00 AM to 5:00 PM.\n\nEmergency and trauma care, Labor & Delivery, X-Ray, and the Tertiary Clinical Laboratory continue to operate 24/7.",
                'published_at' => now()->subDays(7),
                'expires_at' => null,
                'is_pinned' => false,
                'is_published' => true,
                'created_by' => $pio?->id,
            ],
        ];

        foreach ($announcements as $item) {
            Announcement::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item,
            );
        }
    }
}
