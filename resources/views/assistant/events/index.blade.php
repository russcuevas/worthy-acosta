@extends('layouts.app')

@section('title', 'Events - Assistant Portal')
@section('user_name', 'Assistant')
@section('user_role_label', 'Assistant Portal')
@section('user_initials', 'AS')

@section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        .assistant-module-container {
            width: 100%;
            max-width: 100%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Controls Header */
        .assistant-controls-header {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .header-title-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            padding-bottom: 16px;
            border-bottom: 1px solid #EEF2F6;
        }

        .header-title-left h1 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-title-left p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin: 3px 0 0 0;
        }

        .header-actions-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-action-primary {
            background: linear-gradient(135deg, var(--color-primary-blue), #0b4575);
            color: #FFFFFF;
            border: none;
            border-radius: var(--radius-md);
            padding: 9px 18px;
            font-size: 0.86rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(7, 89, 152, 0.25);
            transition: all var(--transition-fast);
        }

        .btn-action-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(7, 89, 152, 0.35);
        }

        .btn-action-secondary {
            background: #F8FAFC;
            color: var(--color-deep-navy);
            border: 1.5px solid #CBD5E1;
            border-radius: var(--radius-md);
            padding: 8px 14px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all var(--transition-fast);
        }

        .btn-action-secondary:hover {
            background: #E2E8F0;
        }

        /* Filter Controls */
        .filter-controls-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            flex: 1;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .filter-item label {
            font-size: 0.72rem;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .form-select-sm, .form-input-sm {
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--color-deep-navy);
            outline: none;
            transition: all var(--transition-fast);
        }

        .form-select-sm:focus, .form-input-sm:focus {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.12);
        }

        /* Stat Cards */
        .stats-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        @media (max-width: 1024px) {
            .stats-grid-4 { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 600px) {
            .stats-grid-4 { grid-template-columns: 1fr; }
        }

        .stat-card-clean {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            position: relative;
            overflow: hidden;
        }

        .stat-card-clean::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--color-primary-blue);
        }

        .stat-card-clean.accent-amber::before { background: #F59E0B; }
        .stat-card-clean.accent-green::before { background: #10B981; }
        .stat-card-clean.accent-navy::before { background: var(--color-deep-navy); }

        .stat-card-title {
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
        }

        .stat-card-val {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            letter-spacing: -0.02em;
        }

        .stat-card-sub {
            font-size: 0.76rem;
            color: #64748B;
        }

        /* Table Card */
        .table-card-container {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 24px;
            margin-bottom: 24px;
        }

        .table-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 18px;
        }

        .table-header-title {
            font-size: 1.08rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .records-count-pill {
            font-size: 0.76rem;
            font-weight: 800;
            background: #EFF6FF;
            color: var(--color-primary-blue);
            padding: 2px 10px;
            border-radius: 999px;
            border: 1px solid #BFDBFE;
        }

        /* DataTables Custom Polish */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .dataTables_wrapper {
            font-family: inherit;
            color: var(--color-deep-navy);
            font-size: 0.84rem;
            width: 100%;
        }

        .dataTables_wrapper .dataTables_length {
            margin-bottom: 14px;
            font-weight: 700;
            color: var(--text-muted);
            float: left;
        }

        .dataTables_wrapper .dataTables_length select {
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            padding: 5px 24px 5px 10px;
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--color-deep-navy);
            outline: none;
            margin: 0 4px;
        }

        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 14px;
            font-weight: 700;
            color: var(--text-muted);
            float: right;
        }

        .dataTables_wrapper .dataTables_filter input {
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--color-deep-navy);
            outline: none;
            margin-left: 6px;
            width: 220px;
            transition: all var(--transition-fast);
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.12);
        }

        table.dataTable {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 8px;
            overflow: hidden;
        }

        table.dataTable thead th {
            background: #F8FAFC !important;
            color: var(--color-deep-navy) !important;
            font-weight: 800 !important;
            font-size: 0.78rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important;
            padding: 12px 14px !important;
            border-bottom: 1px solid #E2E8F0 !important;
        }

        table.dataTable tbody td {
            padding: 11px 14px !important;
            border-bottom: 1px solid #F1F5F9 !important;
            vertical-align: middle !important;
            font-size: 0.84rem !important;
        }

        table.dataTable tbody tr:hover td {
            background-color: #F8FAFC !important;
        }

        .dataTables_wrapper .dataTables_info {
            padding-top: 14px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
            float: left;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding-top: 12px;
            float: right;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #E2E8F0 !important;
            border-radius: 6px !important;
            background: #FFFFFF !important;
            color: var(--color-deep-navy) !important;
            font-weight: 700 !important;
            font-size: 0.80rem !important;
            padding: 5px 11px !important;
            cursor: pointer !important;
            transition: all var(--transition-fast) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #F1F5F9 !important;
            border-color: #CBD5E1 !important;
            color: var(--color-primary-blue) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #075998 !important;
            color: #FFFFFF !important;
            border-color: #075998 !important;
            box-shadow: 0 2px 6px rgba(7, 89, 152, 0.3) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            opacity: 0.45 !important;
            background: #F8FAFC !important;
            color: var(--text-muted) !important;
            border-color: #E2E8F0 !important;
            cursor: not-allowed !important;
        }

        /* Action Buttons */
        .actions-cell-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            justify-content: center;
        }

        .btn-action-view {
            background: #F1F5F9;
            color: var(--color-deep-navy);
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all var(--transition-fast);
        }

        .btn-action-view:hover {
            background: #E2E8F0;
            color: var(--color-primary-blue);
            border-color: #94A3B8;
        }

        .btn-action-edit {
            background: #EFF6FF;
            color: var(--color-primary-blue);
            border: 1px solid #BFDBFE;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all var(--transition-fast);
        }

        .btn-action-edit:hover {
            background: var(--color-primary-blue);
            color: #FFFFFF;
            border-color: var(--color-primary-blue);
        }

        .btn-action-delete {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all var(--transition-fast);
        }

        .btn-action-delete:hover {
            background: #DC2626;
            color: #FFFFFF;
            border-color: #DC2626;
        }

        /* Status & Type Pills */
        .event-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 800;
        }

        .status-Upcoming { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
        .status-Past { background: #F1F5F9; color: #64748B; border: 1px solid #CBD5E1; }

        .attendance-badge-pill {
            display: inline-flex;
            align-items: center;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .att-Confirmed { background: #DCFCE7; color: #15803D; }
        .att-Tentative { background: #FEF3C7; color: #B45309; }
        .att-Declined { background: #FEE2E2; color: #DC2626; }
        .att-ForConfirmation { background: #F1F5F9; color: #475569; }

        .table-bgy-pill {
            font-weight: 700;
            color: var(--color-deep-navy);
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.80rem;
        }

        /* Modals */
        .modal-backdrop-custom {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal-dialog-custom {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 660px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header-custom {
            padding: 18px 24px;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header-custom h3 {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin: 0;
        }

        .modal-close-btn {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            color: var(--text-muted);
            cursor: pointer;
        }

        .modal-close-btn:hover { color: #DC2626; }

        .modal-body-custom {
            padding: 22px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-group-custom {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group-custom label {
            font-size: 0.80rem;
            font-weight: 700;
            color: var(--color-deep-navy);
        }

        .form-control-custom {
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.86rem;
            color: var(--color-deep-navy);
            outline: none;
            transition: all var(--transition-fast);
        }

        .form-control-custom:focus {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.12);
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media (max-width: 500px) {
            .form-row-2 { grid-template-columns: 1fr; }
        }

        .modal-footer-custom {
            padding: 16px 24px;
            border-top: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-modal-cancel {
            background: #F1F5F9;
            color: var(--color-deep-navy);
            border: 1px solid #CBD5E1;
            border-radius: var(--radius-md);
            padding: 8px 16px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-modal-save {
            background: var(--color-primary-blue);
            color: #FFFFFF;
            border: none;
            border-radius: var(--radius-md);
            padding: 8px 20px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
        }

        /* View Modal Specs */
        .view-hero-badge {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #075998, #0F172A);
            color: #FFFFFF;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 4px;
        }

        .view-detail-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .view-detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 8px;
            border-bottom: 1px dashed #E2E8F0;
        }

        .view-detail-row:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }

        .view-detail-label {
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .view-detail-value {
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--color-deep-navy);
        }

        /* Bottom-Right Toast */
        .app-toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .app-toast {
            min-width: 300px;
            max-width: 420px;
            background: #FFFFFF;
            border-radius: 8px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #10B981;
            pointer-events: auto;
            transform: translateX(120%);
            opacity: 0;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .app-toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .app-toast.toast-error { border-left-color: #DC2626; }
        .app-toast.toast-info { border-left-color: #075998; }
        .app-toast-msg { font-size: 0.85rem; font-weight: 700; color: #1E293B; flex: 1; }
    </style>
@endsection

@section('content')
<div class="assistant-module-container">

    <!-- 1. Top Controls Header -->
    <div class="assistant-controls-header">
        <div class="header-title-bar">
            <div class="header-title-left">
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" style="color:var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    Events Management
                </h1>
                <p>Assistant Portal &bull; Schedules, invitations, attendance confirmations, and event logs</p>
            </div>
            <div class="header-actions-right">
                <button type="button" class="btn-action-primary" id="btnOpenAddModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add New Event
                </button>
            </div>
        </div>

        <!-- Filter Controls Row -->
        <div class="filter-controls-row">
            <div class="filter-group">
                <div class="filter-item">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus" class="form-select-sm">
                        <option value="all">All Events</option>
                        <option value="Upcoming">Upcoming Only</option>
                        <option value="Past">Past Only</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="filterBarangay">Barangay</label>
                    <select id="filterBarangay" class="form-select-sm">
                        <option value="all">All 18 Barangays</option>
                        @foreach ($barangays as $bgy)
                            <option value="{{ $bgy->id }}">Brgy. {{ $bgy->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label for="filterEventType">Event Type</label>
                    <select id="filterEventType" class="form-select-sm">
                        <option value="all">All Types</option>
                        @foreach ($eventTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label for="filterAttendanceStatus">Attendance</label>
                    <select id="filterAttendanceStatus" class="form-select-sm">
                        <option value="all">All Statuses</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="Tentative">Tentative</option>
                        <option value="Declined">Declined</option>
                        <option value="For Confirmation">For Confirmation</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="filterDateFrom">Date From</label>
                    <input type="date" id="filterDateFrom" class="form-input-sm">
                </div>
                <div class="filter-item">
                    <label for="filterDateTo">Date To</label>
                    <input type="date" id="filterDateTo" class="form-input-sm">
                </div>
                <div class="filter-item" style="align-self: flex-end;">
                    <button type="button" class="btn-action-secondary" id="btnResetFilters" style="padding: 6px 12px;">Reset</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Overview Stat Cards -->
    <div class="stats-grid-4">
        <div class="stat-card-clean">
            <div class="stat-card-title">Total Events</div>
            <div class="stat-card-val" id="kpiTotalEvents">0</div>
            <div class="stat-card-sub">Recorded in system</div>
        </div>
        <div class="stat-card-clean accent-amber">
            <div class="stat-card-title">Upcoming Events</div>
            <div class="stat-card-val" id="kpiUpcomingEvents" style="color:#B45309;">0</div>
            <div class="stat-card-sub">Scheduled on calendar</div>
        </div>
        <div class="stat-card-clean accent-green">
            <div class="stat-card-title">Past Events</div>
            <div class="stat-card-val" id="kpiPastEvents" style="color:#15803D;">0</div>
            <div class="stat-card-sub">Successfully concluded</div>
        </div>
        <div class="stat-card-clean accent-navy">
            <div class="stat-card-title">Actual Attendees</div>
            <div class="stat-card-val" id="kpiActualAttendees">0</div>
            <div class="stat-card-sub">Total participants logged</div>
        </div>
    </div>

    <!-- 3. DataTable Card (No Maps) -->
    <div class="table-card-container">
        <div class="table-header-bar">
            <div class="table-header-title">
                Events Schedule & History
                <span class="records-count-pill" id="recordsCountBadge">0 Records</span>
            </div>
        </div>

        <div class="table-responsive">
            <table id="recordsTable" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Event Name & Theme</th>
                        <th>Barangay & Venue</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Attendance</th>
                        <th style="text-align: center; width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="recordsTableBody">
                    <!-- Populated via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- 4. Add Record Modal -->
<div class="modal-backdrop-custom" id="addRecordModalBackdrop">
    <div class="modal-dialog-custom">
        <div class="modal-header-custom">
            <h3>Add New Event</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#addRecordModalBackdrop">&times;</button>
        </div>
        <form id="addRecordForm">
            @csrf
            <div class="modal-body-custom">
                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="addStatus">Event Status *</label>
                        <select id="addStatus" name="status" class="form-control-custom" required>
                            <option value="Upcoming" selected>Upcoming</option>
                            <option value="Past">Past</option>
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label for="addAttendanceStatus">Attendance Confirmation *</label>
                        <select id="addAttendanceStatus" name="attendance_status" class="form-control-custom">
                            <option value="For Confirmation" selected>For Confirmation</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Tentative">Tentative</option>
                            <option value="Declined">Declined</option>
                        </select>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="addName">Event Name *</label>
                    <input type="text" id="addName" name="name" class="form-control-custom" placeholder="e.g. Barangay Assembly 2026, Senior Citizens Day" required>
                </div>

                <div class="form-group-custom">
                    <label for="addTheme">Event Theme / Topic</label>
                    <input type="text" id="addTheme" name="theme" class="form-control-custom" placeholder="e.g. Pagkakaisa at Kaunlaran">
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="addBarangayId">Barangay *</label>
                        <select id="addBarangayId" name="barangay_id" class="form-control-custom" required>
                            <option value="" disabled selected>-- Select Barangay --</option>
                            @foreach ($barangays as $bgy)
                                <option value="{{ $bgy->id }}">Brgy. {{ $bgy->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label for="addVenue">Venue / Location *</label>
                        <input type="text" id="addVenue" name="venue" class="form-control-custom" placeholder="e.g. Covered Court, Plaza" required>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="addEventDatetime">Date & Time *</label>
                        <input type="datetime-local" id="addEventDatetime" name="event_datetime" class="form-control-custom" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="addEventType">Event Type *</label>
                        <select id="addEventType" name="event_type" class="form-control-custom" required>
                            @foreach ($eventTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group-custom" id="addCustomTypeWrap" style="display: none;">
                    <label for="addCustomType">Custom Type Description *</label>
                    <input type="text" id="addCustomType" name="custom_type" class="form-control-custom">
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="addWhoInvited">Who Invited (Organizer) *</label>
                        <input type="text" id="addWhoInvited" name="who_invited" class="form-control-custom" placeholder="e.g. Kapitan Santos, Parish Priest" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="addContactInfo">Organizer Contact Info</label>
                        <input type="text" id="addContactInfo" name="contact_info" class="form-control-custom" placeholder="Phone or email">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="addExpectedAttendees">Expected Attendees *</label>
                        <input type="number" min="0" id="addExpectedAttendees" name="expected_attendees" class="form-control-custom" value="100" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="addActualAttendees">Actual Attendees (Optional)</label>
                        <input type="number" min="0" id="addActualAttendees" name="actual_attendees" class="form-control-custom">
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="addSpeechRequired">Speech Required? *</label>
                    <select id="addSpeechRequired" name="speech_required" class="form-control-custom" required>
                        <option value="0">No Speech Required</option>
                        <option value="1">Yes, Speech Required</option>
                    </select>
                </div>

                <div class="form-group-custom">
                    <label for="addDetails">Details / Itinerary (Optional)</label>
                    <textarea id="addDetails" name="details" class="form-control-custom" rows="2" placeholder="Program schedule or special instructions..."></textarea>
                </div>

                <div class="form-group-custom">
                    <label for="addRequest">Specific Requests (e.g. Sound system, financial assistance)</label>
                    <input type="text" id="addRequest" name="request" class="form-control-custom">
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" data-close-modal="#addRecordModalBackdrop">Cancel</button>
                <button type="submit" class="btn-modal-save" id="btnSubmitAdd">Save Event</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. Edit Record Modal -->
<div class="modal-backdrop-custom" id="editRecordModalBackdrop">
    <div class="modal-dialog-custom">
        <div class="modal-header-custom">
            <h3>Edit Event Record</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#editRecordModalBackdrop">&times;</button>
        </div>
        <form id="editRecordForm">
            @csrf
            <input type="hidden" id="editRecordId" name="record_id">
            <div class="modal-body-custom">
                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="editStatus">Event Status *</label>
                        <select id="editStatus" name="status" class="form-control-custom" required>
                            <option value="Upcoming">Upcoming</option>
                            <option value="Past">Past</option>
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label for="editAttendanceStatus">Attendance Confirmation *</label>
                        <select id="editAttendanceStatus" name="attendance_status" class="form-control-custom">
                            <option value="For Confirmation">For Confirmation</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Tentative">Tentative</option>
                            <option value="Declined">Declined</option>
                        </select>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="editName">Event Name *</label>
                    <input type="text" id="editName" name="name" class="form-control-custom" required>
                </div>

                <div class="form-group-custom">
                    <label for="editTheme">Event Theme / Topic</label>
                    <input type="text" id="editTheme" name="theme" class="form-control-custom">
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="editBarangayId">Barangay *</label>
                        <select id="editBarangayId" name="barangay_id" class="form-control-custom" required>
                            @foreach ($barangays as $bgy)
                                <option value="{{ $bgy->id }}">Brgy. {{ $bgy->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label for="editVenue">Venue / Location *</label>
                        <input type="text" id="editVenue" name="venue" class="form-control-custom" required>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="editEventDatetime">Date & Time *</label>
                        <input type="datetime-local" id="editEventDatetime" name="event_datetime" class="form-control-custom" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="editEventType">Event Type *</label>
                        <select id="editEventType" name="event_type" class="form-control-custom" required>
                            @foreach ($eventTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group-custom" id="editCustomTypeWrap" style="display: none;">
                    <label for="editCustomType">Custom Type Description *</label>
                    <input type="text" id="editCustomType" name="custom_type" class="form-control-custom">
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="editWhoInvited">Who Invited (Organizer) *</label>
                        <input type="text" id="editWhoInvited" name="who_invited" class="form-control-custom" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="editContactInfo">Organizer Contact Info</label>
                        <input type="text" id="editContactInfo" name="contact_info" class="form-control-custom">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="editExpectedAttendees">Expected Attendees *</label>
                        <input type="number" min="0" id="editExpectedAttendees" name="expected_attendees" class="form-control-custom" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="editActualAttendees">Actual Attendees</label>
                        <input type="number" min="0" id="editActualAttendees" name="actual_attendees" class="form-control-custom">
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="editSpeechRequired">Speech Required? *</label>
                    <select id="editSpeechRequired" name="speech_required" class="form-control-custom" required>
                        <option value="0">No Speech Required</option>
                        <option value="1">Yes, Speech Required</option>
                    </select>
                </div>

                <div class="form-group-custom">
                    <label for="editDetails">Details / Itinerary (Optional)</label>
                    <textarea id="editDetails" name="details" class="form-control-custom" rows="2"></textarea>
                </div>

                <div class="form-group-custom">
                    <label for="editRequest">Specific Requests</label>
                    <input type="text" id="editRequest" name="request" class="form-control-custom">
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" data-close-modal="#editRecordModalBackdrop">Cancel</button>
                <button type="submit" class="btn-modal-save" id="btnSubmitEdit">Update Event</button>
            </div>
        </form>
    </div>
</div>

<!-- 6. View Record Modal (Eye Icon) -->
<div class="modal-backdrop-custom" id="viewRecordModalBackdrop">
    <div class="modal-dialog-custom">
        <div class="modal-header-custom">
            <h3>Event Details</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#viewRecordModalBackdrop">&times;</button>
        </div>
        <div class="modal-body-custom">
            <div class="view-hero-badge">
                <div>
                    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; opacity:0.85;" id="viewHeroDatetime">Date & Time</div>
                    <div style="font-size:1.35rem; font-weight:800;" id="viewHeroName">Event Title</div>
                </div>
                <div>
                    <span class="event-status-pill status-Upcoming" id="viewHeroStatus">Upcoming</span>
                </div>
            </div>

            <div class="view-detail-card">
                <div class="view-detail-row">
                    <span class="view-detail-label">Barangay & Venue</span>
                    <span class="view-detail-value" id="viewVenue">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Event Type</span>
                    <span class="view-detail-value" id="viewType">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Attendance Status</span>
                    <span class="view-detail-value" id="viewAttendanceStatus">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Invited By</span>
                    <span class="view-detail-value" id="viewWhoInvited">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Speech Required</span>
                    <span class="view-detail-value" id="viewSpeech">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Expected / Actual</span>
                    <span class="view-detail-value" id="viewAttendees">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Theme / Topic</span>
                    <span class="view-detail-value" id="viewTheme" style="font-weight:500;">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Specific Request</span>
                    <span class="view-detail-value" id="viewRequest" style="font-weight:500; color:var(--color-primary-blue);">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Details / Notes</span>
                    <span class="view-detail-value" id="viewDetails" style="font-weight:500; color:#475569; max-width:320px; text-align:right;">--</span>
                </div>
            </div>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-modal-cancel" data-close-modal="#viewRecordModalBackdrop">Close</button>
            <button type="button" class="btn-action-primary" id="btnEditFromView">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                </svg>
                Edit This Event
            </button>
        </div>
    </div>
</div>

<!-- 7. Delete Record Modal -->
<div class="modal-backdrop-custom" id="deleteModalBackdrop">
    <div class="modal-dialog-custom" style="max-width: 440px;">
        <div class="modal-header-custom">
            <h3 style="color:#DC2626;">Confirm Deletion</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#deleteModalBackdrop">&times;</button>
        </div>
        <div class="modal-body-custom">
            <p style="font-size:0.9rem; color:#334155; margin:0;">
                Are you sure you want to delete this event record?
            </p>
            <div style="background:#FEF2F2; border:1px solid #FECACA; padding:12px 14px; border-radius:6px; font-size:0.84rem; color:#991B1B;" id="deleteRecordSummary">
                --
            </div>
        </div>
        <div class="modal-footer-custom">
            <input type="hidden" id="deleteRecordId">
            <button type="button" class="btn-modal-cancel" data-close-modal="#deleteModalBackdrop">Cancel</button>
            <button type="button" class="btn-action-delete" id="btnConfirmDelete" style="padding: 8px 18px;">Delete Event</button>
        </div>
    </div>
</div>

<!-- 8. Floating Bottom-Right Toast -->
<div class="app-toast-container" id="toastContainer"></div>
@endsection

@section('scripts')
    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            let dataTableInstance = null;
            let currentActiveRecordId = null;

            // Toast helper
            window.showAppToast = function(message, type = 'success') {
                const container = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                toast.className = `app-toast toast-${type}`;
                
                let iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="${type === 'error' ? '#DC2626' : '#10B981'}"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>`;
                if (type === 'error') {
                    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#DC2626"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>`;
                }

                toast.innerHTML = `${iconSvg}<div class="app-toast-msg">${message}</div>`;
                container.appendChild(toast);

                setTimeout(() => toast.classList.add('show'), 10);
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 400);
                }, 3500);
            };

            // Modal Toggles
            $('[data-close-modal]').on('click', function() {
                const target = $(this).attr('data-close-modal');
                $(target).fadeOut(150);
            });

            $('#btnOpenAddModal').on('click', function() {
                $('#addRecordForm')[0].reset();
                $('#addCustomTypeWrap').hide();
                $('#addRecordModalBackdrop').css('display', 'flex').hide().fadeIn(150);
            });

            $('#addEventType').on('change', function() {
                $('#addCustomTypeWrap').toggle($(this).val() === 'Others');
            });
            $('#editEventType').on('change', function() {
                $('#editCustomTypeWrap').toggle($(this).val() === 'Others');
            });

            $('#addEventDatetime').on('change', function() {
                if (this.value) {
                    const selected = new Date(this.value);
                    const now = new Date();
                    if (selected < now) {
                        $('#addStatus').val('Past');
                    } else {
                        $('#addStatus').val('Upcoming');
                    }
                }
            });

            $('#editEventDatetime').on('change', function() {
                if (this.value) {
                    const selected = new Date(this.value);
                    const now = new Date();
                    if (selected < now) {
                        $('#editStatus').val('Past');
                    } else {
                        $('#editStatus').val('Upcoming');
                    }
                }
            });

            // Load Data
            function loadEventsData() {
                const status = $('#filterStatus').val();
                const bgy = $('#filterBarangay').val();
                const eType = $('#filterEventType').val();
                const attStatus = $('#filterAttendanceStatus').val();
                const dFrom = $('#filterDateFrom').val();
                const dTo = $('#filterDateTo').val();

                $.ajax({
                    url: "{{ route('assistant.events.data') }}",
                    method: 'GET',
                    data: {
                        status: status,
                        barangay_id: bgy,
                        event_type: eType,
                        attendance_status: attStatus,
                        date_from: dFrom,
                        date_to: dTo
                    },
                    success: function(res) {
                        if (!res.success) return;

                        // 1. Update KPIs
                        $('#kpiTotalEvents').text(Number(res.kpis.total_events || 0).toLocaleString());
                        $('#kpiUpcomingEvents').text(Number(res.kpis.upcoming_events || 0).toLocaleString());
                        $('#kpiPastEvents').text(Number(res.kpis.past_events || 0).toLocaleString());
                        $('#kpiActualAttendees').text(Number(res.kpis.actual_attendees || 0).toLocaleString());

                        renderTable(res.records || []);
                    },
                    error: function() {
                        showAppToast('Failed to load events', 'error');
                    }
                });
            }

            function renderTable(records) {
                $('#recordsCountBadge').text(`${records.length} Records`);

                if ($.fn.DataTable.isDataTable('#recordsTable')) {
                    $('#recordsTable').DataTable().clear().destroy();
                }

                const tbody = $('#recordsTableBody');
                tbody.empty();

                records.forEach(r => {
                    const attClass = 'att-' + (r.attendance_status || 'ForConfirmation').replace(/\s+/g, '');
                    const tr = $(`
                        <tr>
                            <td data-order="${r.event_datetime}" style="white-space:nowrap; font-weight:700; color:var(--color-deep-navy);">
                                ${r.formatted_datetime || r.event_datetime}
                            </td>
                            <td>
                                <div style="font-weight:700; color:var(--color-deep-navy);">${r.name}</div>
                                ${r.theme ? `<div style="font-size:0.75rem; color:#64748B;">${r.theme}</div>` : ''}
                            </td>
                            <td>
                                <div><span class="table-bgy-pill">${r.barangay_name}</span></div>
                                <div style="font-size:0.76rem; color:#64748B; margin-top:2px;">${r.venue}</div>
                            </td>
                            <td><span style="font-size:0.80rem; font-weight:600; color:#475569;">${r.display_type || r.event_type}</span></td>
                            <td>
                                <span class="event-status-pill status-${r.status}">
                                    ${r.status}
                                </span>
                            </td>
                            <td>
                                <span class="attendance-badge-pill ${attClass}">${r.attendance_status}</span>
                                <div style="font-size:0.74rem; color:#64748B; margin-top:2px;">by ${r.who_invited}</div>
                            </td>
                            <td>
                                <div class="actions-cell-wrap">
                                    <button type="button" class="btn-action-view" onclick="openViewModal(${r.id})" title="View Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        View
                                    </button>
                                    <button type="button" class="btn-action-edit" onclick="openEditModal(${r.id})" title="Edit Event">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <button type="button" class="btn-action-delete" onclick="openDeleteModal(${r.id}, '${r.name.replace(/'/g, "\\'")}', '${r.formatted_date || ''}')" title="Delete Event">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `);
                    tbody.append(tr);
                });

                dataTableInstance = $('#recordsTable').DataTable({
                    pagingType: 'full_numbers',
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    order: [[0, 'desc']],
                    columnDefs: [{ orderable: false, targets: [6] }],
                    language: {
                        search: "Search Events:",
                        searchPlaceholder: "Search any name or venue...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ events",
                        paginate: {
                            first: "«",
                            previous: "‹",
                            next: "›",
                            last: "»"
                        }
                    }
                });
            }

            // View Details Modal (Eye icon)
            window.openViewModal = function(id) {
                currentActiveRecordId = id;
                $.ajax({
                    url: `/assistant/events/record/${id}`,
                    method: 'GET',
                    success: function(res) {
                        if (!res.success) return;
                        const r = res.record;
                        $('#viewHeroName').text(r.name);
                        $('#viewHeroDatetime').text(r.formatted_datetime);
                        $('#viewHeroStatus').text(r.status).attr('class', 'event-status-pill status-' + r.status);
                        $('#viewVenue').text(`Brgy. ${r.barangay_name} (${r.venue})`);
                        $('#viewType').text(r.display_type || r.event_type);
                        $('#viewAttendanceStatus').text(r.attendance_status);
                        $('#viewWhoInvited').text(r.who_invited + (r.contact_info ? ' (' + r.contact_info + ')' : ''));
                        $('#viewSpeech').text(r.speech_required ? 'Yes, Speech Required' : 'No Speech Required');
                        $('#viewAttendees').text(`Expected: ${r.expected_attendees || 0} | Actual: ${r.actual_attendees !== null ? r.actual_attendees : 'Pending'}`);
                        $('#viewTheme').text(r.theme || 'None');
                        $('#viewRequest').text(r.request || 'None requested');
                        $('#viewDetails').text(r.details || 'No additional details provided');

                        $('#viewRecordModalBackdrop').css('display', 'flex').hide().fadeIn(150);
                    },
                    error: function() {
                        showAppToast('Failed to fetch event details', 'error');
                    }
                });
            };

            $('#btnEditFromView').on('click', function() {
                $('#viewRecordModalBackdrop').fadeOut(100);
                if (currentActiveRecordId) {
                    openEditModal(currentActiveRecordId);
                }
            });

            // Edit Modal
            window.openEditModal = function(id) {
                currentActiveRecordId = id;
                $.ajax({
                    url: `/assistant/events/record/${id}`,
                    method: 'GET',
                    success: function(res) {
                        if (!res.success) return;
                        const r = res.record;
                        $('#editRecordId').val(r.id);
                        $('#editStatus').val(r.status);
                        $('#editAttendanceStatus').val(r.attendance_status);
                        $('#editName').val(r.name);
                        $('#editTheme').val(r.theme || '');
                        $('#editBarangayId').val(r.barangay_id);
                        $('#editVenue').val(r.venue);
                        $('#editEventDatetime').val(r.event_datetime);
                        $('#editEventType').val(r.event_type);
                        $('#editCustomType').val(r.custom_type || '');
                        $('#editCustomTypeWrap').toggle(r.event_type === 'Others');
                        $('#editWhoInvited').val(r.who_invited);
                        $('#editContactInfo').val(r.contact_info || '');
                        $('#editExpectedAttendees').val(r.expected_attendees);
                        $('#editActualAttendees').val(r.actual_attendees !== null ? r.actual_attendees : '');
                        $('#editSpeechRequired').val(r.speech_required ? '1' : '0');
                        $('#editDetails').val(r.details || '');
                        $('#editRequest').val(r.request || '');

                        $('#editRecordModalBackdrop').css('display', 'flex').hide().fadeIn(150);
                    },
                    error: function() {
                        showAppToast('Failed to fetch event for editing', 'error');
                    }
                });
            };

            // Delete Modal
            window.openDeleteModal = function(id, name, dt) {
                $('#deleteRecordId').val(id);
                $('#deleteRecordSummary').html(`<strong>${name}</strong><br>Date: ${dt}`);
                $('#deleteModalBackdrop').css('display', 'flex').hide().fadeIn(150);
            };

            // Add Record Submit
            $('#addRecordForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmitAdd');
                btn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: "{{ route('assistant.events.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        btn.prop('disabled', false).text('Save Event');
                        if (res.success) {
                            $('#addRecordModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Event created successfully!');
                            loadEventsData();
                        } else {
                            showAppToast(res.message || 'Failed to save event', 'error');
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text('Save Event');
                        const err = xhr.responseJSON ? (xhr.responseJSON.message || 'Validation error') : 'Server error';
                        showAppToast(err, 'error');
                    }
                });
            });

            // Edit Record Submit
            $('#editRecordForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#editRecordId').val();
                const btn = $('#btnSubmitEdit');
                btn.prop('disabled', true).text('Updating...');

                $.ajax({
                    url: `/assistant/events/update/${id}`,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        btn.prop('disabled', false).text('Update Event');
                        if (res.success) {
                            $('#editRecordModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Event updated successfully!');
                            loadEventsData();
                        } else {
                            showAppToast(res.message || 'Failed to update event', 'error');
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text('Update Event');
                        const err = xhr.responseJSON ? (xhr.responseJSON.message || 'Validation error') : 'Server error';
                        showAppToast(err, 'error');
                    }
                });
            });

            // Confirm Delete
            $('#btnConfirmDelete').on('click', function() {
                const id = $('#deleteRecordId').val();
                const btn = $(this);
                btn.prop('disabled', true).text('Deleting...');

                $.ajax({
                    url: `/assistant/events/delete/${id}`,
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        btn.prop('disabled', false).text('Delete Event');
                        if (res.success) {
                            $('#deleteModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Event deleted successfully!');
                            loadEventsData();
                        } else {
                            showAppToast(res.message || 'Failed to delete event', 'error');
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false).text('Delete Event');
                        showAppToast('Failed to delete event', 'error');
                    }
                });
            });

            // Filter Change Listeners
            $('#filterStatus, #filterBarangay, #filterEventType, #filterAttendanceStatus, #filterDateFrom, #filterDateTo').on('change', function() {
                loadEventsData();
            });

            $('#btnResetFilters').on('click', function() {
                $('#filterStatus').val('all');
                $('#filterBarangay').val('all');
                $('#filterEventType').val('all');
                $('#filterAttendanceStatus').val('all');
                $('#filterDateFrom').val('');
                $('#filterDateTo').val('');
                loadEventsData();
            });

            // Initial Load
            loadEventsData();
        });
    </script>
@endsection
