<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ElectionYear;
use App\Models\ElectoralRecord;
use App\Models\Barangay;

class ElectoralDataController extends Controller
{
    /**
     * Get barangay name map from database (fallback to defaults if table empty)
     */
    private function getBarangayMap(): array
    {
        $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        if ($barangays->isEmpty()) {
            return [
                1 => "Alion", 2 => "Batangas II", 3 => "Cabcaben", 4 => "Lucanin",
                5 => "Balon-Anito", 6 => "Maligaya", 7 => "Biaan", 8 => "Malaya",
                9 => "Townsite", 10 => "San Isidro", 11 => "Mt. View", 12 => "Alas-Asin",
                13 => "Camaya", 14 => "Baseco Country", 15 => "San Carlos", 16 => "Poblacion",
                17 => "Sisiman", 18 => "Ipag"
            ];
        }
        return $barangays->pluck('name', 'id')->toArray();
    }

    /**
     * Get full formatted dataset from MySQL database
     */
    private function getDatasetFromDatabase(?string $filterYear = null, ?string $filterPosition = null): array
    {
        $query = ElectoralRecord::with('barangay');

        if ($filterYear) {
            $query->where('year', $filterYear);
        }

        if ($filterPosition) {
            $query->where('position', $filterPosition);
        }

        $records = $query->get();
        $dataset = [];

        foreach ($records as $record) {
            $yr = (string)$record->year;
            $pos = (string)$record->position;
            $bgyId = (int)$record->barangay_id;
            $bgyName = $record->barangay ? $record->barangay->name : $record->barangay_name;

            if (!isset($dataset[$yr])) {
                $dataset[$yr] = [];
            }
            if (!isset($dataset[$yr][$pos])) {
                $dataset[$yr][$pos] = [];
            }

            $dataset[$yr][$pos][$bgyId] = [
                'barangay_id' => $bgyId,
                'barangay_name' => $bgyName,
                'registered_voters' => (int)$record->registered_voters,
                'actual_votes' => (int)$record->actual_votes,
                'turnout_percentage' => (float)$record->turnout_percentage,
                'winner_name' => $record->winner_name ?? 'None',
                'winner_color' => $record->winner_color ?? '#075998',
                'winner_votes' => (int)$record->winner_votes,
                'candidates' => is_array($record->candidates_data) ? $record->candidates_data : []
            ];
        }

        return $dataset;
    }

    /**
     * Display the main electoral map view for Mariveles.
     */
    public function index()
    {
        $electionYears = ElectionYear::where('is_active', true)
            ->orderBy('year', 'asc')
            ->get();

        // If no election years in DB, seed or fallback
        if ($electionYears->isEmpty()) {
            $years = ['2013', '2016', '2019', '2022', '2023', '2025'];
        } else {
            $years = $electionYears->pluck('year')->toArray();
        }

        $dataset = $this->getDatasetFromDatabase();
        $barangayNames = $this->getBarangayMap();
        $barangays = Barangay::where('is_active', true)->orderBy('id')->get();

        // Map year positions
        $yearPositionsMap = [];
        foreach ($electionYears as $ey) {
            $yearPositionsMap[$ey->year] = $ey->positions ?? ['Mayor', 'Vice Mayor', 'Governor', 'Congressman', 'Councilors'];
        }

        // Default to latest year
        $defaultYear = in_array('2025', $years) ? '2025' : (end($years) ?: '2025');
        $defaultPositions = $yearPositionsMap[$defaultYear] ?? ['Mayor', 'Vice Mayor', 'Governor', 'Congressman', 'Councilors'];
        $defaultPosition = $defaultPositions[0] ?? 'Mayor';

        return view('admin.electoral', [
            'electionYears' => $electionYears,
            'years' => $years,
            'yearPositionsMap' => $yearPositionsMap,
            'defaultYear' => $defaultYear,
            'defaultPosition' => $defaultPosition,
            'initialDataset' => $dataset,
            'barangayNames' => $barangayNames,
            'barangays' => $barangays,
        ]);
    }

    /**
     * Return list of available election years
     */
    public function getYears()
    {
        $years = ElectionYear::where('is_active', true)
            ->orderBy('year', 'asc')
            ->get(['year', 'title', 'positions', 'is_active']);

        return response()->json($years);
    }

