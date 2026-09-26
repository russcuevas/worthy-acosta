<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barangay;
use App\Models\SurveyPeriod;
use App\Models\SurveyRecord;
use Illuminate\Support\Facades\DB;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if barangays exist
        $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        if ($barangays->isEmpty()) {
            $this->call(BarangaySeeder::class);
            $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        }

        // Clear existing survey data
        DB::table('survey_records')->truncate();
        DB::table('survey_periods')->delete();

        // 1. Define Survey Periods
        $periodsData = [
            [
                'name' => 'June 5-7, 2026',
                'start_date' => '2026-06-05',
                'end_date' => '2026-06-07',
                'sample_size' => 1800,
                'methodology' => 'Face-to-face multi-stage stratified random sampling across 18 barangays (Margin of Error ±2.3%)',
                'notes' => 'Baseline municipal election readiness and voter sentiment benchmark survey.',
            ],
            [
                'name' => 'July 10-12, 2026',
                'start_date' => '2026-07-10',
                'end_date' => '2026-07-12',
                'sample_size' => 1800,
                'methodology' => 'Face-to-face random sampling across 18 barangays with randomized household selection (Margin of Error ±2.3%)',
                'notes' => 'Mid-quarter tracking survey following the municipal infrastructure & civic assistance rollouts.',
            ],
            [
                'name' => 'August 15-17, 2026',
                'start_date' => '2026-08-15',
                'end_date' => '2026-08-17',
                'sample_size' => 1800,
                'methodology' => 'Independent face-to-face voter perception survey with demographic balancing (Margin of Error ±2.3%)',
                'notes' => 'Third-wave pre-election assessment highlighting recent campaign events and barangay consultations.',
            ],
        ];

        // Candidates and colors
        $candidates = [
            ['name' => 'Worthy Acosta', 'color' => '#075998'], // Primary Blue
            ['name' => 'Candidate B', 'color' => '#16A34A'],   // Emerald Green
            ['name' => 'Candidate C', 'color' => '#EAB308'],   // Amber Yellow
            ['name' => 'Undecided / Others', 'color' => '#94A3B8'], // Slate Grey
        ];

        // Base distribution weights per barangay for realistic variety across Mariveles 18 barangays
        // Some barangays heavily Acosta, some B, some C
        $bgyWeights = [
            1 => [48, 32, 14, 6],   // Alion: Acosta leads
            2 => [52, 28, 13, 7],   // Batangas II: Acosta leads
            3 => [44, 38, 12, 6],   // Cabcaben: Acosta leads
            4 => [34, 45, 15, 6],   // Lucanin: Candidate B leads
            5 => [49, 31, 14, 6],   // Balon-Anito: Acosta leads
            6 => [33, 44, 16, 7],   // Maligaya: Candidate B leads
            7 => [30, 28, 35, 7],   // Biaan: Candidate C leads
            8 => [46, 33, 14, 7],   // Malaya: Acosta leads
            9 => [51, 29, 13, 7],   // Townsite: Acosta leads
            10 => [32, 46, 15, 7],  // San Isidro: Candidate B leads
            11 => [53, 27, 13, 7],  // Mt. View: Acosta leads
            12 => [47, 34, 13, 6],  // Alas-Asin: Acosta leads
            13 => [50, 31, 13, 6],  // Camaya: Acosta leads
            14 => [45, 36, 13, 6],  // Baseco Country: Acosta leads
            15 => [31, 47, 15, 7],  // San Carlos: Candidate B leads
            16 => [54, 26, 14, 6],  // Poblacion: Acosta leads
            17 => [32, 27, 34, 7],  // Sisiman: Candidate C leads
            18 => [48, 33, 13, 6],  // Ipag: Acosta leads
        ];

        // Period progression modifiers
        // Period 0 (June): base
        // Period 1 (July): Acosta gains +2 to +4, B changes -1 to -2, C changes -1
        // Period 2 (August): Acosta gains +3 to +6, B changes -2, undecided drops
        $periodModifiers = [
            0 => [0, 0, 0, 0],
            1 => [3.2, -1.8, -0.8, -0.6],
            2 => [6.4, -3.2, -1.8, -1.4],
        ];

        foreach ($periodsData as $pIndex => $pData) {
            $period = SurveyPeriod::create($pData);
            $mod = $periodModifiers[$pIndex];

            foreach ($barangays as $bgy) {
                $base = $bgyWeights[$bgy->id] ?? [45, 33, 15, 7];

                // Apply period modifier with slight random variation for realism
                $rAcosta = round(max(15, min(75, $base[0] + $mod[0] + rand(-10, 10) / 10)), 1);
                $rB = round(max(15, min(65, $base[1] + $mod[1] + rand(-10, 10) / 10)), 1);
                $rC = round(max(5, min(50, $base[2] + $mod[2] + rand(-10, 10) / 10)), 1);
                
                // Remainder is Undecided
                $rUndecided = round(max(2.0, 100.0 - ($rAcosta + $rB + $rC)), 1);

                $ratings = [$rAcosta, $rB, $rC, $rUndecided];

                foreach ($candidates as $cIndex => $candidate) {
                    SurveyRecord::create([
                        'survey_period_id' => $period->id,
                        'barangay_id' => $bgy->id,
                        'candidate_name' => $candidate['name'],
                        'candidate_color' => $candidate['color'],
                        'rating' => $ratings[$cIndex],
                        'sample_size' => 100, // 100 respondents per barangay
                        'methodology' => 'Random cluster sampling within barangay voting precincts',
                        'notes' => 'Confidence level: 95%, localized margin of error: ±4.8%',
                    ]);
                }
            }
        }
    }
}
