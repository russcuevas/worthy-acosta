<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ElectionYear;
use App\Models\ElectoralRecord;
use App\Models\Barangay;

class ElectoralDataController extends Controller
{
    /**
     * Get barangay list mapping
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
     * Display Assistant's Electoral Data Management (Data Table & Forms, No Map)
     */
    public function index(Request $request)
    {
        $electionYears = ElectionYear::where('is_active', true)
            ->orderBy('year', 'asc')
            ->get();

        $years = $electionYears->isEmpty()
            ? ['2013', '2016', '2019', '2022', '2023', '2025']
            : $electionYears->pluck('year')->toArray();

        $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        $barangayNames = $this->getBarangayMap();

        // Build map of positions per year
        $yearPositionsMap = [];
        foreach ($electionYears as $ey) {
            $yearPositionsMap[$ey->year] = $ey->positions ?? ['Mayor', 'Vice Mayor', 'Governor', 'Congressman', 'Councilors'];
        }

        $defaultYear = $request->query('year', in_array('2025', $years) ? '2025' : (end($years) ?: '2025'));
        $defaultPositions = $yearPositionsMap[$defaultYear] ?? ['Mayor', 'Vice Mayor', 'Governor', 'Congressman', 'Councilors'];
        $defaultPosition = $request->query('position', $defaultPositions[0] ?? 'Mayor');

        // Query records for initial table load
        $records = ElectoralRecord::with('barangay')
            ->where('year', $defaultYear)
            ->when($defaultPosition !== 'all', function ($q) use ($defaultPosition) {
                return $q->where('position', $defaultPosition);
            })
            ->orderBy('barangay_id', 'asc')
            ->get();

        // Calculate summary metrics
        $totalRegistered = $records->sum('registered_voters');
        $totalActual = $records->sum('actual_votes');
        $overallTurnout = $totalRegistered > 0 ? round(($totalActual / $totalRegistered) * 100, 1) : 0;
        $encodedBarangaysCount = $records->where('registered_voters', '>', 0)->count();

        return view('assistant.electoral', [
            'electionYears' => $electionYears,
            'years' => $years,
            'yearPositionsMap' => $yearPositionsMap,
            'barangays' => $barangays,
            'barangayNames' => $barangayNames,
            'defaultYear' => $defaultYear,
            'defaultPosition' => $defaultPosition,
            'records' => $records,
            'totalRegistered' => $totalRegistered,
            'totalActual' => $totalActual,
            'overallTurnout' => $overallTurnout,
            'encodedBarangaysCount' => $encodedBarangaysCount,
        ]);
    }

    /**
     * Return JSON data for DataTables / dynamic filters
     */
    public function getData(Request $request)
    {
        $year = $request->query('year');
        $position = $request->query('position');
        $barangayId = $request->query('barangay_id');

        $query = ElectoralRecord::with('barangay');

        if ($year) {
            $query->where('year', $year);
        }

        if ($position && $position !== 'all') {
            $query->where('position', $position);
        }

        if ($barangayId) {
            $query->where('barangay_id', $barangayId);
        }

        $records = $query->orderBy('year', 'desc')
            ->orderBy('barangay_id', 'asc')
            ->get();

        $barangayNames = $this->getBarangayMap();

        $formatted = $records->map(function ($record) use ($barangayNames) {
            $bgyName = $record->barangay ? $record->barangay->name : ($barangayNames[$record->barangay_id] ?? $record->barangay_name);
            return [
                'id' => $record->id,
                'year' => $record->year,
                'position' => $record->position,
                'barangay_id' => $record->barangay_id,
                'barangay_name' => $bgyName,
                'registered_voters' => (int)$record->registered_voters,
                'actual_votes' => (int)$record->actual_votes,
                'turnout_percentage' => (float)$record->turnout_percentage,
                'winner_name' => $record->winner_name ?: 'None',
                'winner_color' => $record->winner_color ?: '#075998',
                'winner_votes' => (int)$record->winner_votes,
                'candidates' => is_array($record->candidates_data) ? $record->candidates_data : [],
            ];
        });

        // Compute summary metrics for filtered dataset
        $totalRegistered = $records->sum('registered_voters');
        $totalActual = $records->sum('actual_votes');
        $turnout = $totalRegistered > 0 ? round(($totalActual / $totalRegistered) * 100, 1) : 0;
        $encodedCount = $records->where('registered_voters', '>', 0)->count();

        return response()->json([
            'success' => true,
            'data' => $formatted,
            'metrics' => [
                'total_registered' => $totalRegistered,
                'total_actual' => $totalActual,
                'overall_turnout' => $turnout,
                'encoded_count' => $encodedCount,
            ]
        ]);
    }

