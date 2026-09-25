<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barangay;
use App\Models\Event;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventController extends Controller
{
    /**
     * Display the Events Module page.
     */
    public function index(Request $request)
    {
        $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        if ($barangays->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'BarangaySeeder']);
            $barangays = Barangay::where('is_active', true)->orderBy('id')->get();
        }

        $eventTypes = ['Municipal', 'Barangay', 'Sectoral', 'Political', 'Others'];

        return view('admin.events.index', compact('barangays', 'eventTypes'));
    }

    /**
     * Get consolidated data, map metrics, breakdowns, upcoming list, and records via AJAX.
     */
    public function getData(Request $request)
    {
        $status = $request->input('status', 'all'); // 'all', 'Upcoming', 'Past'
        $barangayId = $request->input('barangay_id');
        $eventType = $request->input('event_type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $speechRequired = $request->input('speech_required');
        $attendanceStatus = $request->input('attendance_status');
        $search = $request->input('search');
        $metric = $request->input('metric', 'total_events'); // 'total_events', 'upcoming_events', 'past_events', 'actual_attendees', 'expected_attendees'

        // Base Query with filters (status, type, date range, speech required, attendance status, search)
        $query = Event::with('barangay');

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        if (!empty($eventType) && $eventType !== 'all') {
            $query->where('event_type', $eventType);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('event_datetime', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('event_datetime', '<=', $dateTo);
        }

        if ($speechRequired !== null && $speechRequired !== '' && $speechRequired !== 'all') {
            $query->where('speech_required', (bool) $speechRequired);
        }

        if (!empty($attendanceStatus) && $attendanceStatus !== 'all') {
            $query->where('attendance_status', $attendanceStatus);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('theme', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('who_invited', 'like', "%{$search}%")
                  ->orWhere('contact_info', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhere('request', 'like', "%{$search}%")
                  ->orWhere('custom_type', 'like', "%{$search}%")
                  ->orWhereHas('barangay', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Get all events matching filters across Mariveles (for map pins & municipal KPIs)
        $allEvents = (clone $query)->orderBy('event_datetime', 'desc')->get();

        // Municipal / Overall KPIs
        $totalEvents = $allEvents->count();
        $upcomingCount = $allEvents->where('status', 'Upcoming')->count();
        $pastCount = $allEvents->where('status', 'Past')->count();
        $totalExpected = (int) $allEvents->sum('expected_attendees');
        $totalActual = (int) $allEvents->sum('actual_attendees');
        $speechRequiredCount = $allEvents->where('speech_required', true)->count();

        // 18 Mariveles Barangays metrics (always complete for spatial map visualization)
        $allBarangays = Barangay::where('is_active', true)->orderBy('id')->get();
        $eventsByBarangay = $allEvents->groupBy('barangay_id');

        $standardTypes = ['Municipal', 'Barangay', 'Sectoral', 'Political', 'Others'];

        $barangayMapData = [];
        $topBarangay = null;
        $maxMetricValue = 0;

        foreach ($allBarangays as $bgy) {
            $bgyEvents = $eventsByBarangay->get($bgy->id, collect());

            $bgyTotal = $bgyEvents->count();
            $bgyUpcoming = $bgyEvents->where('status', 'Upcoming')->count();
            $bgyPast = $bgyEvents->where('status', 'Past')->count();
            $bgyExpected = (int) $bgyEvents->sum('expected_attendees');
            $bgyActual = (int) $bgyEvents->sum('actual_attendees');
            $bgySpeech = $bgyEvents->where('speech_required', true)->count();

            // Type Breakdown for this barangay
            $typeBreakdown = [];
            foreach ($standardTypes as $st) {
                $typeItems = $bgyEvents->where('event_type', $st);
                $typeBreakdown[$st] = [
                    'type' => $st,
                    'count' => $typeItems->count(),
                    'upcoming' => $typeItems->where('status', 'Upcoming')->count(),
                    'past' => $typeItems->where('status', 'Past')->count(),
                    'expected' => (int) $typeItems->sum('expected_attendees'),
                    'actual' => (int) $typeItems->sum('actual_attendees'),
                ];
            }

            // Determine active map metric value for color/intensity/tooltips
            $metricVal = 0;
            if ($metric === 'upcoming_events') {
                $metricVal = $bgyUpcoming;
            } elseif ($metric === 'past_events') {
                $metricVal = $bgyPast;
            } elseif ($metric === 'actual_attendees') {
                $metricVal = $bgyActual;
            } elseif ($metric === 'expected_attendees') {
                $metricVal = $bgyExpected;
            } else {
                $metricVal = $bgyTotal;
            }

            if ($metricVal > $maxMetricValue) {
                $maxMetricValue = $metricVal;
                $topBarangay = [
                    'id' => $bgy->id,
                    'name' => $bgy->name,
                    'metric_value' => $metricVal,
                    'total_events' => $bgyTotal,
                    'upcoming_events' => $bgyUpcoming,
                    'past_events' => $bgyPast,
                    'actual_attendees' => $bgyActual,
                    'expected_attendees' => $bgyExpected,
                ];
            }

            $barangayMapData[$bgy->id] = [
                'id' => $bgy->id,
                'name' => $bgy->name,
                'pin_x' => (float) $bgy->pin_x,
                'pin_y' => (float) $bgy->pin_y,
                'total_events' => $bgyTotal,
                'upcoming_events' => $bgyUpcoming,
                'past_events' => $bgyPast,
                'expected_attendees' => $bgyExpected,
                'actual_attendees' => $bgyActual,
                'speech_required_count' => $bgySpeech,
                'types' => $typeBreakdown,
            ];
        }

        // Overall Breakdown by Event Type (for table & chart)
        $overallTypeBreakdown = [];
        foreach ($standardTypes as $st) {
            $typeItems = $allEvents->where('event_type', $st);
            $typeCount = $typeItems->count();
            $typeUpcoming = $typeItems->where('status', 'Upcoming')->count();
            $typePast = $typeItems->where('status', 'Past')->count();
            $typeExpected = (int) $typeItems->sum('expected_attendees');
            $typeActual = (int) $typeItems->sum('actual_attendees');

            $overallTypeBreakdown[] = [
                'type' => $st,
                'count' => $typeCount,
                'upcoming' => $typeUpcoming,
                'past' => $typePast,
                'expected' => $typeExpected,
                'actual' => $typeActual,
                'percent_events' => $totalEvents > 0 ? round(($typeCount / $totalEvents) * 100, 1) : 0,
                'percent_attendance' => $totalActual > 0 ? round(($typeActual / $totalActual) * 100, 1) : 0,
            ];
        }

        // Formatted records list
        $recordsList = $allEvents->map(function ($item) {
            return [
                'id' => $item->id,
                'status' => $item->status,
                'attendance_status' => $item->attendance_status ?? 'For Confirmation',
                'barangay_id' => $item->barangay_id,
                'barangay_name' => $item->barangay ? $item->barangay->name : 'N/A',
                'name' => $item->name,
                'theme' => $item->theme ?? '',
                'event_datetime' => $item->event_datetime ? $item->event_datetime->format('Y-m-d H:i') : '',
                'formatted_datetime' => $item->event_datetime ? $item->event_datetime->format('M. d, Y h:i A') : '',
                'formatted_date' => $item->event_datetime ? $item->event_datetime->format('M. d, Y') : '',
                'formatted_time' => $item->event_datetime ? $item->event_datetime->format('h:i A') : '',
                'days_remaining' => $item->event_datetime && $item->status === 'Upcoming' ? Carbon::now()->diffInDays($item->event_datetime, false) : null,
                'venue' => $item->venue,
                'event_type' => $item->event_type,
                'custom_type' => $item->custom_type ?? '',
                'display_type' => $item->display_type,
                'who_invited' => $item->who_invited,
                'contact_info' => $item->contact_info ?? '',
                'details' => $item->details ?? '',
                'request' => $item->request ?? '',
                'speech_required' => (bool) $item->speech_required,
                'expected_attendees' => (int) $item->expected_attendees,
                'actual_attendees' => $item->actual_attendees !== null ? (int) $item->actual_attendees : null,
            ];
        });

        // Dedicated Upcoming Events highlights (ordered chronologically ascending)
        $upcomingHighlights = $allEvents
            ->where('status', 'Upcoming')
            ->sortBy('event_datetime')
            ->values()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'barangay_id' => $item->barangay_id,
                    'barangay_name' => $item->barangay ? $item->barangay->name : 'N/A',
                    'event_datetime' => $item->event_datetime ? $item->event_datetime->format('M. d, Y h:i A') : '',
                    'formatted_date' => $item->event_datetime ? $item->event_datetime->format('M. d, Y') : '',
                    'formatted_time' => $item->event_datetime ? $item->event_datetime->format('h:i A') : '',
                    'day_num' => $item->event_datetime ? $item->event_datetime->format('d') : '',
                    'month_str' => $item->event_datetime ? $item->event_datetime->format('M') : '',
                    'venue' => $item->venue,
                    'who_invited' => $item->who_invited,
                    'contact_info' => $item->contact_info ?? '',
                    'request' => $item->request ?? '',
                    'speech_required' => (bool) $item->speech_required,
                    'attendance_status' => $item->attendance_status ?? 'For Confirmation',
                    'expected_attendees' => (int) $item->expected_attendees,
                    'theme' => $item->theme ?? '',
                    'display_type' => $item->display_type,
                ];
            });

        return response()->json([
            'success' => true,
            'kpis' => [
                'total_events' => $totalEvents,
                'upcoming_events' => $upcomingCount,
                'past_events' => $pastCount,
                'expected_attendees' => $totalExpected,
                'actual_attendees' => $totalActual,
                'speech_required_count' => $speechRequiredCount,
                'top_barangay' => $topBarangay,
                'max_metric_value' => $maxMetricValue,
            ],
            'barangays' => $barangayMapData,
            'type_breakdown' => $overallTypeBreakdown,
            'records' => $recordsList,
            'upcoming_highlights' => $upcomingHighlights,
        ]);
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Upcoming,Past',
            'attendance_status' => 'nullable|string|in:Confirmed,Tentative,Declined,For Confirmation',
            'barangay_id' => 'required|exists:barangays,id',
            'name' => 'required|string|max:255',
            'event_datetime' => 'required|date',
            'venue' => 'required|string|max:255',
            'event_type' => 'required|string|in:Municipal,Barangay,Sectoral,Political,Others',
            'custom_type' => 'nullable|string|max:255',
            'who_invited' => 'required|string|max:255',
            'contact_info' => 'nullable|string|max:500',
            'details' => 'nullable|string|max:3000',
            'request' => 'nullable|string|max:1000',
            'speech_required' => 'required|boolean',
            'theme' => 'nullable|string|max:255',
            'expected_attendees' => 'required|integer|min:0',
            'actual_attendees' => 'nullable|integer|min:0',
        ]);

        if ($validated['event_type'] !== 'Others') {
            $validated['custom_type'] = null;
        }

        // If status is Past and actual_attendees is not set, default to expected or 0
        if ($validated['status'] === 'Past' && (!isset($validated['actual_attendees']) || $validated['actual_attendees'] === null)) {
            $validated['actual_attendees'] = $validated['expected_attendees'];
        }

        $validated['created_by'] = auth()->id();

        $event = Event::create($validated);
        $event->load('barangay');

        return response()->json([
            'success' => true,
            'message' => 'Event record created successfully!',
            'record' => $event,
        ]);
    }

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        $event = Event::with('barangay')->findOrFail($id);

        return response()->json([
            'success' => true,
            'record' => [
                'id' => $event->id,
                'status' => $event->status,
                'attendance_status' => $event->attendance_status ?? 'For Confirmation',
                'barangay_id' => $event->barangay_id,
                'barangay_name' => $event->barangay ? $event->barangay->name : '',
                'name' => $event->name,
                'event_datetime' => $event->event_datetime ? $event->event_datetime->format('Y-m-d\TH:i') : '',
                'formatted_datetime' => $event->event_datetime ? $event->event_datetime->format('M. d, Y h:i A') : '',
                'venue' => $event->venue,
                'event_type' => $event->event_type,
                'custom_type' => $event->custom_type ?? '',
                'display_type' => $event->display_type,
                'who_invited' => $event->who_invited,
                'contact_info' => $event->contact_info ?? '',
                'details' => $event->details ?? '',
                'request' => $event->request ?? '',
                'speech_required' => (bool) $event->speech_required,
                'theme' => $event->theme ?? '',
                'expected_attendees' => (int) $event->expected_attendees,
                'actual_attendees' => $event->actual_attendees !== null ? (int) $event->actual_attendees : null,
            ]
        ]);
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:Upcoming,Past',
            'attendance_status' => 'nullable|string|in:Confirmed,Tentative,Declined,For Confirmation',
            'barangay_id' => 'required|exists:barangays,id',
            'name' => 'required|string|max:255',
            'event_datetime' => 'required|date',
            'venue' => 'required|string|max:255',
            'event_type' => 'required|string|in:Municipal,Barangay,Sectoral,Political,Others',
            'custom_type' => 'nullable|string|max:255',
            'who_invited' => 'required|string|max:255',
            'contact_info' => 'nullable|string|max:500',
            'details' => 'nullable|string|max:3000',
            'request' => 'nullable|string|max:1000',
            'speech_required' => 'required|boolean',
            'theme' => 'nullable|string|max:255',
            'expected_attendees' => 'required|integer|min:0',
            'actual_attendees' => 'nullable|integer|min:0',
        ]);

        if ($validated['event_type'] !== 'Others') {
            $validated['custom_type'] = null;
        }

        $event->update($validated);
        $event->load('barangay');

        return response()->json([
            'success' => true,
            'message' => 'Event record updated successfully!',
            'record' => $event,
        ]);
    }

    /**
     * Change status from Upcoming to Past and encode Total Number Who Attended.
     * Retains original expected attendance for comparison.
     */
    public function markPast(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'actual_attendees' => 'required|integer|min:0',
            'attendance_status' => 'nullable|string|in:Confirmed,Tentative,Declined,For Confirmation',
            'details' => 'nullable|string|max:3000',
        ]);

        $event->status = 'Past';
        $event->actual_attendees = $validated['actual_attendees'];
        
        if (!empty($validated['attendance_status'])) {
            $event->attendance_status = $validated['attendance_status'];
        }
        if (!empty($validated['details'])) {
            $event->details = $validated['details'];
        }

        $event->save();
        $event->load('barangay');

        return response()->json([
            'success' => true,
            'message' => "Event '{$event->name}' marked as Past with {$event->actual_attendees} actual attendees recorded!",
            'record' => $event,
        ]);
    }

    /**
     * Remove the specified event.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event record deleted successfully!',
        ]);
    }

    /**
     * Export filtered events to CSV.
     */
    public function exportCsv(Request $request)
    {
        $status = $request->input('status', 'all');
        $barangayId = $request->input('barangay_id');
        $eventType = $request->input('event_type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $speechRequired = $request->input('speech_required');
        $search = $request->input('search');

        $query = Event::with('barangay');

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        if (!empty($barangayId) && $barangayId !== 'all') {
            $query->where('barangay_id', $barangayId);
        }

        if (!empty($eventType) && $eventType !== 'all') {
            $query->where('event_type', $eventType);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('event_datetime', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('event_datetime', '<=', $dateTo);
        }

        if ($speechRequired !== null && $speechRequired !== '' && $speechRequired !== 'all') {
            $query->where('speech_required', (bool) $speechRequired);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('theme', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('who_invited', 'like', "%{$search}%");
            });
        }

        $records = $query->orderBy('event_datetime', 'desc')->get();

        $filename = 'Events_Report_' . date('Y-m-d_His') . '.csv';

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
                'Status',
                'Date & Time',
                'Barangay',
                'Event Name',
                'Theme',
                'Event Type',
                'Venue',
                'Who Invited',
                'Contact Information',
                'Speech Required',
                'Attendance Status',
                'Expected Attendees',
                'Actual Attendees',
                'Request from Organizer',
                'Details / Notes',
            ]);

            foreach ($records as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->status,
                    $row->event_datetime ? $row->event_datetime->format('Y-m-d H:i') : '',
                    $row->barangay ? $row->barangay->name : '',
                    $row->name,
                    $row->theme ?? '',
                    $row->display_type,
                    $row->venue,
                    $row->who_invited,
                    $row->contact_info ?? '',
                    $row->speech_required ? 'YES' : 'NO',
                    $row->attendance_status ?? '',
                    $row->expected_attendees,
                    $row->actual_attendees !== null ? $row->actual_attendees : 'Pending (Upcoming)',
                    $row->request ?? '',
                    $row->details ?? '',
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
