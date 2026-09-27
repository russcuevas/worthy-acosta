@extends('layouts.app')

@section('title', 'Survey - Assistant Portal')
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

        .stat-card-clean.accent-green::before { background: #10B981; }
        .stat-card-clean.accent-amber::before { background: #F59E0B; }
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

        /* Candidate Dot */
        .candidate-color-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            display: inline-block;
            flex-shrink: 0;
        }

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
            max-width: 600px;
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                    Survey & Preference Ratings
                </h1>
                <p>Assistant Portal &bull; Political surveys, candidate approval ratings, sample sizes, and methodology</p>
            </div>
            <div class="header-actions-right">
                <button type="button" class="btn-action-secondary" id="btnOpenPeriodModal">
                    New Survey Period
                </button>
                <button type="button" class="btn-action-primary" id="btnOpenAddModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Encode Survey Data
                </button>
            </div>
        </div>

        <!-- Filter Controls Row -->
        <div class="filter-controls-row">
            <div class="filter-group">
                <div class="filter-item">
                    <label for="filterPeriod">Survey Period</label>
                    <select id="filterPeriod" class="form-select-sm">
                        @foreach ($periods as $p)
                            <option value="{{ $p->id }}" {{ $loop->first ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->start_date->format('M d') }} - {{ $p->end_date->format('M d, Y') }})
                            </option>
                        @endforeach
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
                    <label for="filterCandidate">Candidate</label>
                    <select id="filterCandidate" class="form-select-sm">
                        <option value="all">All Candidates</option>
                        @foreach ($candidates as $c)
                            <option value="{{ $c->candidate_name }}">{{ $c->candidate_name }}</option>
                        @endforeach
                    </select>
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
            <div class="stat-card-title">Survey Leader</div>
            <div class="stat-card-val" id="kpiLeader" style="font-size:1.35rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">--</div>
            <div class="stat-card-sub" id="kpiLeaderSub">Overall rank leader</div>
        </div>
        <div class="stat-card-clean accent-green">
            <div class="stat-card-title">Average Rating</div>
            <div class="stat-card-val" id="kpiRating" style="color:#15803D;">0.0%</div>
            <div class="stat-card-sub" id="kpiRatingSub">Municipal survey share</div>
        </div>
        <div class="stat-card-clean accent-amber">
            <div class="stat-card-title">Lead Margin</div>
            <div class="stat-card-val" id="kpiMargin" style="color:#B45309;">0.0%</div>
            <div class="stat-card-sub" id="kpiMarginSub">Advantage over runner-up</div>
        </div>
        <div class="stat-card-clean accent-navy">
            <div class="stat-card-title">Sample Size</div>
            <div class="stat-card-val" id="kpiSample">0</div>
            <div class="stat-card-sub">Surveyed respondents</div>
        </div>
    </div>

    <!-- 3. DataTable Card (No Maps) -->
    <div class="table-card-container">
        <div class="table-header-bar">
            <div class="table-header-title">
                Survey Ratings List
                <span class="records-count-pill" id="recordsCountBadge">0 Records</span>
            </div>
        </div>

        <div class="table-responsive">
            <table id="recordsTable" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Barangay</th>
                        <th>Candidate</th>
                        <th style="text-align: right;">Rating (%)</th>
                        <th style="text-align: right;">Sample Size</th>
                        <th>Survey Period</th>
                        <th>Methodology / Notes</th>
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
            <h3>Encode Survey Rating</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#addRecordModalBackdrop">&times;</button>
        </div>
        <form id="addRecordForm">
            @csrf
            <div class="modal-body-custom">
                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="addPeriodId">Survey Period *</label>
                        <select id="addPeriodId" name="survey_period_id" class="form-control-custom" required>
                            @foreach ($periods as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label for="addBarangayId">Target Barangay *</label>
                        <select id="addBarangayId" name="barangay_id" class="form-control-custom" required>
                            <option value="" disabled selected>-- Select Barangay --</option>
                            <option value="all_barangays">★ Batch Encode across ALL 18 Barangays</option>
                            @foreach ($barangays as $bgy)
                                <option value="{{ $bgy->id }}">Brgy. {{ $bgy->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="addCandidateName">Candidate Name *</label>
                        <input type="text" id="addCandidateName" name="candidate_name" class="form-control-custom" placeholder="e.g. Mayor Juan Santos" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="addCandidateColor">Candidate Color *</label>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <input type="color" id="addCandidateColor" name="candidate_color" value="#075998" style="height:38px; width:48px; border:none; border-radius:6px; cursor:pointer;">
                            <input type="text" id="addCandidateColorHex" class="form-control-custom" value="#075998" style="flex:1;" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="addRating">Preference Rating (%) *</label>
                        <input type="number" step="0.1" min="0" max="100" id="addRating" name="rating" class="form-control-custom" placeholder="0.0" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="addSampleSize">Sample Size (Respondents)</label>
                        <input type="number" min="1" id="addSampleSize" name="sample_size" class="form-control-custom" value="100">
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="addMethodology">Methodology / Sampling</label>
                    <input type="text" id="addMethodology" name="methodology" class="form-control-custom" placeholder="e.g. Face-to-face cluster random sampling">
                </div>

                <div class="form-group-custom">
                    <label for="addNotes">Notes / Remarks</label>
                    <textarea id="addNotes" name="notes" class="form-control-custom" rows="2" placeholder="Key observations, pulse notes..."></textarea>
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" data-close-modal="#addRecordModalBackdrop">Cancel</button>
                <button type="submit" class="btn-modal-save" id="btnSubmitAdd">Save Rating</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. Add Period Modal -->
<div class="modal-backdrop-custom" id="addPeriodModalBackdrop">
    <div class="modal-dialog-custom">
        <div class="modal-header-custom">
            <h3>Add New Survey Period</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#addPeriodModalBackdrop">&times;</button>
        </div>
        <form id="addPeriodForm">
            @csrf
            <div class="modal-body-custom">
                <div class="form-group-custom">
                    <label for="periodName">Period Name *</label>
                    <input type="text" id="periodName" name="name" class="form-control-custom" placeholder="e.g. September 2026 Pulse Survey" required>
                </div>
                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="periodStartDate">Start Date *</label>
                        <input type="date" id="periodStartDate" name="start_date" class="form-control-custom" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group-custom">
                        <label for="periodEndDate">End Date *</label>
                        <input type="date" id="periodEndDate" name="end_date" class="form-control-custom" required value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                    </div>
                </div>
                <div class="form-group-custom">
                    <label for="periodSampleSize">Municipal Target Sample Size</label>
                    <input type="number" min="1" id="periodSampleSize" name="sample_size" class="form-control-custom" value="1800">
                </div>
                <div class="form-group-custom">
                    <label for="periodMethodology">Methodology Description</label>
                    <input type="text" id="periodMethodology" name="methodology" class="form-control-custom" placeholder="e.g. Stratified probability sampling across 18 barangays">
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" data-close-modal="#addPeriodModalBackdrop">Cancel</button>
                <button type="submit" class="btn-modal-save" id="btnSubmitPeriod">Create Period</button>
            </div>
        </form>
    </div>
</div>

<!-- 6. Edit Record Modal -->
<div class="modal-backdrop-custom" id="editRecordModalBackdrop">
    <div class="modal-dialog-custom">
        <div class="modal-header-custom">
            <h3>Edit Survey Record</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#editRecordModalBackdrop">&times;</button>
        </div>
        <form id="editRecordForm">
            @csrf
            <input type="hidden" id="editRecordId" name="record_id">
            <div class="modal-body-custom">
                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="editPeriodId">Survey Period *</label>
                        <select id="editPeriodId" name="survey_period_id" class="form-control-custom" required>
                            @foreach ($periods as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label for="editBarangayId">Barangay *</label>
                        <select id="editBarangayId" name="barangay_id" class="form-control-custom" required>
                            @foreach ($barangays as $bgy)
                                <option value="{{ $bgy->id }}">Brgy. {{ $bgy->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="editCandidateName">Candidate Name *</label>
                        <input type="text" id="editCandidateName" name="candidate_name" class="form-control-custom" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="editCandidateColor">Candidate Color *</label>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <input type="color" id="editCandidateColor" name="candidate_color" style="height:38px; width:48px; border:none; border-radius:6px; cursor:pointer;">
                            <input type="text" id="editCandidateColorHex" class="form-control-custom" style="flex:1;" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="editRating">Preference Rating (%) *</label>
                        <input type="number" step="0.1" min="0" max="100" id="editRating" name="rating" class="form-control-custom" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="editSampleSize">Sample Size</label>
                        <input type="number" min="1" id="editSampleSize" name="sample_size" class="form-control-custom">
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="editMethodology">Methodology</label>
                    <input type="text" id="editMethodology" name="methodology" class="form-control-custom">
                </div>

                <div class="form-group-custom">
                    <label for="editNotes">Notes / Remarks</label>
                    <textarea id="editNotes" name="notes" class="form-control-custom" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" data-close-modal="#editRecordModalBackdrop">Cancel</button>
                <button type="submit" class="btn-modal-save" id="btnSubmitEdit">Update Rating</button>
            </div>
        </form>
    </div>
</div>

<!-- 7. View Record Modal (Eye Icon) -->
<div class="modal-backdrop-custom" id="viewRecordModalBackdrop">
    <div class="modal-dialog-custom">
        <div class="modal-header-custom">
            <h3>Survey Rating Details</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#viewRecordModalBackdrop">&times;</button>
        </div>
        <div class="modal-body-custom">
            <div class="view-hero-badge">
                <div>
                    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; opacity:0.85;" id="viewHeroCandidate">Candidate</div>
                    <div style="font-size:1.6rem; font-weight:800;" id="viewHeroRating">0.0%</div>
                </div>
                <div>
                    <span class="candidate-color-dot" id="viewHeroColorDot" style="width:16px; height:16px; border:2px solid #FFFFFF;"></span>
                </div>
            </div>

            <div class="view-detail-card">
                <div class="view-detail-row">
                    <span class="view-detail-label">Barangay</span>
                    <span class="view-detail-value" id="viewBarangay">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Survey Period</span>
                    <span class="view-detail-value" id="viewPeriod">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Sample Size</span>
                    <span class="view-detail-value" id="viewSample">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Methodology</span>
                    <span class="view-detail-value" id="viewMethodology">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Notes & Remarks</span>
                    <span class="view-detail-value" id="viewNotes" style="font-weight:500; color:#475569; max-width:300px; text-align:right;">--</span>
                </div>
            </div>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-modal-cancel" data-close-modal="#viewRecordModalBackdrop">Close</button>
            <button type="button" class="btn-action-primary" id="btnEditFromView">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                </svg>
                Edit This Record
            </button>
        </div>
    </div>
</div>

<!-- 8. Delete Record Modal -->
<div class="modal-backdrop-custom" id="deleteModalBackdrop">
    <div class="modal-dialog-custom" style="max-width: 440px;">
        <div class="modal-header-custom">
            <h3 style="color:#DC2626;">Confirm Deletion</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#deleteModalBackdrop">&times;</button>
        </div>
        <div class="modal-body-custom">
            <p style="font-size:0.9rem; color:#334155; margin:0;">
                Are you sure you want to delete this survey record?
            </p>
            <div style="background:#FEF2F2; border:1px solid #FECACA; padding:12px 14px; border-radius:6px; font-size:0.84rem; color:#991B1B;" id="deleteRecordSummary">
                --
            </div>
        </div>
        <div class="modal-footer-custom">
            <input type="hidden" id="deleteRecordId">
            <button type="button" class="btn-modal-cancel" data-close-modal="#deleteModalBackdrop">Cancel</button>
            <button type="button" class="btn-action-delete" id="btnConfirmDelete" style="padding: 8px 18px;">Delete Record</button>
        </div>
    </div>
</div>

<!-- 9. Floating Bottom-Right Toast -->
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

            // Color picker synchronization
            $('#addCandidateColor').on('input', function() {
                $('#addCandidateColorHex').val(this.value);
            });
            $('#editCandidateColor').on('input', function() {
                $('#editCandidateColorHex').val(this.value);
            });

            // Modal Toggles
            $('[data-close-modal]').on('click', function() {
                const target = $(this).attr('data-close-modal');
                $(target).fadeOut(150);
            });

            $('#btnOpenAddModal').on('click', function() {
                $('#addRecordForm')[0].reset();
                $('#addCandidateColorHex').val('#075998');
                $('#addRecordModalBackdrop').css('display', 'flex').hide().fadeIn(150);
            });

            $('#btnOpenPeriodModal').on('click', function() {
                $('#addPeriodForm')[0].reset();
                $('#addPeriodModalBackdrop').css('display', 'flex').hide().fadeIn(150);
            });

            // Load Data
            function loadSurveyData() {
                const period = $('#filterPeriod').val();
                const bgy = $('#filterBarangay').val();
                const candidate = $('#filterCandidate').val();

                $.ajax({
                    url: "{{ route('assistant.survey.data') }}",
                    method: 'GET',
                    data: {
                        period_id: period,
                        barangay_id: bgy,
                        candidate: candidate
                    },
                    success: function(res) {
                        if (!res.success) return;

                        // 1. Update KPIs
                        if (res.kpis) {
                            $('#kpiLeader').text(res.kpis.leader_name || '--');
                            $('#kpiRating').text((res.kpis.leader_rating || '0.0') + '%');
                            $('#kpiMargin').text(res.kpis.leader_margin || '0.0%');
                            $('#kpiSample').text(Number(res.kpis.total_sample_size || 0).toLocaleString());
                        }

                        renderTable(res.records || []);
                    },
                    error: function() {
                        showAppToast('Failed to load survey records', 'error');
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
                    const tr = $(`
                        <tr>
                            <td><span class="table-bgy-pill">${r.barangay_name}</span></td>
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span class="candidate-color-dot" style="background:${r.candidate_color || '#075998'};"></span>
                                    <strong style="color:var(--color-deep-navy);">${r.candidate_name}</strong>
                                </div>
                            </td>
                            <td style="text-align:right; font-weight:800; color:var(--color-deep-navy);" data-order="${r.rating}">
                                ${Number(r.rating).toFixed(1)}%
                            </td>
                            <td style="text-align:right; font-weight:600;" data-order="${r.sample_size}">
                                ${Number(r.sample_size).toLocaleString()}
                            </td>
                            <td><span style="font-size:0.80rem; font-weight:600; color:#475569;">${r.period_name}</span></td>
                            <td style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:var(--text-muted); font-size:0.8rem;" title="${r.notes || r.methodology}">
                                ${r.notes || r.methodology || '—'}
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
                                    <button type="button" class="btn-action-edit" onclick="openEditModal(${r.id})" title="Edit Rating">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <button type="button" class="btn-action-delete" onclick="openDeleteModal(${r.id}, '${r.candidate_name.replace(/'/g, "\\'")}', '${r.barangay_name}')" title="Delete Rating">
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
                    order: [[0, 'asc']],
                    columnDefs: [{ orderable: false, targets: [6] }],
                    language: {
                        search: "Search Ratings:",
                        searchPlaceholder: "Search any candidate or barangay...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ ratings",
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
                    url: `/assistant/survey/record/${id}`,
                    method: 'GET',
                    success: function(res) {
                        if (!res.success) return;
                        const r = res.record;
                        $('#viewHeroCandidate').text(r.candidate_name);
                        $('#viewHeroRating').text(Number(r.rating).toFixed(1) + '%');
                        $('#viewHeroColorDot').css('background', r.candidate_color || '#075998');
                        $('#viewBarangay').text('Brgy. ' + r.barangay_name);
                        $('#viewPeriod').text(r.period_name);
                        $('#viewSample').text(Number(r.sample_size || 100).toLocaleString() + ' respondents');
                        $('#viewMethodology').text(r.methodology || 'Standard cluster sampling');
                        $('#viewNotes').text(r.notes || 'No remarks provided');

                        $('#viewRecordModalBackdrop').css('display', 'flex').hide().fadeIn(150);
                    },
                    error: function() {
                        showAppToast('Failed to fetch survey details', 'error');
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
                    url: `/assistant/survey/record/${id}`,
                    method: 'GET',
                    success: function(res) {
                        if (!res.success) return;
                        const r = res.record;
                        $('#editRecordId').val(r.id);
                        $('#editPeriodId').val(r.survey_period_id);
                        $('#editBarangayId').val(r.barangay_id);
                        $('#editCandidateName').val(r.candidate_name);
                        $('#editCandidateColor').val(r.candidate_color);
                        $('#editCandidateColorHex').val(r.candidate_color);
                        $('#editRating').val(r.rating);
                        $('#editSampleSize').val(r.sample_size);
                        $('#editMethodology').val(r.methodology || '');
                        $('#editNotes').val(r.notes || '');

                        $('#editRecordModalBackdrop').css('display', 'flex').hide().fadeIn(150);
                    },
                    error: function() {
                        showAppToast('Failed to fetch rating for editing', 'error');
                    }
                });
            };

            // Delete Modal
            window.openDeleteModal = function(id, name, bgy) {
                $('#deleteRecordId').val(id);
                $('#deleteRecordSummary').html(`<strong>${name}</strong> in <strong>Brgy. ${bgy}</strong>`);
                $('#deleteModalBackdrop').css('display', 'flex').hide().fadeIn(150);
            };

            // Add Rating Submit
            $('#addRecordForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmitAdd');
                btn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: "{{ route('assistant.survey.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        btn.prop('disabled', false).text('Save Rating');
                        if (res.success) {
                            $('#addRecordModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Survey rating recorded successfully!');
                            loadSurveyData();
                        } else {
                            showAppToast(res.message || 'Failed to save rating', 'error');
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text('Save Rating');
                        const err = xhr.responseJSON ? (xhr.responseJSON.message || 'Validation error') : 'Server error';
                        showAppToast(err, 'error');
                    }
                });
            });

            // Add Period Submit
            $('#addPeriodForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmitPeriod');
                btn.prop('disabled', true).text('Creating...');

                $.ajax({
                    url: "{{ route('assistant.survey.period.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        btn.prop('disabled', false).text('Create Period');
                        if (res.success) {
                            $('#addPeriodModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Survey period created successfully!');
                            // Add new option to period select
                            const opt = `<option value="${res.period.id}" selected>${res.period.name}</option>`;
                            $('#filterPeriod, #addPeriodId, #editPeriodId').append(opt);
                            $('#filterPeriod').val(res.period.id);
                            loadSurveyData();
                        } else {
                            showAppToast(res.message || 'Failed to create period', 'error');
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text('Create Period');
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
                    url: `/assistant/survey/update/${id}`,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        btn.prop('disabled', false).text('Update Rating');
                        if (res.success) {
                            $('#editRecordModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Survey rating updated successfully!');
                            loadSurveyData();
                        } else {
                            showAppToast(res.message || 'Failed to update rating', 'error');
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text('Update Rating');
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
                    url: `/assistant/survey/delete/${id}`,
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        btn.prop('disabled', false).text('Delete Record');
                        if (res.success) {
                            $('#deleteModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Survey record deleted successfully!');
                            loadSurveyData();
                        } else {
                            showAppToast(res.message || 'Failed to delete record', 'error');
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false).text('Delete Record');
                        showAppToast('Failed to delete record', 'error');
                    }
                });
            });

            // Filter Change Listeners
            $('#filterPeriod, #filterBarangay, #filterCandidate').on('change', function() {
                loadSurveyData();
            });

            $('#btnResetFilters').on('click', function() {
                $('#filterBarangay').val('all');
                $('#filterCandidate').val('all');
                loadSurveyData();
            });

            // Initial Load
            loadSurveyData();
        });
    </script>
@endsection
