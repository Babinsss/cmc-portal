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
                'description' => 'Official Department of Pathology Laboratory Pricelist (as of January 5, 2026): Blood Chemistry, Hematology, Serology, Blood Bank, Microbiology, Microscopy, Histopath, and Drug Testing.',
                'sort_order' => 1,
                'items' => $this->laboratoryItems(),
            ],
            [
                'name' => 'Pharmacy',
                'slug' => 'pharmacy',
                'description' => 'Official Pharmacy Department Price List (as of August 29, 2025) covering Consigned Stocks (Drugs & Medicine) and Non-Consigned Stocks (IV Fluids & Medicines).',
                'sort_order' => 2,
                'items' => $this->pharmacyItems(),
            ],
            [
                'name' => 'Central Supply Room',
                'slug' => 'central-supply-room',
                'description' => 'Official Central Supply Room - Medical Supplies Pricelist (as of October 26, 2026) covering Medical Supplies, Sutures, Dialysis, and Cardio Pulmonary consumables.',
                'sort_order' => 3,
                'items' => $this->csrItems(),
            ],
            [
                'name' => 'Radiology',
                'slug' => 'radiology',
                'description' => 'Official Radiology X-Ray and Computed Tomography (CT Scan) Examinations price lists (as of January 1, 2026 per Ordinance No. 367, s.2024).',
                'sort_order' => 4,
                'items' => $this->radiologyItems(),
            ],
            [
                'name' => 'Ultrasound',
                'slug' => 'ultrasound',
                'description' => 'Radiology Department Ultrasound Services (as of January 1, 2026). Schedule of Exam: Dr. Emmanuel M. Palomata, DPBR (Monday, Wednesday, Friday).',
                'sort_order' => 5,
                'items' => $this->ultrasoundItems(),
            ],
            [
                'name' => 'Cardio Pulmonary Unit',
                'slug' => 'cardio-pulmonary-unit',
                'description' => 'Official Cardio Pulmonary Unit schedule: ABG, ECG, Oxygen Therapy, Mechanical & Transport Ventilators, Circuits, and Flow Sensors.',
                'sort_order' => 6,
                'items' => [
                    ['item_code' => 'CPU-ABG', 'name' => 'ABG (Arterial Blood Gas)', 'unit' => 'Per Test', 'price' => 1326.00, 'notes' => 'Diagnostics & Respiratory Care'],
                    ['item_code' => 'CPU-ECG', 'name' => 'ECG (Electrocardiogram)', 'unit' => 'Per Exam', 'price' => 423.00, 'notes' => 'Diagnostics & Respiratory Care'],
                    ['item_code' => 'CPU-O2', 'name' => 'Oxygen Therapy', 'unit' => 'Per PSI', 'price' => 0.85, 'notes' => 'OECB: PHP 70.00'],
                    ['item_code' => 'CPU-VENT-24H', 'name' => 'Mechanical & Transport Ventilator (1st 24 Hours)', 'unit' => '1st 24 Hours', 'price' => 2000.00, 'notes' => 'Ventilator Use | Succeeding rate: PHP 1,000.00 per day/use'],
                    ['item_code' => 'CPU-VENT-DAY', 'name' => 'Mechanical & Transport Ventilator (Per Day / Use)', 'unit' => 'Per Day / Use', 'price' => 1000.00, 'notes' => 'Ventilator Use | Initial 1st 24 hours: PHP 2,000.00'],
                    ['item_code' => 'CPU-CIRC-PED', 'name' => 'Pedia Circuit', 'unit' => 'Per Set', 'price' => 2250.00, 'notes' => 'Circuits & Flow Sensors'],
                    ['item_code' => 'CPU-CIRC-NEO', 'name' => 'Neonatal Circuit', 'unit' => 'Per Set', 'price' => 2250.00, 'notes' => 'Circuits & Flow Sensors'],
                    ['item_code' => 'CPU-CIRC-TRN', 'name' => 'Transport Vent. Circuit', 'unit' => 'Per Set', 'price' => 2000.00, 'notes' => 'Circuits & Flow Sensors'],
                    ['item_code' => 'CPU-FLOW-SV300', 'name' => 'Flow Sensor (SV300 PRO)', 'unit' => 'Per Use', 'price' => 6000.00, 'notes' => 'Circuits & Flow Sensors'],
                    ['item_code' => 'CPU-FLOW-TRN', 'name' => 'Flow Sensor (Transport Ventilator)', 'unit' => 'Per Use', 'price' => 5000.00, 'notes' => 'Circuits & Flow Sensors'],
                ],
            ],
            [
                'name' => 'PhilHealth',
                'slug' => 'philhealth',
                'description' => 'Updated PhilHealth Case Rates (Annex A: Medical ICD-10, Annex B: RVS Procedures, Annex C: Excluded Benefits & Z-Benefits) and No Balance Billing (NBB) guidelines.',
                'sort_order' => 7,
                'items' => $this->philhealthItems(),
            ],
            [
                'name' => 'Room Rates',
                'slug' => 'room-rates',
                'description' => 'Official Hospital Room, Ward, ICU/NICU, Delivery, Recovery, and Operating Room Rates (as of June 20, 2026 per Ordinance No. 367, s.2024).',
                'sort_order' => 8,
                'items' => [
                    ['item_code' => 'RM-ER', 'name' => 'Emergency Room', 'unit' => 'Per Use', 'price' => 100.00, 'notes' => 'Ordinance No. 367, s.2024 (As of June 20, 2026)'],
                    ['item_code' => 'RM-WARD', 'name' => 'Basic / Ward', 'unit' => 'Per Day', 'price' => 300.00, 'notes' => 'Covered under NBB for qualified PhilHealth members | Ordinance No. 367, s.2024'],
                    ['item_code' => 'RM-VET-AC', 'name' => 'Veterans Room (Air Conditioned)', 'unit' => 'Per Day', 'price' => 1600.00, 'notes' => 'Ordinance No. 367, s.2024 (As of June 20, 2026)'],
                    ['item_code' => 'RM-VET-SH', 'name' => 'Veterans Room (Air Conditioned - Sharing)', 'unit' => 'Per Day', 'price' => 1400.00, 'notes' => 'Ordinance No. 367, s.2024 (As of June 20, 2026)'],
                    ['item_code' => 'RM-PRIV-AC', 'name' => 'Private Room (Air Conditioned)', 'unit' => 'Per Day', 'price' => 1600.00, 'notes' => 'Ordinance No. 367, s.2024 (As of June 20, 2026)'],
                    ['item_code' => 'RM-PRIV-FAN', 'name' => 'Private Room (Electric Fan)', 'unit' => 'Per Day', 'price' => 1000.00, 'notes' => 'Ordinance No. 367, s.2024 (As of June 20, 2026)'],
                    ['item_code' => 'RM-ICU-PRIV', 'name' => 'ICU (From Private Accommodation)', 'unit' => 'Per Day', 'price' => 1500.00, 'notes' => 'Intensive Care Unit | From Ward Accommodation: PHP 1,000.00'],
                    ['item_code' => 'RM-ICU-WARD', 'name' => 'ICU (From Ward Accommodation)', 'unit' => 'Per Day', 'price' => 1000.00, 'notes' => 'Intensive Care Unit | From Private Accommodation: PHP 1,500.00'],
                    ['item_code' => 'RM-NICU-PRIV', 'name' => 'NICU (From Private Accommodation)', 'unit' => 'Per Day', 'price' => 800.00, 'notes' => 'Neonatal Intensive Care Unit | From Ward Accommodation: PHP 600.00'],
                    ['item_code' => 'RM-NICU-WARD', 'name' => 'NICU (From Ward Accommodation)', 'unit' => 'Per Day', 'price' => 600.00, 'notes' => 'Neonatal Intensive Care Unit | From Private Accommodation: PHP 800.00'],
                    ['item_code' => 'RM-DR-PRIV', 'name' => 'Delivery Room Fee (From Private Accommodation)', 'unit' => 'Per Use', 'price' => 1600.00, 'notes' => 'From Ward Accommodation: PHP 1,200.00'],
                    ['item_code' => 'RM-DR-WARD', 'name' => 'Delivery Room Fee (From Ward Accommodation)', 'unit' => 'Per Use', 'price' => 1200.00, 'notes' => 'From Private Accommodation: PHP 1,600.00'],
                    ['item_code' => 'RM-RR-PRIV', 'name' => 'Recovery Room Fee (From Private Accommodation)', 'unit' => 'Per Use', 'price' => 400.00, 'notes' => 'From Ward Accommodation: PHP 200.00'],
                    ['item_code' => 'RM-RR-WARD', 'name' => 'Recovery Room Fee (From Ward Accommodation)', 'unit' => 'Per Use', 'price' => 200.00, 'notes' => 'From Private Accommodation: PHP 400.00'],
                    ['item_code' => 'RM-OR-MIN', 'name' => 'Operating Room Fee (Minor Operation)', 'unit' => 'Per Procedure', 'price' => 1600.00, 'notes' => 'Major Operation fee: PHP 3,500.00'],
                    ['item_code' => 'RM-OR-MAJ', 'name' => 'Operating Room Fee (Major Operation)', 'unit' => 'Per Procedure', 'price' => 3500.00, 'notes' => 'Minor Operation fee: PHP 1,600.00'],
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

            $keepCodes = array_column($items, 'item_code');
            $existingCodes = PricelistItem::query()
                ->where('category_id', $category->id)
                ->pluck('item_code')
                ->all();
            $staleCodes = array_values(array_diff($existingCodes, $keepCodes));

            foreach (array_chunk($staleCodes, 500) as $staleChunk) {
                PricelistItem::query()
                    ->where('category_id', $category->id)
                    ->whereIn('item_code', $staleChunk)
                    ->delete();
            }

            if (count($items) > 100) {
                $now = now();
                $rows = array_map(fn (array $itemData): array => [
                    'category_id' => $category->id,
                    'item_code' => $itemData['item_code'],
                    'name' => $itemData['name'],
                    'unit' => $itemData['unit'] ?? null,
                    'price' => $itemData['price'],
                    'notes' => $itemData['notes'] ?? null,
                    'is_available' => true,
                    'updated_by' => $encoder?->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], $items);

                foreach (array_chunk($rows, 500) as $chunk) {
                    PricelistItem::query()->upsert(
                        $chunk,
                        ['category_id', 'item_code'],
                        ['name', 'unit', 'price', 'notes', 'is_available', 'updated_by', 'updated_at'],
                    );
                }

                continue;
            }

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

    /**
     * Load official Central Supply Room supplies (as of October 26, 2026).
     *
     * @return list<array{item_code: string, name: string, unit: string, price: float, notes: string}>
     */
    private function csrItems(): array
    {
        return $this->loadJsonItems('csr_supplies.json');
    }

    /**
     * Load the official PhilHealth Case Rates (Annex A, Annex B, Annex C, and NBB).
     *
     * @return list<array{item_code: string, name: string, unit: string, price: float, notes: string}>
     */
    private function philhealthItems(): array
    {
        return $this->loadJsonItems('philhealth_case_rates.json');
    }

    /**
     * Read and decode a structured pricelist JSON dataset from seeders/data.
     *
     * @return list<array{item_code: string, name: string, unit: string, price: float, notes: string}>
     */
    private function loadJsonItems(string $filename): array
    {
        $path = database_path("seeders/data/{$filename}");
        /** @var list<array{item_code: string, name: string, unit: string, price: float, notes: string}> $decoded */
        $decoded = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        return $decoded;
    }

    /**
     * Build the official Pharmacy Department rate items (Price List as of August 29, 2025).
     *
     * @return list<array{item_code: string, name: string, unit: string, price: float, notes: string}>
     */
    private function pharmacyItems(): array
    {
        $consignedNote = 'Consigned Stocks: Drugs & Medicine (As of Aug 29, 2025)';
        $nonConsignedIvNote = 'Non-Consigned Stocks: Intravenous Fluids (As of Aug 29, 2025)';
        $nonConsignedMedNote = 'Non-Consigned Stocks: Medicines (As of Aug 29, 2025)';

        $consigned = [
            [1, '0.9% SODIUM CHLORIDE SOLUTION FOR IV INFUSION (PNSS) 1L BOTTLE', 'Per Bottle', 229.00],
            [2, '5% DEXTROSE IN 0.3% SODIUM CHLORIDE SOLUTION (D5 0.3% NaCl) 1L BOTTLE', 'Per Bottle', 95.00],
            [3, "5% DEXTROSE IN LACTATED RINGER'S SOLUTION 1L (D5LR) BOTTLE", 'Per Bottle', 91.00],
            [4, 'ADENOSINE 3mg/ml x 2ml AMPOULE', 'Per Ampoule', 501.00],
            [5, 'ALLOPURINOL 300mg TABLET', 'Per Tablet', 5.00],
            [6, 'AMIODARONE HCL 50mg/ml (150mg/3ml) AMPOULE', 'Per Ampoule', 637.00],
            [7, 'AMLODIPINE 5mg TABLET', 'Per Tablet', 2.00],
            [8, 'AMOXICILLIN 100mg/ml x 10ml DROPS SYRUP', 'Per Bottle', 29.00],
            [9, 'AMOXICILLIN 250mg/5ml SUSPENSION X 60ML BOT', 'Per Bottle', 110.50],
            [10, 'AMPICILLIN + SULBACTAM 1.5g VIAL', 'Per Vial', 374.00],
            [11, 'AMPICILLIN 250mg VIAL', 'Per Vial', 25.00],
            [12, 'ATRACURIUM 10mg/ml AMPOULE', 'Per Ampoule', 292.50],
            [13, 'AZITHROMYCIN 500mg TABLET', 'Per Tablet', 65.00],
            [14, 'BETAHISTINE 16mg TABLET', 'Per Tablet', 68.00],
            [15, 'BIPHASIC INSULIN, HUMAN 70/30', 'Per Vial', 747.50],
            [16, 'BISACODYL 10mg SUPPOSITORY', 'Per Suppository', 71.00],
            [17, 'BISACODYL 5MG TABLET', 'Per Tablet', 12.00],
            [18, 'BLUMEA BALSAMIFERA L. CAPSULE 500mg (SAMBONG LEAF)', 'Per Capsule', 8.00],
            [19, 'BUDESONIDE 250mcg/ml x 2ml NEBULE', 'Per Nebule', 112.00],
            [20, 'BUPIVACAINE 0.5% HEAVY AMPOULE', 'Per Ampoule', 390.00],
            [21, 'BUPIVACAINE 5% 5mg/ml X 4ML AMPOULE (ISOBARIC)', 'Per Ampoule', 618.00],
            [22, 'CALCIUM GLUCONATE (10%) 100mcg/ml x 10ml AMPOULE', 'Per Ampoule', 95.00],
            [23, 'CARVEDILOL 25mg TABLET', 'Per Tablet', 11.00],
            [24, 'CEFAZOLIN 1g VIAL', 'Per Vial', 203.00],
            [25, 'CEFIXIME 200mg CAPSULE', 'Per Capsule', 12.00],
            [26, 'CEFIXIME 20mg/ml X 10ML drops', 'Per Bottle', 153.00],
            [27, 'CEFIXIME 400mg CAPSULE', 'Per Capsule', 39.00],
            [28, 'CEFOTAXIME 500mg VIAL', 'Per Vial', 1198.00],
            [29, 'CEFTAZIDIME 1g VIAL', 'Per Vial', 203.00],
            [30, 'CEFTRIAXONE 1g VIAL', 'Per Vial', 336.00],
            [31, 'CEFUROXIME 1.5g VIAL', 'Per Vial', 69.00],
            [32, 'CEFUROXIME 500mg TABLET', 'Per Tablet', 24.00],
            [33, 'CEFUROXIME 750mg VIAL', 'Per Vial', 1047.00],
            [34, 'CELECOXIB 400mg CAPSULE', 'Per Capsule', 79.00],
            [35, 'CETIRIZINE 10MG TABLET', 'Per Tablet', 2.00],
            [36, 'CETIRIZINE 2.5mg/ml x 10ml BOTTLE', 'Per Bottle', 51.00],
            [37, 'CETIRIZINE 5mg/5ml X 60ml SYRUP', 'Per Bottle', 98.00],
            [38, 'CINNARIZINE 25mg TABLET', 'Per Tablet', 2.00],
            [39, 'CIPROFLOXACIN 2mg/ml x 100ml VIAL', 'Per Vial', 286.00],
            [40, 'CLARITHROMYCIN 250mg/5ml x 70ml susp BOTTLE', 'Per Bottle', 450.00],
            [41, 'CLINDAMYCIN 300mg CAPSULE', 'Per Capsule', 10.00],
            [42, 'CLOPIDOGREL 75mg TABLET', 'Per Tablet', 37.00],
            [43, 'CLOXACILLIN 500mg CAPSULE', 'Per Capsule', 6.00],
            [44, 'CO-AMOXICLAV 457mg/5mL X 70ML SUSPENSION', 'Per Bottle', 384.00],
            [45, 'CO-AMOXICLAV 625mg TABLET', 'Per Tablet', 15.00],
            [46, 'CO-TRIMOXAZOLE 960mg TABLET', 'Per Tablet', 3.00],
            [47, 'DIAZEPAM 5mg/ml x 2ml AMPOULE', 'Per Ampoule', 237.00],
            [48, 'DIGOXIN 250mcg/ml x 2ml AMPOULE', 'Per Ampoule', 73.00],
            [49, 'DIPHENHYDRAMINE 50mg AMPOULE', 'Per Ampoule', 120.00],
            [50, 'DOMPERIDONE 10MG TABLET', 'Per Tablet', 2.00],
            [51, 'DOPAMINE 40mg/ml x 5ml AMPOULE', 'Per Ampoule', 139.00],
            [52, 'ENOXAPARIN 40mg/ 0.4ml PREFILLED SYRINGE', 'Per Prefilled Syringe', 455.00],
            [53, 'EPHEDRINE 30mg/ml AMPOULE', 'Per Ampoule', 156.00],
            [54, 'EPINEPHRINE 1mg/ml AMPOULE', 'Per Ampoule', 100.00],
            [55, 'FENOFIBRATE 160mg CAPSULE', 'Per Capsule', 36.25],
            [56, 'FENTANYL CITRATE 50mcg/mL AMPOULE', 'Per Ampoule', 260.00],
            [57, 'FINASTERIDE 5mg TABLET', 'Per Tablet', 24.00],
            [58, 'FOLIC ACID 5mg CAPSULE', 'Per Capsule', 5.25],
            [59, 'FUROSEMIDE 20mg TABLET', 'Per Tablet', 6.50],
            [60, 'FUROSEMIDE 40mg TABLET', 'Per Tablet', 7.00],
            [61, 'GENTAMYCIN 40mg/ml x 2ml AMPOULE', 'Per Ampoule', 12.00],
            [62, 'GLICLAZIDE 30mg MR TABLET', 'Per Tablet', 5.00],
            [63, 'GLICLAZIDE 60mg TABLET', 'Per Tablet', 12.00],
            [64, 'GLICLAZIDE 80mg TABLET (Glycinorm-80)', 'Per Tablet', 3.00],
            [65, 'GLICLAZIDE 80mg TABLET (Zebet)', 'Per Tablet', 8.00],
            [66, 'HEPARIN 1000 IU/ml (5000iu/5ml) VIAL', 'Per Vial', 153.00],
            [67, 'HUMAN ALBUMIN 200g/L x 50ml VIAL', 'Per Vial', 3380.00],
            [68, 'HUMAN TETANUS IMMUNOGLOBULIN (HTIG) 250 iu/ml prefilled syringe', 'Per Prefilled Syringe', 1322.00],
            [69, 'HYDRALAZINE 20mg/ml AMPOULE', 'Per Ampoule', 38.00],
            [70, 'HYDROCORTISONE 100MG VIAL', 'Per Vial', 253.00],
            [71, 'HYDROCORTISONE 250mg VIAL', 'Per Vial', 432.00],
            [72, 'HYOSCINE 20mg/ml AMPOULE', 'Per Ampoule', 160.00],
            [73, 'HYOSCINE N-BUTYLBROMIDE 10mg TABLET', 'Per Tablet', 11.00],
            [74, 'IODIXANOL 652mg/ml equiv to 320mg IODINE 100ml VIAL', 'Per Vial', 9230.00],
            [75, 'IODIXANOL 652mg/ml equiv to 320mg IODINE 50ml VIAL', 'Per Vial', 5200.00],
            [76, 'KETAMINE HCl 50mg/ml x 10ml vial', 'Per cc (10ml Vial)', 165.00],
            [77, 'KETOROLAC 30mg/ml AMPOULE', 'Per Ampoule', 88.00],
            [78, "LACTATED RINGER'S SOLUTION 1L BOTTLE", 'Per Bottle', 233.00],
            [79, 'LACTULOSE 3.33mg/5ml x 120ml BOTTLE', 'Per Bottle', 103.00],
            [80, 'LEVOFLOXACIN 500mg TABLET', 'Per Tablet', 30.00],
            [81, 'LEVOFLOXACIN 5mg/ml x 100ml VIAL', 'Per Vial', 676.00],
            [82, 'LIDOCAINE 2% (20mg/ml x 5ml) AMPOULE', 'Per Ampoule', 62.00],
            [83, 'LOPERAMIDE 2mg CAPSULE', 'Per Capsule', 2.00],
            [84, 'LOSARTAN 100MG TABLET', 'Per Tablet', 5.00],
            [85, 'MEFENAMIC ACID 500mg CAPSULE', 'Per Capsule', 21.00],
            [86, 'MEROPENEM 1g VIAL', 'Per Vial', 779.00],
            [87, 'METFORMIN 500mg TABLET', 'Per Tablet', 19.00],
            [88, 'METHYLERGOMETRINE MALEATE 200mcg/ml AMPULE', 'Per Ampoule', 104.00],
            [89, 'METHYLPREDNISOLONE 16mg TABLET (METHYDEN-TANN PHARMA)', 'Per Tablet', 19.00],
            [90, 'METHYLPREDNISOLONE 16mg TABLET (METHYDEN-DYNASTY)', 'Per Tablet', 19.50],
            [91, 'METOCLOPRAMIDE 5mg/ml x 2ml AMPOULE', 'Per Ampoule', 27.00],
            [92, 'METOPROLOL TARTRATE 50mg TABLET', 'Per Tablet', 3.25],
            [93, 'METRONIDAZOLE 125mg/5ml x 60ml', 'Per Bottle', 36.00],
            [94, 'METRONIDAZOLE 500mg TABLET', 'Per Tablet', 3.00],
            [95, 'METRONIDAZOLE 5mg/ml x 100ml vial', 'Per Vial', 26.00],
            [96, 'MIDAZOLAM 5mg/ml x 3ml AMPOULE', 'Per Ampoule', 254.00],
            [97, 'MONTELUKAST 10mg TABLET (AZMONT)', 'Per Tablet', 8.00],
            [98, 'MORPHINE 10mg/ml x ml AMPOULE', 'Per Ampoule', 169.00],
            [99, 'MULTIVITAMINS CAPSULE', 'Per Capsule', 5.00],
            [100, 'MULTIVITAMINS SYRUP ORAL DROPS x 15ml BOTTLE', 'Per Bottle', 72.00],
            [101, 'NALOXONE 400mcg VIAL', 'Per Vial', 354.00],
            [102, 'OMEPRAZOLE 40mg VIAL', 'Per Vial', 517.00],
            [103, 'PARACETAMOL 125mg/5ml X 60ml SUSPENSION', 'Per Bottle', 29.00],
            [104, 'PARACETAMOL 500mg TABLET', 'Per Tablet', 1.00],
            [105, 'PHENYTOIN 100mg CAPSULE', 'Per Capsule', 39.00],
            [106, 'PHYTOMENADIONE 10mg/ml AMPULE', 'Per Ampoule', 46.00],
            [107, 'PIPERACILLIN + TAZOBACTAM 4.5g VIAL', 'Per Vial', 546.00],
            [108, 'PNSS 50ML VIAL', 'Per Vial', 59.00],
            [109, 'PREDNISONE 10mg TABLET', 'Per Tablet', 5.75],
            [110, 'PREDNISONE 5mg TABLET', 'Per Tablet', 3.25],
            [111, 'PROPOFOL 10mg/ml X 20ml AMPOULE (DIPRIFOL)', 'Per Ampoule', 455.00],
            [112, 'PROPOFOL 10mg/ml X 20ml AMPOULE (SEDAFOL)', 'Per Ampoule', 559.00],
            [113, 'RECOMBINANT HUMAN ERYTHROPOIETIN 4000IU/PFS', 'Per Prefilled Syringe', 780.00],
            [114, 'RIFAMPICIN 150mg + ISONIAZID 75mg + PYRAZINAMIDE 400mg + ETHAMBUTOL 275mg TABLET (HRZE)', 'Per Tablet', 13.00],
            [115, 'SACUBITRIL + VALSARTAN 200mg (97.2mg+102.8mg) TABLET', 'Per Tablet', 72.00],
            [116, 'SALBUTAMOL + IPRATROPIUM 200mcg/ 1mg per ml (500mcg/2.5mg per 2.5ml) AMPOULE', 'Per Ampoule', 60.00],
            [117, 'SALBUTAMOL 1mg/ml x 2.5ml NEBULE', 'Per Nebule', 17.00],
            [118, 'SIMVASTATIN 20mg TABLET', 'Per Tablet', 4.00],
            [119, 'STERILE WATER FOR INJECTION 50ML PLASTIC VIAL', 'Per Vial', 50.00],
            [120, 'SUXAMETHONIUM 20mg/ml x 10ml VIAL', 'Per Vial', 683.00],
            [121, 'TAMSULOSIN 200mcg TABLET', 'Per Tablet', 26.00],
            [122, 'TELMISARTAN 40mg TABLET', 'Per Tablet', 5.00],
            [123, 'TETANUS ANTITOXIN AMPOULE', 'Per Ampoule', 260.00],
            [124, 'TETANUS TOXOID 40iu/ml AMPOULE', 'Per Ampoule', 98.00],
            [125, 'TRAMADOL 50mg CAPSULE', 'Per Capsule', 13.00],
            [126, 'TRAMADOL 50mg/ml x 2ml AMPOULE', 'Per Ampoule', 148.00],
            [127, 'TRANEXAMIC ACID 100mg/ml x 5ml AMPOULE', 'Per Ampoule', 234.00],
            [128, 'TRANEXAMIC ACID 500mg CAPSULE', 'Per Capsule', 10.00],
            [129, 'TRIMETAZIDINE 35mg TABLET', 'Per Tablet', 7.00],
            [130, 'VIT. B COMPLEX TABLET', 'Per Tablet', 5.00],
            [131, 'ZINC SULFATE 20mg/5ml x 60ml BOT', 'Per Bottle', 39.00],
        ];

        $nonConsignedIvFluids = [
            [1, 'BALANCED MULTIPLE MAINTENANCE SOLUTION WITH 5% DEXTROSE (D5 IMB) 1L BOTTLE', 'Per Bottle', 58.50],
            [2, 'BALANCED MULTIPLE MAINTENANCE SOLUTION WITH 5% DEXTROSE (D5 IMB) 500 ML BOTTLE', 'Per Bottle', 60.00],
            [3, 'BALANCED MULTIPLE MAINTENANCE SOLUTION WITH 5% DEXTROSE (D5 NM) 1L BOTTLE', 'Per Bottle', 58.50],
            [4, '5% DEXTROSE IN WATER (D5 WATER) 500ML BOTTLE', 'Per Bottle', 39.00],
        ];

        $nonConsignedMedicines = [
            [1, "ACETYLCYSTEINE 100mg/ml x 3ml SOL'N FOR INHALATION AMPOULE", 'Per Ampoule', 171.00],
            [2, 'AZITHROMYCIN 500mg VIAL', 'Per Vial', 765.00],
            [3, 'BENZATHINE BENZYLPENICILLIN 1.2M VIAL', 'Per Vial', 123.00],
            [4, 'BENZYLPENICILLIN 5 MILLION', 'Per Vial', 47.00],
            [5, 'CALCIUM CARBONATE 500MG TABLET', 'Per Tablet', 3.25],
            [6, 'CAPTOPRIL 25MG TABLET', 'Per Tablet', 2.50],
            [7, 'CEFALEXIN 250mg/5ml X 60ml SUSPENSION', 'Per Bottle', 55.00],
            [8, 'CEFALEXIN 500mg CAPSULE', 'Per Capsule', 5.50],
            [9, 'CELECOXIB 400mg CAPSULE', 'Per Capsule', 79.00],
            [10, 'CLARITHROMYCIN 125mg/5ml X 60ml SUSPENSION', 'Per Bottle', 259.00],
            [11, 'CLONIDINE 150mcg TABLET', 'Per Tablet', 49.00],
            [12, 'CLONIDINE 150mcg/ml AMPOULE', 'Per Ampoule', 119.00],
            [13, 'MEFENAMIC ACID 250mg CAPSULE', 'Per Capsule', 2.50],
            [14, 'NALBUPHINE 10mg/ml AMPOULE', 'Per Ampoule', 117.00],
            [15, 'OSELTAMIVIR 75mg TABLET', 'Per Tablet', 40.00],
            [16, 'RANITIDINE 50mg AMPOULE', 'Per Ampoule', 33.00],
            [17, 'SALBUTAMOL 2mg/5ml X 60ml SYRUP', 'Per Bottle', 19.00],
            [18, 'SEVOFLURANE 100% X 250ml BOTTLE', 'Per cc (250ml Bottle)', 100.00],
            [19, 'VITAMIN B COMPLEX AMPOULE 100mg/100mg/1mgml x 3ml', 'Per Ampoule', 41.00],
        ];

        $mapRows = static fn (array $rows, string $prefix, string $note): array => array_map(
            static fn (array $row): array => [
                'item_code' => sprintf('%s-%03d', $prefix, $row[0]),
                'name' => $row[1],
                'unit' => $row[2],
                'price' => $row[3],
                'notes' => $note,
            ],
            $rows,
        );

        return array_merge(
            $mapRows($consigned, 'PHM-CS', $consignedNote),
            $mapRows($nonConsignedIvFluids, 'PHM-NC-IV', $nonConsignedIvNote),
            $mapRows($nonConsignedMedicines, 'PHM-NC-MD', $nonConsignedMedNote),
        );
    }

    /**
     * Build the official Department of Pathology laboratory rates (As of January 5, 2026).
     *
     * @return list<array{item_code: string, name: string, unit: string, price: float, notes: string}>
     */
    private function laboratoryItems(): array
    {
        $mapSection = static fn (array $rows, string $prefix, string $note): array => array_map(
            static fn (array $row, int $idx): array => [
                'item_code' => sprintf('%s-%02d', $prefix, $idx + 1),
                'name' => $row[0],
                'unit' => $row[1],
                'price' => $row[2],
                'notes' => $note,
            ],
            $rows,
            array_keys($rows),
        );

        $bloodChemistry = [
            ['24 HR, URINE COLLECTION CLEARANCE', 'Per Test', 510.00],
            ['ABG', 'Per Test', 1070.00],
            ['ALBUMIN', 'Per Test', 165.00],
            ['ALKALINE PHOS.', 'Per Test', 165.00],
            ['AMYLASE', 'Per Test', 395.00],
            ['B1B2', 'Per Test', 260.00],
            ['BUN', 'Per Test', 130.00],
            ['CALCIUM', 'Per Test', 180.00],
            ['CHLORIDE', 'Per Test', 180.00],
            ['CHOLESTEROL', 'Per Test', 180.00],
            ['CREATININE', 'Per Test', 130.00],
            ['ELECTROLYTE PACKAGE', 'Per Package', 550.00],
            ['FBS/RBS/PPBS', 'Per Test', 130.00],
            ['HBA1C', 'Per Test', 880.00],
            ['HDL-CHOL.', 'Per Test', 280.00],
            ['HGT', 'Per Test', 130.00],
            ['LDH', 'Per Test', 400.00],
            ['LIPID PROFILE(PACKAGE)', 'Per Package', 650.00],
            ['MAGNESIUM', 'Per Test', 275.00],
            ['OGCT', 'Per Test', 375.00],
            ['OGTT', 'Per Test', 750.00],
            ['PHOSPHORUS', 'Per Test', 275.00],
            ['POTASSIUM', 'Per Test', 180.00],
            ['SGOT/AST', 'Per Test', 150.00],
            ['SGPT/ALT', 'Per Test', 150.00],
            ['SODIUM', 'Per Test', 180.00],
            ['TOTAL PROTEIN', 'Per Test', 180.00],
            ['TPAG', 'Per Test', 340.00],
            ['TRIGLYCERIDES', 'Per Test', 250.00],
            ['URIC ACID', 'Per Test', 160.00],
        ];

        $bloodBank = [
            ['ABO', 'Per Test', 130.00],
            ['ABO/RH', 'Per Test', 200.00],
            ['BEDSIDE TYPING', 'Per Test', 200.00],
            ["COOMB'S TEST", 'Per Test', 270.00],
            ['CROSSMATCHING - (SLIDE) FFP, PC', 'Per Test', 350.00],
            ['CROSSMATCHING - (BROAD) WB/PRBC', 'Per Test', 700.00],
        ];

        $microbiology = [
            ['CULTURE SENSITIVITY = BLOOD', 'Per Specimen', 2750.00],
            ['CULTURE SENSITIVITY = WOUND/SPUTUM/PLEURAL FLUIDS/URINE', 'Per Specimen', 2500.00],
            ['AFB', 'Per Test', 200.00],
            ['GRAM STAIN', 'Per Test', 150.00],
            ['KOH', 'Per Test', 150.00],
            ['THROAT SWAB', 'Per Test', 150.00],
        ];

        $microscopy = [
            ['BENEDICTS', 'Per Test', 60.00],
            ['BODY FLUIDS', 'Per Test', 130.00],
            ['FECALYSIS', 'Per Test', 75.00],
            ['FECALYSIS - CONCENTRATION METHOD', 'Per Test', 165.00],
            ['KETONES(URINE STRIPS)', 'Per Test', 70.00],
            ['URINALYSIS', 'Per Test', 75.00],
        ];

        $otherTest = [
            ['DRUG TESTING', 'Per Test', 200.00],
        ];

        $hematology = [
            ['APC/PLT. (MACHINE)', 'Per Test', 140.00],
            ['APTT', 'Per Test', 700.00],
            ['CBC (MACHINE)', 'Per Test', 140.00],
            ['CBC/APC (PACKAGE)', 'Per Package', 260.00],
            ['CTBT', 'Per Test', 70.00],
            ['DIFF. CT', 'Per Test', 50.00],
            ['ESR', 'Per Test', 100.00],
            ['HGB/HCT (MACHINE)', 'Per Test', 140.00],
            ['HGB/HCT (MANUAL)', 'Per Test', 70.00],
            ['MALARIAL SMEAR', 'Per Test', 150.00],
            ['PBS', 'Per Test', 150.00],
            ['PROTIME', 'Per Test', 700.00],
            ['WBC CT.', 'Per Test', 50.00],
        ];

        $serology = [
            ['AFP (Quanti)', 'Per Test', 810.00],
            ['Anti-HBs (Quali)', 'Per Test', 500.00],
            ['Anti-HBs (Quanti)', 'Per Test', 1170.00],
            ['ANTI-HAV', 'Per Test', 450.00],
            ['Anti-HCV (Quali)', 'Per Test', 400.00],
            ['Anti-HCV (Quanti)', 'Per Test', 1040.00],
            ['ASO TITER', 'Per Test', 200.00],
            ['Beta HCg (Quanti)', 'Per Test', 1170.00],
            ['CA 19-9', 'Per Test', 1160.00],
            ['CA-125 (Quanti)', 'Per Test', 1300.00],
            ['CEA (Quanti)', 'Per Test', 1135.00],
            ['COVID-19 Antibody Rapid Test', 'Per Test', 820.00],
            ['COVID-19 Antigen Rapid Test', 'Per Test', 660.00],
            ['CRP', 'Per Test', 200.00],
            ['D-DIMER', 'Per Test', 1500.00],
            ['DENGUE RAPID TEST', 'Per Test', 900.00],
            ['FERRITIN', 'Per Test', 1200.00],
            ['FT3 (Quanti)', 'Per Test', 820.00],
            ['FT4 (Quanti)', 'Per Test', 820.00],
            ['H. PYLORI', 'Per Test', 600.00],
            ['HBSAG', 'Per Test', 195.00],
            ['PROCALCITONIN', 'Per Test', 2000.00],
            ['PSA (Quanti)', 'Per Test', 1100.00],
            ['RF', 'Per Test', 200.00],
            ['RPR/SYPHILIS/VDRL', 'Per Test', 350.00],
            ['SALMONELLA TYPHI', 'Per Test', 650.00],
            ['SERUM PREG. TEST', 'Per Test', 205.00],
            ['T3 (Quanti)', 'Per Test', 770.00],
            ['T4 (Quanti)', 'Per Test', 770.00],
            ['TOTAL PSA (Quanti)', 'Per Test', 1120.00],
            ['TROPONIN I (Quanti)', 'Per Test', 1050.00],
            ['TROPONIN I (Quali)', 'Per Test', 600.00],
            ['TSH (Quanti)', 'Per Test', 900.00],
            ['URINE PREG. TEST', 'Per Test', 195.00],
        ];

        $histopath = [
            ['CELL BLOCK (CYTO)', 'Per Specimen', 1400.00],
            ['FNAB (OPD CASE)', 'Per Case', 300.00],
            ['FNAB (ADMITTED)', 'Per Case', 700.00],
            ['PAPSMEAR (OPD CASE)', 'Per Case', 150.00],
            ['HISTOPATH (SMALL)', 'Per Specimen', 1400.00],
            ['HISTOPATH (MEDIUM)', 'Per Specimen', 1600.00],
            ['HISTOPATH (LARGE OR XL)', 'Per Specimen', 3200.00],
        ];

        return array_merge(
            $mapSection($bloodChemistry, 'LAB-CHEM', 'Blood Chemistry (As of Jan 5, 2026)'),
            $mapSection($bloodBank, 'LAB-BB', 'Blood Bank (As of Jan 5, 2026)'),
            $mapSection($microbiology, 'LAB-MICR', 'Microbiology (As of Jan 5, 2026)'),
            $mapSection($microscopy, 'LAB-MICS', 'Microscopy (As of Jan 5, 2026)'),
            $mapSection($otherTest, 'LAB-OTH', 'Other Test (As of Jan 5, 2026)'),
            $mapSection($hematology, 'LAB-HEMA', 'Hematology (As of Jan 5, 2026)'),
            $mapSection($serology, 'LAB-SERO', 'Serology (As of Jan 5, 2026)'),
            $mapSection($histopath, 'LAB-HIST', 'Histopath (As of Jan 5, 2026)'),
        );
    }

    /**
     * Build the Radiology X-Ray and CT Scan Examinations rates (both as of January 1, 2026 per Ordinance No. 367, s.2024).
     *
     * @return list<array{item_code: string, name: string, unit: string, price: float, notes: string}>
     */
    private function radiologyItems(): array
    {
        $mapXray = static fn (array $rows, string $prefix, string $seriesLabel): array => array_map(
            static fn (array $row, int $idx): array => [
                'item_code' => sprintf('%s-%02d', $prefix, $idx + 1),
                'name' => $row[0],
                'unit' => $row[1],
                'price' => $row[2],
                'notes' => "X-Ray ({$seriesLabel}): As of Jan 1, 2026 per Ordinance No. 367, s.2024",
            ],
            $rows,
            array_keys($rows),
        );

        $skullSeries = [
            ['SKULL APL', 'Per Exam', 580.00],
            ['NASAL BONE', 'Per Exam', 325.00],
            ['MANDIBLE APO', 'Per Exam', 580.00],
            ["WATER'S VIEW", 'Per Exam', 325.00],
            ["TOWNE'S VIEW", 'Per Exam', 325.00],
        ];

        $thorax = [
            ['CHEST PA/AP', 'Per Exam', 250.00],
            ['CHEST PAL/APL (ADULT)', 'Per Exam', 500.00],
            ['CHEST APL/PAL (PEDIA/NEWBORN)', 'Per Exam', 550.00],
            ['CHEST BUCKY', 'Per Exam', 350.00],
            ['CHEST LAT./DECUB.', 'Per Exam', 260.00],
            ['CHEST APICOLORDOTIC VIEW', 'Per Exam', 260.00],
        ];

        $abdomen = [
            ['ABDOMEN AP/KUB (ADULT)', 'Per Exam', 315.00],
            ['ABDOMEN S/U', 'Per Exam', 630.00],
            ['ABDOMEN APL (PEDIA/NEWBORN)', 'Per Exam', 590.00],
            ['FETOGRAM', 'Per Exam', 365.00],
            ['PELVIS AP', 'Per Exam', 365.00],
        ];

        $extremities = [
            ['FOREARM APL', 'Per Exam', 315.00],
            ['FOOT APO', 'Per Exam', 315.00],
            ['HAND APO', 'Per Exam', 315.00],
            ['BONE AGING', 'Per Exam', 315.00],
            ['ARM APL', 'Per Exam', 315.00],
            ['SHOULDER AP', 'Per Exam', 315.00],
            ['SHOULDER Y', 'Per Exam', 315.00],
            ['THIGH APL', 'Per Exam', 315.00],
            ['ANKLE APL', 'Per Exam', 315.00],
            ['WRIST APL', 'Per Exam', 315.00],
            ['KNEE APL', 'Per Exam', 315.00],
            ['ELBOW APL', 'Per Exam', 315.00],
            ['LEG APL', 'Per Exam', 315.00],
        ];

        $vertebralColumn = [
            ['LUMBO-SACRAL APL', 'Per Exam', 650.00],
            ['THORACIC SPINE APL', 'Per Exam', 650.00],
            ['THORACOLUMBAR SPINE APL', 'Per Exam', 1300.00],
            ['CERVICAL APL', 'Per Exam', 580.00],
        ];

        $specialProcedures = [
            ['CHOLANGIOGRAM', 'Per Exam', 1820.00],
            ['BARIUM ENEMA', 'Per Exam', 2080.00],
            ['UPPER GI', 'Per Exam', 2080.00],
            ['IVP', 'Per Exam', 2080.00],
            ['RETROGRADE', 'Per Exam', 1820.00],
            ['BARIUM SWALLOW', 'Per Exam', 1820.00],
            ['COLONOGRAM', 'Per Exam', 1820.00],
            ['SKELETAL SURVEY', 'Per Exam', 5200.00],
            ['INTRA OP (SP)', 'Per Procedure', 1820.00],
            ['PORTABLE MACHINE (ADDITIONAL CHARGE)', 'Additional Charge', 500.00],
        ];

        $xray = array_merge(
            $mapXray($skullSeries, 'RAD-XR-SKL', 'Skull Series'),
            $mapXray($thorax, 'RAD-XR-THX', 'Thorax'),
            $mapXray($abdomen, 'RAD-XR-ABD', 'Abdomen'),
            $mapXray($extremities, 'RAD-XR-EXT', 'Extremities'),
            $mapXray($vertebralColumn, 'RAD-XR-VRT', 'Vertebral Column'),
            $mapXray($specialProcedures, 'RAD-XR-SPC', 'Special Procedures'),
        );

        $ctNote = 'CT Scan Examinations (As of Jan 1, 2026 per Ordinance No. 367, s.2024)';
        $ctRows = [
            ['CRANIAL CT PLAIN', 'Per Exam', 5700.00],
            ['CERVICAL SPINE CT', 'Per Exam', 5300.00],
            ['CERVICO-THORACIC SPINE CT', 'Per Exam', 8000.00],
            ['CHEST CT', 'Per Exam', 7000.00],
            ['EXTREMITIES CT (LOWER)', 'Per Exam', 5000.00],
            ['EXTREMITIES CT (UPPER)', 'Per Exam', 5200.00],
            ['FACIAL BONE CT', 'Per Exam', 3250.00],
            ['HIP CT', 'Per Exam', 10000.00],
            ['HRCT (HIGH RESOLUTION)', 'Per Exam', 5000.00],
            ['KUB CT', 'Per Exam', 4950.00],
            ['LOWER ABDOMEN CT', 'Per Exam', 10000.00],
            ['LUMBAR SPINE CT', 'Per Exam', 10000.00],
            ['MANDIBLE/MASTOID CT', 'Per Exam', 4900.00],
            ['NECK/NASOPHARYNX/OROPHARYNX CT', 'Per Exam', 10000.00],
            ['PELVIS CT', 'Per Exam', 10000.00],
            ['PNS CT', 'Per Exam', 4500.00],
            ['STONOGRAM CT', 'Per Exam', 8000.00],
            ['THORACIC SPINE CT', 'Per Exam', 5300.00],
            ['THORACO-LUMBAR SPINE CT', 'Per Exam', 8000.00],
            ['UPPER ABDOMEN CT', 'Per Exam', 10000.00],
            ['UROGRAM CT', 'Per Exam', 8125.00],
            ['WHOLE ABDOMEN CT', 'Per Exam', 10000.00],
            ['WHOLE SPINE CT', 'Per Exam', 12000.00],
            ['CRANIAL CT WITH CONTRAST', 'Per Exam', 6500.00],
            ['PNS CT WITH CONTRAST', 'Per Exam', 11700.00],
            ['CHEST CT WITH CONTRAST', 'Per Exam', 10000.00],
            ['UPPER ABDOMEN CT WITH CONTRAST', 'Per Exam', 11700.00],
            ['LOWER ABDOMEN CT WITH CONTRAST', 'Per Exam', 11700.00],
            ['WHOLE ABDOMEN CT WITH CONTRAST', 'Per Exam', 13960.00],
            ['WHOLE ABDOMEN CT-3 PHASE', 'Per Exam', 15000.00],
            ['WHOLE ABDOMEN TRIPLE WITH CONTRAST', 'Per Exam', 15000.00],
            ['CT SCAN FILM', 'Per Sheet', 250.00],
            ['ORBITS CT', 'Per Exam', 4900.00],
            ['PNS', 'Per Exam', 3250.00],
            ['PNS SCREENING', 'Per Exam', 4500.00],
            ['TEMPORAL/MASTOID/PIT', 'Per Exam', 1300.00],
            ['NECK/THYROID', 'Per Exam', 10000.00],
            ['EXTREMITIES', 'Per Exam', 6000.00],
            ['KIDNEYS/ADRENAL LIVER/PANCREAS', 'Per Exam', 10000.00],
            ['CERVICAL', 'Per Exam', 5000.00],
            ['THORACIC', 'Per Exam', 5000.00],
            ['LUMBO-SACRAL', 'Per Exam', 10000.00],
            ['VENOGRAM', 'Per Exam', 10000.00],
            ['CT ANGIOGRAM', 'Per Exam', 10000.00],
            ['BIOPSY GUIDED', 'Per Procedure', 5000.00],
        ];

        $ct = array_map(
            static fn (array $row, int $idx): array => [
                'item_code' => sprintf('RAD-CT-%03d', $idx + 1),
                'name' => $row[0],
                'unit' => $row[1],
                'price' => $row[2],
                'notes' => $ctNote,
            ],
            $ctRows,
            array_keys($ctRows),
        );

        return array_merge($xray, $ct);
    }

    /**
     * Build the official Ultrasound price list (As of January 1, 2026).
     *
     * @return list<array{item_code: string, name: string, unit: string, price: float, notes: string}>
     */
    private function ultrasoundItems(): array
    {
        $note = 'As of Jan 1, 2026 · Mon/Wed/Fri (Dr. Emmanuel M. Palomata, DPBR)';

        return [
            ['item_code' => 'UTS-001', 'name' => 'Whole Abdomen Ultrasound', 'unit' => 'Per Exam', 'price' => 1700.00, 'notes' => $note],
            ['item_code' => 'UTS-002', 'name' => 'KUB Ultrasound', 'unit' => 'Per Exam', 'price' => 1200.00, 'notes' => $note],
            ['item_code' => 'UTS-003', 'name' => 'KUBP Ultrasound', 'unit' => 'Per Exam', 'price' => 1200.00, 'notes' => $note],
            ['item_code' => 'UTS-004', 'name' => 'Upper/Lower Abdomen Ultrasound', 'unit' => 'Per Exam', 'price' => 1200.00, 'notes' => $note],
            ['item_code' => 'UTS-005', 'name' => 'Ultrasound Guided Procedure', 'unit' => 'Per Procedure', 'price' => 1200.00, 'notes' => $note],
        ];
    }
}
