@extends('layouts.app')

@section('title', 'Issues - Assistant Portal')
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

        .stat-card-clean.accent-red::before { background: #DC2626; }
        .stat-card-clean.accent-amber::before { background: #F59E0B; }
        .stat-card-clean.accent-green::before { background: #10B981; }

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

        /* Priority & Status Pills */
        .priority-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .priority-Urgent { background: #FEE2E2; color: #DC2626; }
        .priority-High { background: #FFEDD5; color: #EA580C; }
        .priority-Medium { background: #FEF3C7; color: #D97706; }
        .priority-Low { background: #F1F5F9; color: #64748B; }

        .status-badge-pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 700;
        }

        .issue-status-New { background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; }
        .issue-status-Ongoing { background: #FEF3C7; color: #D97706; border: 1px solid #FDE68A; }
        .issue-status-ForAction { background: #F3E8FF; color: #7C3AED; border: 1px solid #DDD6FE; }
        .issue-status-Resolved { background: #DCFCE7; color: #16A34A; border: 1px solid #BBF7D0; }

        /* Scope Pills */
        .scope-badge-muni {
            background: #E0E7FF;
            color: #3730A3;
            font-weight: 800;
            font-size: 0.75rem;
            padding: 2px 7px;
            border-radius: 4px;
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

        /* Barangays Checkbox Grid */
        .bgy-checkbox-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            max-height: 150px;
            overflow-y: auto;
            background: #F8FAFC;
            padding: 10px;
            border: 1px solid #CBD5E1;
            border-radius: 6px;
        }

        .bgy-chk-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.80rem;
            color: #334155;
            cursor: pointer;
        }
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    Issues & Grievances Management
                </h1>
                <p>Assistant Portal &bull; Track community problems, citizen grievances, priorities, and resolutions</p>
            </div>
            <div class="header-actions-right">
                <button type="button" class="btn-action-primary" id="btnOpenAddModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Log New Issue
                </button>
            </div>
        </div>

        <!-- Filter Controls Row -->
        <div class="filter-controls-row">
            <div class="filter-group">
                <div class="filter-item">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus" class="form-select-sm">
                        <option value="all">All Statuses</option>
                        @foreach ($statuses as $st)
                            <option value="{{ $st }}">{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label for="filterIssueType">Issue Category</label>
                    <select id="filterIssueType" class="form-select-sm">
                        <option value="all">All Categories</option>
                        @foreach ($issueTypes as $it)
                            <option value="{{ $it }}">{{ $it }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label for="filterPriority">Priority</label>
                    <select id="filterPriority" class="form-select-sm">
                        <option value="all">All Priorities</option>
                        @foreach ($priorities as $pr)
                            <option value="{{ $pr }}">{{ $pr }}</option>
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
                <div class="filter-item" style="align-self: flex-end;">
                    <button type="button" class="btn-action-secondary" id="btnResetFilters" style="padding: 6px 12px;">Reset</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Overview Stat Cards -->
    <div class="stats-grid-4">
        <div class="stat-card-clean">
            <div class="stat-card-title">Total Issues</div>
            <div class="stat-card-val" id="kpiTotalIssues">0</div>
            <div class="stat-card-sub">Recorded across municipality</div>
        </div>
        <div class="stat-card-clean accent-red">
            <div class="stat-card-title">Urgent Issues</div>
            <div class="stat-card-val" id="kpiUrgentIssues" style="color:#DC2626;">0</div>
            <div class="stat-card-sub">Requiring immediate response</div>
        </div>
        <div class="stat-card-clean accent-amber">
            <div class="stat-card-title">Ongoing / For Action</div>
            <div class="stat-card-val" id="kpiOngoingIssues" style="color:#D97706;">0</div>
            <div class="stat-card-sub">Under active processing</div>
        </div>
        <div class="stat-card-clean accent-green">
            <div class="stat-card-title">Resolved Issues</div>
            <div class="stat-card-val" id="kpiResolvedIssues" style="color:#16A34A;">0</div>
            <div class="stat-card-sub">Successfully addressed</div>
        </div>
    </div>

    <!-- 3. DataTable Card (No Maps) -->
    <div class="table-card-container">
        <div class="table-header-bar">
            <div class="table-header-title">
                Issues Log & Status
                <span class="records-count-pill" id="recordsCountBadge">0 Records</span>
            </div>
        </div>

        <div class="table-responsive">
            <table id="recordsTable" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Date Reported</th>
                        <th>Issue Title</th>
                        <th>Scope / Location</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
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
            <h3>Log Community Issue</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#addRecordModalBackdrop">&times;</button>
        </div>
        <form id="addRecordForm">
            @csrf
            <div class="modal-body-custom">
                <div class="form-group-custom">
                    <label for="addTitle">Issue Title / Subject *</label>
                    <input type="text" id="addTitle" name="title" class="form-control-custom" placeholder="e.g. Unrepaired drainage in Sitio Ilaya" required>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="addIssueType">Category *</label>
                        <select id="addIssueType" name="issue_type" class="form-control-custom" required>
                            @foreach ($issueTypes as $it)
                                <option value="{{ $it }}">{{ $it }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label for="addPriority">Priority Level *</label>
                        <select id="addPriority" name="priority" class="form-control-custom" required>
                            @foreach ($priorities as $pr)
                                <option value="{{ $pr }}" {{ $pr === 'Medium' ? 'selected' : '' }}>{{ $pr }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="addStatus">Status *</label>
                        <select id="addStatus" name="status" class="form-control-custom" required>
                            @foreach ($statuses as $st)
                                <option value="{{ $st }}" {{ $st === 'New' ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label for="addDateReported">Date Reported *</label>
                        <input type="date" id="addDateReported" name="date_reported" class="form-control-custom" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="form-group-custom">
                    <label>
                        <input type="checkbox" id="addIsMunicipalWide" name="is_municipal_wide" value="1">
                        <strong>Municipal-Wide Issue (Affects All Barangays)</strong>
                    </label>
                </div>

                <div class="form-group-custom" id="addBarangaysWrap">
                    <label>Select Affected Barangay(s) *</label>
                    <div class="bgy-checkbox-grid">
                        @foreach ($barangays as $bgy)
                            <label class="bgy-chk-label">
                                <input type="checkbox" name="barangay_ids[]" value="{{ $bgy->id }}">
                                Brgy. {{ $bgy->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="addWhoAffected">Who is Affected (Sector / Community) *</label>
                    <input type="text" id="addWhoAffected" name="who_affected" class="form-control-custom" placeholder="e.g. Fisherfolk community, Residents near creek" required>
                </div>

                <div class="form-group-custom">
                    <label for="addDetails">Detailed Description of Issue</label>
                    <textarea id="addDetails" name="details" class="form-control-custom" rows="2" placeholder="Full details of what is happening..."></textarea>
                </div>

                <div class="form-group-custom">
                    <label for="addActionTaken">Action Taken (Optional)</label>
                    <textarea id="addActionTaken" name="action_taken" class="form-control-custom" rows="2" placeholder="Endorsed to Engineering Office, Inspection scheduled..."></textarea>
                </div>

                <div class="form-group-custom">
                    <label for="addResolutionNotes">Resolution Notes (If resolved)</label>
                    <textarea id="addResolutionNotes" name="resolution_notes" class="form-control-custom" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" data-close-modal="#addRecordModalBackdrop">Cancel</button>
                <button type="submit" class="btn-modal-save" id="btnSubmitAdd">Save Issue</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. Edit Record Modal -->
<div class="modal-backdrop-custom" id="editRecordModalBackdrop">
    <div class="modal-dialog-custom">
        <div class="modal-header-custom">
            <h3>Edit Issue Record</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#editRecordModalBackdrop">&times;</button>
        </div>
        <form id="editRecordForm">
            @csrf
            <input type="hidden" id="editRecordId" name="record_id">
            <div class="modal-body-custom">
                <div class="form-group-custom">
                    <label for="editTitle">Issue Title / Subject *</label>
                    <input type="text" id="editTitle" name="title" class="form-control-custom" required>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="editIssueType">Category *</label>
                        <select id="editIssueType" name="issue_type" class="form-control-custom" required>
                            @foreach ($issueTypes as $it)
                                <option value="{{ $it }}">{{ $it }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label for="editPriority">Priority Level *</label>
                        <select id="editPriority" name="priority" class="form-control-custom" required>
                            @foreach ($priorities as $pr)
                                <option value="{{ $pr }}">{{ $pr }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="editStatus">Status *</label>
                        <select id="editStatus" name="status" class="form-control-custom" required>
                            @foreach ($statuses as $st)
                                <option value="{{ $st }}">{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label for="editDateReported">Date Reported *</label>
                        <input type="date" id="editDateReported" name="date_reported" class="form-control-custom" required>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label>
                        <input type="checkbox" id="editIsMunicipalWide" name="is_municipal_wide" value="1">
                        <strong>Municipal-Wide Issue (Affects All Barangays)</strong>
                    </label>
                </div>

                <div class="form-group-custom" id="editBarangaysWrap">
                    <label>Select Affected Barangay(s)</label>
                    <div class="bgy-checkbox-grid" id="editBgyCheckboxes">
                        @foreach ($barangays as $bgy)
                            <label class="bgy-chk-label">
                                <input type="checkbox" name="barangay_ids[]" value="{{ $bgy->id }}" class="edit-bgy-chk" id="editBgy_{{ $bgy->id }}">
                                Brgy. {{ $bgy->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="editWhoAffected">Who is Affected *</label>
                    <input type="text" id="editWhoAffected" name="who_affected" class="form-control-custom" required>
                </div>

                <div class="form-group-custom">
                    <label for="editDetails">Detailed Description</label>
                    <textarea id="editDetails" name="details" class="form-control-custom" rows="2"></textarea>
                </div>

                <div class="form-group-custom">
                    <label for="editActionTaken">Action Taken</label>
                    <textarea id="editActionTaken" name="action_taken" class="form-control-custom" rows="2"></textarea>
                </div>

                <div class="form-group-custom">
                    <label for="editResolutionNotes">Resolution Notes</label>
                    <textarea id="editResolutionNotes" name="resolution_notes" class="form-control-custom" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" data-close-modal="#editRecordModalBackdrop">Cancel</button>
                <button type="submit" class="btn-modal-save" id="btnSubmitEdit">Update Issue</button>
            </div>
        </form>
    </div>
</div>

<!-- 6. View Record Modal (Eye Icon) -->
<div class="modal-backdrop-custom" id="viewRecordModalBackdrop">
    <div class="modal-dialog-custom">
        <div class="modal-header-custom">
            <h3>Issue Details</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#viewRecordModalBackdrop">&times;</button>
        </div>
        <div class="modal-body-custom">
            <div class="view-hero-badge">
                <div>
                    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; opacity:0.85;" id="viewHeroCategory">Category</div>
                    <div style="font-size:1.35rem; font-weight:800;" id="viewHeroTitle">Issue Subject</div>
                </div>
                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
                    <span class="priority-badge-pill priority-Urgent" id="viewHeroPriority">Urgent</span>
                    <span class="status-badge-pill issue-status-New" id="viewHeroStatus">New</span>
                </div>
            </div>

            <div class="view-detail-card">
                <div class="view-detail-row">
                    <span class="view-detail-label">Geographic Scope</span>
                    <span class="view-detail-value" id="viewScope">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Who is Affected</span>
                    <span class="view-detail-value" id="viewWhoAffected">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Date Reported</span>
                    <span class="view-detail-value" id="viewDateReported">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Details</span>
                    <span class="view-detail-value" id="viewDetails" style="font-weight:500; max-width:320px; text-align:right;">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Action Taken</span>
                    <span class="view-detail-value" id="viewActionTaken" style="font-weight:500; color:var(--color-primary-blue); max-width:320px; text-align:right;">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Resolution Notes</span>
                    <span class="view-detail-value" id="viewResolutionNotes" style="font-weight:500; color:#15803D; max-width:320px; text-align:right;">--</span>
                </div>
            </div>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-modal-cancel" data-close-modal="#viewRecordModalBackdrop">Close</button>
            <button type="button" class="btn-action-primary" id="btnEditFromView">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                </svg>
                Edit This Issue
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
                Are you sure you want to delete this issue record?
            </p>
            <div style="background:#FEF2F2; border:1px solid #FECACA; padding:12px 14px; border-radius:6px; font-size:0.84rem; color:#991B1B;" id="deleteRecordSummary">
                --
            </div>
        </div>
        <div class="modal-footer-custom">
            <input type="hidden" id="deleteRecordId">
            <button type="button" class="btn-modal-cancel" data-close-modal="#deleteModalBackdrop">Cancel</button>
            <button type="button" class="btn-action-delete" id="btnConfirmDelete" style="padding: 8px 18px;">Delete Issue</button>
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
                $('#addBarangaysWrap').show();
                $('#addRecordModalBackdrop').css('display', 'flex').hide().fadeIn(150);
            });

            // Municipal-wide checkbox toggle
            $('#addIsMunicipalWide').on('change', function() {
                $('#addBarangaysWrap').toggle(!this.checked);
            });
            $('#editIsMunicipalWide').on('change', function() {
                $('#editBarangaysWrap').toggle(!this.checked);
            });

            // Load Data
            function loadIssuesData() {
                const status = $('#filterStatus').val();
                const iType = $('#filterIssueType').val();
                const priority = $('#filterPriority').val();
                const bgy = $('#filterBarangay').val();

                $.ajax({
                    url: "{{ route('assistant.issues.data') }}",
                    method: 'GET',
                    data: {
                        status: status,
                        issue_type: iType,
                        priority: priority,
                        barangay_id: bgy
                    },
                    success: function(res) {
                        if (!res.success) return;

                        // 1. Update KPIs
                        $('#kpiTotalIssues').text(Number(res.kpis.total_issues || 0).toLocaleString());
                        $('#kpiUrgentIssues').text(Number(res.kpis.urgent_issues || 0).toLocaleString());
                        $('#kpiOngoingIssues').text(Number((res.kpis.ongoing_issues || 0) + (res.kpis.for_action_issues || 0)).toLocaleString());
                        $('#kpiResolvedIssues').text(Number(res.kpis.resolved_issues || 0).toLocaleString());

                        // Client-side filter for priority if set
                        let records = res.records || [];
                        if (priority !== 'all') {
                            records = records.filter(r => r.priority === priority);
                        }

                        renderTable(records);
                    },
                    error: function() {
                        showAppToast('Failed to load issues', 'error');
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
                    const statusClass = 'issue-status-' + (r.status || 'New').replace(/\s+/g, '');
                    const scopeHtml = r.is_municipal_wide 
                        ? `<span class="scope-badge-muni">Municipal-Wide</span>`
                        : (r.barangay_names && r.barangay_names.length > 0 
                            ? `<span class="table-bgy-pill">${r.barangay_names.join(', ')}</span>`
                            : `<span style="color:#94A3B8;">None specified</span>`);

                    const tr = $(`
                        <tr>
                            <td data-order="${r.date_reported}" style="white-space:nowrap; font-weight:700; color:var(--color-deep-navy);">
                                ${r.formatted_date || r.date_reported}
                            </td>
                            <td>
                                <div style="font-weight:700; color:var(--color-deep-navy);">${r.title}</div>
                                <div style="font-size:0.75rem; color:#64748B;">Affects: ${r.who_affected}</div>
                            </td>
                            <td>${scopeHtml}</td>
                            <td><span style="font-size:0.80rem; font-weight:600; color:#475569;">${r.issue_type}</span></td>
                            <td>
                                <span class="priority-badge-pill priority-${r.priority}">
                                    ${r.priority}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge-pill ${statusClass}">
                                    ${r.status}
                                </span>
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
                                    <button type="button" class="btn-action-edit" onclick="openEditModal(${r.id})" title="Edit Issue">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <button type="button" class="btn-action-delete" onclick="openDeleteModal(${r.id}, '${r.title.replace(/'/g, "\\'")}', '${r.issue_type}')" title="Delete Issue">
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
                        search: "Search Issues:",
                        searchPlaceholder: "Search any title, scope, or details...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ issues",
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
                    url: `/assistant/issues/record/${id}`,
                    method: 'GET',
                    success: function(res) {
                        if (!res.success) return;
                        const r = res.record;
                        $('#viewHeroTitle').text(r.title);
                        $('#viewHeroCategory').text(r.issue_type);
                        $('#viewHeroPriority').text(r.priority).attr('class', 'priority-badge-pill priority-' + r.priority);
                        $('#viewHeroStatus').text(r.status).attr('class', 'status-badge-pill issue-status-' + r.status.replace(/\s+/g, ''));
                        
                        $('#viewScope').text(r.is_municipal_wide ? 'Municipal-Wide (All 18 Barangays)' : (r.barangay_names && r.barangay_names.length ? r.barangay_names.join(', ') : 'Not specified'));
                        $('#viewWhoAffected').text(r.who_affected);
                        $('#viewDateReported').text(r.formatted_date || r.date_reported);
                        $('#viewDetails').text(r.details || 'None provided');
                        $('#viewActionTaken').text(r.action_taken || 'No action recorded yet');
                        $('#viewResolutionNotes').text(r.resolution_notes || 'Not resolved yet');

                        $('#viewRecordModalBackdrop').css('display', 'flex').hide().fadeIn(150);
                    },
                    error: function() {
                        showAppToast('Failed to fetch issue details', 'error');
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
                    url: `/assistant/issues/record/${id}`,
                    method: 'GET',
                    success: function(res) {
                        if (!res.success) return;
                        const r = res.record;
                        $('#editRecordId').val(r.id);
                        $('#editTitle').val(r.title);
                        $('#editIssueType').val(r.issue_type);
                        $('#editPriority').val(r.priority);
                        $('#editStatus').val(r.status);
                        $('#editDateReported').val(r.date_reported);
                        $('#editIsMunicipalWide').prop('checked', !!r.is_municipal_wide);
                        $('#editBarangaysWrap').toggle(!r.is_municipal_wide);

                        // Clear and re-check barangay checkboxes
                        $('.edit-bgy-chk').prop('checked', false);
                        if (r.barangay_ids && r.barangay_ids.length) {
                            r.barangay_ids.forEach(bId => {
                                $(`#editBgy_${bId}`).prop('checked', true);
                            });
                        }

                        $('#editWhoAffected').val(r.who_affected);
                        $('#editDetails').val(r.details || '');
                        $('#editActionTaken').val(r.action_taken || '');
                        $('#editResolutionNotes').val(r.resolution_notes || '');

                        $('#editRecordModalBackdrop').css('display', 'flex').hide().fadeIn(150);
                    },
                    error: function() {
                        showAppToast('Failed to fetch issue for editing', 'error');
                    }
                });
            };

            // Delete Modal
            window.openDeleteModal = function(id, title, type) {
                $('#deleteRecordId').val(id);
                $('#deleteRecordSummary').html(`<strong>${title}</strong><br>Category: ${type}`);
                $('#deleteModalBackdrop').css('display', 'flex').hide().fadeIn(150);
            };

            // Add Record Submit
            $('#addRecordForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmitAdd');
                btn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: "{{ route('assistant.issues.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        btn.prop('disabled', false).text('Save Issue');
                        if (res.success) {
                            $('#addRecordModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Issue logged successfully!');
                            loadIssuesData();
                        } else {
                            showAppToast(res.message || 'Failed to save issue', 'error');
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text('Save Issue');
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
                    url: `/assistant/issues/update/${id}`,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        btn.prop('disabled', false).text('Update Issue');
                        if (res.success) {
                            $('#editRecordModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Issue updated successfully!');
                            loadIssuesData();
                        } else {
                            showAppToast(res.message || 'Failed to update issue', 'error');
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text('Update Issue');
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
                    url: `/assistant/issues/delete/${id}`,
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        btn.prop('disabled', false).text('Delete Issue');
                        if (res.success) {
                            $('#deleteModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Issue deleted successfully!');
                            loadIssuesData();
                        } else {
                            showAppToast(res.message || 'Failed to delete issue', 'error');
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false).text('Delete Issue');
                        showAppToast('Failed to delete issue', 'error');
                    }
                });
            });

            // Filter Change Listeners
            $('#filterStatus, #filterIssueType, #filterPriority, #filterBarangay').on('change', function() {
                loadIssuesData();
            });

            $('#btnResetFilters').on('click', function() {
                $('#filterStatus').val('all');
                $('#filterIssueType').val('all');
                $('#filterPriority').val('all');
                $('#filterBarangay').val('all');
                loadIssuesData();
            });

            // Initial Load
            loadIssuesData();
        });
    </script>
@endsection
