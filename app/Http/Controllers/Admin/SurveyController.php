<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barangay;
use App\Models\SurveyPeriod;
use App\Models\SurveyRecord;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    /**
     * Display the main Survey module page.
     */
    public function index(Request $request)
    {
        $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        if ($barangays->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'BarangaySeeder']);
            $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        }

        $periods = SurveyPeriod::where('is_active', true)->orderBy('start_date', 'desc')->get();
        if ($periods->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'SurveySeeder']);
            $periods = SurveyPeriod::where('is_active', true)->orderBy('start_date', 'desc')->get();
        }

        // Distinct candidate names and colors
        $candidates = SurveyRecord::select('candidate_name', 'candidate_color')
            ->distinct()
            ->orderBy('candidate_name')
            ->get();

        $defaultColors = [
            '#075998', // Primary Blue
            '#16A34A', // Emerald Green
            '#EAB308', // Amber Yellow
            '#DC2626', // Crimson Red
            '#8B5CF6', // Violet Purple
            '#EA580C', // Orange
            '#06B6D4', // Cyan
            '#94A3B8', // Slate Grey
        ];

        return view('admin.survey.index', compact('barangays', 'periods', 'candidates', 'defaultColors'));
    }

    /**
     * Get consolidated survey data, map metrics, headline results, and filtered records via AJAX.
     */
    public function getData(Request $request)
    {
        $periodId = $request->input('period_id');
        $barangayId = $request->input('barangay_id');
        $candidateFilter = $request->input('candidate');
        $search = $request->input('search');

        // Determine active period
        if (empty($periodId) || $periodId === 'latest') {
            $activePeriod = SurveyPeriod::where('is_active', true)->orderBy('start_date', 'desc')->first();
        } else {
            $activePeriod = SurveyPeriod::find($periodId);
        }

        if (!$activePeriod) {
            return response()->json([
                'success' => false,
                'message' => 'No active survey periods found.'
            ], 404);
        }

        // 1. Get all records for the selected period
        $periodRecords = SurveyRecord::with('barangay')
            ->where('survey_period_id', $activePeriod->id)
            ->get();

        // All active barangays
        $allBarangays = Barangay::where('is_active', true)->orderBy('id')->get();

        // 1. All records for the active period
        $allPeriodRecords = SurveyRecord::with('barangay')
            ->where('survey_period_id', $activePeriod->id)
            ->get();

        // 2. Check filters
        $isSpecificBarangay = (!empty($barangayId) && $barangayId !== 'all');
        $isSpecificCandidate = (!empty($candidateFilter) && $candidateFilter !== 'all');
        $selectedBarangayModel = $isSpecificBarangay ? Barangay::find($barangayId) : null;

        // Scoped records based on selected Barangay
        $scopedRecords = $isSpecificBarangay 
            ? $allPeriodRecords->where('barangay_id', (int) $barangayId) 
            : $allPeriodRecords;

        // 3. Compute Headline Results
        $headlineGrouped = $scopedRecords->groupBy('candidate_name');
        $headlineResults = [];

        foreach ($headlineGrouped as $cName => $records) {
            $avgRating = round($records->avg('rating'), 1);
            $color = $records->first()->candidate_color ?? '#075998';

            // Find peak across all records for this candidate
            $candAllRecords = $allPeriodRecords->where('candidate_name', $cName);
            $highestRecord = $candAllRecords->sortByDesc('rating')->first();
            $lowestRecord = $candAllRecords->sortBy('rating')->first();

            // Count how many barangays this candidate leads across all 18
            $ledCount = 0;
            foreach ($allBarangays as $b) {
                $bgyRecords = $allPeriodRecords->where('barangay_id', $b->id)->sortByDesc('rating');
                if ($bgyRecords->first() && $bgyRecords->first()->candidate_name === $cName) {
                    $ledCount++;
                }
            }

            $headlineResults[] = [
                'candidate_name' => $cName,
                'color' => $color,
                'average_rating' => $avgRating,
                'highest_barangay' => $highestRecord ? $highestRecord->barangay->name . ' (' . $highestRecord->rating . '%)' : '—',
                'lowest_barangay' => $lowestRecord ? $lowestRecord->barangay->name . ' (' . $lowestRecord->rating . '%)' : '—',
                'barangays_led_count' => $ledCount,
                'is_focused' => ($isSpecificCandidate && $candidateFilter === $cName),
            ];
        }

        // Sort candidates by average rating desc to determine true rank and margins
        usort($headlineResults, function ($a, $b) {
            return $b['average_rating'] <=> $a['average_rating'];
        });

        $trueOverallLeader = $headlineResults[0] ?? null;
        $trueRunnerUp = $headlineResults[1] ?? null;

        // 4. Map Data & Leading / Filtered Candidate per Barangay
        $barangayMapData = [];
        $totalSampleSum = 0;

        foreach ($allBarangays as $bgy) {
            $bgyRecords = $allPeriodRecords->where('barangay_id', $bgy->id)->sortByDesc('rating');
            $leader = $bgyRecords->first();
            $runnerUp = $bgyRecords->skip(1)->first();

            $bgySample = $bgyRecords->first()->sample_size ?? 100;
            $totalSampleSum += $bgySample;

            $candidatesList = [];
            $rank = 1;
            $specificCandRating = null;
            $specificCandColor = null;
            $specificCandRank = null;

            foreach ($bgyRecords as $rec) {
                $candidatesList[] = [
                    'candidate_name' => $rec->candidate_name,
                    'color' => $rec->candidate_color,
                    'rating' => (float) $rec->rating,
                    'sample_size' => $rec->sample_size,
                    'notes' => $rec->notes,
                    'rank' => $rank,
                ];

                if ($isSpecificCandidate && $rec->candidate_name === $candidateFilter) {
                    $specificCandRating = (float) $rec->rating;
                    $specificCandColor = $rec->candidate_color;
                    $specificCandRank = $rank;
                }

                $rank++;
            }

            $leadingCandidate = $leader ? $leader->candidate_name : 'No Data';
            $leadingColor = $leader ? $leader->candidate_color : '#64748B';
            $leadingRating = $leader ? (float) $leader->rating : 0.0;
            $margin = ($leader && $runnerUp) ? round($leader->rating - $runnerUp->rating, 1) : 0.0;

            // Map pin dynamic display for candidate filter
            $pinColor = $leadingColor;
            $pinLabelRating = $leadingRating;
            $isCandidateLeading = ($leader && $leader->candidate_name === $candidateFilter);

            if ($isSpecificCandidate) {
                $pinColor = $specificCandColor ?: '#64748B';
                $pinLabelRating = $specificCandRating !== null ? $specificCandRating : 0.0;
            }

            $barangayMapData[$bgy->id] = [
                'id' => $bgy->id,
                'name' => $bgy->name,
                'pin_x' => $bgy->pin_x,
                'pin_y' => $bgy->pin_y,
                'leading_candidate' => $leadingCandidate,
                'leading_color' => $leadingColor,
                'leading_rating' => $leadingRating,
                'runner_up' => $runnerUp ? $runnerUp->candidate_name : null,
                'margin' => $margin,
                'sample_size' => $bgySample,
                'candidates' => $candidatesList,
                'candidate_rating' => $specificCandRating,
                'candidate_color' => $specificCandColor,
                'candidate_rank' => $specificCandRank,
                'candidate_is_leading' => $isCandidateLeading,
                'pin_display_color' => $pinColor,
                'pin_display_rating' => $pinLabelRating,
                'is_selected_barangay' => ($isSpecificBarangay && (int)$barangayId === $bgy->id),
            ];
        }

        // 5. Compute Top KPI Cards dynamically based on active filters
        if ($isSpecificCandidate) {
            // Metrics focused on the selected Candidate
            $candObj = collect($headlineResults)->firstWhere('candidate_name', $candidateFilter);
            $candColor = $candObj['color'] ?? '#075998';
            $candAvg = $candObj['average_rating'] ?? 0.0;
            $candLed = $candObj['barangays_led_count'] ?? 0;
            $candPeak = $candObj['highest_barangay'] ?? 'None';

            // Calculate true position in headline results
            $candRank = 1;
            foreach ($headlineResults as $idx => $hr) {
                if ($hr['candidate_name'] === $candidateFilter) {
                    $candRank = $idx + 1;
                    break;
                }
            }

            $marginText = '';
            if ($trueOverallLeader && $trueOverallLeader['candidate_name'] === $candidateFilter) {
                $m = $trueRunnerUp ? round($candAvg - $trueRunnerUp['average_rating'], 1) : 0.0;
                $marginText = "+{$m}% lead over #2 ({$trueRunnerUp['candidate_name']})";
            } else {
                $leadDiff = $trueOverallLeader ? round($trueOverallLeader['average_rating'] - $candAvg, 1) : 0.0;
                $marginText = "-{$leadDiff}% behind leader ({$trueOverallLeader['candidate_name']})";
            }

            $kpis = [
                'period_name' => $activePeriod->name,
                'date_range' => $activePeriod->start_date->format('M d') . ' - ' . $activePeriod->end_date->format('M d, Y'),
                'total_sample_size' => $isSpecificBarangay ? ($barangayMapData[$barangayId]['sample_size'] ?? 100) : ($activePeriod->sample_size ?: $totalSampleSum),
                'leader_name' => "{$candidateFilter} (Rank #{$candRank} of " . count($headlineResults) . ")",
                'leader_color' => $candColor,
                'leader_rating' => $candAvg,
                'leader_margin' => $marginText,
                'barangays_led' => $isSpecificBarangay ? "Barangay {$selectedBarangayModel->name}" : "{$candLed} / {$allBarangays->count()} Barangays Led",
                'total_barangays' => $allBarangays->count(),
                'stronghold' => $candPeak,
                'methodology' => $activePeriod->methodology ?: 'Multi-stage random sampling across 18 barangays',
                'notes' => $activePeriod->notes,
                'filter_context' => "Candidate: {$candidateFilter}" . ($isSpecificBarangay ? " in {$selectedBarangayModel->name}" : ""),
            ];
        } elseif ($isSpecificBarangay) {
            // Metrics focused on the selected Barangay
            $bgyInfo = $barangayMapData[$barangayId] ?? null;
            $bgyLeader = $bgyInfo ? $bgyInfo['leading_candidate'] : 'None';
            $bgyLeaderColor = $bgyInfo ? $bgyInfo['leading_color'] : '#075998';
            $bgyLeaderRating = $bgyInfo ? $bgyInfo['leading_rating'] : 0.0;
            $bgyMargin = $bgyInfo ? $bgyInfo['margin'] : 0.0;
            $bgySample = $bgyInfo ? $bgyInfo['sample_size'] : 100;

            $kpis = [
                'period_name' => $activePeriod->name,
                'date_range' => $activePeriod->start_date->format('M d') . ' - ' . $activePeriod->end_date->format('M d, Y'),
                'total_sample_size' => $bgySample,
                'leader_name' => "{$bgyLeader} (Leader in {$selectedBarangayModel->name})",
                'leader_color' => $bgyLeaderColor,
                'leader_rating' => $bgyLeaderRating,
                'leader_margin' => "+{$bgyMargin}% lead margin",
                'barangays_led' => "Brgy. {$selectedBarangayModel->name}",
                'total_barangays' => 1,
                'stronghold' => "{$selectedBarangayModel->name}: {$bgyLeader} ({$bgyLeaderRating}%)",
                'methodology' => $activePeriod->methodology ?: 'Standard cluster sampling in this barangay',
                'notes' => $activePeriod->notes,
                'filter_context' => "Barangay: {$selectedBarangayModel->name}",
            ];
        } else {
            // Standard Municipal-wide aggregate
            $overallLeader = $trueOverallLeader ?? [
                'candidate_name' => 'None',
                'color' => '#075998',
                'average_rating' => 0.0,
                'barangays_led_count' => 0,
            ];
            $overallMargin = $trueRunnerUp ? round($overallLeader['average_rating'] - $trueRunnerUp['average_rating'], 1) : 0.0;

            $topRecord = $allPeriodRecords->sortByDesc('rating')->first();
            $strongholdText = $topRecord ? $topRecord->barangay->name . ' (' . $topRecord->candidate_name . ' ' . $topRecord->rating . '%)' : 'None';

            $kpis = [
                'period_name' => $activePeriod->name,
                'date_range' => $activePeriod->start_date->format('M d') . ' - ' . $activePeriod->end_date->format('M d, Y'),
                'total_sample_size' => $activePeriod->sample_size ?: $totalSampleSum,
                'leader_name' => $overallLeader['candidate_name'],
                'leader_color' => $overallLeader['color'],
                'leader_rating' => $overallLeader['average_rating'],
                'leader_margin' => "+{$overallMargin}% lead margin",
                'barangays_led' => "{$overallLeader['barangays_led_count']} / {$allBarangays->count()}",
                'total_barangays' => $allBarangays->count(),
                'stronghold' => $strongholdText,
                'methodology' => $activePeriod->methodology ?: 'Multi-stage random sampling across 18 barangays',
                'notes' => $activePeriod->notes,
                'filter_context' => "Municipal Aggregate &bull; All 18 Barangays",
            ];
        }

        // 6. Historical Comparison Trend (Spec #7: June -> July -> August)
        $allPeriods = SurveyPeriod::where('is_active', true)->orderBy('start_date', 'asc')->get();
        $historicalTrend = [];

        foreach ($allPeriods as $p) {
            $pQuery = SurveyRecord::where('survey_period_id', $p->id);
            if ($isSpecificBarangay) {
                $pQuery->where('barangay_id', (int)$barangayId);
            }
            if ($isSpecificCandidate) {
                $pQuery->where('candidate_name', $candidateFilter);
            }
            $pRecs = $pQuery->get();

            $candidatesSummary = [];
            foreach ($pRecs->groupBy('candidate_name') as $cName => $rList) {
                $candidatesSummary[] = [
                    'candidate' => $cName,
                    'color' => $rList->first()->candidate_color ?? '#075998',
                    'average' => round($rList->avg('rating'), 1),
                ];
            }
            $historicalTrend[] = [
                'period_id' => $p->id,
                'period_name' => $p->name,
                'start_date' => $p->start_date->format('Y-m-d'),
                'candidates' => $candidatesSummary,
            ];
        }

        // 7. Filtered Table Records
        $tableQuery = SurveyRecord::with(['barangay', 'period'])
            ->where('survey_period_id', $activePeriod->id);

        if ($isSpecificBarangay) {
            $tableQuery->where('barangay_id', (int)$barangayId);
        }

        if ($isSpecificCandidate) {
            $tableQuery->where('candidate_name', $candidateFilter);
        }

        if (!empty($search)) {
            $tableQuery->where(function ($q) use ($search) {
                $q->where('candidate_name', 'like', "%{$search}%")
                  ->orWhere('methodology', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('barangay', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $records = $tableQuery->orderBy('barangay_id')->orderByDesc('rating')->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'period_name' => $r->period->name,
                'barangay_id' => $r->barangay_id,
                'barangay_name' => $r->barangay->name,
                'candidate_name' => $r->candidate_name,
                'candidate_color' => $r->candidate_color,
                'rating' => (float) $r->rating,
                'sample_size' => $r->sample_size ?: 100,
                'methodology' => $r->methodology ?: 'Standard cluster sampling',
                'notes' => $r->notes ?: '—',
                'created_at' => $r->created_at ? $r->created_at->format('M d, Y') : '—',
            ];
        });

        return response()->json([
            'success' => true,
            'active_period' => [
                'id' => $activePeriod->id,
                'name' => $activePeriod->name,
                'start_date' => $activePeriod->start_date->format('Y-m-d'),
                'end_date' => $activePeriod->end_date->format('Y-m-d'),
                'sample_size' => $activePeriod->sample_size,
                'methodology' => $activePeriod->methodology,
                'notes' => $activePeriod->notes,
            ],
            'kpis' => $kpis,
            'headline_results' => $headlineResults,
            'barangays' => $barangayMapData,
            'historical_trend' => $historicalTrend,
            'records' => $records,
            'total_records' => $records->count(),
        ]);
    }

    /**
     * Store a newly created survey record or survey period.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'survey_period_id' => 'required|exists:survey_periods,id',
            'barangay_id' => 'required',
            'candidate_name' => 'required|string|max:150',
            'candidate_color' => 'required|string|max:20',
            'rating' => 'required|numeric|min:0|max:100',
            'sample_size' => 'nullable|integer|min:1',
            'methodology' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $createdCount = 0;

        // Support for "All Barangays" batch encoding
        if ($validated['barangay_id'] === 'all_barangays') {
            $allBarangays = Barangay::where('is_active', true)->get();
            foreach ($allBarangays as $bgy) {
                SurveyRecord::updateOrCreate(
                    [
                        'survey_period_id' => $validated['survey_period_id'],
                        'barangay_id' => $bgy->id,
                        'candidate_name' => $validated['candidate_name'],
                    ],
                    [
                        'candidate_color' => $validated['candidate_color'],
                        'rating' => $validated['rating'],
                        'sample_size' => $validated['sample_size'] ?: 100,
                        'methodology' => $validated['methodology'],
                        'notes' => $validated['notes'],
                    ]
                );
                $createdCount++;
            }
        } else {
            SurveyRecord::updateOrCreate(
                [
                    'survey_period_id' => $validated['survey_period_id'],
                    'barangay_id' => $validated['barangay_id'],
                    'candidate_name' => $validated['candidate_name'],
                ],
                [
                    'candidate_color' => $validated['candidate_color'],
                    'rating' => $validated['rating'],
                    'sample_size' => $validated['sample_size'] ?: 100,
                    'methodology' => $validated['methodology'],
                    'notes' => $validated['notes'],
                ]
            );
            $createdCount = 1;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully recorded {$createdCount} survey result(s).",
        ]);
    }

    /**
     * Store a new Survey Period (Date Coverage).
     */
    public function storePeriod(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'sample_size' => 'nullable|integer|min:1',
            'methodology' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $period = SurveyPeriod::create($validated);

        return response()->json([
            'success' => true,
            'period' => $period,
            'message' => "Survey period '{$period->name}' created successfully.",
        ]);
    }

    /**
     * Get single record detail.
     */
    public function show($id)
    {
        $record = SurveyRecord::with(['barangay', 'period'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'record' => [
                'id' => $record->id,
                'survey_period_id' => $record->survey_period_id,
                'period_name' => $record->period->name,
                'barangay_id' => $record->barangay_id,
                'barangay_name' => $record->barangay->name,
                'candidate_name' => $record->candidate_name,
                'candidate_color' => $record->candidate_color,
                'rating' => $record->rating,
                'sample_size' => $record->sample_size,
                'methodology' => $record->methodology,
                'notes' => $record->notes,
                'created_at' => $record->created_at ? $record->created_at->format('M d, Y h:i A') : '—',
                'updated_at' => $record->updated_at ? $record->updated_at->format('M d, Y h:i A') : '—',
            ]
        ]);
    }

    /**
     * Update an existing survey record.
     */
    public function update(Request $request, $id)
    {
        $record = SurveyRecord::findOrFail($id);

        $validated = $request->validate([
            'survey_period_id' => 'required|exists:survey_periods,id',
            'barangay_id' => 'required|exists:barangays,id',
            'candidate_name' => 'required|string|max:150',
            'candidate_color' => 'required|string|max:20',
            'rating' => 'required|numeric|min:0|max:100',
            'sample_size' => 'nullable|integer|min:1',
            'methodology' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $record->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Survey record updated successfully.',
        ]);
    }

    /**
     * Delete a survey record.
     */
    public function destroy($id)
    {
        $record = SurveyRecord::findOrFail($id);
        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Survey record deleted successfully.',
        ]);
    }

    /**
     * Delete a survey period.
     */
    public function destroyPeriod($id)
    {
        $period = SurveyPeriod::findOrFail($id);
        $count = $period->records()->count();
        $period->delete();

        return response()->json([
            'success' => true,
            'message' => "Survey period and its {$count} associated record(s) deleted successfully.",
        ]);
    }

    /**
     * Export survey records to CSV.
     */
    public function exportCsv(Request $request)
    {
        $periodId = $request->input('period_id');
        $activePeriod = $periodId ? SurveyPeriod::find($periodId) : SurveyPeriod::orderBy('start_date', 'desc')->first();

        $query = SurveyRecord::with(['barangay', 'period']);
        if ($activePeriod) {
            $query->where('survey_period_id', $activePeriod->id);
        }

        $records = $query->orderBy('barangay_id')->orderByDesc('rating')->get();
        $filename = 'Survey_Results_' . ($activePeriod ? str_replace(' ', '_', $activePeriod->name) : 'All') . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID',
                'Survey Period',
                'Barangay ID',
                'Barangay Name',
                'Candidate Name',
                'Candidate Color',
                'Rating (%)',
                'Sample Size',
                'Methodology',
                'Notes',
                'Recorded Date'
            ]);

            foreach ($records as $r) {
                fputcsv($file, [
                    $r->id,
                    $r->period->name ?? '—',
                    $r->barangay_id,
                    $r->barangay->name ?? '—',
                    $r->candidate_name,
                    $r->candidate_color,
                    $r->rating,
                    $r->sample_size,
                    $r->methodology,
                    $r->notes,
                    $r->created_at ? $r->created_at->format('Y-m-d H:i:s') : '',
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
