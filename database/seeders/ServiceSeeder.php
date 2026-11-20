<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'code' => 'I',
                'name' => 'Internal Medicine',
                'slug' => 'internal-medicine',
                'schedule_label' => '24/7',
                'is_24_7' => true,
                'is_highlighted' => true,
                'description' => 'Comprehensive 24/7 adult inpatient, emergency, and medical subspecialty care across 11 specialized clinical units.',
                'sort_order' => 1,
                'subspecialties' => [
                    ['name' => 'Neurology', 'schedule_note' => null],
                    ['name' => 'Cardiology', 'schedule_note' => null],
                    ['name' => 'Infectious Diseases', 'schedule_note' => null],
                    ['name' => 'Pulmonology', 'schedule_note' => null],
                    ['name' => 'Nephrology', 'schedule_note' => null],
                    ['name' => 'Oncology', 'schedule_note' => null],
                    ['name' => 'Endocrinology', 'schedule_note' => null],
                    ['name' => 'Gastroenterology', 'schedule_note' => null],
                    ['name' => 'Psychiatry', 'schedule_note' => null],
                    ['name' => 'Dermatology', 'schedule_note' => null],
                    ['name' => 'Rheumatology', 'schedule_note' => null],
                ],
            ],
            [
                'code' => 'II',
                'name' => 'Obstetrics and Gynecology',
                'slug' => 'obstetrics-and-gynecology',
                'schedule_label' => '24/7',
                'is_24_7' => true,
                'is_highlighted' => false,
                'description' => 'Round-the-clock maternal-fetal care, emergency obstetric delivery, and comprehensive women\'s health services.',
                'sort_order' => 2,
                'subspecialties' => [
                    ['name' => 'General Obstetrics and Gynecology', 'schedule_note' => '24/7'],
                ],
            ],
            [
                'code' => 'III',
                'name' => 'Surgery',
                'slug' => 'surgery',
                'schedule_label' => '24/7',
                'is_24_7' => true,
                'is_highlighted' => false,
                'description' => '24/7 emergency trauma surgical response and scheduled elective procedures across major surgical subspecialties.',
                'sort_order' => 3,
                'subspecialties' => [
                    ['name' => 'General Surgery', 'schedule_note' => null],
                    ['name' => 'Orthopedic Surgery', 'schedule_note' => null],
                    ['name' => 'Head & Neck Surgery', 'schedule_note' => null],
                    ['name' => 'Ophthalmology', 'schedule_note' => null],
                    ['name' => 'Hepato-Biliary Surgery', 'schedule_note' => null],
                ],
            ],
            [
                'code' => 'IV',
                'name' => 'Pediatrics',
                'slug' => 'pediatrics',
                'schedule_label' => '24/7',
                'is_24_7' => true,
                'is_highlighted' => false,
                'description' => 'Dedicated 24/7 emergency and inpatient care for newborns, infants, children, and adolescents.',
                'sort_order' => 4,
                'subspecialties' => [
                    ['name' => 'Pulmonology', 'schedule_note' => null],
                    ['name' => 'Infectious Diseases', 'schedule_note' => null],
                    ['name' => 'Neurology', 'schedule_note' => null],
                    ['name' => 'General Pediatrics', 'schedule_note' => null],
                ],
            ],
            [
                'code' => 'V',
                'name' => 'Tertiary Level Clinical Laboratory',
                'slug' => 'tertiary-level-clinical-laboratory',
                'schedule_label' => '24/7',
                'is_24_7' => true,
                'is_highlighted' => false,
                'description' => 'DOH-licensed tertiary clinical laboratory supporting 24/7 diagnostic testing, bacteriology, histopathology, and blood banking.',
                'sort_order' => 5,
                'subspecialties' => [
                    ['name' => 'Blood Chemistry', 'schedule_note' => '24/7'],
                    ['name' => 'Bacteriology including Culture & Sensitivity', 'schedule_note' => null],
                    ['name' => 'Microscopy', 'schedule_note' => '24/7'],
                    ['name' => 'Histopathology', 'schedule_note' => null],
                    ['name' => 'Parasitology', 'schedule_note' => null],
                    ['name' => 'Serology', 'schedule_note' => null],
                    ['name' => 'Blood Station', 'schedule_note' => '24/7'],
                ],
            ],
            [
                'code' => 'VI',
                'name' => 'Diagnostic Imaging',
                'slug' => 'diagnostic-imaging',
                'schedule_label' => '24/7',
                'is_24_7' => true,
                'is_highlighted' => false,
                'description' => '24/7 radiographic and computed tomography imaging with official radiologist interpretation.',
                'sort_order' => 6,
                'subspecialties' => [
                    ['name' => 'Radiology', 'schedule_note' => '24/7'],
                    ['name' => 'CT Scan', 'schedule_note' => '24/7'],
                ],
            ],
            [
                'code' => 'VII',
                'name' => 'Pharmacy Services',
                'slug' => 'pharmacy-services',
                'schedule_label' => '24/7',
                'is_24_7' => true,
                'is_highlighted' => false,
                'description' => 'Round-the-clock inpatient and emergency medication dispensing including DOH GAMOT package medicines.',
                'sort_order' => 7,
                'subspecialties' => [
                    ['name' => 'General Drug Dispensing', 'schedule_note' => '24/7'],
                    ['name' => 'GAMOT Package Program', 'schedule_note' => null],
                ],
            ],
            [
                'code' => 'VIII',
                'name' => 'Physician Residency Training Program',
                'slug' => 'physician-residency-training-program',
                'schedule_label' => '24/7',
                'is_24_7' => true,
                'is_highlighted' => true,
                'description' => 'Accredited postgraduate medical residency programs cultivating compassionate specialists serving Capiz and Western Visayas.',
                'sort_order' => 8,
                'subspecialties' => [
                    ['name' => 'Internal Medicine Residency Training Program', 'schedule_note' => 'Accredited by Philippine College of Physicians'],
                    ['name' => 'General Surgery Residency Training Program', 'schedule_note' => 'Accredited by Philippine Society of General Surgeons'],
                ],
            ],
            [
                'code' => 'IX',
                'name' => 'Out-Patient Department (OPD)',
                'slug' => 'out-patient-department',
                'schedule_label' => '8:00 AM - 4:00 PM, Mon–Fri (except Holidays)',
                'is_24_7' => false,
                'is_highlighted' => true,
                'description' => 'Comprehensive weekday specialty consultations, YAKAP & GAMOT services, diagnostics, animal bite treatment, and specialty clinics.',
                'sort_order' => 9,
                'subspecialties' => [
                    ['name' => 'Internal Medicine (12 Subspecialties)', 'schedule_note' => 'Neurology, Cardiology, Neuropsychology, Pulmonology, Dermatology, Nephrology, Endocrinology, Infectious Diseases, Oncology, Psychiatry, Gastroenterology, Rheumatology'],
                    ['name' => 'Obstetrics and Gynecology', 'schedule_note' => 'Mon–Fri 8:00 AM – 4:00 PM'],
                    ['name' => 'Surgery (General Surgery, Head and Neck Surgery)', 'schedule_note' => 'Mon–Fri 8:00 AM – 4:00 PM'],
                    ['name' => 'Pediatrics', 'schedule_note' => 'Mon–Fri 8:00 AM – 4:00 PM'],
                    ['name' => 'YAKAP Clinic', 'schedule_note' => 'Mon–Fri 8:00 AM – 4:00 PM'],
                    ['name' => 'Dental Clinic', 'schedule_note' => 'Mon–Fri 8:00 AM – 4:00 PM'],
                    ['name' => 'Clinical Laboratory (Hematology, Parasitology, Microscopy, Serology, HIV Screening & Confirmatory, GeneXpert)', 'schedule_note' => 'Mon–Fri 8:00 AM – 4:00 PM'],
                    ['name' => 'Pharmacy (General Drug Dispensing, YAKAP Medicines, GAMOT Package)', 'schedule_note' => 'Mon–Fri 8:00 AM – 4:00 PM'],
                    ['name' => 'Animal Bite Treatment Center', 'schedule_note' => 'Mon–Fri 8:00 AM – 4:00 PM'],
                    ['name' => 'RMPH We CARE Clinic and Treatment Facility', 'schedule_note' => 'Mon–Fri 8:00 AM – 4:00 PM'],
                    ['name' => 'Newborn Screening Continuity Clinic', 'schedule_note' => 'Mon–Fri 8:00 AM – 4:00 PM'],
                    ['name' => 'Gugma Eye Care Clinic (Ophthalmology)', 'schedule_note' => 'Mon–Fri 8:00 AM – 4:00 PM'],
                ],
            ],
            [
                'code' => 'X',
                'name' => 'Gugma para sa Capiz Dialysis Center',
                'slug' => 'gugma-para-sa-capiz-dialysis-center',
                'schedule_label' => '7:00 AM - 5:00 PM, Monday to Saturday',
                'is_24_7' => false,
                'is_highlighted' => true,
                'description' => 'Dedicated renal care and hemodialysis center serving patients across the Province of Capiz six days a week.',
                'sort_order' => 10,
                'subspecialties' => [
                    ['name' => 'Hemodialysis & Renal Care Services', 'schedule_note' => '7:00 AM – 5:00 PM, Mon–Sat'],
                ],
            ],
        ];

        $activeSlugs = collect($services)->pluck('slug')->all();

        Service::query()->whereNotIn('slug', $activeSlugs)->delete();

        foreach ($services as $serviceData) {
            $subspecialties = $serviceData['subspecialties'];
            unset($serviceData['subspecialties']);

            $service = Service::query()->updateOrCreate(
                ['slug' => $serviceData['slug']],
                $serviceData,
            );

            $service->subspecialties()->delete();

            foreach ($subspecialties as $index => $sub) {
                $service->subspecialties()->create([
                    'name' => $sub['name'],
                    'schedule_note' => $sub['schedule_note'],
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}
