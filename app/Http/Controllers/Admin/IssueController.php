<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barangay;
use App\Models\Issue;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IssueController extends Controller
{
    /**
     * Standard issue categories, statuses, and priorities.
     */
    protected array $issueTypes = ['Political', 'Community', 'Municipal-Wide', 'Policy', 'Infrastructure'];
    protected array $statuses = ['New', 'Ongoing', 'For Action', 'Resolved'];
    protected array $priorities = ['Low', 'Medium', 'High', 'Urgent'];

    /**
     * Display the main Issues Module page.
     */
    public function index(Request $request)
    {
        $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        if ($barangays->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'BarangaySeeder']);
            $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        }

        $issueTypes = $this->issueTypes;
        $statuses = $this->statuses;
        $priorities = $this->priorities;

        return view('admin.issues.index', compact('barangays', 'issueTypes', 'statuses', 'priorities'));
    }

    /**
     * Get consolidated data, map metrics, breakdowns, and filtered records via AJAX.
     */
    public function getData(Request $request)
    {
        $barangayId = $request->input('barangay_id');
        $issueType = $request->input('issue_type');
        $status = $request->input('status');
        $priority = $request->input('priority');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $search = $request->input('search');
        $metric = $request->input('metric', 'total_issues'); // 'total_issues', 'urgent_issues', 'ongoing_issues', 'for_action_issues', 'resolved_issues'

        // Base Query with filters
        $query = Issue::with('barangays');

        // Barangay filter
        if (!empty($barangayId) && $barangayId !== 'all') {
            if ($barangayId === 'municipal_wide') {
                $query->where('is_municipal_wide', true);
            } else {
                $query->where(function ($q) use ($barangayId) {
                    $q->whereHas('barangays', function ($bq) use ($barangayId) {
                        $bq->where('barangays.id', $barangayId);
                    })->orWhere('is_municipal_wide', true);
                });
            }
        }

        // Issue Type filter
        if (!empty($issueType) && $issueType !== 'all') {
            $query->where('issue_type', $issueType);
        }

        // Priority filter
        if (!empty($priority) && $priority !== 'all') {
            $query->where('priority', $priority);
        }

        // Date range filter
        if (!empty($dateFrom)) {
            $query->whereDate('date_reported', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('date_reported', '<=', $dateTo);
        }

        // Keyword search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('who_affected', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhere('action_taken', 'like', "%{$search}%")
                  ->orWhereHas('barangays', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Fetch all matching issues (before status filter so KPIs and all status counters never zero out)
        $filteredIssues = (clone $query)->orderBy('date_reported', 'desc')->get();

        // Municipal / Overall KPIs
        $totalIssues = $filteredIssues->count();
        $ongoingIssues = $filteredIssues->where('status', 'Ongoing')->count();
        $forActionIssues = $filteredIssues->where('status', 'For Action')->count();
        $resolvedIssues = $filteredIssues->where('status', 'Resolved')->count();
        $urgentIssues = $filteredIssues->where('priority', 'Urgent')->count();
        $newIssues = $filteredIssues->where('status', 'New')->count();

        // All 18 Mariveles Barangays stats for spatial map visualization
        $allBarangays = Barangay::where('is_active', true)->orderBy('id')->get();
        $barangayMapData = [];
        $topBarangay = null;
        $maxMetricValue = 0;

        foreach ($allBarangays as $bgy) {
            // An issue associates with this barangay if it is directly linked or is municipal-wide
            $bgyIssues = $filteredIssues->filter(function ($issue) use ($bgy) {
                return $issue->is_municipal_wide || $issue->barangays->contains('id', $bgy->id);
            });

            $bgyTotal = $bgyIssues->count();
            $bgyOngoing = $bgyIssues->where('status', 'Ongoing')->count();
            $bgyForAction = $bgyIssues->where('status', 'For Action')->count();
            $bgyResolved = $bgyIssues->where('status', 'Resolved')->count();
            $bgyUrgent = $bgyIssues->where('priority', 'Urgent')->count();
            $bgyNew = $bgyIssues->where('status', 'New')->count();

            // Type Breakdown for this barangay (Section 3 Summary Metric Example: Political, Community, Municipal-Wide, Policy, Infrastructure)
            $typeBreakdown = [];
            foreach ($this->issueTypes as $st) {
                $typeItems = $bgyIssues->where('issue_type', $st);
                $typeBreakdown[$st] = [
                    'type' => $st,
                    'count' => $typeItems->count(),
                    'ongoing' => $typeItems->where('status', 'Ongoing')->count(),
                    'for_action' => $typeItems->where('status', 'For Action')->count(),
                    'resolved' => $typeItems->where('status', 'Resolved')->count(),
                    'urgent' => $typeItems->where('priority', 'Urgent')->count(),
                ];
            }

            // Priority Breakdown
            $priorityBreakdown = [];
            foreach ($this->priorities as $p) {
                $priorityBreakdown[$p] = $bgyIssues->where('priority', $p)->count();
            }

            // Status Breakdown
            $statusBreakdown = [];
            foreach ($this->statuses as $s) {
                $statusBreakdown[$s] = $bgyIssues->where('status', $s)->count();
            }

            // Determine active map metric value for color/intensity/tooltips
            $metricVal = match ($metric) {
                'urgent_issues' => $bgyUrgent,
                'ongoing_issues' => $bgyOngoing,
                'for_action_issues' => $bgyForAction,
                'resolved_issues' => $bgyResolved,
                'new_issues' => $bgyNew,
                default => $bgyTotal,
            };

            if ($metricVal > $maxMetricValue) {
                $maxMetricValue = $metricVal;
                $topBarangay = [
                    'id' => $bgy->id,
                    'name' => $bgy->name,
                    'metric_value' => $metricVal,
                    'total_issues' => $bgyTotal,
                    'urgent_issues' => $bgyUrgent,
                    'ongoing_issues' => $bgyOngoing,
                    'for_action_issues' => $bgyForAction,
                    'resolved_issues' => $bgyResolved,
                ];
            }

            $barangayMapData[$bgy->id] = [
                'id' => $bgy->id,
                'name' => $bgy->name,
                'pin_x' => (float) $bgy->pin_x,
                'pin_y' => (float) $bgy->pin_y,
                'metric_value' => $metricVal,
                'total_issues' => $bgyTotal,
                'ongoing_issues' => $bgyOngoing,
                'for_action_issues' => $bgyForAction,
                'resolved_issues' => $bgyResolved,
                'urgent_issues' => $bgyUrgent,
                'new_issues' => $bgyNew,
                'types' => $typeBreakdown,
                'priorities' => $priorityBreakdown,
                'statuses' => $statusBreakdown,
            ];
        }

        // Overall Breakdown by Issue Type (for table & analytics)
        $overallTypeBreakdown = [];
        foreach ($this->issueTypes as $st) {
            $typeItems = $filteredIssues->where('issue_type', $st);
            $typeCount = $typeItems->count();
            $overallTypeBreakdown[] = [
                'type' => $st,
                'count' => $typeCount,
                'percent' => $totalIssues > 0 ? round(($typeCount / $totalIssues) * 100, 1) : 0,
                'ongoing' => $typeItems->where('status', 'Ongoing')->count(),
                'for_action' => $typeItems->where('status', 'For Action')->count(),
                'resolved' => $typeItems->where('status', 'Resolved')->count(),
                'urgent' => $typeItems->where('priority', 'Urgent')->count(),
            ];
        }

        // Overall Breakdown by Status
        $overallStatusBreakdown = [];
        foreach ($this->statuses as $s) {
            $statusCount = $filteredIssues->where('status', $s)->count();
            $overallStatusBreakdown[] = [
                'status' => $s,
                'count' => $statusCount,
                'percent' => $totalIssues > 0 ? round(($statusCount / $totalIssues) * 100, 1) : 0,
            ];
        }

        // Formatted records list for DataTable & View details (filtered by status if requested)
        $recordsIssues = (!empty($status) && $status !== 'all') ? $filteredIssues->where('status', $status) : $filteredIssues;
        $recordsList = $recordsIssues->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'issue_type' => $item->issue_type,
                'is_municipal_wide' => (bool) $item->is_municipal_wide,
                'scope_text' => $item->scope_display_text,
                'barangay_ids' => $item->barangays->pluck('id')->toArray(),
                'barangay_names' => $item->barangays->pluck('name')->toArray(),
                'who_affected' => $item->who_affected,
                'details' => $item->details ?? '',
                'status' => $item->status,
                'priority' => $item->priority,
                'date_reported' => $item->date_reported ? $item->date_reported->format('Y-m-d') : '',
                'formatted_date' => $item->date_reported ? $item->date_reported->format('M. d, Y') : '',
                'action_taken' => $item->action_taken ?? '',
                'resolution_notes' => $item->resolution_notes ?? '',
            ];
        });

        return response()->json([
            'success' => true,
            'kpis' => [
                'total_issues' => $totalIssues,
                'ongoing_issues' => $ongoingIssues,
                'for_action_issues' => $forActionIssues,
                'resolved_issues' => $resolvedIssues,
                'urgent_issues' => $urgentIssues,
                'new_issues' => $newIssues,
                'top_barangay' => $topBarangay,
                'max_metric_value' => $maxMetricValue,
            ],
            'barangays' => $barangayMapData,
            'type_breakdown' => $overallTypeBreakdown,
            'status_breakdown' => $overallStatusBreakdown,
            'records' => $recordsList,
        ]);
    }

    /**
     * Store a newly created issue.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'issue_type' => 'required|string|in:Political,Community,Municipal-Wide,Policy,Infrastructure',
            'is_municipal_wide' => 'nullable|boolean',
            'barangay_ids' => 'nullable|array',
            'barangay_ids.*' => 'exists:barangays,id',
            'who_affected' => 'required|string|max:500',
            'details' => 'nullable|string|max:3000',
            'status' => 'required|string|in:New,Ongoing,For Action,Resolved',
            'priority' => 'required|string|in:Low,Medium,High,Urgent',
            'date_reported' => 'required|date',
            'action_taken' => 'nullable|string|max:2000',
        ]);

        $isMunicipalWide = $request->boolean('is_municipal_wide') || $validated['issue_type'] === 'Municipal-Wide';

        // If not municipal wide, ensure at least one barangay is specified
        if (!$isMunicipalWide && empty($validated['barangay_ids'])) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one affected barangay or mark the issue as Municipal-Wide.',
            ], 422);
        }

        $issue = Issue::create([
            'title' => $validated['title'],
            'issue_type' => $validated['issue_type'],
            'is_municipal_wide' => $isMunicipalWide,
            'who_affected' => $validated['who_affected'],
            'details' => $validated['details'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'date_reported' => $validated['date_reported'],
            'action_taken' => $validated['action_taken'] ?? null,
            'created_by' => auth()->id(),
        ]);

        if ($isMunicipalWide) {
            // Attach all barangays for quick foreign lookups
            $allBarangayIds = Barangay::pluck('id')->toArray();
            $issue->barangays()->sync($allBarangayIds);
        } else {
            $issue->barangays()->sync($validated['barangay_ids'] ?? []);
        }

        return response()->json([
            'success' => true,
            'message' => 'Issue encoded and recorded successfully!',
            'issue_id' => $issue->id,
        ]);
    }

    /**
     * Show a single issue for viewing or pre-filling edit modal.
     */
    public function show($id)
    {
        $issue = Issue::with('barangays')->find($id);

        if (!$issue) {
            return response()->json([
                'success' => false,
                'message' => 'Issue record not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'issue' => [
                'id' => $issue->id,
                'title' => $issue->title,
                'issue_type' => $issue->issue_type,
                'is_municipal_wide' => (bool) $issue->is_municipal_wide,
                'scope_text' => $issue->scope_display_text,
                'barangay_ids' => $issue->barangays->pluck('id')->toArray(),
                'barangay_names' => $issue->barangays->pluck('name')->toArray(),
                'who_affected' => $issue->who_affected,
                'details' => $issue->details ?? '',
                'status' => $issue->status,
                'priority' => $issue->priority,
                'date_reported' => $issue->date_reported ? $issue->date_reported->format('Y-m-d') : '',
                'formatted_date' => $issue->date_reported ? $issue->date_reported->format('F d, Y') : '',
                'action_taken' => $issue->action_taken ?? '',
                'resolution_notes' => $issue->resolution_notes ?? '',
                'created_at' => $issue->created_at ? $issue->created_at->format('M. d, Y h:i A') : '',
                'updated_at' => $issue->updated_at ? $issue->updated_at->format('M. d, Y h:i A') : '',
            ],
        ]);
    }

    /**
     * Update an existing issue.
     */
    public function update(Request $request, $id)
    {
        $issue = Issue::find($id);

        if (!$issue) {
            return response()->json([
                'success' => false,
                'message' => 'Issue record not found.',
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'issue_type' => 'required|string|in:Political,Community,Municipal-Wide,Policy,Infrastructure',
            'is_municipal_wide' => 'nullable|boolean',
            'barangay_ids' => 'nullable|array',
            'barangay_ids.*' => 'exists:barangays,id',
            'who_affected' => 'required|string|max:500',
            'details' => 'nullable|string|max:3000',
            'status' => 'required|string|in:New,Ongoing,For Action,Resolved',
            'priority' => 'required|string|in:Low,Medium,High,Urgent',
            'date_reported' => 'required|date',
            'action_taken' => 'nullable|string|max:2000',
            'resolution_notes' => 'nullable|string|max:2000',
        ]);

        $isMunicipalWide = $request->boolean('is_municipal_wide') || $validated['issue_type'] === 'Municipal-Wide';

        if (!$isMunicipalWide && empty($validated['barangay_ids'])) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one affected barangay or mark the issue as Municipal-Wide.',
            ], 422);
        }

        $issue->update([
            'title' => $validated['title'],
            'issue_type' => $validated['issue_type'],
            'is_municipal_wide' => $isMunicipalWide,
            'who_affected' => $validated['who_affected'],
            'details' => $validated['details'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'date_reported' => $validated['date_reported'],
            'action_taken' => $validated['action_taken'] ?? null,
            'resolution_notes' => $validated['resolution_notes'] ?? null,
        ]);

        if ($isMunicipalWide) {
            $allBarangayIds = Barangay::pluck('id')->toArray();
            $issue->barangays()->sync($allBarangayIds);
        } else {
            $issue->barangays()->sync($validated['barangay_ids'] ?? []);
        }

        return response()->json([
            'success' => true,
            'message' => 'Issue updated successfully!',
            'issue_id' => $issue->id,
        ]);
    }

    /**
     * Delete an issue.
     */
    public function destroy($id)
    {
        $issue = Issue::find($id);

        if (!$issue) {
            return response()->json([
                'success' => false,
                'message' => 'Issue record not found.',
            ], 404);
        }

        $title = $issue->title;
        $issue->delete();

        return response()->json([
            'success' => true,
            'message' => "Issue '{$title}' has been deleted successfully.",
        ]);
    }

    /**
     * Export issues to CSV based on current filters.
     */
    public function exportCsv(Request $request)
    {
        $barangayId = $request->input('barangay_id');
        $issueType = $request->input('issue_type');
        $status = $request->input('status');
        $priority = $request->input('priority');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $search = $request->input('search');

        $query = Issue::with('barangays');

        if (!empty($barangayId) && $barangayId !== 'all') {
            if ($barangayId === 'municipal_wide') {
                $query->where('is_municipal_wide', true);
            } else {
                $query->where(function ($q) use ($barangayId) {
                    $q->whereHas('barangays', function ($bq) use ($barangayId) {
                        $bq->where('barangays.id', $barangayId);
                    })->orWhere('is_municipal_wide', true);
                });
            }
        }

        if (!empty($issueType) && $issueType !== 'all') {
            $query->where('issue_type', $issueType);
        }

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        if (!empty($priority) && $priority !== 'all') {
            $query->where('priority', $priority);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('date_reported', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('date_reported', '<=', $dateTo);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('who_affected', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhere('action_taken', 'like', "%{$search}%")
                  ->orWhereHas('barangays', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $issues = $query->orderBy('date_reported', 'desc')->get();
        $fileName = 'Mariveles_Issues_Export_' . date('Y-m-d_His') . '.csv';

        $response = new StreamedResponse(function () use ($issues) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($handle, [
                'Issue ID',
                'Title / Specific Issue',
                'Type of Issue',
                'Scope / Affected Barangays',
                'Who Are Affected',
                'Other Details',
                'Status',
                'Priority',
                'Date Reported',
                'Action Taken / Progress',
                'Resolution Notes',
                'Date Encoded'
            ]);

            foreach ($issues as $item) {
                fputcsv($handle, [
                    'ISS-' . str_pad($item->id, 5, '0', STR_PAD_LEFT),
                    $item->title,
                    $item->issue_type,
                    $item->scope_display_text,
                    $item->who_affected,
                    $item->details ?? '',
                    $item->status,
                    $item->priority,
                    $item->date_reported ? $item->date_reported->format('Y-m-d') : '',
                    $item->action_taken ?? '',
                    $item->resolution_notes ?? '',
                    $item->created_at ? $item->created_at->format('Y-m-d H:i') : ''
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }
}