    /**
     * Return list of all Barangays from Database
     */
    public function getBarangayList()
    {
        $barangays = Barangay::where('is_active', true)
            ->orderBy('id', 'asc')
            ->get(['id', 'name', 'slug', 'pin_x', 'pin_y']);

        return response()->json($barangays);
    }

    /**
     * Add a new Election Year (supporting future years e.g. 2028, 2031, 2034)
     */
    public function addYear(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:10',
            'title' => 'nullable|string|max:150',
            'positions' => 'nullable|array',
            'positions.*' => 'string|max:100'
        ]);

        $year = trim($request->input('year'));
        $title = trim($request->input('title') ?? "{$year} General Elections");
        $positions = $request->input('positions', []);

        if (empty($positions)) {
            $positions = ['Mayor', 'Vice Mayor', 'Governor', 'Congressman', 'Councilors'];
        }

        // Clean positions list
        $positions = array_values(array_unique(array_filter(array_map('trim', $positions))));

        // Check if year already exists
        $existing = ElectionYear::where('year', $year)->first();
        if ($existing) {
            $existing->update([
                'title' => $title,
                'positions' => $positions,
                'is_active' => true
            ]);
            $electionYear = $existing;
        } else {
            $electionYear = ElectionYear::create([
                'year' => $year,
                'title' => $title,
                'positions' => $positions,
                'is_active' => true
            ]);
        }

        // Default candidate presets for newly created future election positions
        $defaultCandidatesPreset = [
            'Mayor' => [
                ['name' => 'Worthy Acosta', 'color' => '#075998', 'votes' => 0],
                ['name' => 'Opposition Candidate', 'color' => '#E53935', 'votes' => 0]
            ],
            'Vice Mayor' => [
                ['name' => 'Vice Mayor Candidate A', 'color' => '#075998', 'votes' => 0],
                ['name' => 'Vice Mayor Candidate B', 'color' => '#E53935', 'votes' => 0]
            ],
            'Governor' => [
                ['name' => 'Gubernatorial Candidate A', 'color' => '#2196F3', 'votes' => 0],
                ['name' => 'Gubernatorial Candidate B', 'color' => '#8E24AA', 'votes' => 0]
            ],
            'Congressman' => [
                ['name' => 'Congressional Candidate A', 'color' => '#2196F3', 'votes' => 0],
                ['name' => 'Congressional Candidate B', 'color' => '#E53935', 'votes' => 0]
            ],
            'Councilors' => [
                ['name' => 'Councilor Candidate 1', 'color' => '#075998', 'votes' => 0],
                ['name' => 'Councilor Candidate 2', 'color' => '#E53935', 'votes' => 0],
                ['name' => 'Councilor Candidate 3', 'color' => '#2E7D32', 'votes' => 0]
            ]
        ];

        $barangays = Barangay::where('is_active', true)->orderBy('id')->get();

        // Initialize barangay records in the database for each position if they don't already exist
        foreach ($positions as $position) {
            $cPreset = $defaultCandidatesPreset[$position] ?? [
                ['name' => 'Candidate 1', 'color' => '#075998', 'votes' => 0],
                ['name' => 'Candidate 2', 'color' => '#E53935', 'votes' => 0]
            ];

            if ($barangays->isNotEmpty()) {
                foreach ($barangays as $bgy) {
                    ElectoralRecord::firstOrCreate(
                        [
                            'year' => $year,
                            'position' => $position,
                            'barangay_id' => $bgy->id,
                        ],
                        [
                            'barangay_name' => $bgy->name,
                            'registered_voters' => 0,
                            'actual_votes' => 0,
                            'turnout_percentage' => 0.00,
                            'winner_name' => $cPreset[0]['name'] ?? 'Pending',
                            'winner_color' => $cPreset[0]['color'] ?? '#075998',
                            'winner_votes' => 0,
                            'candidates_data' => $cPreset,
                        ]
                    );
                }
            } else {
                $bgyMap = $this->getBarangayMap();
                foreach ($bgyMap as $bgyId => $bgyName) {
                    ElectoralRecord::firstOrCreate(
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
                            'winner_name' => $cPreset[0]['name'] ?? 'Pending',
                            'winner_color' => $cPreset[0]['color'] ?? '#075998',
                            'winner_votes' => 0,
                            'candidates_data' => $cPreset,
                        ]
                    );
                }
            }
        }

        // Return refreshed dataset for the newly added year
        $newYearDataset = $this->getDatasetFromDatabase($year);

        return response()->json([
            'success' => true,
            'message' => "Election Year {$year} successfully added to database!",
            'year' => $year,
            'title' => $title,
            'positions' => $positions,
            'dataset' => $newYearDataset
        ]);
    }

    /**
     * Update an Election Year and its included Electoral Positions
     */
    public function updateYear(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:10',
            'title' => 'nullable|string|max:150',
            'positions' => 'required|array|min:1',
            'positions.*' => 'string|max:100'
        ]);

        $year = trim($request->input('year'));
        $title = trim($request->input('title') ?? "{$year} Elections");
        $positions = $request->input('positions', []);

        // Clean and unique positions
        $positions = array_values(array_unique(array_filter(array_map('trim', $positions))));

        $electionYear = ElectionYear::where('year', $year)->first();
        if (!$electionYear) {
            return response()->json(['success' => false, 'message' => "Election Year {$year} not found."], 404);
        }

        $oldPositions = $electionYear->positions ?? [];
        $electionYear->update([
            'title' => $title,
            'positions' => $positions,
        ]);

        // Find newly added positions
        $addedPositions = array_diff($positions, $oldPositions);
        // Find removed positions
        $removedPositions = array_diff($oldPositions, $positions);

        // Delete records for removed positions if any
        if (!empty($removedPositions)) {
            ElectoralRecord::where('year', $year)->whereIn('position', $removedPositions)->delete();
        }

        // Initialize barangay records for newly added positions
        if (!empty($addedPositions)) {
            $barangays = Barangay::where('is_active', true)->orderBy('id')->get();

            foreach ($addedPositions as $pos) {
                foreach ($barangays as $bgy) {
                    ElectoralRecord::firstOrCreate(
                        [
                            'year' => $year,
                            'position' => $pos,
                            'barangay_id' => $bgy->id,
                        ],
                        [
                            'barangay_name' => $bgy->name,
                            'registered_voters' => 0,
                            'actual_votes' => 0,
                            'turnout_percentage' => 0.00,
                            'winner_name' => 'None',
                            'winner_color' => '#64748B',
                            'winner_votes' => 0,
                            'candidates_data' => [
                                ['name' => 'Candidate 1', 'color' => '#075998', 'votes' => 0],
                                ['name' => 'Candidate 2', 'color' => '#E53935', 'votes' => 0],
                            ],
                        ]
                    );
                }
            }
        }

        $allYears = ElectionYear::where('is_active', true)->orderBy('year', 'asc')->get();
        $updatedDataset = $this->getDatasetFromDatabase($year);

        return response()->json([
            'success' => true,
            'message' => "Election Year {$year} updated successfully!",
            'year' => $year,
            'title' => $title,
            'positions' => $positions,
            'years' => $allYears,
            'dataset' => $updatedDataset
        ]);
    }

    /**
     * Delete an Election Year and all its associated records
     */
    public function deleteYear(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:10'
        ]);

        $year = trim($request->input('year'));

        $electionYear = ElectionYear::where('year', $year)->first();
        if (!$electionYear) {
            return response()->json(['success' => false, 'message' => "Election Year {$year} not found."], 404);
        }

        // Delete electoral records under this year
        ElectoralRecord::where('year', $year)->delete();

        // Delete the election year
        $electionYear->delete();

        $remainingYears = ElectionYear::where('is_active', true)->orderBy('year', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => "Election Year {$year} deleted successfully!",
            'deleted_year' => $year,
            'remaining_years' => $remainingYears
        ]);
    }

    /**
     * Return entire or filtered election dataset directly from Database
     */
    public function getData(Request $request)
    {
        $year = $request->query('year');
        $position = $request->query('position');

        $data = $this->getDatasetFromDatabase($year, $position);

        if ($year && $position) {
            return response()->json($data[$year][$position] ?? []);
        }

        if ($year) {
            return response()->json($data[$year] ?? []);
        }

        return response()->json($data);
    }

    /**
     * Save/Update candidate data, voter stats, and recalculate winner directly in Database
     */
    public function saveData(Request $request)
    {
        $year = (string)$request->input('year');
        $position = (string)$request->input('position');
        $barangayId = (int)$request->input('barangay_id');
        $regVoters = (int)$request->input('registered_voters', 0);
        $actualVotes = (int)$request->input('actual_votes', 0);
        $candidates = $request->input('candidates', []);

        if (!$year || !$position || !$barangayId) {
            return response()->json(['success' => false, 'message' => 'Missing required fields (year, position, barangay_id).'], 422);
        }

        if ($regVoters <= 0) {
            $regVoters = 1;
        }

        $turnout = round(($actualVotes / $regVoters) * 100, 1);

        // Sanitize candidates
        $cleanCandidates = [];
        foreach ($candidates as $c) {
            $cleanCandidates[] = [
                'name' => trim($c['name'] ?? 'Unnamed Candidate'),
                'color' => $c['color'] ?? '#075998',
                'votes' => (int)($c['votes'] ?? 0)
            ];
        }

        // Determine winner
        $sorted = $cleanCandidates;
        usort($sorted, function($a, $b) { return $b['votes'] - $a['votes']; });
        $winner = !empty($sorted) ? $sorted[0] : ['name' => 'None', 'color' => '#64748B', 'votes' => 0];

        $barangayModel = Barangay::find($barangayId);
        $bgyName = $barangayModel ? $barangayModel->name : ($this->getBarangayMap()[$barangayId] ?? ("Barangay " . $barangayId));

        // Ensure election year exists
        $ey = ElectionYear::firstOrCreate(
            ['year' => $year],
            [
                'title' => "{$year} Elections",
                'positions' => [$position],
                'is_active' => true
            ]
        );

        // Add position to year's position list if not present
        $existingPositions = $ey->positions ?? [];
        if (!in_array($position, $existingPositions)) {
            $existingPositions[] = $position;
            $ey->update(['positions' => array_values(array_unique($existingPositions))]);
        }

        // Update or create Electoral Record in database
        $record = ElectoralRecord::updateOrCreate(
            [
                'year' => $year,
                'position' => $position,
                'barangay_id' => $barangayId,
            ],
            [
                'barangay_name' => $bgyName,
                'registered_voters' => $regVoters,
                'actual_votes' => $actualVotes,
                'turnout_percentage' => $turnout,
                'winner_name' => $winner['name'],
                'winner_color' => $winner['color'],
                'winner_votes' => $winner['votes'],
                'candidates_data' => $cleanCandidates,
            ]
        );

        $formattedRecord = [
            'barangay_id' => $barangayId,
            'barangay_name' => $bgyName,
            'registered_voters' => $regVoters,
            'actual_votes' => $actualVotes,
            'turnout_percentage' => $turnout,
            'winner_name' => $winner['name'],
            'winner_color' => $winner['color'],
            'winner_votes' => $winner['votes'],
            'candidates' => $cleanCandidates
        ];

        return response()->json([
            'success' => true,
            'message' => "Electoral data for Barangay {$bgyName} ({$year} - {$position}) updated successfully in database!",
            'updated_record' => $formattedRecord
        ]);
    }

    /**
     * Return JSON data for the 18 Mariveles Barangays (GeoJSON fallback)
     */
    public function getBarangays()
    {
        $geojsonPath = public_path('geojson/mariveles.json');
        if (file_exists($geojsonPath)) {
            $data = json_decode(file_get_contents($geojsonPath), true);
            return response()->json($data);
        }

        return response()->json(['error' => 'GeoJSON not found'], 404);
    }

    /**
     * Save the edited GeoJSON data directly
     */
    public function saveGeojson(Request $request)
    {
        $geojsonContent = $request->input('geojson');
        if (!$geojsonContent) {
            return response()->json(['success' => false, 'message' => 'No GeoJSON payload provided.'], 400);
        }

        $decoded = is_array($geojsonContent) ? $geojsonContent : json_decode($geojsonContent, true);
        if (!$decoded) {
            return response()->json(['success' => false, 'message' => 'Invalid JSON formatting.'], 400);
        }

        $geojsonPath = public_path('geojson/mariveles.json');
        file_put_contents($geojsonPath, json_encode($decoded, JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'message' => 'Mariveles barangay boundaries saved successfully!'
        ]);
    }
}
