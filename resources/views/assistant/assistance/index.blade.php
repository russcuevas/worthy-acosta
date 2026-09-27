@extends('layouts.app')

@section('title', 'Assistance Records - Assistant Portal')
@section('user_name', 'Assistant')
@section('user_role_label', 'Assistant Portal')
@section('user_initials', 'AS')

@section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        /* Assistant Portal Page Container */
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
            .stats-grid-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .stats-grid-4 {
                grid-template-columns: 1fr;
            }
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
            border-top: none !important;
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

        /* Type Badges */
        .type-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .type-pill-Financial { background: #DCFCE7; color: #166534; }
        .type-pill-Burial { background: #F3E8FF; color: #6B21A8; }
        .type-pill-Tent { background: #FEF3C7; color: #92400E; }
        .type-pill-ItemDonation { background: #E0E7FF; color: #3730A3; }
        .type-pill-Others { background: #F1F5F9; color: #334155; }

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
            max-width: 620px;
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

        /* View Modal Detail Specs */
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Assistance Records Management
                </h1>
                <p>Assistant Portal &bull; Fast records entry, tabular overview, and updates for public assistance</p>
            </div>
            <div class="header-actions-right">
                <button type="button" class="btn-action-primary" id="btnOpenAddModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Assistance Record
                </button>
            </div>
        </div>

        <!-- Filter Controls Row -->
        <div class="filter-controls-row">
            <div class="filter-group">
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
                    <label for="filterType">Assistance Type</label>
                    <select id="filterType" class="form-select-sm">
                        <option value="all">All Types</option>
                        @foreach ($assistanceTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
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
            <div class="stat-card-title">Total Records</div>
            <div class="stat-card-val" id="kpiTotalRecords">0</div>
            <div class="stat-card-sub">Assistance logged</div>
        </div>
        <div class="stat-card-clean accent-green">
            <div class="stat-card-title">Total Disbursed</div>
            <div class="stat-card-val" id="kpiTotalAmount">₱0.00</div>
            <div class="stat-card-sub">Disbursed amount (PHP)</div>
        </div>
        <div class="stat-card-clean accent-amber">
            <div class="stat-card-title">Total Beneficiaries</div>
            <div class="stat-card-val" id="kpiTotalBeneficiaries">0</div>
            <div class="stat-card-sub">Individuals / families</div>
        </div>
        <div class="stat-card-clean accent-navy">
            <div class="stat-card-title">Top Barangay</div>
            <div class="stat-card-val" id="kpiTopBarangay" style="font-size:1.25rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">None</div>
            <div class="stat-card-sub" id="kpiTopBarangaySub">Highest assistance share</div>
        </div>
    </div>

    <!-- 3. DataTable Card (No Maps - Pure Data Management) -->
    <div class="table-card-container">
        <div class="table-header-bar">
            <div class="table-header-title">
                Assistance Records List
                <span class="records-count-pill" id="recordsCountBadge">0 Records</span>
            </div>
        </div>

        <div class="table-responsive">
            <table id="recordsTable" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Barangay</th>
                        <th>Type</th>
                        <th>Assistance Given</th>
                        <th style="text-align: right;">Amount (₱)</th>
                        <th style="text-align: center;">Beneficiaries</th>
                        <th>Notes</th>
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
            <h3>Add Assistance Record</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#addRecordModalBackdrop">&times;</button>
        </div>
        <form id="addRecordForm">
            @csrf
            <div class="modal-body-custom">
                <div class="form-group-custom">
                    <label for="addBarangayId">Barangay *</label>
                    <select id="addBarangayId" name="barangay_id" class="form-control-custom" required>
                        <option value="" disabled selected>-- Select Barangay --</option>
                        @foreach ($barangays as $bgy)
                            <option value="{{ $bgy->id }}">Brgy. {{ $bgy->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="addType">Type of Assistance *</label>
                        <select id="addType" name="type" class="form-control-custom" required>
                            @foreach ($assistanceTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label for="addDate">Date Provided *</label>
                        <input type="date" id="addDate" name="date" class="form-control-custom" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="form-group-custom" id="addCustomTypeWrap" style="display: none;">
                    <label for="addCustomType">Custom Type Description *</label>
                    <input type="text" id="addCustomType" name="custom_type" class="form-control-custom" placeholder="e.g. Scholarship, Livelihood">
                </div>

                <div class="form-group-custom">
                    <label for="addAssistanceGiven">Assistance Given (Description / Name) *</label>
                    <input type="text" id="addAssistanceGiven" name="assistance_given" class="form-control-custom" placeholder="e.g. Medical Assistance, Rice Subsidy, Wake Tent" required>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="addAmount">Amount (PHP ₱) *</label>
                        <input type="number" step="0.01" min="0" id="addAmount" name="amount" class="form-control-custom" placeholder="0.00" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="addBeneficiaries">Beneficiaries Count *</label>
                        <input type="number" min="1" id="addBeneficiaries" name="beneficiaries_count" class="form-control-custom" value="1" required>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="addNotes">Notes / Remarks (Optional)</label>
                    <textarea id="addNotes" name="notes" class="form-control-custom" rows="2" placeholder="Optional notes or details..."></textarea>
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" data-close-modal="#addRecordModalBackdrop">Cancel</button>
                <button type="submit" class="btn-modal-save" id="btnSubmitAdd">Save Record</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. Edit Record Modal -->
<div class="modal-backdrop-custom" id="editRecordModalBackdrop">
    <div class="modal-dialog-custom">
        <div class="modal-header-custom">
            <h3>Edit Assistance Record</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#editRecordModalBackdrop">&times;</button>
        </div>
        <form id="editRecordForm">
            @csrf
            <input type="hidden" id="editRecordId" name="record_id">
            <div class="modal-body-custom">
                <div class="form-group-custom">
                    <label for="editBarangayId">Barangay *</label>
                    <select id="editBarangayId" name="barangay_id" class="form-control-custom" required>
                        @foreach ($barangays as $bgy)
                            <option value="{{ $bgy->id }}">Brgy. {{ $bgy->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="editType">Type of Assistance *</label>
                        <select id="editType" name="type" class="form-control-custom" required>
                            @foreach ($assistanceTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label for="editDate">Date Provided *</label>
                        <input type="date" id="editDate" name="date" class="form-control-custom" required>
                    </div>
                </div>

                <div class="form-group-custom" id="editCustomTypeWrap" style="display: none;">
                    <label for="editCustomType">Custom Type Description *</label>
                    <input type="text" id="editCustomType" name="custom_type" class="form-control-custom">
                </div>

                <div class="form-group-custom">
                    <label for="editAssistanceGiven">Assistance Given *</label>
                    <input type="text" id="editAssistanceGiven" name="assistance_given" class="form-control-custom" required>
                </div>

                <div class="form-row-2">
                    <div class="form-group-custom">
                        <label for="editAmount">Amount (PHP ₱) *</label>
                        <input type="number" step="0.01" min="0" id="editAmount" name="amount" class="form-control-custom" required>
                    </div>
                    <div class="form-group-custom">
                        <label for="editBeneficiaries">Beneficiaries Count *</label>
                        <input type="number" min="1" id="editBeneficiaries" name="beneficiaries_count" class="form-control-custom" required>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="editNotes">Notes / Remarks (Optional)</label>
                    <textarea id="editNotes" name="notes" class="form-control-custom" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" data-close-modal="#editRecordModalBackdrop">Cancel</button>
                <button type="submit" class="btn-modal-save" id="btnSubmitEdit">Update Record</button>
            </div>
        </form>
    </div>
</div>

<!-- 6. View Record Modal (Eye Icon) -->
<div class="modal-backdrop-custom" id="viewRecordModalBackdrop">
    <div class="modal-dialog-custom">
        <div class="modal-header-custom">
            <h3>Assistance Record Details</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#viewRecordModalBackdrop">&times;</button>
        </div>
        <div class="modal-body-custom">
            <div class="view-hero-badge">
                <div>
                    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; opacity:0.85;">Total Amount Disbursed</div>
                    <div style="font-size:1.6rem; font-weight:800;" id="viewHeroAmount">₱0.00</div>
                </div>
                <div id="viewHeroTypeBadge">
                    <span class="type-badge-pill type-pill-Financial" id="viewHeroType">Financial</span>
                </div>
            </div>

            <div class="view-detail-card">
                <div class="view-detail-row">
                    <span class="view-detail-label">Barangay</span>
                    <span class="view-detail-value" id="viewBarangay">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Date Provided</span>
                    <span class="view-detail-value" id="viewDate">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Assistance Given</span>
                    <span class="view-detail-value" id="viewAssistanceGiven" style="color:var(--color-primary-blue);">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Beneficiaries</span>
                    <span class="view-detail-value" id="viewBeneficiaries">--</span>
                </div>
                <div class="view-detail-row">
                    <span class="view-detail-label">Notes / Remarks</span>
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

<!-- 7. Delete Record Modal -->
<div class="modal-backdrop-custom" id="deleteModalBackdrop">
    <div class="modal-dialog-custom" style="max-width: 440px;">
        <div class="modal-header-custom">
            <h3 style="color:#DC2626;">Confirm Deletion</h3>
            <button type="button" class="modal-close-btn" data-close-modal="#deleteModalBackdrop">&times;</button>
        </div>
        <div class="modal-body-custom">
            <p style="font-size:0.9rem; color:#334155; margin:0;">
                Are you sure you want to permanently delete this assistance record?
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

<!-- 8. Floating Bottom-Right Toast Notification -->
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

            // Toggle custom type input
            $('#addType').on('change', function() {
                $('#addCustomTypeWrap').toggle($(this).val() === 'Others');
            });
            $('#editType').on('change', function() {
                $('#editCustomTypeWrap').toggle($(this).val() === 'Others');
            });

            // Currency Formatter
            function formatPeso(amount) {
                return '₱' + Number(amount || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            // Load Module Data
            function loadAssistanceData() {
                const bgy = $('#filterBarangay').val();
                const type = $('#filterType').val();
                const dFrom = $('#filterDateFrom').val();
                const dTo = $('#filterDateTo').val();

                $.ajax({
                    url: "{{ route('assistant.assistance.data') }}",
                    method: 'GET',
                    data: {
                        barangay_id: bgy,
                        type: type,
                        date_from: dFrom,
                        date_to: dTo
                    },
                    success: function(res) {
                        if (!res.success) return;

                        // 1. Update KPIs
                        $('#kpiTotalRecords').text(Number(res.kpis.total_records || 0).toLocaleString());
                        $('#kpiTotalAmount').text(formatPeso(res.kpis.total_amount));
                        $('#kpiTotalBeneficiaries').text(Number(res.kpis.total_beneficiaries || 0).toLocaleString());

                        if (res.kpis.top_barangay) {
                            $('#kpiTopBarangay').text('Brgy. ' + res.kpis.top_barangay.name);
                            $('#kpiTopBarangaySub').text(`${res.kpis.top_barangay.count} records (${formatPeso(res.kpis.top_barangay.amount)})`);
                        } else {
                            $('#kpiTopBarangay').text('None');
                            $('#kpiTopBarangaySub').text('No data recorded');
                        }

                        // 2. Render Table
                        renderTable(res.records || []);
                    },
                    error: function() {
                        showAppToast('Failed to load assistance records', 'error');
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
                    const pillClass = 'type-pill-' + (r.type ? r.type.replace(/\s+/g, '') : 'Financial');
                    const tr = $(`
                        <tr>
                            <td data-order="${r.date}" style="white-space:nowrap; font-weight:600;">${r.formatted_date || r.date}</td>
                            <td><span class="table-bgy-pill">${r.barangay_name}</span></td>
                            <td><span class="type-badge-pill ${pillClass}">${r.display_type || r.type}</span></td>
                            <td style="font-weight:700; color:var(--color-deep-navy);">${r.assistance_given}</td>
                            <td style="text-align:right; font-weight:700;" data-order="${r.amount}">${formatPeso(r.amount)}</td>
                            <td style="text-align:center; font-weight:700;">
                                <span style="background:#F1F5F9; padding:2px 8px; border-radius:10px;">${Number(r.beneficiaries_count).toLocaleString()}</span>
                            </td>
                            <td style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:var(--text-muted); font-size:0.8rem;" title="${r.notes || ''}">
                                ${r.notes || '—'}
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
                                    <button type="button" class="btn-action-edit" onclick="openEditModal(${r.id})" title="Edit Record">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <button type="button" class="btn-action-delete" onclick="openDeleteModal(${r.id}, '${(r.assistance_given || '').replace(/'/g, "\\'")}', '${r.barangay_name}')" title="Delete Record">
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
                    columnDefs: [{ orderable: false, targets: [7] }],
                    language: {
                        search: "Search Records:",
                        searchPlaceholder: "Search any field...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ records",
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
                    url: `/assistant/assistance/record/${id}`,
                    method: 'GET',
                    success: function(res) {
                        if (!res.success) return;
                        const r = res.record;
                        $('#viewHeroAmount').text(formatPeso(r.amount));
                        $('#viewHeroType').text(r.type).attr('class', 'type-badge-pill type-pill-' + r.type.replace(/\s+/g, ''));
                        $('#viewBarangay').text('Brgy. ' + r.barangay_name);
                        $('#viewDate').text(r.date);
                        $('#viewAssistanceGiven').text(r.assistance_given);
                        $('#viewBeneficiaries').text(Number(r.beneficiaries_count).toLocaleString() + ' individuals / families');
                        $('#viewNotes').text(r.notes || 'No remarks provided');

                        $('#viewRecordModalBackdrop').css('display', 'flex').hide().fadeIn(150);
                    },
                    error: function() {
                        showAppToast('Failed to fetch record details', 'error');
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
                    url: `/assistant/assistance/record/${id}`,
                    method: 'GET',
                    success: function(res) {
                        if (!res.success) return;
                        const r = res.record;
                        $('#editRecordId').val(r.id);
                        $('#editBarangayId').val(r.barangay_id);
                        $('#editType').val(r.type);
                        $('#editDate').val(r.date);
                        $('#editCustomType').val(r.custom_type || '');
                        $('#editCustomTypeWrap').toggle(r.type === 'Others');
                        $('#editAssistanceGiven').val(r.assistance_given);
                        $('#editAmount').val(r.amount);
                        $('#editBeneficiaries').val(r.beneficiaries_count);
                        $('#editNotes').val(r.notes || '');

                        $('#editRecordModalBackdrop').css('display', 'flex').hide().fadeIn(150);
                    },
                    error: function() {
                        showAppToast('Failed to fetch record for editing', 'error');
                    }
                });
            };

            // Delete Modal
            window.openDeleteModal = function(id, name, bgy) {
                $('#deleteRecordId').val(id);
                $('#deleteRecordSummary').html(`<strong>${name}</strong><br>Barangay: ${bgy}`);
                $('#deleteModalBackdrop').css('display', 'flex').hide().fadeIn(150);
            };

            // Add Record Submit
            $('#addRecordForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmitAdd');
                btn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: "{{ route('assistant.assistance.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        btn.prop('disabled', false).text('Save Record');
                        if (res.success) {
                            $('#addRecordModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Assistance record added successfully!');
                            loadAssistanceData();
                        } else {
                            showAppToast(res.message || 'Failed to save record', 'error');
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text('Save Record');
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
                    url: `/assistant/assistance/update/${id}`,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        btn.prop('disabled', false).text('Update Record');
                        if (res.success) {
                            $('#editRecordModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Assistance record updated successfully!');
                            loadAssistanceData();
                        } else {
                            showAppToast(res.message || 'Failed to update record', 'error');
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text('Update Record');
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
                    url: `/assistant/assistance/delete/${id}`,
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        btn.prop('disabled', false).text('Delete Record');
                        if (res.success) {
                            $('#deleteModalBackdrop').fadeOut(150);
                            showAppToast(res.message || 'Assistance record deleted successfully!');
                            loadAssistanceData();
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
            $('#filterBarangay, #filterType, #filterDateFrom, #filterDateTo').on('change', function() {
                loadAssistanceData();
            });

            $('#btnResetFilters').on('click', function() {
                $('#filterBarangay').val('all');
                $('#filterType').val('all');
                $('#filterDateFrom').val('');
                $('#filterDateTo').val('');
                loadAssistanceData();
            });

            // Initial Load
            loadAssistanceData();
        });
    </script>
@endsection
