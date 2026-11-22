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
                'title' => 'Department of Surgery — Now Accepting Applicants for 5-Year PSGS-Accredited General Surgery Residency Training Program',
                'slug' => 'psgs-accredited-general-surgery-residency-open',
                'category' => 'Training & Education',
                'summary' => 'CMC Department of Surgery is now accepting applicants for its 5-Year Residency Training Program in General Surgery, accredited by the Philippine Society of General Surgeons (PSGS).',
                'body' => "Capiz Medical Center (CMC) Department of Surgery is now accepting applicants for its 5-Year Residency Training Program in General Surgery, accredited by the Philippine Society of General Surgeons.\n\nProgram Benefits:\n- Plantilla position\n- Monthly PhilHealth Share\n- Clothing Allowance\n- Monthly Hazard Pay\n- Monthly Subsistence and Laundry Allowance\n- Midyear and Yearend Bonus\n- Productivity Enhancement Incentive\n- Cash Gift\n- Service Recognition Incentive\n- Collective Negotiation Agreement (CNA) Incentive\n\nApplication Requirements:\n- Letter of Intent addressed to:\n  Florencio O. Luching, Jr., MD, FPCS, FPSGS — Chief of Hospital\n  Thru: Jheimar B. Francisco, MD, FPCS, FPSGS — Chair, Department of Surgery\n  Thru: Roy Jason P. Montecillo, MD, FPCS, FPSGS — Training Officer, Department of Surgery\n- Updated Curriculum Vitae with 2x2 photo\n- Class Ranking (Photocopy)\n- PRC Board Rating (Photocopy)\n- PRC License Card (Photocopy)\n- PhilHealth Accreditation Card (Photocopy)\n- Transcript of Records (Certified True Copy)\n- Medical School Diploma (Photocopy)\n- Internship Certificate (Photocopy)\n\nHow to Apply:\nInterested applicants may submit their application to the Office of the Department of Surgery at Capiz Medical Center.\n- Telephone: (036) 6210-030 local 178\n- Email: rmphrxcapiz@yahoo.com",
                'image_path' => 'images/announcements/general-surgery-residency.jpg',
                'published_at' => now(),
                'expires_at' => now()->addMonths(3),
                'is_pinned' => true,
                'is_published' => true,
                'created_by' => $pio?->id,
            ],
            [
                'title' => 'Department of Internal Medicine — Now Accepting Applicants for 3-Year PCP-Accredited Residency Training Program',
                'slug' => 'pcp-accredited-internal-medicine-residency-open',
                'category' => 'Training & Education',
                'summary' => 'Ready to elevate your medical career? Join our team! The Department of Internal Medicine at Capiz Medical Center is now accepting applicants for our 3-Year PCP-Accredited Residency Training Program.',
                'body' => "Ready to elevate your medical career? Join our team!\n\nThe Department of Internal Medicine at Capiz Medical Center is NOW ACCEPTING APPLICANTS for our 3-Year PCP-Accredited Residency Training Program. Take the next step in your clinical journey and serve the community with pride.\n\nApplication Letter Addressed To:\n- Governor Fredenil H. Castro, Provincial Governor\n- Thru: Dr. Florencio O. Luching, MD, FPCS, Chief of Hospital II\n- Thru: Dr. Mark Anthony E. Agana, MD, FPCP, FPSMO, Chairman, Department of Internal Medicine\n\nDocumentary Requirements:\n- Application Letter\n- Board Certificate\n- PRC ID\n- Medical School Diploma\n- Updated Curriculum Vitae\n- Board Rating\n- Certificate of Internship\n- Transcript of Records\n\nGet in Touch With Us (Have questions or ready to submit?):\n- Landline: (036) 6210-0030 Loc. 160\n- Chief Resident: Dr. Ma. Camille Belasoto — 09175066702\n- Email: dim.rmph0234@gmail.com",
                'image_path' => 'images/announcements/internal-medicine-residency.jpg',
                'published_at' => now()->subMinutes(5),
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
