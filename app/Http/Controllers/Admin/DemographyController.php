<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\DemographicRecord;
use App\Models\DemographicSector;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DemographyController extends Controller
{
    /**
     * Display the Demography Module dashboard.
     */
    public function index(Request $request)
    {
        $role = $request->is('assistant*') ? 'assistant' : 'admin';
        $sectors = DemographicSector::orderBy('id')->get();
        $barangays = Barangay::orderBy('id')->get();

        return view('admin.demography.index', compact('sectors', 'barangays', 'role'));
    }

    /**
     * Fetch dynamic demographic data for interactive map, KPI cards, sidebar, and DataTable.
     */
    public function getData(Request $request): JsonResponse
    {
        $sectorFilter = $request->input('sector_id', 'all');
        $barangayFilter = $request->input('barangay_id', 'all');
        $search = trim($request->input('search', ''));

        $isSectorFiltered = ($sectorFilter !== 'all' && is_numeric($sectorFilter));
        $isBarangayFiltered = ($barangayFilter !== 'all' && is_numeric($barangayFilter));

        // All configured sectors and barangays
        $allSectors = DemographicSector::orderBy('id')->get();
        $allBarangays = Barangay::orderBy('id')->get();

        $selectedSectorObj = $isSectorFiltered ? $allSectors->firstWhere('id', (int) $sectorFilter) : null;
        $selectedBarangayObj = $isBarangayFiltered ? $allBarangays->firstWhere('id', (int) $barangayFilter) : null;

        // Municipal-wide total across all 18 barangays and all sectors
        $totalMunicipalAllSectors = (int) DemographicRecord::sum('members_count');

        // Total in selected barangay (if filtered)
        $totalSelectedBarangay = $isBarangayFiltered
            ? (int) DemographicRecord::where('barangay_id', $selectedBarangayObj->id)->sum('members_count')
            : $totalMunicipalAllSectors;

        // Sector rankings across municipality
        $sortedMunicipalSectors = collect($allSectors)->map(function ($sec) {
            return [
                'id'    => $sec->id,
                'name'  => $sec->name,
                'color' => $sec->color,
                'total' => (int) DemographicRecord::where('sector_id', $sec->id)->sum('members_count'),
            ];
        })->sortByDesc('total')->values();

        $dominantSectorOverall = $sortedMunicipalSectors->first();

        // 1. Sector population summaries
        // If barangay is filtered, summaries show counts for that barangay + percentage share in that barangay
        $sectorSummaries = [];
        foreach ($allSectors as $sec) {
            $municipalSum = (int) DemographicRecord::where('sector_id', $sec->id)->sum('members_count');
            $topBgyRecord = DemographicRecord::with('barangay')
                ->where('sector_id', $sec->id)
                ->orderByDesc('members_count')
                ->first();

            $barangayCount = $isBarangayFiltered
                ? (int) DemographicRecord::where('barangay_id', $selectedBarangayObj->id)->where('sector_id', $sec->id)->value('members_count')
                : $municipalSum;

            $activeCount = $isBarangayFiltered ? $barangayCount : $municipalSum;
            $denominator = $isBarangayFiltered ? $totalSelectedBarangay : $totalMunicipalAllSectors;
            $sharePct = $denominator > 0 ? round(($activeCount / $denominator) * 100, 1) : 0.0;

            $sectorSummaries[$sec->id] = [
                'id'              => $sec->id,
                'name'            => $sec->name,
                'slug'            => $sec->slug,
                'color'           => $sec->color,
                'description'     => $sec->description,
                'is_system'       => $sec->is_system,
                'total_members'   => $activeCount,
                'municipal_total' => $municipalSum,
                'share_pct'       => $sharePct,
                'top_barangay'    => $topBgyRecord && $topBgyRecord->barangay ? $topBgyRecord->barangay->name : 'N/A',
                'top_bgy_count'   => $topBgyRecord ? (int) $topBgyRecord->members_count : 0,
            ];
        }

        // 2. Fetch demographic records for map building
        $records = DemographicRecord::with(['barangay', 'sector'])->get();
        $bgyRecordsMap = [];
        foreach ($records as $r) {
            $bgyRecordsMap[$r->barangay_id][$r->sector_id] = $r;
        }

        // 3. Build 18 Barangays Map Payload
        $barangaysMap = [];
        $highestCountOverall = 0;
        $highestBgyOverall = null;

        foreach ($allBarangays as $bgy) {
            $bgySectorList = [];
            $bgyTotalMembers = 0;
            $dominantSector = null;
            $highestSectorInBgy = -1;
            $selectedSectorCount = 0;
            $selectedSectorNotes = '';
            $latestUpdated = null;

            foreach ($allSectors as $sec) {
                $rec = $bgyRecordsMap[$bgy->id][$sec->id] ?? null;
                $count = $rec ? (int) $rec->members_count : 0;
                $notes = $rec ? $rec->notes : null;
                $updatedAt = $rec ? $rec->last_updated_date : null;

                if ($updatedAt && (!$latestUpdated || Carbon::parse($updatedAt)->gt(Carbon::parse($latestUpdated)))) {
                    $latestUpdated = $updatedAt;
                }

                $bgyTotalMembers += $count;

                if ($count > $highestSectorInBgy) {
                    $highestSectorInBgy = $count;
                    $dominantSector = [
                        'name'  => $sec->name,
                        'count' => $count,
                        'color' => $sec->color,
                    ];
                }

                if ($selectedSectorObj && $selectedSectorObj->id === $sec->id) {
                    $selectedSectorCount = $count;
                    $selectedSectorNotes = $notes;
                }

                $bgySectorList[] = [
                    'sector_id'    => $sec->id,
                    'sector_name'  => $sec->name,
                    'sector_color' => $sec->color,
                    'count'        => $count,
                    'notes'        => $notes,
                    'updated_at'   => $updatedAt ? Carbon::parse($updatedAt)->format('M d, Y') : null,
                ];
            }

            foreach ($bgySectorList as &$bSec) {
                $bSec['share_in_bgy_pct'] = $bgyTotalMembers > 0
                    ? round(($bSec['count'] / $bgyTotalMembers) * 100, 1)
                    : 0.0;
            }
            unset($bSec);

            usort($bgySectorList, function ($a, $b) {
                return $b['count'] <=> $a['count'];
            });

            if ($selectedSectorObj) {
                $displayCount = $selectedSectorCount;
                $displayColor = $selectedSectorObj->color;
                $displayLabel = $bgy->name . ': ' . number_format($selectedSectorCount);
            } else {
                $displayCount = $bgyTotalMembers;
                $displayColor = $dominantSector ? $dominantSector['color'] : '#075998';
                $displayLabel = $bgy->name;
            }

            if ($displayCount > $highestCountOverall) {
                $highestCountOverall = $displayCount;
                $highestBgyOverall = $bgy->name;
            }

            $barangaysMap[$bgy->id] = [
                'id'                     => $bgy->id,
                'name'                   => $bgy->name,
                'pin_x'                  => $bgy->pin_x,
                'pin_y'                  => $bgy->pin_y,
                'total_members'          => $bgyTotalMembers,
                'display_count'          => $displayCount,
                'display_color'          => $displayColor,
                'display_label'          => $displayLabel,
                'dominant_sector'        => $dominantSector,
                'selected_sector_count'  => $selectedSectorCount,
                'selected_sector_notes'  => $selectedSectorNotes,
                'sectors'                => $bgySectorList,
                'last_updated'           => $latestUpdated ? Carbon::parse($latestUpdated)->format('M d, Y') : 'Recently validated',
            ];
        }

        // 4. Calculate Top 5 KPI Cards according to active filters
        if ($isBarangayFiltered && $isSectorFiltered) {
            // SCENARIO 1: BOTH Barangay AND Sector Selected (e.g. San Carlos + Fishermen)
            $rec = DemographicRecord::where('barangay_id', $selectedBarangayObj->id)
                ->where('sector_id', $selectedSectorObj->id)
                ->first();
            $count = $rec ? (int) $rec->members_count : 0;
            $bgyTotal = (int) DemographicRecord::where('barangay_id', $selectedBarangayObj->id)->sum('members_count');
            $secMunicipalTotal = (int) DemographicRecord::where('sector_id', $selectedSectorObj->id)->sum('members_count');

            $shareInBgy = $bgyTotal > 0 ? round(($count / $bgyTotal) * 100, 1) : 0;
            $shareOfMunicipalSec = $secMunicipalTotal > 0 ? round(($count / $secMunicipalTotal) * 100, 1) : 0;

            $peakRecord = DemographicRecord::with('barangay')
                ->where('sector_id', $selectedSectorObj->id)
                ->orderByDesc('members_count')
                ->first();

            $kpis = [
                'total_population'       => number_format($count),
                'total_population_raw'   => $count,
                'selected_sector_title'  => $selectedSectorObj->name . ' in ' . $selectedBarangayObj->name,
                'selected_sector_sub'    => $shareInBgy . '% of Brgy. ' . $selectedBarangayObj->name . ' population',

                'dominant_title'         => 'Municipal Sector Share',
                'dominant_sector_name'   => $shareOfMunicipalSec . '% Share',
                'dominant_sector_count'  => number_format($count) . ' of ' . number_format($secMunicipalTotal),
                'dominant_sector_color'  => $selectedSectorObj->color,
                'dominant_sub'           => 'Of ' . number_format($secMunicipalTotal) . ' total in Mariveles',

                'concentration_title'    => 'Municipal Peak (' . $selectedSectorObj->name . ')',
                'top_barangay_name'      => $peakRecord && $peakRecord->barangay ? $peakRecord->barangay->name : 'N/A',
                'top_barangay_count'     => $peakRecord ? number_format($peakRecord->members_count) : '0',
                'top_barangay_sub'       => 'Highest concentration in municipality',

                'active_sectors_title'   => 'Target Community Sector',
                'active_sectors_count'   => $selectedSectorObj->name,
                'active_sectors_sub'     => 'Program planning demographic target',

                'coverage_title'         => 'Target Barangay Scope',
                'barangays_covered'      => 'Brgy. ' . $selectedBarangayObj->name,
                'planning_coverage_sub'  => 'Barangay #' . $selectedBarangayObj->id . ' of 18 (Validated)',
            ];
        } elseif ($isBarangayFiltered) {
            // SCENARIO 2: ONLY Barangay Selected (e.g. San Carlos)
            $bgyTotal = (int) DemographicRecord::where('barangay_id', $selectedBarangayObj->id)->sum('members_count');
            $bgyShareOfMuni = $totalMunicipalAllSectors > 0 ? round(($bgyTotal / $totalMunicipalAllSectors) * 100, 1) : 0;

            $topSectorRec = DemographicRecord::with('sector')
                ->where('barangay_id', $selectedBarangayObj->id)
                ->orderByDesc('members_count')
                ->first();

            $secondSectorRec = DemographicRecord::with('sector')
                ->where('barangay_id', $selectedBarangayObj->id)
                ->orderByDesc('members_count')
                ->skip(1)
                ->first();

            $activeSecCount = DemographicRecord::where('barangay_id', $selectedBarangayObj->id)
                ->where('members_count', '>', 0)
                ->count();

            // Calculate Barangay Rank in municipality
            $bgyRankList = collect($allBarangays)->map(function ($b) {
                return [
                    'id'    => $b->id,
                    'total' => (int) DemographicRecord::where('barangay_id', $b->id)->sum('members_count'),
                ];
            })->sortByDesc('total')->values();

            $bgyRank = $bgyRankList->search(function ($item) use ($selectedBarangayObj) {
                return $item['id'] === $selectedBarangayObj->id;
            });
            $bgyRankDisplay = $bgyRank !== false ? ($bgyRank + 1) : 1;

            $topSecCount = $topSectorRec ? (int) $topSectorRec->members_count : 0;
            $topSecShare = $bgyTotal > 0 ? round(($topSecCount / $bgyTotal) * 100, 1) : 0;

            $kpis = [
                'total_population'       => number_format($bgyTotal),
                'total_population_raw'   => $bgyTotal,
                'selected_sector_title'  => 'Total Population (' . $selectedBarangayObj->name . ')',
                'selected_sector_sub'    => $bgyShareOfMuni . '% of Mariveles total population',

                'dominant_title'         => 'Dominant in ' . $selectedBarangayObj->name,
                'dominant_sector_name'   => $topSectorRec && $topSectorRec->sector ? $topSectorRec->sector->name : 'N/A',
                'dominant_sector_count'  => number_format($topSecCount),
                'dominant_sector_color'  => $topSectorRec && $topSectorRec->sector ? $topSectorRec->sector->color : '#075998',
                'dominant_sub'           => $topSecShare . '% of this barangay population',

                'concentration_title'    => 'Second Largest Sector',
                'top_barangay_name'      => $secondSectorRec && $secondSectorRec->sector ? $secondSectorRec->sector->name : 'N/A',
                'top_barangay_count'     => $secondSectorRec ? number_format($secondSectorRec->members_count) : '0',
                'top_barangay_sub'       => 'Runner-up sector in ' . $selectedBarangayObj->name,

                'active_sectors_title'   => 'Active Sectors in Barangay',
                'active_sectors_count'   => $activeSecCount . ' of ' . count($allSectors),
                'active_sectors_sub'     => 'Community sectors with encoded members',

                'coverage_title'         => 'Barangay Rank & Scope',
                'barangays_covered'      => 'Rank #' . $bgyRankDisplay,
                'planning_coverage_sub'  => 'Brgy. ' . $selectedBarangayObj->name . ' (1 of 18)',
            ];
        } elseif ($isSectorFiltered) {
            // SCENARIO 3: ONLY Sector Selected (e.g. Fishermen)
            $secMunicipalTotal = (int) DemographicRecord::where('sector_id', $selectedSectorObj->id)->sum('members_count');
            $secSharePct = $totalMunicipalAllSectors > 0 ? round(($secMunicipalTotal / $totalMunicipalAllSectors) * 100, 1) : 0;

            $topBgyRecord = DemographicRecord::with('barangay')
                ->where('sector_id', $selectedSectorObj->id)
                ->orderByDesc('members_count')
                ->first();

            $coveredBgys = DemographicRecord::where('sector_id', $selectedSectorObj->id)
                ->where('members_count', '>', 0)
                ->count();

            $sectorRankIdx = $sortedMunicipalSectors->search(function ($item) use ($selectedSectorObj) {
                return $item['id'] === $selectedSectorObj->id;
            });
            $sectorRank = $sectorRankIdx !== false ? ($sectorRankIdx + 1) : 1;

            $topBgyCount = $topBgyRecord ? (int) $topBgyRecord->members_count : 0;
            $topBgyShare = $secMunicipalTotal > 0 ? round(($topBgyCount / $secMunicipalTotal) * 100, 1) : 0;

            $kpis = [
                'total_population'       => number_format($secMunicipalTotal),
                'total_population_raw'   => $secMunicipalTotal,
                'selected_sector_title'  => $selectedSectorObj->name . ' Population',
                'selected_sector_sub'    => $secSharePct . '% of all municipal sector population',

                'dominant_title'         => 'Municipal Sector Rank',
                'dominant_sector_name'   => 'Rank #' . $sectorRank . ' of ' . count($allSectors),
                'dominant_sector_count'  => $secSharePct . '% Share',
                'dominant_sector_color'  => $selectedSectorObj->color,
                'dominant_sub'           => number_format($secMunicipalTotal) . ' recorded across Mariveles',

                'concentration_title'    => 'Peak Concentration',
                'top_barangay_name'      => $topBgyRecord && $topBgyRecord->barangay ? $topBgyRecord->barangay->name : 'N/A',
                'top_barangay_count'     => number_format($topBgyCount),
                'top_barangay_sub'       => $topBgyShare . '% of all ' . $selectedSectorObj->name,

                'active_sectors_title'   => 'Selected Community Sector',
                'active_sectors_count'   => $selectedSectorObj->name,
                'active_sectors_sub'     => 'Geographic concentration mapped',

                'coverage_title'         => 'Barangay Presence',
                'barangays_covered'      => $coveredBgys . ' / ' . count($allBarangays),
                'planning_coverage_sub'  => 'Barangays with ' . $selectedSectorObj->name,
            ];
        } else {
            // SCENARIO 4: Default All / All
            $kpis = [
                'total_population'       => number_format($totalMunicipalAllSectors),
                'total_population_raw'   => $totalMunicipalAllSectors,
                'selected_sector_title'  => 'Total Sector Members',
                'selected_sector_sub'    => 'Across all 18 Mariveles Barangays',

                'dominant_title'         => 'Dominant Sector',
                'dominant_sector_name'   => $dominantSectorOverall ? $dominantSectorOverall['name'] : 'Factory Workers',
                'dominant_sector_count'  => $dominantSectorOverall ? number_format($dominantSectorOverall['total']) : '0',
                'dominant_sector_color'  => $dominantSectorOverall ? $dominantSectorOverall['color'] : '#F59E0B',
                'dominant_sub'           => 'Highest recorded population (' . ($totalMunicipalAllSectors > 0 && $dominantSectorOverall ? round(($dominantSectorOverall['total'] / $totalMunicipalAllSectors) * 100, 1) : 0) . '% share)',

                'concentration_title'    => 'Peak Concentration',
                'top_barangay_name'      => $highestBgyOverall ?? 'Baseco Country',
                'top_barangay_count'     => number_format($highestCountOverall),
                'top_barangay_sub'       => 'Peak concentration (Combined)',

                'active_sectors_title'   => 'Active Sectors',
                'active_sectors_count'   => (string) count($allSectors),
                'active_sectors_sub'     => 'Configurable community groups',

                'coverage_title'         => 'Barangay Coverage',
                'barangays_covered'      => count($allBarangays) . ' / ' . count($allBarangays),
                'planning_coverage_sub'  => 'Validated Barangay Baseline',
            ];
        }

        // 5. DataTables Tabular Records List (Searchable and Filterable)
        $dtQuery = DemographicRecord::with(['barangay', 'sector']);

        if ($isSectorFiltered) {
            $dtQuery->where('sector_id', (int) $sectorFilter);
        }

        if ($isBarangayFiltered) {
            $dtQuery->where('barangay_id', (int) $barangayFilter);
        }

        if (!empty($search)) {
            $dtQuery->where(function ($q) use ($search) {
                $q->whereHas('barangay', function ($b) use ($search) {
                    $b->where('name', 'like', "%{$search}%");
                })->orWhereHas('sector', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                })->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $tableRecords = $dtQuery->orderBy('barangay_id')->orderBy('sector_id')->get()->map(function ($r) use ($sortedMunicipalSectors) {
            $secMunicipalRec = $sortedMunicipalSectors->firstWhere('id', $r->sector_id);
            $secMunicipalTotal = $secMunicipalRec ? $secMunicipalRec['total'] : 0;
            $municipalShare = $secMunicipalTotal > 0
                ? round(($r->members_count / $secMunicipalTotal) * 100, 1)
                : 0.0;

            return [
                'id'              => $r->id,
                'barangay_id'     => $r->barangay_id,
                'barangay_name'   => $r->barangay ? $r->barangay->name : 'N/A',
                'sector_id'       => $r->sector_id,
                'sector_name'     => $r->sector ? $r->sector->name : 'N/A',
                'sector_color'    => $r->sector ? $r->sector->color : '#075998',
                'members_count'   => (int) $r->members_count,
                'members_display' => number_format($r->members_count),
                'municipal_share' => $municipalShare,
                'notes'           => $r->notes ?? '—',
                'last_updated'    => $r->last_updated_date ? Carbon::parse($r->last_updated_date)->format('M d, Y') : Carbon::parse($r->updated_at)->format('M d, Y'),
            ];
        });

        return response()->json([
            'success'              => true,
            'is_sector_filtered'   => $isSectorFiltered,
            'is_barangay_filtered' => $isBarangayFiltered,
            'kpis'                 => $kpis,
            'sector_summaries'     => array_values($sectorSummaries),
            'barangays'            => $barangaysMap,
            'records'              => $tableRecords,
            'active_sector'        => $selectedSectorObj ? [
                'id'          => $selectedSectorObj->id,
                'name'        => $selectedSectorObj->name,
                'color'       => $selectedSectorObj->color,
                'description' => $selectedSectorObj->description,
            ] : null,
            'sectors'              => $allSectors,
        ]);
    }

    /**
     * Store/Upsert Demographic Data (Spec Section 1 & 9).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'barangay_id'   => 'required',
            'sector_id'     => 'required|exists:demographic_sectors,id',
            'members_count' => 'required|integer|min:0',
            'notes'         => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        $sectorId = (int) $validated['sector_id'];
        $count = (int) $validated['members_count'];
        $notes = $validated['notes'] ?? null;

        if ($validated['barangay_id'] === 'all_barangays') {
            $allBarangays = Barangay::all();
            foreach ($allBarangays as $b) {
                DemographicRecord::updateOrCreate(
                    [
                        'barangay_id' => $b->id,
                        'sector_id'   => $sectorId,
                    ],
                    [
                        'members_count'     => $count,
                        'notes'             => $notes,
                        'last_updated_date' => Carbon::now(),
                        'created_by'        => $userId,
                    ]
                );
            }
            return response()->json([
                'success' => true,
                'message' => 'Demographic record applied to all 18 Barangays successfully.',
            ]);
        }

        $bgyId = (int) $validated['barangay_id'];

        DemographicRecord::updateOrCreate(
            [
                'barangay_id' => $bgyId,
                'sector_id'   => $sectorId,
            ],
            [
                'members_count'     => $count,
                'notes'             => $notes,
                'last_updated_date' => Carbon::now(),
                'created_by'        => $userId,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Demographic data recorded successfully.',
        ]);
    }

    /**
     * Fetch a single demographic record for view/edit.
     */
    public function show($id): JsonResponse
    {
        $record = DemographicRecord::with(['barangay', 'sector'])->find($id);

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'record'  => [
                'id'            => $record->id,
                'barangay_id'   => $record->barangay_id,
                'barangay_name' => $record->barangay ? $record->barangay->name : '',
                'sector_id'     => $record->sector_id,
                'sector_name'   => $record->sector ? $record->sector->name : '',
                'members_count' => $record->members_count,
                'notes'         => $record->notes,
                'last_updated'  => Carbon::parse($record->last_updated_date)->format('M d, Y h:i A'),
            ],
        ]);
    }

    /**
     * Update an existing demographic record.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $record = DemographicRecord::find($id);

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $validated = $request->validate([
            'members_count' => 'required|integer|min:0',
            'notes'         => 'nullable|string|max:1000',
        ]);

        $record->update([
            'members_count'     => (int) $validated['members_count'],
            'notes'             => $validated['notes'] ?? null,
            'last_updated_date' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Demographic record updated successfully.',
        ]);
    }

    /**
     * Delete a demographic record.
     */
    public function destroy($id): JsonResponse
    {
        $record = DemographicRecord::find($id);

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Demographic record deleted successfully.',
        ]);
    }

    /**
     * Add a new Configurable Community Sector (Spec Section 2).
     */
    public function storeSector(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:demographic_sectors,name',
            'color'       => 'required|string|max:20',
            'description' => 'nullable|string|max:500',
        ]);

        $sector = DemographicSector::create([
            'name'        => trim($validated['name']),
            'slug'        => Str::slug($validated['name']),
            'color'       => $validated['color'],
            'description' => $validated['description'] ?? null,
            'is_system'   => false,
        ]);

        // Auto-seed initial 0 records for all 18 barangays so the sector is immediately functional
        $allBarangays = Barangay::all();
        $userId = Auth::id();
        foreach ($allBarangays as $b) {
            DemographicRecord::firstOrCreate(
                [
                    'barangay_id' => $b->id,
                    'sector_id'   => $sector->id,
                ],
                [
                    'members_count'     => 0,
                    'notes'             => 'Initial baseline entry',
                    'last_updated_date' => Carbon::now(),
                    'created_by'        => $userId,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Sector '{$sector->name}' added successfully and initialized across all 18 Barangays.",
            'sector'  => $sector,
        ]);
    }

    /**
     * Update an existing sector.
     */
    public function updateSector(Request $request, $id): JsonResponse
    {
        $sector = DemographicSector::find($id);

        if (!$sector) {
            return response()->json(['success' => false, 'message' => 'Sector not found.'], 404);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:demographic_sectors,name,' . $id,
            'color'       => 'required|string|max:20',
            'description' => 'nullable|string|max:500',
        ]);

        $sector->update([
            'name'        => trim($validated['name']),
            'slug'        => Str::slug($validated['name']),
            'color'       => $validated['color'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Sector '{$sector->name}' updated successfully.",
            'sector'  => $sector,
        ]);
    }

    /**
     * Delete a community sector.
     */
    public function destroySector($id): JsonResponse
    {
        $sector = DemographicSector::find($id);

        if (!$sector) {
            return response()->json(['success' => false, 'message' => 'Sector not found.'], 404);
        }

        $name = $sector->name;
        $sector->delete(); // Cascades delete demographic_records

        return response()->json([
            'success' => true,
            'message' => "Sector '{$name}' deleted successfully.",
        ]);
    }
}
