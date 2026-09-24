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
                'Mayor' => [
                    ['name' => 'Worthy Acosta', 'color' => '#075998'],
                    ['name' => 'Ace Jello Concepcion', 'color' => '#E53935'],
                    ['name' => 'Jocelyn Castaneda', 'color' => '#2E7D32'],
                ],
                'Vice Mayor' => [
                    ['name' => 'Angel Peliglorio', 'color' => '#075998'],
                    ['name' => 'Lito Del Rosario', 'color' => '#E53935'],
                    ['name' => 'Danilo Rubia', 'color' => '#FF9800'],
                ],
                'Governor' => [
                    ['name' => 'Jose Enrique Garcia III', 'color' => '#2196F3'],
                    ['name' => 'Rogelio Valenzuela', 'color' => '#8E24AA'],
                ],
                'Congressman' => [
                    ['name' => 'Albert Garcia', 'color' => '#2196F3'],
                    ['name' => 'Antonio Roman Jr.', 'color' => '#E53935'],
                ],
                'Councilors' => [
                    ['name' => 'Ivan Ricafrente', 'color' => '#075998'],
                    ['name' => 'Ronald Arcenal', 'color' => '#075998'],
                    ['name' => 'Manny Valencia', 'color' => '#E53935'],
                    ['name' => 'Tito Catipon', 'color' => '#E53935'],
                    ['name' => 'Joey Carandang', 'color' => '#2E7D32'],
                    ['name' => 'Susan Banzon', 'color' => '#2E7D32'],
                ]
            ],
            '2023' => [
                'Barangay Captain' => [
                    ['name' => 'Candidate Alpha', 'color' => '#2196F3'],
                    ['name' => 'Candidate Beta', 'color' => '#E53935'],
                    ['name' => 'Candidate Gamma', 'color' => '#2E7D32'],
                ],
                'Barangay Kagawads' => [
                    ['name' => 'Team Pagbabago (Unified)', 'color' => '#075998'],
                    ['name' => 'Team Serbisyong Tapat', 'color' => '#E53935'],
                ]
            ],
            '2022' => [
                'Mayor' => [
                    ['name' => 'Ace Jello Concepcion', 'color' => '#E53935'],
                    ['name' => 'Jocelyn Castaneda', 'color' => '#2E7D32'],
                    ['name' => 'Worthy Acosta', 'color' => '#075998'],
                ],
                'Vice Mayor' => [
                    ['name' => 'Lito Del Rosario', 'color' => '#E53935'],
                    ['name' => 'Angel Peliglorio', 'color' => '#075998'],
                ],
                'Governor' => [
                    ['name' => 'Jose Enrique Garcia III', 'color' => '#2196F3'],
                    ['name' => 'Herminio Aquino', 'color' => '#FF9800'],
                ],
                'Congressman' => [
                    ['name' => 'Albert Garcia', 'color' => '#2196F3'],
                    ['name' => 'Maria Victoria Sy', 'color' => '#E53935'],
                ],
                'Councilors' => [
                    ['name' => 'Ivan Ricafrente', 'color' => '#075998'],
                    ['name' => 'Ronald Arcenal', 'color' => '#E53935'],
                    ['name' => 'Manny Valencia', 'color' => '#2E7D32'],
                ]
            ],
            '2019' => [
                'Mayor' => [
                    ['name' => 'Jocelyn Castaneda', 'color' => '#2E7D32'],
                    ['name' => 'Ace Jello Concepcion', 'color' => '#E53935'],
                ],
                'Vice Mayor' => [
                    ['name' => 'Lito Del Rosario', 'color' => '#E53935'],
                    ['name' => 'Danilo Rubia', 'color' => '#FF9800'],
                ],
                'Governor' => [
                    ['name' => 'Albert Garcia', 'color' => '#2196F3'],
                    ['name' => 'Dante Manalili', 'color' => '#8E24AA'],
                ],
                'Congressman' => [
                    ['name' => 'Jose Enrique Garcia III', 'color' => '#2196F3'],
                    ['name' => 'Alexis Rivera', 'color' => '#E53935'],
                ],
                'Councilors' => [
                    ['name' => 'Team Castaneda Alliance', 'color' => '#2E7D32'],
                    ['name' => 'Team Concepcion Coalition', 'color' => '#E53935'],
                ]
            ],
            '2016' => [
                'Mayor' => [
                    ['name' => 'Ace Jello Concepcion', 'color' => '#E53935'],
                    ['name' => 'Jesse Concepcion', 'color' => '#2196F3'],
                ],
                'Vice Mayor' => [
                    ['name' => 'Lito Del Rosario', 'color' => '#E53935'],
                    ['name' => 'Angel Peliglorio', 'color' => '#075998'],
                ],
                'Governor' => [
                    ['name' => 'Albert Garcia', 'color' => '#2196F3'],
                    ['name' => 'Manuel Mendoza', 'color' => '#FF9800'],
                ],
                'Congressman' => [
                    ['name' => 'Jose Enrique Garcia III', 'color' => '#2196F3'],
                    ['name' => 'Antonio Roman Jr.', 'color' => '#E53935'],
                ],
                'Councilors' => [
                    ['name' => 'Kabalikat ng Bayan', 'color' => '#E53935'],
                    ['name' => 'Asenso Mariveles', 'color' => '#2196F3'],
                ]
            ],
            '2013' => [
                'Mayor' => [
                    ['name' => 'Jesse Concepcion', 'color' => '#2196F3'],
                    ['name' => 'Angel Peliglorio', 'color' => '#075998'],
                ],
                'Vice Mayor' => [
                    ['name' => 'Lito Del Rosario', 'color' => '#E53935'],
                    ['name' => 'Victor Ocon', 'color' => '#2E7D32'],
                ],
                'Governor' => [
                    ['name' => 'Albert Garcia', 'color' => '#2196F3'],
                    ['name' => 'Ramon Arnaiz', 'color' => '#E53935'],
                ],
                'Congressman' => [
                    ['name' => 'Herminia Roman', 'color' => '#FF9800'],
                    ['name' => 'Felicito Payumo', 'color' => '#2196F3'],
                ],
                'Councilors' => [
                    ['name' => 'Lakas Mariveles', 'color' => '#2196F3'],
                    ['name' => 'Liberal Party Team', 'color' => '#FF9800'],
                ]
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
