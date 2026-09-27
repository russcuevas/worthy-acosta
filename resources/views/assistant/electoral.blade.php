@extends('layouts.app')

@section('title', 'Electoral Records Management - Assistant Portal')

@section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        /* Top Controls Header */
        .electoral-controls-header {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 18px 24px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .header-title-bar-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid #EEF2F6;
        }

        .header-title-left h1 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-title-left p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin: 4px 0 0 0;
        }

        .header-actions-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-encode-data {
            background: linear-gradient(135deg, var(--color-primary-blue), #0b4575);
            color: #FFFFFF;
            border: none;
            border-radius: var(--radius-md);
            padding: 9px 18px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 3px 10px rgba(7, 89, 152, 0.25);
            transition: all var(--transition-fast);
        }

        .btn-encode-data:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(7, 89, 152, 0.35);
        }

        /* Year Navigation & Ribbon */
        .electoral-year-bar-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .year-nav-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
        }

        .year-nav-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--color-deep-navy);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .year-pills-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .year-pill-btn {
            background: #F1F5F9;
            color: var(--text-muted);
            border: 1px solid #E2E8F0;
            border-radius: 999px;
            padding: 5px 14px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all var(--transition-fast);
        }

        .year-pill-btn:hover {
            background: #E2E8F0;
            color: var(--color-deep-navy);
        }

        .year-pill-btn.active {
            background: var(--color-deep-navy);
            color: #FFFFFF;
            border-color: var(--color-deep-navy);
            box-shadow: 0 2px 6px rgba(16, 42, 78, 0.25);
        }

        .year-tag-future {
            font-size: 0.65rem;
            background: #10B981;
            color: #FFFFFF;
            padding: 2px 6px;
            border-radius: 999px;
            text-transform: uppercase;
        }

        .year-actions-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-year-action {
            background: transparent;
            border: 1px dashed #CBD5E1;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.76rem;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all var(--transition-fast);
        }

        .btn-year-action:hover {
            background: #F8FAFC;
            border-color: var(--color-primary-blue);
            color: var(--color-primary-blue);
        }

        /* Filter Controls */
        .controls-sub-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding-top: 12px;
            border-top: 1px solid #EEF2F6;
        }

        .filter-controls-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-item-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--color-deep-navy);
            margin: 0;
            white-space: nowrap;
        }

        .custom-select-input {
            padding: 7px 12px;
            border: 1.5px solid #CBD5E1;
            border-radius: 6px;
            font-size: 0.84rem;
            color: var(--text-main);
            background: #FFFFFF;
            outline: none;
            cursor: pointer;
            transition: border-color var(--transition-fast);
        }

        .custom-select-input:focus {
            border-color: var(--color-primary-blue);
        }

        /* Stats Metric Cards */
        .electoral-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-card-clean {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            gap: 4px;
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

        .stat-card-clean.accent-green::before {
            background: #10B981;
        }

        .stat-card-clean.accent-navy::before {
            background: var(--color-deep-navy);
        }

        .stat-card-clean.accent-cyan::before {
            background: var(--color-wave-cyan);
        }

        .stat-card-title {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
        }

        .stat-card-val {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            letter-spacing: -0.02em;
        }

        .stat-card-sub {
            font-size: 0.76rem;
            color: #64748B;
        }

        /* Table Card Container */
        .table-card-container {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 24px;
            margin-bottom: 30px;
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
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* DataTables Custom Layout & Wrapper */
        .table-responsive {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            display: block;
            margin-bottom: 8px;
        }

        .dataTables_wrapper {
            font-family: inherit;
            color: var(--color-deep-navy);
            font-size: 0.84rem;
            width: 100%;
            max-width: 100%;
            position: relative;
            clear: both;
        }

        .dataTables_wrapper .dataTables_length {
            margin-bottom: 14px;
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--text-muted);
            float: left;
        }

        .dataTables_wrapper .dataTables_length select {
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            padding: 6px 28px 6px 12px;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--color-deep-navy);
            outline: none;
            cursor: pointer;
            margin: 0 6px;
            transition: all var(--transition-fast);
        }

        .dataTables_wrapper .dataTables_length select:focus {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.12);
        }

        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 14px;
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--text-muted);
            float: right;
        }

        .dataTables_wrapper .dataTables_filter input {
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--color-deep-navy);
            outline: none;
            margin-left: 8px;
            min-width: 240px;
            transition: all var(--transition-fast);
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.15);
        }

        /* Custom DataTables Table Structure (Matching Admin Modules) */
        table.dataTable, #electoralDataTable {
            width: 100% !important;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 10px !important;
            overflow: hidden !important;
            margin: 10px 0 16px 0 !important;
        }

        table.dataTable thead th, #electoralDataTable thead th {
            background: #F8FAFC !important;
            color: #475569 !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 0.74rem !important;
            letter-spacing: 0.05em !important;
            padding: 13px 14px !important;
            border-bottom: 1.5px solid #E2E8F0 !important;
            white-space: nowrap !important;
            user-select: none !important;
        }

        table.dataTable tbody td, #electoralDataTable tbody td {
            padding: 12px 14px !important;
            border-bottom: 1px solid #F1F5F9 !important;
            color: var(--color-deep-navy) !important;
            font-size: 0.85rem !important;
            vertical-align: middle !important;
        }

        table.dataTable tbody tr:hover td, #electoralDataTable tbody tr:hover td {
            background: #F8FAFC !important;
        }

        table.dataTable.no-footer, #electoralDataTable.no-footer {
            border-bottom: 1px solid #E2E8F0 !important;
        }

        /* Custom DataTables Footer & Pagination (Exact Match to Admin Assistance & Events) */
        .dataTables_wrapper .dataTables_info {
            padding-top: 14px !important;
            font-size: 0.82rem !important;
            font-weight: 700 !important;
            color: var(--text-muted) !important;
            float: left;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding-top: 12px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-end !important;
            gap: 4px !important;
            float: right;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            border: 1.5px solid #E2E8F0 !important;
            background: #F8FAFC !important;
            color: var(--color-deep-navy) !important;
            font-size: 0.80rem !important;
            font-weight: 700 !important;
            padding: 5px 12px !important;
            margin: 0 2px !important;
            cursor: pointer !important;
            transition: all var(--transition-fast) !important;
            box-shadow: none !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #EFF6FF !important;
            border-color: #BFDBFE !important;
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

        .barangay-cell-name {
            font-weight: 700;
            color: var(--color-deep-navy);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .barangay-id-badge {
            background: var(--color-mist-blue);
            color: var(--color-primary-blue);
            font-size: 0.74rem;
            font-weight: 800;
            width: 22px;
            height: 22px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .turnout-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .turnout-high {
            background: #DCFCE7;
            color: #15803D;
        }

        .turnout-mid {
            background: #FEF3C7;
            color: #B45309;
        }

        .turnout-low {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .winner-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
            font-size: 0.84rem;
        }

        .winner-color-dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            flex-shrink: 0;
        }

        .candidates-summary-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            max-width: 320px;
        }

        .c-chip-sm {
            background: #F1F5F9;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 0.74rem;
            color: #334155;
            white-space: nowrap;
        }

        .c-chip-sm strong {
            color: var(--color-deep-navy);
        }

        /* Actions Column */
        .actions-cell-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
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

        /* Modal Styles */
        .modal-backdrop-custom {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(16, 42, 78, 0.6);
            backdrop-filter: blur(4px);
            z-index: 3000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal-card-custom {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 620px;
            box-shadow: var(--shadow-lg);
            display: flex;
            flex-direction: column;
            max-height: 90vh;
            overflow: hidden;
            animation: modalFadeIn 0.25s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header-custom {
            padding: 16px 22px;
            border-bottom: 1px solid #EEF2F6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header-custom h2 {
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

        .modal-body-custom {
            padding: 20px 22px;
            overflow-y: auto;
            flex: 1;
        }

        .modal-footer-custom {
            padding: 14px 22px;
            border-top: 1px solid #EEF2F6;
            background: #F8FAFC;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .form-row-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .form-group-custom {
            margin-bottom: 14px;
        }

        .form-group-custom label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--color-deep-navy);
            margin-bottom: 6px;
        }

        .form-control-custom {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #CBD5E1;
            border-radius: 6px;
            font-size: 0.88rem;
            color: var(--text-main);
            outline: none;
            box-sizing: border-box;
            transition: border-color var(--transition-fast);
        }

        .form-control-custom:focus {
            border-color: var(--color-primary-blue);
        }

        .candidate-input-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .candidate-color-picker {
            width: 38px;
            height: 38px;
            padding: 2px;
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .btn-remove-candidate {
            background: transparent;
            border: 1px solid #FECACA;
            color: #DC2626;
            border-radius: 6px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: all var(--transition-fast);
        }

        .btn-remove-candidate:hover {
            background: #DC2626;
            color: #FFFFFF;
        }

        .btn-add-candidate-row {
            background: #EFF6FF;
            border: 1px dashed #93C5FD;
            color: var(--color-primary-blue);
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            margin-top: 6px;
            transition: all var(--transition-fast);
        }

        .btn-add-candidate-row:hover {
            background: #DBEAFE;
        }

        .btn-modal-cancel {
            background: #F1F5F9;
            color: var(--text-muted);
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            padding: 9px 16px;
            font-size: 0.86rem;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-modal-save {
            background: linear-gradient(135deg, var(--color-primary-blue), #0b4575);
            color: #FFFFFF;
            border: none;
            border-radius: 6px;
            padding: 9px 20px;
            font-size: 0.86rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(7, 89, 152, 0.25);
        }

        /* ==============================================================
           RESPONSIVENESS & MEDIA QUERIES (Exact Mobile & Tablet Polish)
           ============================================================== */
        @media (max-width: 1024px) {
            .electoral-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .electoral-controls-header {
                padding: 14px 16px;
            }

            .header-title-bar-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .header-actions-right {
                width: 100%;
            }

            .btn-encode-data {
                width: 100%;
                justify-content: center;
            }

            .electoral-year-bar-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .year-nav-group {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
            }

            .year-actions-wrap {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .controls-sub-row {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .filter-controls-group {
                flex-direction: column;
                align-items: stretch;
                width: 100%;
            }

            .filter-item-wrap {
                width: 100%;
                justify-content: space-between;
            }

            .custom-select-input {
                flex: 1;
            }

            .table-card-container {
                padding: 16px;
            }

            /* Responsive DataTables Controls */
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                float: none !important;
                width: 100% !important;
                text-align: left !important;
                margin-bottom: 12px !important;
            }

            .dataTables_wrapper .dataTables_filter {
                display: flex !important;
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 6px !important;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100% !important;
                min-width: 0 !important;
                margin-left: 0 !important;
                box-sizing: border-box !important;
            }

            .dataTables_wrapper .dataTables_info {
                float: none !important;
                text-align: center !important;
                padding-top: 10px !important;
                margin-bottom: 6px !important;
            }

            .dataTables_wrapper .dataTables_paginate {
                float: none !important;
                width: 100% !important;
                justify-content: center !important;
                flex-wrap: wrap !important;
                gap: 4px !important;
                padding-top: 8px !important;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 4px 9px !important;
                font-size: 0.76rem !important;
                margin: 1px !important;
            }

            .form-row-2col {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .candidate-input-row {
                flex-wrap: wrap;
                gap: 6px;
            }

            .candidate-input-row .form-control-custom:nth-child(1),
            .candidate-input-row .form-control-custom:nth-child(2) {
                flex: 1 1 130px;
            }

            .candidate-input-row .form-control-custom:nth-child(3) {
                flex: 1 1 80px;
            }
        }

        @media (max-width: 480px) {
            .electoral-stats-grid {
                grid-template-columns: 1fr;
            }

            .table-header-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
            }

            .year-pills-wrapper {
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-bottom: 6px;
                width: 100%;
                -webkit-overflow-scrolling: touch;
            }

            .year-pill-btn {
                flex-shrink: 0;
            }

            .modal-card-custom {
                max-width: 100%;
                margin: 8px;
            }
        }

        /* Quick Year Suggestion Buttons */
        .year-quick-suggestions {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            flex-wrap: wrap;
        }

        .year-suggest-chip {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            color: #1D4ED8;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.74rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .year-suggest-chip:hover {
            background: #DBEAFE;
        }

        /* Positions Checkboxes Grid (Matching Admin Electoral) */
        .positions-checklist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 8px;
            margin-top: 6px;
        }

        .pos-check-label {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            padding: 7px 10px;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--color-deep-navy);
            cursor: pointer;
            transition: all var(--transition-fast);
            user-select: none;
        }

        .pos-check-label:hover {
            background: #F1F5F9;
            border-color: #CBD5E1;
        }

        .pos-check-label input[type="checkbox"] {
            cursor: pointer;
            accent-color: var(--color-primary-blue);
        }
    </style>
@endsection

@section('content')
    <!-- 1. Top Controls Header -->
    <div class="electoral-controls-header">
        <div class="header-title-bar-row">
            <div class="header-title-left">
                <div>
                    <h1>
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                        Electoral Records Management
                    </h1>
                    <p>Official records encoding, editing, and voter turnout dataset for Mariveles, Bataan</p>
                </div>
            </div>

            <div class="header-actions-right">
                <button type="button" class="btn-encode-data" id="btnOpenAddRecordModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Encode / Add Election Data
                </button>
            </div>
        </div>

        <!-- Election Year Ribbon -->
        <div class="electoral-year-bar-row">
            <div class="year-nav-group">
                <div class="year-nav-label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span>Election Year:</span>
                </div>

                <div class="year-pills-wrapper" id="yearPillsContainer">
                    @foreach ($years as $yr)
                        <button type="button" class="year-pill-btn {{ $yr === $defaultYear ? 'active' : '' }}" data-year="{{ $yr }}">
                            <span>{{ $yr }}</span>
                            @if ((int)$yr > 2025)
                                <span class="year-tag-future">Future</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                <div class="year-actions-wrap">
                    <button type="button" class="btn-year-action" id="btnOpenAddYearModal" title="Add Future / Custom Year">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Add Year</span>
                    </button>
                    <button type="button" class="btn-year-action" id="btnOpenEditYearModal" title="Edit Positions in Current Year">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                        </svg>
                        <span>Edit Year</span>
                    </button>
                    <button type="button" class="btn-year-action" id="btnOpenDeleteYearModal" title="Delete Current Year" style="color: #DC2626; border-color: #FECACA;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Controls Sub-Row -->
        <div class="controls-sub-row">
            <div class="filter-controls-group">
                <div class="filter-item-wrap">
                    <label for="selectFilterPosition" class="filter-label">Position:</label>
                    <select id="selectFilterPosition" class="custom-select-input">
                        <!-- Populated dynamically based on active year -->
                    </select>
                </div>

                <div class="filter-item-wrap">
                    <label for="selectFilterBarangay" class="filter-label">Barangay:</label>
                    <select id="selectFilterBarangay" class="custom-select-input">
                        <option value="">-- All 18 Barangays --</option>
                        @foreach ($barangayNames as $bId => $bName)
                            <option value="{{ $bId }}">Brgy. {{ $bName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="font-size: 0.8rem; color: var(--text-muted);">
                Showing data for: <strong id="currentFilterLabel" style="color: var(--color-deep-navy);">{{ $defaultYear }} - {{ $defaultPosition }}</strong>
            </div>
        </div>
    </div>

    <!-- 2. Overview Metric Cards -->
    <div class="electoral-stats-grid">
        <div class="stat-card-clean accent-navy">
            <span class="stat-card-title">Total Registered Voters</span>
            <span class="stat-card-val" id="metricTotalReg">{{ number_format($totalRegistered) }}</span>
            <span class="stat-card-sub">Active electorate in selected scope</span>
        </div>

        <div class="stat-card-clean accent-cyan">
            <span class="stat-card-title">Actual Votes Cast</span>
            <span class="stat-card-val" id="metricTotalActual">{{ number_format($totalActual) }}</span>
            <span class="stat-card-sub">Total verified ballots counted</span>
        </div>

        <div class="stat-card-clean accent-green">
            <span class="stat-card-title">Overall Voter Turnout</span>
            <span class="stat-card-val" id="metricTurnout">{{ $overallTurnout }}%</span>
            <span class="stat-card-sub">Percentage of electorate participation</span>
        </div>

        <div class="stat-card-clean">
            <span class="stat-card-title">Barangays Encoded</span>
            <span class="stat-card-val" id="metricEncodedCount">{{ $encodedBarangaysCount }} / 18</span>
            <span class="stat-card-sub">Completed barangay records</span>
        </div>
    </div>

    <!-- 3. Electoral Data Table (No Map) -->
    <div class="table-card-container">
        <div class="table-header-bar">
            <div class="table-header-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="var(--color-primary-blue)">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
                <span>Electoral Data Table</span>
            </div>
            <div style="font-size: 0.8rem; color: var(--text-muted);">
                Click <strong>Edit</strong> to modify votes or <strong>Delete</strong> to remove records.
            </div>
        </div>

        <div class="table-responsive">
            <table id="electoralDataTable" class="display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Barangay</th>
                        <th>Year</th>
                        <th>Position</th>
                        <th>Registered Voters</th>
                        <th>Actual Votes</th>
                        <th>Turnout</th>
                        <th>Winner</th>
                        <th>Candidates Breakdown</th>
                        <th style="width: 140px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="electoralTableBody">
                    <!-- Populated dynamically via JS -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. MODAL: Add / Edit Electoral Record Form -->
    <div class="modal-backdrop-custom" id="recordModalBackdrop">
        <div class="modal-card-custom">
            <div class="modal-header-custom">
                <h2 id="recordModalTitle">Encode Election Data</h2>
                <button type="button" class="modal-close-btn" id="btnCloseRecordModal">&times;</button>
            </div>

            <form id="recordForm">
                @csrf
                <input type="hidden" id="modalRecordId" name="id" value="">

                <div class="modal-body-custom">
                    <div class="form-row-2col">
                        <div class="form-group-custom">
                            <label for="modalYear">Election Year</label>
                            <select id="modalYear" name="year" class="form-control-custom" required>
                                @foreach ($years as $yr)
                                    <option value="{{ $yr }}" {{ $yr === $defaultYear ? 'selected' : '' }}>{{ $yr }} Election</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group-custom">
                            <label for="modalPosition">Position</label>
                            <select id="modalPosition" name="position" class="form-control-custom" required>
                                <!-- Populated dynamically -->
                            </select>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="modalBarangay">Barangay</label>
                        <select id="modalBarangay" name="barangay_id" class="form-control-custom" required>
                            @foreach ($barangayNames as $bId => $bName)
                                <option value="{{ $bId }}">Brgy. {{ $bName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row-2col">
                        <div class="form-group-custom">
                            <label for="modalRegVoters">Registered Voters</label>
                            <input type="number" id="modalRegVoters" name="registered_voters" class="form-control-custom" min="0" required placeholder="e.g. 5200">
                        </div>

                        <div class="form-group-custom">
                            <label for="modalActualVotes">Actual Votes Cast</label>
                            <input type="number" id="modalActualVotes" name="actual_votes" class="form-control-custom" min="0" required placeholder="e.g. 4350">
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <label style="margin: 0;">Candidates &amp; Votes Received</label>
                            <span style="font-size: 0.74rem; color: var(--text-muted);">Highest votes will be auto-set as Winner</span>
                        </div>

                        <div id="modalCandidatesContainer">
                            <!-- Dynamic candidate rows injected here -->
                        </div>

                        <button type="button" class="btn-add-candidate-row" id="btnAddCandidateRow">
                            + Add Candidate
                        </button>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelRecordModal">Cancel</button>
                    <button type="submit" class="btn-modal-save" id="btnSubmitRecord">Save to Database</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4B. MODAL: View Electoral Record Details (Eye Icon) -->
    <div class="modal-backdrop-custom" id="viewRecordModalBackdrop">
        <div class="modal-card-custom" style="max-width: 580px;">
            <div class="modal-header-custom">
                <h2 style="display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="var(--color-primary-blue)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span id="viewModalBarangayTitle">Electoral Record Details</span>
                </h2>
                <button type="button" class="modal-close-btn" id="btnCloseViewRecordModal">&times;</button>
            </div>

            <div class="modal-body-custom" style="padding: 20px 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid #EEF2F6;">
                    <div>
                        <span id="viewModalYearBadge" style="background: var(--color-mist-blue); color: var(--color-primary-blue); font-weight: 800; font-size: 0.8rem; padding: 4px 10px; border-radius: 6px;">2025</span>
                        <span id="viewModalPositionBadge" style="background: #F1F5F9; color: var(--color-deep-navy); font-weight: 800; font-size: 0.8rem; padding: 4px 10px; border-radius: 6px; margin-left: 4px;">Mayor</span>
                    </div>
                    <div style="font-size: 0.82rem; color: var(--text-muted);">
                        Municipality: <strong style="color: var(--color-deep-navy);">Mariveles, Bataan</strong>
                    </div>
                </div>

                <div class="electoral-stats-grid" style="grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 16px;">
                    <div class="stat-card-clean accent-navy" style="padding: 12px 14px;">
                        <span class="stat-card-title" style="font-size: 0.7rem;">Registered Voters</span>
                        <span class="stat-card-val" id="viewModalRegVoters" style="font-size: 1.25rem;">0</span>
                    </div>
                    <div class="stat-card-clean accent-cyan" style="padding: 12px 14px;">
                        <span class="stat-card-title" style="font-size: 0.7rem;">Actual Votes</span>
                        <span class="stat-card-val" id="viewModalActualVotes" style="font-size: 1.25rem;">0</span>
                    </div>
                    <div class="stat-card-clean accent-green" style="padding: 12px 14px;">
                        <span class="stat-card-title" style="font-size: 0.7rem;">Turnout</span>
                        <span class="stat-card-val" id="viewModalTurnout" style="font-size: 1.25rem;">0%</span>
                    </div>
                </div>

                <!-- Winner Card -->
                <div id="viewModalWinnerCard" style="background: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 0.70rem; font-weight: 800; color: #1E40AF; text-transform: uppercase; letter-spacing: 0.05em;">Election Winner</div>
                        <div id="viewModalWinnerName" style="font-size: 1.1rem; font-weight: 800; color: var(--color-deep-navy); margin-top: 2px;">Candidate Name</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.70rem; color: #64748B; font-weight: 700;">Votes Received</div>
                        <div id="viewModalWinnerVotes" style="font-size: 1.1rem; font-weight: 800; color: var(--color-primary-blue);">0</div>
                    </div>
                </div>

                <!-- Candidates Breakdown List -->
                <div>
                    <label style="font-size: 0.8rem; font-weight: 800; color: var(--color-deep-navy); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 8px; display: block;">
                        Candidates Vote Share
                    </label>
                    <div id="viewModalCandidatesList" style="display: flex; flex-direction: column; gap: 8px; max-height: 220px; overflow-y: auto; padding-right: 4px;">
                        <!-- Injected dynamically -->
                    </div>
                </div>
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnCancelViewRecordModal">Close</button>
                <button type="button" class="btn-modal-save" id="btnEditFromViewRecord">Edit Record</button>
            </div>
        </div>
    </div>

    <!-- 5. MODAL: Delete Record Confirmation -->
    <div class="modal-backdrop-custom" id="deleteRecordModalBackdrop">
        <div class="modal-card-custom" style="max-width: 440px;">
            <div class="modal-header-custom" style="border-bottom-color: #FEE2E2;">
                <h2 style="color: #DC2626; display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#DC2626">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    Confirm Deletion
                </h2>
                <button type="button" class="modal-close-btn" id="btnCloseDeleteRecordModal">&times;</button>
            </div>

            <div class="modal-body-custom">
                <p style="font-size: 0.92rem; color: var(--color-deep-navy); margin-bottom: 12px; line-height: 1.5;">
                    Are you sure you want to delete the electoral record for <strong id="deleteRecordTargetLabel">Brgy. Name</strong>?
                </p>
                <div style="font-size: 0.80rem; color: #991B1B; background: #FEF2F2; padding: 10px 14px; border-radius: 8px; border: 1px solid #FECACA;">
                    This will permanently remove this barangay's vote counts and candidate data from the database.
                </div>
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnCancelDeleteRecordModal">Cancel</button>
                <button type="button" class="btn-modal-save" id="btnConfirmDeleteRecord" style="background: #DC2626; border: none;">
                    Yes, Delete Record
                </button>
            </div>
        </div>
    </div>

    <!-- 6. MODAL: Add New Election Year -->
    <div class="modal-backdrop-custom" id="addYearModalBackdrop">
        <div class="modal-card-custom" style="max-width: 540px;">
            <div class="modal-header-custom">
                <h2>Add New Election Year</h2>
                <button type="button" class="modal-close-btn" id="btnCloseAddYearModal">&times;</button>
            </div>

            <form id="addYearForm">
                @csrf
                <div class="modal-body-custom">
                    <div class="form-group-custom">
                        <label for="inputNewYear">Election Year (e.g. 2028, 2031)</label>
                        <input type="number" id="inputNewYear" name="year" class="form-control-custom" placeholder="2028" min="2000" max="2100" required>
                        <div class="year-quick-suggestions">
                            <span style="font-size:0.72rem; color:var(--text-muted); font-weight:700;">Quick Pick:</span>
                            <button type="button" class="year-suggest-chip" data-suggest="2028">2028</button>
                            <button type="button" class="year-suggest-chip" data-suggest="2031">2031</button>
                            <button type="button" class="year-suggest-chip" data-suggest="2034">2034</button>
                            <button type="button" class="year-suggest-chip" data-suggest="2037">2037</button>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="inputNewYearTitle">Title / Description</label>
                        <input type="text" id="inputNewYearTitle" name="title" class="form-control-custom" placeholder="e.g. 2028 Presidential & Local Elections">
                    </div>

                    <div class="form-group-custom">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                            <label style="margin:0;">Electoral Positions to Include</label>
                            <span style="font-size:0.72rem; color:var(--text-muted);">Check/uncheck positions</span>
                        </div>
                        <div class="positions-checklist-grid" id="addPositionsChecklist">
                            <label class="pos-check-label">
                                <input type="checkbox" name="positions[]" value="Mayor" checked> Mayor
                            </label>
                            <label class="pos-check-label">
                                <input type="checkbox" name="positions[]" value="Vice Mayor" checked> Vice Mayor
                            </label>
                            <label class="pos-check-label">
                                <input type="checkbox" name="positions[]" value="Governor" checked> Governor
                            </label>
                            <label class="pos-check-label">
                                <input type="checkbox" name="positions[]" value="Congressman" checked> Congressman
                            </label>
                            <label class="pos-check-label">
                                <input type="checkbox" name="positions[]" value="Councilors" checked> Councilors
                            </label>
                            <label class="pos-check-label">
                                <input type="checkbox" name="positions[]" value="Barangay Captain"> Brgy. Captain
                            </label>
                            <label class="pos-check-label">
                                <input type="checkbox" name="positions[]" value="Barangay Kagawads"> Brgy. Kagawads
                            </label>
                            <label class="pos-check-label">
                                <input type="checkbox" name="positions[]" value="SK Chairman"> SK Chairman
                            </label>
                            <label class="pos-check-label">
                                <input type="checkbox" name="positions[]" value="SK Kagawads"> SK Kagawads
                            </label>
                        </div>

                        <!-- Add custom position field (Others) -->
                        <div style="margin-top: 10px; display: flex; gap: 8px;">
                            <input type="text" id="inputAddCustomPosNew" class="form-control-custom" style="font-size:0.84rem; padding:8px 12px;"
                                placeholder="Add other position (e.g. Senator, Party-list, SK Chairman)">
                            <button type="button" class="btn-modal-cancel" id="btnAddCustomPosNew"
                                style="padding:8px 14px; white-space:nowrap; font-weight:800; color:var(--color-primary-blue); background:#EFF6FF; border-color:#BFDBFE;">
                                + Add
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelAddYearModal">Cancel</button>
                    <button type="submit" class="btn-modal-save">Add Year</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 7. MODAL: Edit Year Positions -->
    <div class="modal-backdrop-custom" id="editYearModalBackdrop">
        <div class="modal-card-custom" style="max-width: 540px;">
            <div class="modal-header-custom">
                <h2>Edit Positions for <span id="editYearDisplayLabel">2025</span></h2>
                <button type="button" class="modal-close-btn" id="btnCloseEditYearModal">&times;</button>
            </div>

            <form id="editYearForm">
                @csrf
                <input type="hidden" id="inputEditYearVal" name="year" value="">
                <div class="modal-body-custom">
                    <div class="form-group-custom">
                        <label for="inputEditYearTitle">Title / Description</label>
                        <input type="text" id="inputEditYearTitle" name="title" class="form-control-custom" required>
                    </div>

                    <div class="form-group-custom">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                            <label style="margin:0;">Included Positions</label>
                            <span style="font-size:0.72rem; color:var(--text-muted);">Check/uncheck positions</span>
                        </div>
                        <div id="editPositionsChecklist" class="positions-checklist-grid"></div>

                        <!-- Add custom position field (Others) -->
                        <div style="margin-top: 10px; display: flex; gap: 8px;">
                            <input type="text" id="inputAddCustomPosEdit" class="form-control-custom" style="font-size:0.84rem; padding:8px 12px;"
                                placeholder="Add other position (e.g. Senator, Party-list, SK Chairman)">
                            <button type="button" class="btn-modal-cancel" id="btnAddCustomPosEdit"
                                style="padding:8px 14px; white-space:nowrap; font-weight:800; color:var(--color-primary-blue); background:#EFF6FF; border-color:#BFDBFE;">
                                + Add
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelEditYearModal">Cancel</button>
                    <button type="submit" class="btn-modal-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 8. MODAL: Delete Year Confirmation -->
    <div class="modal-backdrop-custom" id="deleteYearModalBackdrop">
        <div class="modal-card-custom" style="max-width: 440px;">
            <div class="modal-header-custom" style="border-bottom-color: #FEE2E2;">
                <h2 style="color: #DC2626; display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#DC2626">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    Delete Election Year
                </h2>
                <button type="button" class="modal-close-btn" id="btnCloseDeleteYearModal">&times;</button>
            </div>

            <div class="modal-body-custom">
                <p style="font-size: 0.92rem; color: var(--color-deep-navy); margin-bottom: 12px; line-height: 1.5;">
                    Permanently delete Election Year <strong id="deleteYearDisplayLabel" style="color: #DC2626;">2025</strong>?
                </p>
                <div style="font-size: 0.80rem; color: #991B1B; background: #FEF2F2; padding: 10px 14px; border-radius: 8px; border: 1px solid #FECACA;">
                    All records and candidate data for this year will be permanently deleted.
                </div>
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnCancelDeleteYearModal">Cancel</button>
                <button type="button" class="btn-modal-save" id="btnConfirmDeleteYear" style="background: #DC2626; border: none;">
                    Yes, Delete Year
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let activeYear = "{{ $defaultYear }}";
            let activePosition = "{{ $defaultPosition }}";
            let positionsByYear = @json($yearPositionsMap);
            let availableYears = @json($years);
            let barangayMap = @json($barangayNames);
            let pendingDeleteRecordId = null;

            // Initialize DataTable (Matching Admin Modules)
            let dataTable = $('#electoralDataTable').DataTable({
                responsive: true,
                pagingType: 'full_numbers',
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [[1, 'asc']], // Order by Barangay name
                columnDefs: [
                    { targets: [0, 9], orderable: false }
                ],
                language: {
                    search: "Search Records:",
                    searchPlaceholder: "Search any field in table...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                    infoEmpty: "Showing 0 to 0 of 0 records",
                    infoFiltered: "(filtered from _MAX_ total records)",
                    emptyTable: "No electoral records found for the selected year and position.",
                    paginate: {
                        first: "«",
                        previous: "‹",
                        next: "›",
                        last: "»"
                    }
                }
            });

            // Populate Position dropdowns
            function populatePositions(year, selectElId, selectedVal) {
                const selectEl = document.getElementById(selectElId);
                if (!selectEl) return;
                const posList = positionsByYear[year] || ['Mayor', 'Vice Mayor', 'Governor', 'Congressman', 'Councilors'];

                selectEl.innerHTML = '';
                if (selectElId === 'selectFilterPosition') {
                    const allOpt = document.createElement('option');
                    allOpt.value = 'all';
                    allOpt.textContent = '-- All Positions --';
                    selectEl.appendChild(allOpt);
                }

                posList.forEach(pos => {
                    const opt = document.createElement('option');
                    opt.value = pos;
                    opt.textContent = pos;
                    if (selectedVal && selectedVal === pos) {
                        opt.selected = true;
                    }
                    selectEl.appendChild(opt);
                });
            }

            // Load records and update table
            function loadTableData() {
                const bgyFilter = document.getElementById('selectFilterBarangay').value;
                const params = new URLSearchParams({
                    year: activeYear,
                    position: activePosition
                });
                if (bgyFilter) params.append('barangay_id', bgyFilter);

                document.getElementById('currentFilterLabel').textContent = `${activeYear} - ${activePosition === 'all' ? 'All Positions' : activePosition}`;

                fetch(`{{ route('assistant.electoral.data') }}?${params.toString()}`)
                    .then(res => res.json())
                    .then(res => {
                        if (!res.success) return;

                        // Update Metrics
                        document.getElementById('metricTotalReg').textContent = Number(res.metrics.total_registered).toLocaleString();
                        document.getElementById('metricTotalActual').textContent = Number(res.metrics.total_actual).toLocaleString();
                        document.getElementById('metricTurnout').textContent = res.metrics.overall_turnout + '%';
                        document.getElementById('metricEncodedCount').textContent = res.metrics.encoded_count + ' / 18';

                        // Clear & Repopulate DataTable
                        dataTable.clear();

                        res.data.forEach((row, idx) => {
                            // Turnout badge
                            let turnoutClass = 'turnout-low';
                            if (row.turnout_percentage >= 75) turnoutClass = 'turnout-high';
                            else if (row.turnout_percentage >= 50) turnoutClass = 'turnout-mid';

                            const turnoutHtml = `<span class="turnout-pill ${turnoutClass}">${row.turnout_percentage}%</span>`;

                            // Winner badge
                            const winnerHtml = `<div class="winner-pill">
                                <span class="winner-color-dot" style="background-color: ${row.winner_color};"></span>
                                <span>${row.winner_name}</span>
                                <small style="color: #64748B;">(${Number(row.winner_votes).toLocaleString()}v)</small>
                            </div>`;

                            // Candidates chips
                            let candHtml = '<div class="candidates-summary-chips">';
                            if (row.candidates && row.candidates.length > 0) {
                                row.candidates.forEach(c => {
                                    candHtml += `<span class="c-chip-sm"><span style="color:${c.color || '#075998'}; font-weight:800;">●</span> ${c.name}: <strong>${Number(c.votes).toLocaleString()}</strong></span>`;
                                });
                            } else {
                                candHtml += '<span style="color:#94A3B8; font-size:0.75rem;">None encoded</span>';
                            }
                            candHtml += '</div>';

                            // Action buttons
                            const actionsHtml = `<div class="actions-cell-wrap">
                                <button type="button" class="btn-action-view" onclick="openViewModal(${row.id})" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    View
                                </button>
                                <button type="button" class="btn-action-edit" onclick="openEditModal(${row.id})" title="Edit Record">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    </svg>
                                    Edit
                                </button>
                                <button type="button" class="btn-action-delete" onclick="openDeleteModal(${row.id}, '${row.barangay_name.replace(/'/g, "\\'")}', '${row.year}', '${row.position}')" title="Delete Record">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                    Delete
                                </button>
                            </div>`;

                            const bgyCell = `<div class="barangay-cell-name">
                                <span class="barangay-id-badge">${row.barangay_id}</span>
                                <span>Brgy. ${row.barangay_name}</span>
                            </div>`;

                            dataTable.row.add([
                                idx + 1,
                                bgyCell,
                                row.year,
                                row.position,
                                Number(row.registered_voters).toLocaleString(),
                                Number(row.actual_votes).toLocaleString(),
                                turnoutHtml,
                                winnerHtml,
                                candHtml,
                                actionsHtml
                            ]);
                        });

                        dataTable.draw();
                    })
                    .catch(err => {
                        console.error('Error fetching table data:', err);
                    });
            }

            // Year Pill selection
            document.querySelectorAll('.year-pill-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.year-pill-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    activeYear = this.dataset.year;

                    populatePositions(activeYear, 'selectFilterPosition', activePosition);
                    const selPos = document.getElementById('selectFilterPosition');
                    activePosition = selPos.value || 'all';

                    loadTableData();
                });
            });

            // Position & Barangay filter changes
            document.getElementById('selectFilterPosition').addEventListener('change', function() {
                activePosition = this.value;
                loadTableData();
            });

            document.getElementById('selectFilterBarangay').addEventListener('change', function() {
                loadTableData();
            });

            // Candidate row generator helper
            function createCandidateRow(name = '', votes = 0, color = '#075998') {
                const div = document.createElement('div');
                div.className = 'candidate-input-row';
                div.innerHTML = `
                    <input type="text" name="candidates_names[]" class="form-control-custom" placeholder="Candidate Name" value="${name}" required style="flex:2;">
                    <input type="number" name="candidates_votes[]" class="form-control-custom" placeholder="Votes" min="0" value="${votes}" required style="flex:1;">
                    <input type="color" name="candidates_colors[]" class="candidate-color-picker" value="${color}">
                    <button type="button" class="btn-remove-candidate" title="Remove Candidate">&times;</button>
                `;
                div.querySelector('.btn-remove-candidate').addEventListener('click', function() {
                    div.remove();
                });
                return div;
            }

            const candidatesContainer = document.getElementById('modalCandidatesContainer');
            document.getElementById('btnAddCandidateRow').addEventListener('click', function() {
                const palette = ['#075998', '#E53935', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899'];
                const randomColor = palette[candidatesContainer.children.length % palette.length];
                candidatesContainer.appendChild(createCandidateRow('', 0, randomColor));
            });

            // Open Add Record Modal
            document.getElementById('btnOpenAddRecordModal').addEventListener('click', function() {
                document.getElementById('recordForm').reset();
                document.getElementById('modalRecordId').value = '';
                document.getElementById('recordModalTitle').textContent = 'Encode New Election Data';
                document.getElementById('modalYear').value = activeYear;

                populatePositions(activeYear, 'modalPosition', activePosition !== 'all' ? activePosition : null);

                // Add 2 default candidate rows
                candidatesContainer.innerHTML = '';
                candidatesContainer.appendChild(createCandidateRow('', 0, '#075998'));
                candidatesContainer.appendChild(createCandidateRow('', 0, '#E53935'));

                document.getElementById('recordModalBackdrop').style.display = 'flex';
            });

            // Modal Close Buttons
            function setupModalClose(btnId, modalId) {
                const btn = document.getElementById(btnId);
                const modal = document.getElementById(modalId);
                if (btn && modal) {
                    btn.addEventListener('click', () => modal.style.display = 'none');
                }
            }
            setupModalClose('btnCloseRecordModal', 'recordModalBackdrop');
            setupModalClose('btnCancelRecordModal', 'recordModalBackdrop');
            setupModalClose('btnCloseDeleteRecordModal', 'deleteRecordModalBackdrop');
            setupModalClose('btnCancelDeleteRecordModal', 'deleteRecordModalBackdrop');
            setupModalClose('btnCloseAddYearModal', 'addYearModalBackdrop');
            setupModalClose('btnCancelAddYearModal', 'addYearModalBackdrop');
            setupModalClose('btnCloseEditYearModal', 'editYearModalBackdrop');
            setupModalClose('btnCancelEditYearModal', 'editYearModalBackdrop');
            setupModalClose('btnCloseDeleteYearModal', 'deleteYearModalBackdrop');
            setupModalClose('btnCancelDeleteYearModal', 'deleteYearModalBackdrop');

            // Save Record (Add / Edit) Submission
            document.getElementById('recordForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const names = Array.from(document.querySelectorAll('input[name="candidates_names[]"]')).map(el => el.value.trim());
                const votes = Array.from(document.querySelectorAll('input[name="candidates_votes[]"]')).map(el => parseInt(el.value) || 0);
                const colors = Array.from(document.querySelectorAll('input[name="candidates_colors[]"]')).map(el => el.value);

                const candidates = names.map((name, i) => ({
                    name: name,
                    votes: votes[i],
                    color: colors[i]
                })).filter(c => c.name !== '');

                const payload = {
                    year: document.getElementById('modalYear').value,
                    position: document.getElementById('modalPosition').value,
                    barangay_id: document.getElementById('modalBarangay').value,
                    registered_voters: document.getElementById('modalRegVoters').value,
                    actual_votes: document.getElementById('modalActualVotes').value,
                    candidates: candidates,
                    _token: "{{ csrf_token() }}"
                };

                const saveBtn = document.getElementById('btnSubmitRecord');
                saveBtn.disabled = true;
                saveBtn.textContent = 'Saving...';

                fetch("{{ route('assistant.electoral.save') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save to Database';

                    if (data.success) {
                        document.getElementById('recordModalBackdrop').style.display = 'none';
                        showAppToast(data.message, 'success');
                        loadTableData();
                    } else {
                        showAppToast(data.message || 'Validation error saving data.', 'error');
                    }
                })
                .catch(err => {
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save to Database';
                    showAppToast('An unexpected error occurred.', 'error');
                });
            });

            // View Record Handler (Exposed globally for onclick)
            let currentViewingRecordId = null;

            window.openViewModal = function(id) {
                currentViewingRecordId = id;
                fetch(`{{ url('assistant/electoral/record') }}/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            showAppToast(data.message || 'Record not found.', 'error');
                            return;
                        }
                        const rec = data.record;
                        document.getElementById('viewModalBarangayTitle').textContent = `Brgy. ${rec.barangay_name}`;
                        document.getElementById('viewModalYearBadge').textContent = `${rec.year} Election`;
                        document.getElementById('viewModalPositionBadge').textContent = rec.position;
                        document.getElementById('viewModalRegVoters').textContent = Number(rec.registered_voters).toLocaleString();
                        document.getElementById('viewModalActualVotes').textContent = Number(rec.actual_votes).toLocaleString();
                        document.getElementById('viewModalTurnout').textContent = `${rec.turnout_percentage}%`;
                        document.getElementById('viewModalWinnerName').textContent = rec.winner_name || 'None';
                        document.getElementById('viewModalWinnerVotes').textContent = `${Number(rec.winner_votes).toLocaleString()} votes`;

                        const listEl = document.getElementById('viewModalCandidatesList');
                        listEl.innerHTML = '';
                        const candidates = rec.candidates || [];
                        const totalActual = Number(rec.actual_votes) || 0;

                        if (candidates.length === 0) {
                            listEl.innerHTML = '<div style="color:#94A3B8; font-size:0.82rem; padding:8px 0;">No candidates encoded for this record.</div>';
                        } else {
                            candidates.forEach(c => {
                                const cVotes = Number(c.votes) || 0;
                                const sharePct = totalActual > 0 ? ((cVotes / totalActual) * 100).toFixed(1) : 0;
                                const item = document.createElement('div');
                                item.style.cssText = 'background:#F8FAFC; border:1px solid #E2E8F0; border-radius:8px; padding:10px 12px;';
                                item.innerHTML = `
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background-color:${c.color || '#075998'};"></span>
                                            <strong style="color:var(--color-deep-navy); font-size:0.86rem;">${c.name}</strong>
                                        </div>
                                        <div style="font-size:0.82rem; color:var(--color-deep-navy);">
                                            <strong>${cVotes.toLocaleString()}</strong> votes <small style="color:#64748B;">(${sharePct}%)</small>
                                        </div>
                                    </div>
                                    <div style="height:6px; background:#E2E8F0; border-radius:999px; overflow:hidden;">
                                        <div style="height:100%; width:${sharePct}%; background-color:${c.color || '#075998'}; border-radius:999px;"></div>
                                    </div>
                                `;
                                listEl.appendChild(item);
                            });
                        }

                        document.getElementById('viewRecordModalBackdrop').style.display = 'flex';
                    })
                    .catch(err => showAppToast('Failed to load record details.', 'error'));
            };

            document.getElementById('btnCloseViewRecordModal').addEventListener('click', () => {
                document.getElementById('viewRecordModalBackdrop').style.display = 'none';
            });
            document.getElementById('btnCancelViewRecordModal').addEventListener('click', () => {
                document.getElementById('viewRecordModalBackdrop').style.display = 'none';
            });
            document.getElementById('btnEditFromViewRecord').addEventListener('click', () => {
                document.getElementById('viewRecordModalBackdrop').style.display = 'none';
                if (currentViewingRecordId) {
                    openEditModal(currentViewingRecordId);
                }
            });

            // Edit Record Handler (Exposed globally for onclick)
            window.openEditModal = function(id) {
                fetch(`{{ url('assistant/electoral/record') }}/${id}`)
                    .then(res => res.json())
                    .then(res => {
                        if (!res.success) return;
                        const rec = res.record;

                        document.getElementById('modalRecordId').value = rec.id;
                        document.getElementById('recordModalTitle').textContent = `Edit Record - Brgy. ${rec.barangay_name}`;
                        document.getElementById('modalYear').value = rec.year;

                        populatePositions(rec.year, 'modalPosition', rec.position);
                        document.getElementById('modalBarangay').value = rec.barangay_id;
                        document.getElementById('modalRegVoters').value = rec.registered_voters;
                        document.getElementById('modalActualVotes').value = rec.actual_votes;

                        // Rebuild candidates
                        candidatesContainer.innerHTML = '';
                        if (rec.candidates && rec.candidates.length > 0) {
                            rec.candidates.forEach(c => {
                                candidatesContainer.appendChild(createCandidateRow(c.name, c.votes, c.color || '#075998'));
                            });
                        } else {
                            candidatesContainer.appendChild(createCandidateRow('', 0, '#075998'));
                        }

                        document.getElementById('recordModalBackdrop').style.display = 'flex';
                    })
                    .catch(err => showAppToast('Failed to load record details.', 'error'));
            };

            // Delete Record Handler (Exposed globally for onclick)
            window.openDeleteModal = function(id, bgyName, year, position) {
                pendingDeleteRecordId = id;
                document.getElementById('deleteRecordTargetLabel').textContent = `Brgy. ${bgyName} (${year} - ${position})`;
                document.getElementById('deleteRecordModalBackdrop').style.display = 'flex';
            };

            document.getElementById('btnConfirmDeleteRecord').addEventListener('click', function() {
                if (!pendingDeleteRecordId) return;

                const delBtn = this;
                delBtn.disabled = true;
                delBtn.textContent = 'Deleting...';

                fetch(`{{ url('assistant/electoral/delete') }}/${pendingDeleteRecordId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    delBtn.disabled = false;
                    delBtn.textContent = 'Yes, Delete Record';
                    document.getElementById('deleteRecordModalBackdrop').style.display = 'none';

                    if (data.success) {
                        showAppToast(data.message, 'success');
                        loadTableData();
                    } else {
                        showAppToast(data.message || 'Error deleting record.', 'error');
                    }
                })
                .catch(err => {
                    delBtn.disabled = false;
                    delBtn.textContent = 'Yes, Delete Record';
                    showAppToast('Failed to delete record.', 'error');
                });
            });

            // Quick Year Suggestions
            document.querySelectorAll('.year-suggest-chip').forEach(chip => {
                chip.addEventListener('click', function() {
                    const yr = this.dataset.suggest;
                    document.getElementById('inputNewYear').value = yr;
                    document.getElementById('inputNewYearTitle').value = `${yr} Presidential & Local Elections`;
                });
            });

            // Helper to add custom position to any checklist
            function addCustomPositionToContainer(containerId, inputId) {
                const inputEl = document.getElementById(inputId);
                if (!inputEl) return;
                const val = inputEl.value.trim();
                if (!val) return;

                const container = document.getElementById(containerId);
                if (!container) return;

                const existingInputs = Array.from(container.querySelectorAll('input[type="checkbox"]'));
                const found = existingInputs.find(i => i.value.toLowerCase() === val.toLowerCase());
                if (found) {
                    found.checked = true;
                    inputEl.value = '';
                    return;
                }

                const label = document.createElement('label');
                label.className = 'pos-check-label';
                label.innerHTML = `<input type="checkbox" name="positions[]" value="${val}" checked> ${val}`;
                container.appendChild(label);
                inputEl.value = '';
                inputEl.focus();
            }

            // Custom Position Adder in Add Year Modal
            document.getElementById('btnAddCustomPosNew').addEventListener('click', function() {
                addCustomPositionToContainer('addPositionsChecklist', 'inputAddCustomPosNew');
            });
            document.getElementById('inputAddCustomPosNew').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addCustomPositionToContainer('addPositionsChecklist', 'inputAddCustomPosNew');
                }
            });

            // Custom Position Adder in Edit Year Modal
            document.getElementById('btnAddCustomPosEdit').addEventListener('click', function() {
                addCustomPositionToContainer('editPositionsChecklist', 'inputAddCustomPosEdit');
            });
            document.getElementById('inputAddCustomPosEdit').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addCustomPositionToContainer('editPositionsChecklist', 'inputAddCustomPosEdit');
                }
            });

            // Manage Year Modals
            document.getElementById('btnOpenAddYearModal').addEventListener('click', () => {
                document.getElementById('addYearForm').reset();
                document.getElementById('inputAddCustomPosNew').value = '';
                document.getElementById('addYearModalBackdrop').style.display = 'flex';
            });

            document.getElementById('addYearForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch("{{ route('assistant.electoral.add_year') }}", {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('addYearModalBackdrop').style.display = 'none';
                        showAppToast(data.message, 'success');
                        window.location.href = `{{ route('assistant.electoral') }}?year=${data.year}`;
                    } else {
                        showAppToast(data.message || 'Failed to add election year.', 'error');
                    }
                });
            });

            const standardPositionsList = [
                'Mayor', 'Vice Mayor', 'Governor', 'Congressman', 'Councilors',
                'Barangay Captain', 'Barangay Kagawads', 'SK Chairman', 'SK Kagawads'
            ];

            document.getElementById('btnOpenEditYearModal').addEventListener('click', () => {
                document.getElementById('editYearDisplayLabel').textContent = activeYear;
                document.getElementById('inputEditYearVal').value = activeYear;
                document.getElementById('inputEditYearTitle').value = `${activeYear} Elections`;

                const container = document.getElementById('editPositionsChecklist');
                container.innerHTML = '';
                const currentPositions = positionsByYear[activeYear] || ['Mayor', 'Vice Mayor', 'Governor', 'Congressman', 'Councilors'];
                const combinedPositions = Array.from(new Set([...currentPositions, ...standardPositionsList]));

                combinedPositions.forEach(pos => {
                    const checked = currentPositions.includes(pos) ? 'checked' : '';
                    const label = document.createElement('label');
                    label.className = 'pos-check-label';
                    label.innerHTML = `<input type="checkbox" name="positions[]" value="${pos}" ${checked}> ${pos}`;
                    container.appendChild(label);
                });

                document.getElementById('inputAddCustomPosEdit').value = '';
                document.getElementById('editYearModalBackdrop').style.display = 'flex';
            });

            document.getElementById('editYearForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch("{{ route('assistant.electoral.update_year') }}", {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('editYearModalBackdrop').style.display = 'none';
                        showAppToast(data.message, 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        showAppToast(data.message || 'Failed to update election year.', 'error');
                    }
                });
            });

            document.getElementById('btnOpenDeleteYearModal').addEventListener('click', () => {
                document.getElementById('deleteYearDisplayLabel').textContent = activeYear;
                document.getElementById('deleteYearModalBackdrop').style.display = 'flex';
            });

            document.getElementById('btnConfirmDeleteYear').addEventListener('click', function() {
                const payload = new FormData();
                payload.append('year', activeYear);
                payload.append('_token', "{{ csrf_token() }}");

                fetch("{{ route('assistant.electoral.delete_year') }}", {
                    method: 'POST',
                    body: payload,
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('deleteYearModalBackdrop').style.display = 'none';
                        showAppToast(data.message, 'success');
                        setTimeout(() => window.location.href = "{{ route('assistant.electoral') }}", 1000);
                    } else {
                        showAppToast(data.message || 'Failed to delete year.', 'error');
                    }
                });
            });

            // Dynamic bottom-right toast creator
            function showAppToast(message, type = 'success') {
                const container = document.getElementById('bottomToastContainer');
                if (!container) return;

                const toast = document.createElement('div');
                toast.className = `bottom-toast ${type === 'error' ? 'toast-error' : 'toast-success'}`;
                
                const iconSvg = type === 'error'
                    ? '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="#EF4444"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>'
                    : '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#10B981"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>';

                toast.innerHTML = `
                    <span class="toast-icon">${iconSvg}</span>
                    <span class="toast-text">${message}</span>
                    <button type="button" class="toast-close-btn" onclick="dismissToast(this)">&times;</button>
                `;

                container.appendChild(toast);

                setTimeout(() => {
                    dismissToast(toast);
                }, 3200);
            }

            // Initial load of positions and table
            populatePositions(activeYear, 'selectFilterPosition', activePosition);
            loadTableData();
        });
    </script>
@endsection
