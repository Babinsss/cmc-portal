<?php

namespace Database\Seeders;

use App\Models\PricelistCategory;
use App\Models\PricelistItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class PricelistSeeder extends Seeder
{
    public function run(): void
    {
        $encoder = User::query()->where('email', 'encoder@cmc.local')->first()
            ?? User::query()->first();

        $categories = [
            [
                'name' => 'Laboratory',
                'slug' => 'laboratory',
                'description' => 'Tertiary clinical laboratory tests including hematology, blood chemistry, microscopy, serology, bacteriology, and blood station fees.',
                'sort_order' => 1,
                'items' => [
                    ['item_code' => 'LAB-CBC', 'name' => 'Complete Blood Count (CBC) with Platelet', 'unit' => 'Per Test', 'price' => 220.00, 'notes' => 'Results within 1–2 hours (24/7)'],
                    ['item_code' => 'LAB-UA', 'name' => 'Urinalysis (Routine Microscopic & Chemical)', 'unit' => 'Per Test', 'price' => 110.00, 'notes' => 'Sterile container required'],
                    ['item_code' => 'LAB-FA', 'name' => 'Fecalysis (Routine Stool Exam)', 'unit' => 'Per Test', 'price' => 95.00, 'notes' => null],
                    ['item_code' => 'LAB-FBS', 'name' => 'Fasting Blood Sugar (FBS)', 'unit' => 'Per Test', 'price' => 160.00, 'notes' => '8–10 hours fasting required'],
                    ['item_code' => 'LAB-LIPID', 'name' => 'Lipid Profile (TC, TG, HDL, LDL)', 'unit' => 'Per Panel', 'price' => 580.00, 'notes' => '10–12 hours fasting required'],
                    ['item_code' => 'LAB-CREA', 'name' => 'Serum Creatinine', 'unit' => 'Per Test', 'price' => 180.00, 'notes' => null],
                    ['item_code' => 'LAB-ELEC', 'name' => 'Serum Electrolytes (Na, K, Cl)', 'unit' => 'Per Panel', 'price' => 450.00, 'notes' => '24/7 Emergency STAT available'],
                    ['item_code' => 'LAB-BT', 'name' => 'Blood Typing (ABO & Rh)', 'unit' => 'Per Test', 'price' => 150.00, 'notes' => null],
                    ['item_code' => 'LAB-CS', 'name' => 'Culture and Sensitivity (Blood / Urine / Sputum)', 'unit' => 'Per Specimen', 'price' => 850.00, 'notes' => '3–5 working days turnaround'],
                ],
            ],
            [
                'name' => 'Pharmacy',
                'slug' => 'pharmacy',
                'description' => 'Hospital Pharmacy essential medicines regulated under the DOH Drug Price Reference Index (DPRI).',
                'sort_order' => 2,
                'items' => [
                    ['item_code' => 'PHM-PARA500', 'name' => 'Paracetamol 500mg Tablet', 'unit' => 'Per Tablet', 'price' => 2.50, 'notes' => 'DPRI-compliant rate'],
                    ['item_code' => 'PHM-AMOX500', 'name' => 'Amoxicillin 500mg Capsule', 'unit' => 'Per Capsule', 'price' => 4.00, 'notes' => 'Prescription required'],
                    ['item_code' => 'PHM-CEFT1G', 'name' => 'Ceftriaxone 1g Vial (IV)', 'unit' => 'Per Vial', 'price' => 65.00, 'notes' => 'With diluent'],
                    ['item_code' => 'PHM-OMEP40', 'name' => 'Omeprazole 40mg IV Vial', 'unit' => 'Per Vial', 'price' => 88.00, 'notes' => null],
                    ['item_code' => 'PHM-LOS50', 'name' => 'Losartan Potassium 50mg Tablet', 'unit' => 'Per Tablet', 'price' => 3.75, 'notes' => null],
                    ['item_code' => 'PHM-PNSS1L', 'name' => '0.9% Sodium Chloride (PNSS) 1 Liter Bottle', 'unit' => 'Per Bottle', 'price' => 58.00, 'notes' => null],
                ],
            ],
            [
                'name' => 'Central Supply Room',
                'slug' => 'central-supply-room',
                'description' => 'Sterile medical-surgical supplies, IV consumables, and dressing materials dispensed by the Central Supply Room (CSR).',
                'sort_order' => 3,
                'items' => [
                    ['item_code' => 'CSR-IVC20', 'name' => 'IV Cannula Gauge 20 (Sterile)', 'unit' => 'Per Piece', 'price' => 38.00, 'notes' => null],
                    ['item_code' => 'CSR-IVSET', 'name' => 'Macrodrip IV Infusion Set', 'unit' => 'Per Set', 'price' => 32.00, 'notes' => null],
                    ['item_code' => 'CSR-SYR5', 'name' => 'Disposable Syringe 5mL with Needle', 'unit' => 'Per Piece', 'price' => 9.50, 'notes' => null],
                    ['item_code' => 'CSR-GLV75', 'name' => 'Sterile Surgical Gloves Size 7.5', 'unit' => 'Per Pair', 'price' => 28.00, 'notes' => null],
                    ['item_code' => 'CSR-FC16', 'name' => 'Foley Catheter Fr. 16 (2-Way Silicone Coated)', 'unit' => 'Per Piece', 'price' => 95.00, 'notes' => null],
                ],
            ],
            [
                'name' => 'Radiology',
                'slug' => 'radiology',
                'description' => '24/7 X-Ray imaging and Computed Tomography (CT Scan) procedures with official radiologist reading.',
                'sort_order' => 4,
                'items' => [
                    ['item_code' => 'RAD-CXR-PA', 'name' => 'Chest X-Ray (PA View)', 'unit' => 'Per Exam', 'price' => 280.00, 'notes' => '24/7 service; includes official reading'],
                    ['item_code' => 'RAD-CXR-APL', 'name' => 'Chest X-Ray (AP & Lateral)', 'unit' => 'Per Exam', 'price' => 460.00, 'notes' => null],
                    ['item_code' => 'RAD-KUB', 'name' => 'Abdomen / KUB X-Ray (Supine & Upright)', 'unit' => 'Per Exam', 'price' => 480.00, 'notes' => null],
                    ['item_code' => 'RAD-EXT', 'name' => 'Extremity X-Ray (2 Views)', 'unit' => 'Per Region', 'price' => 360.00, 'notes' => null],
                    ['item_code' => 'RAD-CT-CRAN', 'name' => 'Cranial / Head CT Scan (Plain)', 'unit' => 'Per Exam', 'price' => 3200.00, 'notes' => 'Contrast media billed separately if indicated'],
                    ['item_code' => 'RAD-CT-ABD', 'name' => 'Whole Abdomen CT Scan (Plain)', 'unit' => 'Per Exam', 'price' => 5800.00, 'notes' => 'Prior scheduling and creatinine required'],
                ],
            ],
            [
                'name' => 'Ultrasound',
                'slug' => 'ultrasound',
                'description' => 'Diagnostic sonography for abdominal, pelvic, obstetric, and soft-tissue evaluations.',
                'sort_order' => 5,
                'items' => [
                    ['item_code' => 'UTS-WABD', 'name' => 'Whole Abdomen Ultrasound', 'unit' => 'Per Exam', 'price' => 1150.00, 'notes' => '6–8 hours fasting required'],
                    ['item_code' => 'UTS-HBT', 'name' => 'Hepatobiliary Tree (HBT) Ultrasound', 'unit' => 'Per Exam', 'price' => 750.00, 'notes' => 'Fasting required'],
                    ['item_code' => 'UTS-KUB', 'name' => 'KUB Ultrasound (Kidneys, Ureters, Bladder)', 'unit' => 'Per Exam', 'price' => 700.00, 'notes' => 'Full bladder preparation'],
                    ['item_code' => 'UTS-PELV', 'name' => 'Pelvic / Obstetric Ultrasound', 'unit' => 'Per Exam', 'price' => 650.00, 'notes' => null],
                    ['item_code' => 'UTS-BPS', 'name' => 'Biophysical Scoring (BPS) Ultrasound', 'unit' => 'Per Exam', 'price' => 850.00, 'notes' => null],
                ],
            ],
            [
                'name' => 'Cardio Pulmonary Unit',
                'slug' => 'cardio-pulmonary-unit',
                'description' => 'Electrocardiography (ECG), 2D Echocardiography, respiratory therapy, and arterial blood gas services.',
                'sort_order' => 6,
                'items' => [
                    ['item_code' => 'CPU-ECG12', 'name' => '12-Lead Electrocardiogram (ECG)', 'unit' => 'Per Test', 'price' => 250.00, 'notes' => '24/7 Emergency & Inpatient'],
                    ['item_code' => 'CPU-2DECHO', 'name' => '2D Echocardiography with Doppler', 'unit' => 'Per Exam', 'price' => 2400.00, 'notes' => 'By schedule'],
                    ['item_code' => 'CPU-ABG', 'name' => 'Arterial Blood Gas (ABG) Analysis', 'unit' => 'Per Test', 'price' => 650.00, 'notes' => '24/7 STAT'],
                    ['item_code' => 'CPU-NEB', 'name' => 'Nebulization Session (Unit Fee)', 'unit' => 'Per Session', 'price' => 80.00, 'notes' => 'Nebule solution billed separately'],
                ],
            ],
            [
                'name' => 'PhilHealth',
                'slug' => 'philhealth',
                'description' => 'PhilHealth All-Case Rates, No Balance Billing (NBB) eligibility for indigent/sponsored members, and Z-Benefit desk guidelines.',
                'sort_order' => 7,
                'items' => [
                    ['item_code' => 'PHIC-NBB', 'name' => 'No Balance Billing (NBB) — Basic Ward Accommodation', 'unit' => 'Eligible Members', 'price' => 0.00, 'notes' => 'Zero co-pay for qualified Indigent, Sponsored, Kasambahay, and Senior Citizen members in service wards'],
                    ['item_code' => 'PHIC-CAP', 'name' => 'Community-Acquired Pneumonia (Moderate Risk) Case Rate', 'unit' => 'Case Package', 'price' => 15000.00, 'notes' => 'Reference PhilHealth benefit deduction (subject to final ICD-10 coding)'],
                    ['item_code' => 'PHIC-NSD', 'name' => 'Normal Spontaneous Delivery (NSD) Maternity Package', 'unit' => 'Case Package', 'price' => 6500.00, 'notes' => 'Includes Essential Intrapartum & Newborn Care'],
                    ['item_code' => 'PHIC-CS', 'name' => 'Cesarean Section Case Rate Benefit', 'unit' => 'Case Package', 'price' => 19000.00, 'notes' => 'Includes hospital & professional fee share'],
                    ['item_code' => 'PHIC-HD', 'name' => 'Hemodialysis Session Benefit Coverage', 'unit' => 'Per Session', 'price' => 4000.00, 'notes' => 'Verify updated PhilHealth circular at Billing Desk'],
                ],
            ],
            [
                'name' => 'Room Rates',
                'slug' => 'room-rates',
                'description' => 'Authorized inpatient accommodation rates across service wards, semi-private rooms, private rooms, and intensive care units.',
                'sort_order' => 8,
                'items' => [
                    ['item_code' => 'RM-WARD', 'name' => 'Service Ward Bed (General Medicine / Surgery / OB / Pedia)', 'unit' => 'Per Day', 'price' => 400.00, 'notes' => 'Covered under NBB for qualified PhilHealth members'],
                    ['item_code' => 'RM-SEMI', 'name' => 'Semi-Private Room (2-Bed Airconditioned)', 'unit' => 'Per Day', 'price' => 950.00, 'notes' => 'Shared bathroom and airconditioning'],
                    ['item_code' => 'RM-PRIV', 'name' => 'Private Room (Single Airconditioned)', 'unit' => 'Per Day', 'price' => 1800.00, 'notes' => 'Private CR, patient bed, and watcher sofa'],
                    ['item_code' => 'RM-SUITE', 'name' => 'Deluxe Private Room', 'unit' => 'Per Day', 'price' => 2600.00, 'notes' => 'Subject to room availability'],
                    ['item_code' => 'RM-ICU', 'name' => 'Intensive Care Unit (ICU / NICU)', 'unit' => 'Per Day', 'price' => 2500.00, 'notes' => 'Continuous bedside monitoring'],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $items = $categoryData['items'];
            unset($categoryData['items']);

            $category = PricelistCategory::query()->updateOrCreate(
                ['slug' => $categoryData['slug']],
                array_merge($categoryData, ['last_updated_at' => now()]),
            );

            foreach ($items as $itemData) {
                PricelistItem::query()->updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'item_code' => $itemData['item_code'],
                    ],
                    array_merge($itemData, [
                        'is_available' => true,
                        'updated_by' => $encoder?->id,
                    ]),
                );
            }
        }
    }
}
