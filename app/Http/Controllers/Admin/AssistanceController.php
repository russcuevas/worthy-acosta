<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barangay;
use App\Models\Assistance;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssistanceController extends Controller
{
    /**
     * Display the main Assistance module page.
     */
    public function index(Request $request)
    {
        $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        if ($barangays->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'BarangaySeeder']);
            $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        }

        $assistanceTypes = ['Financial', 'Burial', 'Tent', 'Item Donation', 'Others'];

        return view('admin.assistance.index', compact('barangays', 'assistanceTypes'));
    }

    /**
     * Get consolidated data, map metrics, breakdowns, and filtered records via AJAX.
     */
    public function getData(Request $request)
    {
        $barangayId = $request->input('barangay_id');
        $type = $request->input('type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $search = $request->input('search');
        $metric = $request->input('metric', 'count'); // 'count', 'amount', 'beneficiaries'

        // Base Query with filters
        $query = Assistance::with('barangay');

        if (!empty($barangayId) && $barangayId !== 'all') {
            $query->where('barangay_id', $barangayId);
        }

        if (!empty($type) && $type !== 'all') {
            $query->where('type', $type);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('date', '<=', $dateTo);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('assistance_given', 'like', "%{$search}%")
                  ->orWhere('custom_type', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('barangay', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Clone query for KPI aggregates
        $filteredAssistances = (clone $query)->orderBy('date', 'desc')->get();

        $totalRecords = $filteredAssistances->count();
        $totalAmount = (float) $filteredAssistances->sum('amount');
        $totalBeneficiaries = (int) $filteredAssistances->sum('beneficiaries_count');

        // All 18 Barangays stats (even with 0 if no records)
        $allBarangays = Barangay::where('is_active', true)->orderBy('id')->get();
        
        // Group filtered records by barangay
        $recordsByBarangay = $filteredAssistances->groupBy('barangay_id');

        $standardTypes = ['Financial', 'Burial', 'Tent', 'Item Donation', 'Others'];

        $barangayMapData = [];
        $topBarangay = null;
        $maxMetricValue = 0;

        foreach ($allBarangays as $bgy) {
            $bgyRecords = $recordsByBarangay->get($bgy->id, collect());
            
            $bgyCount = $bgyRecords->count();
            $bgyAmount = (float) $bgyRecords->sum('amount');
            $bgyBeneficiaries = (int) $bgyRecords->sum('beneficiaries_count');

            // Breakdown by type for this barangay
            $typeBreakdown = [];
            foreach ($standardTypes as $st) {
                $typeItems = $bgyRecords->where('type', $st);
                $typeBreakdown[$st] = [
                    'type' => $st,
                    'count' => $typeItems->count(),
                    'amount' => (float) $typeItems->sum('amount'),
                    'beneficiaries' => (int) $typeItems->sum('beneficiaries_count')
                ];
            }

            // Determine active metric value
            $metricVal = 0;
            if ($metric === 'amount') {
                $metricVal = $bgyAmount;
            } elseif ($metric === 'beneficiaries') {
                $metricVal = $bgyBeneficiaries;
            } else {
                $metricVal = $bgyCount;
            }

            if ($metricVal > $maxMetricValue) {
                $maxMetricValue = $metricVal;
                $topBarangay = [
                    'id' => $bgy->id,
                    'name' => $bgy->name,
                    'value' => $metricVal,
                    'amount' => $bgyAmount,
                    'count' => $bgyCount,
                    'beneficiaries' => $bgyBeneficiaries
                ];
            }

            $barangayMapData[$bgy->id] = [
                'id' => $bgy->id,
                'name' => $bgy->name,
                'pin_x' => (float) $bgy->pin_x,
                'pin_y' => (float) $bgy->pin_y,
                'count' => $bgyCount,
                'amount' => $bgyAmount,
                'beneficiaries' => $bgyBeneficiaries,
                'types' => $typeBreakdown,
            ];
        }

        // Overall Type Breakdown (for the current active filter scope)
        $overallTypeBreakdown = [];
        foreach ($standardTypes as $st) {
            $typeItems = $filteredAssistances->where('type', $st);
            $typeCount = $typeItems->count();
            $typeAmount = (float) $typeItems->sum('amount');
            $typeBeneficiaries = (int) $typeItems->sum('beneficiaries_count');

            $overallTypeBreakdown[] = [
                'type' => $st,
                'count' => $typeCount,
                'amount' => $typeAmount,
                'beneficiaries' => $typeBeneficiaries,
                'percent_count' => $totalRecords > 0 ? round(($typeCount / $totalRecords) * 100, 1) : 0,
                'percent_amount' => $totalAmount > 0 ? round(($typeAmount / $totalAmount) * 100, 1) : 0,
            ];
        }

        // Detailed records list
        $recordsList = $filteredAssistances->map(function ($item) {
            return [
                'id' => $item->id,
                'barangay_id' => $item->barangay_id,
                'barangay_name' => $item->barangay ? $item->barangay->name : 'N/A',
                'type' => $item->type,
                'custom_type' => $item->custom_type,
                'display_type' => $item->display_type,
                'assistance_given' => $item->assistance_given,
                'date' => $item->date ? $item->date->format('Y-m-d') : '',
                'formatted_date' => $item->date ? $item->date->format('M. d, Y') : '',
                'amount' => (float) $item->amount,
                'formatted_amount' => 'PHP ' . number_format($item->amount, 2),
                'beneficiaries_count' => (int) $item->beneficiaries_count,
                'notes' => $item->notes ?? '',
            ];
        });

        return response()->json([
            'success' => true,
            'kpis' => [
                'total_records' => $totalRecords,
                'total_amount' => $totalAmount,
                'formatted_total_amount' => 'PHP ' . number_format($totalAmount, 2),
                'total_beneficiaries' => $totalBeneficiaries,
                'top_barangay' => $topBarangay,
                'max_metric_value' => $maxMetricValue,
            ],
            'barangays' => $barangayMapData,
            'type_breakdown' => $overallTypeBreakdown,
            'records' => $recordsList,
        ]);
    }

    /**
     * Store a newly created assistance record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id' => 'required|exists:barangays,id',
            'type' => 'required|string|in:Financial,Burial,Tent,Item Donation,Others',
            'custom_type' => 'nullable|string|max:255',
            'assistance_given' => 'required|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'beneficiaries_count' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validated['type'] !== 'Others') {
            $validated['custom_type'] = null;
        }

        $validated['created_by'] = auth()->id();

        $assistance = Assistance::create($validated);
        $assistance->load('barangay');

        return response()->json([
            'success' => true,
            'message' => 'Assistance record added successfully!',
            'record' => $assistance,
        ]);
    }

    /**
     * Display the specified assistance record.
     */
    public function show($id)
    {
        $assistance = Assistance::with('barangay')->findOrFail($id);

        return response()->json([
            'success' => true,
            'record' => [
                'id' => $assistance->id,
                'barangay_id' => $assistance->barangay_id,
                'barangay_name' => $assistance->barangay ? $assistance->barangay->name : '',
                'type' => $assistance->type,
                'custom_type' => $assistance->custom_type,
                'assistance_given' => $assistance->assistance_given,
                'date' => $assistance->date ? $assistance->date->format('Y-m-d') : '',
                'amount' => (float) $assistance->amount,
                'beneficiaries_count' => (int) $assistance->beneficiaries_count,
                'notes' => $assistance->notes,
            ]
        ]);
    }

    /**
     * Update the specified assistance record.
     */
    public function update(Request $request, $id)
    {
        $assistance = Assistance::findOrFail($id);

        $validated = $request->validate([
            'barangay_id' => 'required|exists:barangays,id',
            'type' => 'required|string|in:Financial,Burial,Tent,Item Donation,Others',
            'custom_type' => 'nullable|string|max:255',
            'assistance_given' => 'required|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'beneficiaries_count' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validated['type'] !== 'Others') {
            $validated['custom_type'] = null;
        }

        $assistance->update($validated);
        $assistance->load('barangay');

        return response()->json([
            'success' => true,
            'message' => 'Assistance record updated successfully!',
            'record' => $assistance,
        ]);
    }

    /**
     * Remove the specified assistance record.
     */
    public function destroy($id)
    {
        $assistance = Assistance::findOrFail($id);
        $assistance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Assistance record deleted successfully!',
        ]);
    }

    /**
     * Export filtered assistance data to CSV.
     */
    public function exportCsv(Request $request)
    {
        $barangayId = $request->input('barangay_id');
        $type = $request->input('type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $search = $request->input('search');

        $query = Assistance::with('barangay');

        if (!empty($barangayId) && $barangayId !== 'all') {
            $query->where('barangay_id', $barangayId);
        }

        if (!empty($type) && $type !== 'all') {
            $query->where('type', $type);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('date', '<=', $dateTo);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('assistance_given', 'like', "%{$search}%")
                  ->orWhere('custom_type', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $records = $query->orderBy('date', 'desc')->get();

        $filename = 'Assistance_Report_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Date', 'Barangay', 'Type of Assistance', 'Assistance Given', 'Amount (PHP)', 'Beneficiaries', 'Notes']);

            foreach ($records as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->date ? $row->date->format('Y-m-d') : '',
                    $row->barangay ? $row->barangay->name : '',
                    $row->display_type,
                    $row->assistance_given,
                    number_format($row->amount, 2, '.', ''),
                    $row->beneficiaries_count,
                    $row->notes ?? '',
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
