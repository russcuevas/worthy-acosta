<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barangay;
use App\Models\Directory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DirectoryController extends Controller
{
    /**
     * Display the main Directory module page.
     */
    public function index(Request $request)
    {
        $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        if ($barangays->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'BarangaySeeder']);
            $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        }

        $contactTypes = Directory::CONTACT_TYPES;
        $internalLabels = array_keys(Directory::INTERNAL_LABELS);

        return view('admin.directory.index', compact('barangays', 'contactTypes', 'internalLabels'));
    }

    /**
     * Get consolidated data, map metrics, breakdowns, and filtered records via AJAX.
     */
    public function getData(Request $request)
    {
        $barangayId = $request->input('barangay_id');
        $contactType = $request->input('contact_type');
        $internalLabel = $request->input('internal_label');
        $search = $request->input('search');
        $metric = $request->input('metric', 'total_contacts'); // 'total_contacts', 'saint', 'sinner', 'savable', or contact type

        // 1. Map & Aggregate Query: always covers all 18 barangays matching contactType & search
        $mapQuery = Directory::query();

        if (!empty($contactType) && $contactType !== 'all') {
            $mapQuery->where('contact_type', $contactType);
        }

        if (!empty($search)) {
            $mapQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('other_info', 'like', "%{$search}%")
                  ->orWhereHas('barangay', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $allMapRecords = $mapQuery->get();

        // All 18 Barangays stats
        $allBarangays = Barangay::where('is_active', true)->orderBy('id')->get();
        $recordsByBarangay = $allMapRecords->groupBy('barangay_id');
        $standardTypes = Directory::CONTACT_TYPES;

        $barangayMapData = [];
        $topBarangay = null;
        $maxMetricValue = 0;

        foreach ($allBarangays as $bgy) {
            $bgyRecords = $recordsByBarangay->get($bgy->id, collect());
            $bgyTotal = $bgyRecords->count();
            $bgySaint = $bgyRecords->where('internal_label', 'Saint')->count();
            $bgySinner = $bgyRecords->where('internal_label', 'Sinner')->count();
            $bgySavable = $bgyRecords->where('internal_label', 'Savable')->count();

            // Breakdown by contact type for this barangay
            $typeBreakdown = [];
            foreach ($standardTypes as $st) {
                $typeCount = $bgyRecords->where('contact_type', $st)->count();
                $typeBreakdown[$st] = [
                    'type' => $st,
                    'count' => $typeCount,
                    'percent' => $bgyTotal > 0 ? round(($typeCount / $bgyTotal) * 100, 1) : 0,
                ];
            }

            // Determine active metric value for map pins
            $metricVal = $bgyTotal;
            if ($metric === 'saint') {
                $metricVal = $bgySaint;
            } elseif ($metric === 'sinner') {
                $metricVal = $bgySinner;
            } elseif ($metric === 'savable') {
                $metricVal = $bgySavable;
            } elseif (in_array($metric, $standardTypes)) {
                $metricVal = $typeBreakdown[$metric]['count'] ?? 0;
            }

            if ($metricVal > $maxMetricValue) {
                $maxMetricValue = $metricVal;
                $topBarangay = [
                    'id' => $bgy->id,
                    'name' => $bgy->name,
                    'value' => $metricVal,
                    'total' => $bgyTotal,
                ];
            }

            $barangayMapData[$bgy->id] = [
                'id' => $bgy->id,
                'name' => $bgy->name,
                'pin_x' => (float) $bgy->pin_x,
                'pin_y' => (float) $bgy->pin_y,
                'total_contacts' => $bgyTotal,
                'saint_count' => $bgySaint,
                'sinner_count' => $bgySinner,
                'savable_count' => $bgySavable,
                'metric_value' => $metricVal,
                'types' => $typeBreakdown,
            ];
        }

        // Active Scope for Top KPIs & Filter Pills: either selected barangay or municipal overview
        $hasSpecificBgy = (!empty($barangayId) && $barangayId !== 'all');
        if ($hasSpecificBgy) {
            $scopeRecords = $allMapRecords->where('barangay_id', (int) $barangayId);
            $selectedBgy = $allBarangays->firstWhere('id', (int) $barangayId);
            $scopeName = $selectedBgy ? 'Brgy. ' . $selectedBgy->name : 'Barangay #' . $barangayId;
        } else {
            $scopeRecords = $allMapRecords;
            $scopeName = 'Across Mariveles 18 Barangays';
        }

        $totalRecords = $scopeRecords->count();
        $saintCount = $scopeRecords->where('internal_label', 'Saint')->count();
        $sinnerCount = $scopeRecords->where('internal_label', 'Sinner')->count();
        $savableCount = $scopeRecords->where('internal_label', 'Savable')->count();

        // Overall totals across all 18 barangays
        $overallTotal = $allMapRecords->count();
        $overallSaint = $allMapRecords->where('internal_label', 'Saint')->count();
        $overallSinner = $allMapRecords->where('internal_label', 'Sinner')->count();
        $overallSavable = $allMapRecords->where('internal_label', 'Savable')->count();

        // Scope Type Breakdown (for sidebar)
        $scopeTypeBreakdown = [];
        foreach ($standardTypes as $st) {
            $typeCount = $scopeRecords->where('contact_type', $st)->count();
            $scopeTypeBreakdown[] = [
                'type' => $st,
                'count' => $typeCount,
                'percent' => $totalRecords > 0 ? round(($typeCount / $totalRecords) * 100, 1) : 0,
            ];
        }

        // Scope Label Breakdown
        $scopeLabelBreakdown = [
            [
                'label' => 'Saint',
                'color' => 'blue',
                'count' => $saintCount,
                'percent' => $totalRecords > 0 ? round(($saintCount / $totalRecords) * 100, 1) : 0,
            ],
            [
                'label' => 'Sinner',
                'color' => 'green',
                'count' => $sinnerCount,
                'percent' => $totalRecords > 0 ? round(($sinnerCount / $totalRecords) * 100, 1) : 0,
            ],
            [
                'label' => 'Savable',
                'color' => 'yellow',
                'count' => $savableCount,
                'percent' => $totalRecords > 0 ? round(($savableCount / $totalRecords) * 100, 1) : 0,
            ],
        ];

        // 2. Query for DataTables records (filtered by contact_type and search so client-side can filter instantaneously across barangays and labels)
        $tableQuery = Directory::with(['barangay', 'creator', 'editor']);

        if (!empty($contactType) && $contactType !== 'all') {
            $tableQuery->where('contact_type', $contactType);
        }

        if (!empty($search)) {
            $tableQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('other_info', 'like', "%{$search}%")
                  ->orWhereHas('barangay', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $recordsList = $tableQuery->orderBy('name', 'asc')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'barangay_id' => $item->barangay_id,
                'barangay_name' => $item->barangay ? $item->barangay->name : 'N/A',
                'contact_type' => $item->contact_type,
                'name' => $item->name,
                'position' => $item->position,
                'contact_number' => $item->contact_number ?? 'N/A',
                'other_info' => $item->other_info ?? '',
                'internal_label' => $item->internal_label,
                'created_by' => $item->creator ? $item->creator->name : 'Authorized Staff',
                'updated_by' => $item->editor ? $item->editor->name : 'Authorized Staff',
                'created_at_formatted' => $item->created_at ? $item->created_at->format('M d, Y h:i A') : 'N/A',
                'updated_at_formatted' => $item->updated_at ? $item->updated_at->format('M d, Y h:i A') : 'N/A',
            ];
        });

        return response()->json([
            'success' => true,
            'kpis' => [
                'total_contacts' => $totalRecords,
                'saint_count' => $saintCount,
                'sinner_count' => $sinnerCount,
                'savable_count' => $savableCount,
                'scope_name' => $scopeName,
                'is_specific_bgy' => $hasSpecificBgy,
                'overall_total' => $overallTotal,
                'overall_saint' => $overallSaint,
                'overall_sinner' => $overallSinner,
                'overall_savable' => $overallSavable,
                'top_barangay' => $topBarangay,
                'max_metric_value' => $maxMetricValue,
            ],
            'barangays' => $barangayMapData,
            'type_breakdown' => $scopeTypeBreakdown,
            'label_breakdown' => $scopeLabelBreakdown,
            'records' => $recordsList,
        ]);
    }

    /**
     * Store a newly created directory record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id' => 'required|exists:barangays,id',
            'contact_type' => 'required|string|in:' . implode(',', Directory::CONTACT_TYPES),
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'other_info' => 'nullable|string|max:2000',
            'internal_label' => 'required|string|in:Saint,Sinner,Savable',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $directory = Directory::create($validated);
        $directory->load(['barangay', 'creator']);

        return response()->json([
            'success' => true,
            'message' => 'Directory contact added successfully!',
            'record' => $directory,
        ]);
    }

    /**
     * Display the specified directory record.
     */
    public function show($id)
    {
        $directory = Directory::with(['barangay', 'creator', 'editor'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'record' => [
                'id' => $directory->id,
                'barangay_id' => $directory->barangay_id,
                'barangay_name' => $directory->barangay ? $directory->barangay->name : '',
                'contact_type' => $directory->contact_type,
                'name' => $directory->name,
                'position' => $directory->position,
                'contact_number' => $directory->contact_number ?? '',
                'other_info' => $directory->other_info ?? '',
                'internal_label' => $directory->internal_label,
                'created_by' => $directory->creator ? $directory->creator->name : 'Authorized Staff',
                'updated_by' => $directory->editor ? $directory->editor->name : 'Authorized Staff',
                'created_at_formatted' => $directory->created_at ? $directory->created_at->format('M d, Y h:i A') : 'N/A',
                'updated_at_formatted' => $directory->updated_at ? $directory->updated_at->format('M d, Y h:i A') : 'N/A',
            ]
        ]);
    }

    /**
     * Update the specified directory record.
     */
    public function update(Request $request, $id)
    {
        $directory = Directory::findOrFail($id);

        $validated = $request->validate([
            'barangay_id' => 'required|exists:barangays,id',
            'contact_type' => 'required|string|in:' . implode(',', Directory::CONTACT_TYPES),
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'other_info' => 'nullable|string|max:2000',
            'internal_label' => 'required|string|in:Saint,Sinner,Savable',
        ]);

        $validated['updated_by'] = auth()->id();

        $directory->update($validated);
        $directory->load(['barangay', 'creator', 'editor']);

        return response()->json([
            'success' => true,
            'message' => 'Directory contact updated successfully!',
            'record' => $directory,
        ]);
    }

    /**
     * Remove the specified directory record.
     */
    public function destroy($id)
    {
        $directory = Directory::findOrFail($id);
        $directory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Directory contact removed successfully!',
        ]);
    }

    /**
     * Export filtered directory contacts to CSV.
     */
    public function exportCsv(Request $request)
    {
        $barangayId = $request->input('barangay_id');
        $contactType = $request->input('contact_type');
        $internalLabel = $request->input('internal_label');
        $search = $request->input('search');

        $query = Directory::with('barangay');

        if (!empty($barangayId) && $barangayId !== 'all') {
            $query->where('barangay_id', $barangayId);
        }

        if (!empty($contactType) && $contactType !== 'all') {
            $query->where('contact_type', $contactType);
        }

        if (!empty($internalLabel) && $internalLabel !== 'all') {
            $query->where('internal_label', $internalLabel);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('other_info', 'like', "%{$search}%");
            });
        }

        $records = $query->orderBy('name', 'asc')->get();

        $filename = 'Mariveles_Directory_Contacts_' . date('Y-m-d_His') . '.csv';

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
                'Barangay',
                'Type of Contact',
                'Name',
                'Position / Designation',
                'Contact Number',
                'Internal Label',
                'Other Info',
                'Created At',
                'Last Updated'
            ]);

            foreach ($records as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->barangay ? $row->barangay->name : '',
                    $row->contact_type,
                    $row->name,
                    $row->position,
                    $row->contact_number ?? '',
                    $row->internal_label,
                    $row->other_info ?? '',
                    $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '',
                    $row->updated_at ? $row->updated_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