    /**
     * Show single record data for editing
     */
    public function show($id)
    {
        $record = ElectoralRecord::with('barangay')->find($id);

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'record' => [
                'id' => $record->id,
                'year' => $record->year,
                'position' => $record->position,
                'barangay_id' => $record->barangay_id,
                'barangay_name' => $record->barangay ? $record->barangay->name : $record->barangay_name,
                'registered_voters' => $record->registered_voters,
                'actual_votes' => $record->actual_votes,
                'turnout_percentage' => $record->turnout_percentage,
                'winner_name' => $record->winner_name,
                'winner_color' => $record->winner_color,
                'winner_votes' => $record->winner_votes,
                'candidates' => is_array($record->candidates_data) ? $record->candidates_data : [],
            ]
        ]);
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
            return response()->json([
                'success' => false,
                'message' => 'Missing required fields (year, position, and barangay).'
            ], 422);
        }

        if ($regVoters <= 0) {
            $regVoters = 1;
        }

        $turnout = round(($actualVotes / $regVoters) * 100, 1);

        // Sanitize candidates
        $cleanCandidates = [];
        foreach ($candidates as $c) {
            $cName = trim($c['name'] ?? '');
            if (!empty($cName)) {
                $cleanCandidates[] = [
                    'name' => $cName,
                    'color' => $c['color'] ?? '#075998',
                    'votes' => (int)($c['votes'] ?? 0)
                ];
            }
        }

        // Determine winner
        $sorted = $cleanCandidates;
        usort($sorted, function ($a, $b) {
            return $b['votes'] - $a['votes'];
        });
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

        // Add position to year if not present
        $existingPositions = $ey->positions ?? [];
        if (!in_array($position, $existingPositions)) {
            $existingPositions[] = $position;
            $ey->update(['positions' => array_values(array_unique($existingPositions))]);
        }

        // Update or create record
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

        return response()->json([
            'success' => true,
            'message' => "Electoral data for Brgy. {$bgyName} ({$year} {$position}) saved successfully!",
            'record' => [
                'id' => $record->id,
                'year' => $record->year,
                'position' => $record->position,
                'barangay_id' => $record->barangay_id,
                'barangay_name' => $bgyName,
                'registered_voters' => $regVoters,
                'actual_votes' => $actualVotes,
                'turnout_percentage' => $turnout,
                'winner_name' => $winner['name'],
                'winner_color' => $winner['color'],
                'winner_votes' => $winner['votes'],
                'candidates' => $cleanCandidates,
            ]
        ]);
    }

    /**
     * Delete an electoral record
     */
    public function destroy($id)
    {
        $record = ElectoralRecord::find($id);

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $bgy = $record->barangay_name;
        $yr = $record->year;
        $pos = $record->position;

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => "Electoral record for Brgy. {$bgy} ({$yr} - {$pos}) deleted successfully."
        ]);
    }

    /**
     * Add New Election Year
     */
    public function addYear(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:10',
            'title' => 'nullable|string|max:255',
            'positions' => 'nullable|array',
        ]);

        $year = trim($request->input('year'));
        $title = $request->input('title') ?: "{$year} Elections";
        $positions = $request->input('positions', ['Mayor', 'Vice Mayor', 'Governor', 'Congressman', 'Councilors']);

        $existing = ElectionYear::where('year', $year)->first();
        if ($existing) {
            return response()->json(['success' => false, 'message' => "Election Year {$year} already exists in database."], 422);
        }

        $electionYear = ElectionYear::create([
            'year' => $year,
            'title' => $title,
            'positions' => $positions,
            'is_active' => true,
        ]);

        // Auto-initialize records for 18 barangays
        $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        foreach ($positions as $pos) {
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
                        'candidates_data' => [],
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Election Year {$year} created successfully with all barangay records initialized!",
            'year' => $year,
        ]);
    }

    /**
     * Update Election Year
     */
    public function updateYear(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:10',
            'title' => 'required|string|max:255',
            'positions' => 'required|array|min:1',
        ]);

        $year = trim($request->input('year'));
        $title = trim($request->input('title'));
        $positions = $request->input('positions');

        $electionYear = ElectionYear::where('year', $year)->first();
        if (!$electionYear) {
            return response()->json(['success' => false, 'message' => "Election Year {$year} not found."], 404);
        }

        $electionYear->update([
            'title' => $title,
            'positions' => $positions,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Election Year {$year} configuration updated successfully!",
        ]);
    }

    /**
     * Delete Election Year
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

        ElectoralRecord::where('year', $year)->delete();
        $electionYear->delete();

        return response()->json([
            'success' => true,
            'message' => "Election Year {$year} and its records deleted successfully!",
        ]);
    }
}
