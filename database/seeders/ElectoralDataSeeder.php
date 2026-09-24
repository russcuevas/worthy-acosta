<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ElectionYear;
use App\Models\ElectoralRecord;
use App\Models\Barangay;

class ElectoralDataSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch all barangays directly from the database table
        $barangays = Barangay::orderBy('id')->get();
        if ($barangays->isEmpty()) {
            $this->call(BarangaySeeder::class);
            $barangays = Barangay::orderBy('id')->get();
        }

        $candidatesByYear = [
            '2025' => [
                'Mayor' => [],
                'Vice Mayor' => [],
                'Governor' => [],
                'Congressman' => [],
                'Councilors' => [],
            ],
            '2023' => [
                'Barangay Captain' => [],
                'Barangay Kagawads' => [],
            ],
            '2022' => [
                'Mayor' => [],
                'Vice Mayor' => [],
                'Governor' => [],
                'Congressman' => [],
                'Councilors' => [],
            ],
            '2019' => [
                'Mayor' => [],
                'Vice Mayor' => [],
                'Governor' => [],
                'Congressman' => [],
                'Councilors' => [],
            ],
            '2016' => [
                'Mayor' => [],
                'Vice Mayor' => [],
                'Governor' => [],
                'Congressman' => [],
                'Councilors' => [],
            ],
            '2013' => [
                'Mayor' => [],
                'Vice Mayor' => [],
                'Governor' => [],
                'Congressman' => [],
                'Councilors' => [],
            ]
        ];

        foreach ($candidatesByYear as $year => $positionsMap) {
            $positions = array_keys($positionsMap);
            
            ElectionYear::updateOrCreate(
                ['year' => $year],
                [
                    'title' => "{$year} " . ($year === '2023' ? 'Barangay Elections' : 'Local & National Elections'),
                    'positions' => $positions,
                    'is_active' => true,
                ]
            );

            foreach ($positions as $position) {
                $candidatesTemplate = $positionsMap[$position];

                // Prepare clean candidate list with 0 votes
                $cleanCandidates = array_map(function ($c) {
                    return [
                        'name' => $c['name'],
                        'color' => $c['color'] ?? '#075998',
                        'votes' => 0
                    ];
                }, $candidatesTemplate);

                foreach ($barangays as $barangay) {
                    $bgyId = $barangay->id;
                    $bgyName = $barangay->name;

                    ElectoralRecord::updateOrCreate(
                        [
                            'year' => $year,
                            'position' => $position,
                            'barangay_id' => $bgyId,
                        ],
                        [
                            'barangay_name' => $bgyName,
                            'registered_voters' => 0,
                            'actual_votes' => 0,
                            'turnout_percentage' => 0.00,
                            'winner_name' => 'None',
                            'winner_color' => '#64748B',
                            'winner_votes' => 0,
                            'candidates_data' => $cleanCandidates,
                        ]
                    );
                }
            }
        }
    }
}
