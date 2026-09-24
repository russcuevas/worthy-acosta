<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barangay;
use App\Models\Assistance;
use Carbon\Carbon;

class AssistanceSeeder extends Seeder
{
    /**
     * Run database seeds for Assistance records.
     */
    public function run(): void
    {
        $barangays = Barangay::orderBy('id')->get();
        if ($barangays->isEmpty()) {
            $this->call(BarangaySeeder::class);
            $barangays = Barangay::orderBy('id')->get();
        }

        // Realistic assistance catalog
        $catalog = [
            'Financial' => [
                ['name' => 'Medical Assistance', 'min_amt' => 3000, 'max_amt' => 10000, 'min_ben' => 1, 'max_ben' => 2],
                ['name' => 'Hospitalization Bill Subsidy', 'min_amt' => 5000, 'max_amt' => 15000, 'min_ben' => 1, 'max_ben' => 1],
                ['name' => 'Educational Assistance / Scholarship Grant', 'min_amt' => 4000, 'max_amt' => 8000, 'min_ben' => 1, 'max_ben' => 5],
                ['name' => 'Emergency Cash Assistance (AICS)', 'min_amt' => 3000, 'max_amt' => 6000, 'min_ben' => 1, 'max_ben' => 3],
                ['name' => 'Senior Citizen Financial Aid', 'min_amt' => 2000, 'max_amt' => 5000, 'min_ben' => 1, 'max_ben' => 10],
            ],
            'Burial' => [
                ['name' => 'Burial Assistance & Casket Aid', 'min_amt' => 5000, 'max_amt' => 15000, 'min_ben' => 1, 'max_ben' => 1],
                ['name' => 'Mortuary Transportation & Burial Aid', 'min_amt' => 3000, 'max_amt' => 8000, 'min_ben' => 1, 'max_ben' => 1],
            ],
            'Tent' => [
                ['name' => 'Wake / Community Tent Installation', 'min_amt' => 2500, 'max_amt' => 5000, 'min_ben' => 40, 'max_ben' => 80],
                ['name' => 'Barangay Activity & Evacuation Tent', 'min_amt' => 5000, 'max_amt' => 10000, 'min_ben' => 100, 'max_ben' => 250],
            ],
            'Item Donation' => [
                ['name' => 'Food Packs Distribution (Relief)', 'min_amt' => 15000, 'max_amt' => 40000, 'min_ben' => 50, 'max_ben' => 150],
                ['name' => 'Rice Subsidy (25kg Sacks)', 'min_amt' => 25000, 'max_amt' => 60000, 'min_ben' => 30, 'max_ben' => 100],
                ['name' => 'Wheelchair & Crutches Donation', 'min_amt' => 6000, 'max_amt' => 18000, 'min_ben' => 2, 'max_ben' => 5],
                ['name' => 'Nebulizer & Medical Supply Kit', 'min_amt' => 4000, 'max_amt' => 12000, 'min_ben' => 3, 'max_ben' => 8],
                ['name' => 'Student Backpack & School Supplies', 'min_amt' => 12000, 'max_amt' => 30000, 'min_ben' => 50, 'max_ben' => 120],
            ],
            'Others' => [
                ['name' => 'Solar Streetlight Unit Setup', 'custom' => 'Infrastructure Aid', 'min_amt' => 8000, 'max_amt' => 20000, 'min_ben' => 20, 'max_ben' => 50],
                ['name' => 'Livelihood Starter Grocery Kit', 'custom' => 'Livelihood Assistance', 'min_amt' => 5000, 'max_amt' => 12000, 'min_ben' => 1, 'max_ben' => 4],
                ['name' => 'Roofing GI Sheet Replacement (Typhoon Aid)', 'custom' => 'Disaster Recovery', 'min_amt' => 10000, 'max_amt' => 25000, 'min_ben' => 3, 'max_ben' => 10],
            ]
        ];

        // Specific sample records matching user specification example for Cabcaben, Poblacion, Camaya, Alas-Asin, etc.
        $startDate = Carbon::now()->subMonths(6);
        $records = [];

        foreach ($barangays as $bgy) {
            // Generate 8-16 records per barangay spread over recent months
            $numRecords = rand(8, 16);

            for ($i = 0; $i < $numRecords; $i++) {
                $types = array_keys($catalog);
                // Weight types: Financial and Item Donation are most frequent
                $typeWeights = ['Financial', 'Financial', 'Financial', 'Burial', 'Burial', 'Tent', 'Item Donation', 'Item Donation', 'Others'];
                $chosenType = $typeWeights[array_rand($typeWeights)];
                $item = $catalog[$chosenType][array_rand($catalog[$chosenType])];

                $daysAgo = rand(0, 180);
                $recDate = Carbon::now()->subDays($daysAgo)->format('Y-m-d');
                $amount = rand($item['min_amt'] / 500, $item['max_amt'] / 500) * 500;
                $beneficiaries = rand($item['min_ben'], $item['max_ben']);

                $records[] = [
                    'barangay_id' => $bgy->id,
                    'type' => $chosenType,
                    'custom_type' => $item['custom'] ?? null,
                    'assistance_given' => $item['name'],
                    'date' => $recDate,
                    'amount' => $amount,
                    'beneficiaries_count' => $beneficiaries,
                    'notes' => 'Encoded via Admin Portal for Brgy. ' . $bgy->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in chunks
        foreach (array_chunk($records, 100) as $chunk) {
            Assistance::insert($chunk);
        }
    }
}
