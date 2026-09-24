<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ElectionYear;
use App\Models\ElectoralRecord;

class ElectoralDataSeeder extends Seeder
{
    public function run(): void
    {
        $barangayNames = [
            1 => "Alion",
            2 => "Batangas II",
            3 => "Cabcaben",
            4 => "Lucanin",
            5 => "Balon-Anito",
            6 => "Maligaya",
            7 => "Biaan",
            8 => "Malaya",
            9 => "Townsite",
            10 => "San Isidro",
            11 => "Mt. View",
            12 => "Alas-Asin",
            13 => "Camaya",
            14 => "Baseco Country",
            15 => "San Carlos",
            16 => "Poblacion",
            17 => "Sisiman",
            18 => "Ipag"
        ];

        $baseVoters = [
            1 => 5840,
            2 => 6920,
            3 => 12450,
            4 => 4610,
            5 => 8100,
            6 => 7430,
            7 => 3200,
            8 => 5350,
            9 => 6780,
            10 => 4910,
            11 => 7890,
            12 => 14200,
            13 => 6120,
            14 => 5410,
            15 => 4820,
            16 => 9850,
            17 => 5100,
            18 => 6730
        ];

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

        // First, check if JSON file exists in storage/app/electoral_data.json
        $jsonPath = storage_path('app/electoral_data.json');
        $cachedJson = file_exists($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : null;

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

                foreach ($barangayNames as $bgyId => $bgyName) {
                    if ($cachedJson && isset($cachedJson[$year][$position][$bgyId])) {
                        $rec = $cachedJson[$year][$position][$bgyId];
                        ElectoralRecord::updateOrCreate(
                            [
                                'year' => $year,
                                'position' => $position,
                                'barangay_id' => $bgyId,
                            ],
                            [
                                'barangay_name' => $rec['barangay_name'] ?? $bgyName,
                                'registered_voters' => $rec['registered_voters'] ?? 5000,
                                'actual_votes' => $rec['actual_votes'] ?? 4200,
                                'turnout_percentage' => $rec['turnout_percentage'] ?? 84.0,
                                'winner_name' => $rec['winner_name'] ?? 'Candidate',
                                'winner_color' => $rec['winner_color'] ?? '#075998',
                                'winner_votes' => $rec['winner_votes'] ?? 0,
                                'candidates_data' => $rec['candidates'] ?? [],
                            ]
                        );
                        continue;
                    }

                    // Fallback generated algorithm
                    $yearFactor = match($year) {
                        '2013' => 0.75,
                        '2016' => 0.82,
                        '2019' => 0.89,
                        '2022' => 0.95,
                        '2023' => 0.97,
                        '2025' => 1.05,
                        default => 1.0,
                    };

                    $regVoters = (int)round(($baseVoters[$bgyId] ?? 5000) * $yearFactor);
                    $turnoutRate = 0.80 + (($bgyId * 7 + (int)$year) % 15) / 100;
                    $actualVotes = (int)round($regVoters * $turnoutRate);

                    $candidateCount = count($candidatesTemplate);
                    $favoredIdx = ($bgyId + (int)substr($year, 2) + strlen($position)) % $candidateCount;

                    $shares = [];
                    $totalWeight = 0;
                    for ($c = 0; $c < $candidateCount; $c++) {
                        $weight = ($c === $favoredIdx) ? 1.8 + ($bgyId % 3) * 0.3 : 1.0 + (($c * 3) % 4) * 0.2;
                        $shares[$c] = $weight;
                        $totalWeight += $weight;
                    }

                    $currentAssigned = 0;
                    $candidatesData = [];
                    for ($c = 0; $c < $candidateCount; $c++) {
                        $cVotes = ($c === $candidateCount - 1)
                            ? ($actualVotes - $currentAssigned)
                            : (int)round($actualVotes * ($shares[$c] / $totalWeight));

                        $currentAssigned += $cVotes;

                        $candidatesData[] = [
                            'name' => $candidatesTemplate[$c]['name'],
                            'color' => $candidatesTemplate[$c]['color'],
                            'votes' => max(0, $cVotes)
                        ];
                    }

                    $sorted = $candidatesData;
                    usort($sorted, function($a, $b) { return $b['votes'] - $a['votes']; });
                    $winner = $sorted[0] ?? ['name' => 'None', 'color' => '#075998', 'votes' => 0];

                    ElectoralRecord::updateOrCreate(
                        [
                            'year' => $year,
                            'position' => $position,
                            'barangay_id' => $bgyId,
                        ],
                        [
                            'barangay_name' => $bgyName,
                            'registered_voters' => $regVoters,
                            'actual_votes' => $actualVotes,
                            'turnout_percentage' => round(($actualVotes / $regVoters) * 100, 1),
                            'winner_name' => $winner['name'],
                            'winner_color' => $winner['color'],
                            'winner_votes' => $winner['votes'],
                            'candidates_data' => $candidatesData,
                        ]
                    );
                }
            }
        }
    }
}
