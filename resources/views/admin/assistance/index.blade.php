@extends('layouts.app')

@section('title', 'Assistance Module - Mariveles, Bataan')
@section('user_name', 'Administrator')
@section('user_role_label', 'Admin Portal')
@section('user_initials', 'AD')

@section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        /* Top Filter & Controls Header (Matches Electoral Controls Header) */
        .assistance-controls-header {
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

        .header-title-left {
            display: flex;
            align-items: center;
            gap: 12px;
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
            margin: 0;
        }

        .header-actions-right {
            display: flex;
            align-items: center;
            gap: 10px;
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

        /* Filter Controls Row (Matches Electoral Filter Controls) */
        .filter-controls-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }

        .filter-left-group {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            flex: 1;
        }

        .filter-item-wrap {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .filter-label {
            font-size: 0.72rem;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .custom-select-input {
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--color-deep-navy);
            outline: none;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .custom-select-input:focus {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.15);
        }

        .date-pills-wrap {
            display: flex;
            align-items: center;
            gap: 4px;
            background: #F1F5F9;
            padding: 4px;
            border-radius: 10px;
        }

        .date-pill-opt {
            background: transparent;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .date-pill-opt.active {
            background: #FFFFFF;
            color: var(--color-deep-navy);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        }

        /* Assistance Metric Legend Strip (Matches Candidate Legend Strip) */
        .candidate-legend-strip {
            background: #FFFFFF;
            border-radius: var(--radius-md);
            border: 1px solid var(--card-border);
            padding: 12px 20px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            box-shadow: var(--shadow-sm);
        }

        .legend-title-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .legend-items-container {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .metric-pill-btn {
            background: #F1F5F9;
            border: 1.5px solid #E2E8F0;
            color: var(--color-deep-navy);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            user-select: none;
        }

        .metric-pill-btn:hover {
            background: #E2E8F0;
            border-color: #CBD5E1;
            transform: translateY(-1px);
        }

        .metric-pill-btn.active {
            background: linear-gradient(135deg, var(--color-primary-blue), var(--color-deep-navy));
            color: #FFFFFF;
            border-color: var(--color-primary-blue);
            box-shadow: 0 3px 10px rgba(7, 89, 152, 0.35);
        }

        /* Main Dashboard Grid (Exact Electoral Grid: 1fr 380px) */
        .electoral-dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 22px;
            align-items: start;
            margin-bottom: 24px;
        }

        @media (max-width: 1100px) {
            .electoral-dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Map Canvas Stage (Identical to Electoral Data) */
        .map-card-container {
            background: #0B192C;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .interactive-map-stage {
            position: relative;
            width: 100%;
            height: calc(100vh - 350px);
            min-height: 560px;
            background: #0A1626;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            cursor: grab;
        }

        .interactive-map-stage.dragging {
            cursor: grabbing;
        }

        .map-viewport-wrapper {
            position: relative;
            display: inline-block;
            transition: transform 0.15s ease-out;
            transform-origin: center center;
            max-width: 100%;
            max-height: 100%;
        }

        .client-map-img {
            display: block;
            max-height: calc(100vh - 370px);
            max-width: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 6px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
            pointer-events: none;
        }

        /* Dynamic Hotspot Barangay Name Badge Pins (Patagilid / Slanted & Compact) */
        .map-hotspot-pin {
            position: absolute;
            padding: 1.5px 5px;
            border-radius: 8px;
            transform: translate(-50%, -50%) rotate(-48deg);
            transform-origin: center center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.50rem;
            font-weight: 700;
            color: #FFFFFF;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.55);
            transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 15;
            white-space: nowrap;
            letter-spacing: 0;
            line-height: 1.2;
            user-select: none;
        }

        .map-hotspot-pin::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 10px;
            border: 1.5px solid inherit;
            animation: pinPulse 2.2s infinite;
            pointer-events: none;
            opacity: 0;
        }

        @keyframes pinPulse {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            70% {
                transform: scale(1.3);
                opacity: 0;
            }

            100% {
                transform: scale(1.3);
                opacity: 0;
            }
        }

        .map-hotspot-pin:hover {
            transform: translate(-50%, -50%) rotate(0deg) scale(1.30);
            z-index: 50;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.75), 0 0 12px rgba(255, 255, 255, 0.9);
            border-color: #FFFFFF;
        }

        .map-hotspot-pin.active-selected {
            transform: translate(-50%, -50%) rotate(0deg) scale(1.35);
            background-color: var(--color-primary-blue) !important;
            border-color: #FFFFFF !important;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.6), 0 8px 25px rgba(0, 0, 0, 0.85) !important;
            z-index: 60;
        }

        .map-hotspot-pin.active-selected::after {
            animation: pinPulseActive 1.4s infinite;
        }

        @keyframes pinPulseActive {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            80% {
                transform: scale(1.5);
                opacity: 0;
            }

            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }

        /* Floating Tooltip */
        #mapHoverTooltip {
            position: absolute;
            display: none;
            background: rgba(16, 42, 78, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 8px;
            padding: 9px 15px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.45);
            border: 1px solid var(--color-wave-cyan);
            color: #FFFFFF;
            pointer-events: none;
            z-index: 50;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transform: translate(-50%, -130%);
            white-space: nowrap;
        }

        #mapHoverTooltip .tip-name {
            font-size: 0.90rem;
            font-weight: 800;
            color: #FFFFFF;
        }

        #mapHoverTooltip .tip-stat {
            font-size: 0.76rem;
            color: #D6E8F6;
            margin-top: 3px;
        }

        /* Floating Map Zoom Controls */
        .map-floating-controls {
            position: absolute;
            top: 16px;
            right: 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            z-index: 20;
        }

        .map-btn-icon {
            width: 38px;
            height: 38px;
            background: #FFFFFF;
            border: 1px solid #D1E3F0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-deep-navy);
            font-weight: 800;
            font-size: 1.15rem;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.18);
            transition: all var(--transition-fast);
        }

        .map-btn-icon:hover {
            background: var(--color-mist-blue);
            color: var(--color-primary-blue);
            transform: translateY(-1px);
        }

        /* Right Detail Sidebar Card (Exact Electoral Data Sidebar Structure) */
        .sidebar-detail-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .sidebar-header-badge {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1.5px solid #EEF2F6;
            padding-bottom: 14px;
        }

        .detail-meta {
            font-size: 0.74rem;
            font-weight: 800;
            color: var(--color-primary-blue);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .detail-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin-top: 2px;
        }

        .bgy-number-badge {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--color-primary-blue);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            font-weight: 800;
            box-shadow: 0 3px 8px rgba(7, 89, 152, 0.35);
        }

        .stats-grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .stat-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 10px 14px;
        }

        .stat-box-label {
            font-size: 0.70rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .stat-box-val {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin-top: 2px;
        }

        .winner-banner-box {
            background: #E0F2FE;
            border: 1px solid #BAE6FD;
            border-radius: 8px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .winner-banner-text {
            font-size: 0.72rem;
            font-weight: 800;
            color: #0369A1;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .winner-name-display {
            font-size: 1.10rem;
            font-weight: 800;
            color: #0c4a6e;
            margin-top: 2px;
        }

        /* Breakdown Cards List (Matches Candidate Row Card) */
        .breakdown-list-wrap {
            display: flex;
            flex-direction: column;
            gap: 6px;
            max-height: 180px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .breakdown-row-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            transition: all var(--transition-fast);
        }

        .breakdown-row-card:hover {
            background: #F0F7FC;
            border-color: #BFDBFE;
        }

        .b-info-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .b-color-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .b-type-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--color-deep-navy);
        }

        .b-amount-val {
            font-size: 0.82rem;
            font-weight: 800;
            color: #0F766E;
        }

        .b-count-tag {
            font-size: 0.72rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* 18 Barangays Mini Quick-Select List (2 Columns Like Electoral Data) */
        .barangay-pills-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
            max-height: 200px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .bgy-pill-btn {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 0.76rem;
            font-weight: 700;
            color: var(--color-deep-navy);
            text-align: left;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .bgy-pill-btn:hover {
            background: #EFF6FF;
            border-color: #BFDBFE;
            color: var(--color-primary-blue);
        }

        .bgy-pill-btn.active {
            background: #075998;
            border-color: #075998;
            color: #FFFFFF;
            box-shadow: 0 2px 6px rgba(7, 89, 152, 0.3);
        }

        /* Detailed Records Table Section */
        .records-card-section {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 22px 24px;
        }

        .records-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1.5px solid #EEF2F6;
        }

        .records-title-group h2 {
            font-size: 1.20rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Detailed Records Table Custom DataTables Styling */
        .records-table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        /* Custom DataTables Wrapper Layout */
        .dataTables_wrapper {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--color-deep-navy);
        }

        .dataTables_wrapper .dataTables_length {
            margin-bottom: 14px;
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--text-muted);
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

        table.dataTable {
            width: 100% !important;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 10px !important;
            overflow: hidden !important;
            margin: 10px 0 16px 0 !important;
        }

        table.dataTable thead th {
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

        table.dataTable tbody td {
            padding: 12px 14px !important;
            border-bottom: 1px solid #F1F5F9 !important;
            color: var(--color-deep-navy) !important;
            font-size: 0.85rem !important;
            vertical-align: middle !important;
        }

        table.dataTable tbody tr:hover td {
            background: #F8FAFC !important;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #E2E8F0 !important;
        }

        /* Custom DataTables Footer */
        .dataTables_wrapper .dataTables_info {
            padding-top: 14px !important;
            font-size: 0.82rem !important;
            font-weight: 700 !important;
            color: var(--text-muted) !important;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding-top: 12px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-end !important;
            gap: 4px !important;
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

        .table-bgy-pill {
            background: #EFF6FF;
            color: var(--color-primary-blue);
            font-weight: 700;
            font-size: 0.78rem;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .table-amount-val {
            font-weight: 800;
            color: #0F766E;
        }

        .type-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px 7px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .type-pill-Financial {
            background: #EFF6FF;
            color: #1D4ED8;
        }

        .type-pill-Burial {
            background: #F5F3FF;
            color: #7C3AED;
        }

        .type-pill-Tent {
            background: #FFFBEB;
            color: #B45309;
        }

        .type-pill-ItemDonation {
            background: #ECFDF5;
            color: #047857;
        }

        .type-pill-Others {
            background: #FDF2F8;
            color: #BE185D;
        }

        .table-actions-cell {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-table-action {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            border: 1px solid #CBD5E1;
            background: #FFFFFF;
            color: var(--color-deep-navy);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .btn-table-action.edit:hover {
            background: #EFF6FF;
            border-color: #93C5FD;
            color: var(--color-primary-blue);
        }

        .btn-table-action.delete:hover {
            background: #FEF2F2;
            border-color: #FECACA;
            color: #DC2626;
        }

        /* Modals */
        .modal-backdrop-custom {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(16, 42, 78, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 16px;
        }

        .modal-dialog-custom {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 520px;
            overflow: hidden;
            animation: modalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalPop {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-header-custom {
            background: #F8FAFC;
            border-bottom: 1px solid #EEF2F6;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header-custom h3 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--color-deep-navy);
        }

        .modal-close-btn {
            background: transparent;
            border: none;
            font-size: 1.4rem;
            line-height: 1;
            color: var(--text-muted);
            cursor: pointer;
        }

        .modal-body-custom {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-height: 75vh;
            overflow-y: auto;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-group-custom {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group-custom label {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--color-deep-navy);
        }

        .form-control-custom {
            padding: 9px 12px;
            border-radius: 8px;
            border: 1.5px solid #CBD5E1;
            background: #FFFFFF;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--color-deep-navy);
            outline: none;
            transition: all var(--transition-fast);
        }

        .form-control-custom:focus {
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.15);
        }

        .modal-footer-custom {
            background: #F8FAFC;
            border-top: 1px solid #EEF2F6;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-modal-cancel {
            background: #F1F5F9;
            border: 1px solid #CBD5E1;
            color: var(--color-deep-navy);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .btn-modal-save {
            background: var(--color-primary-blue);
            border: none;
            color: #FFFFFF;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(7, 89, 152, 0.3);
        }

        .btn-modal-save:hover {
            background: #0A69B1;
        }

        /* Toast notification */
        #toastNotice {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #102A4E;
            color: #FFFFFF;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 700;
            box-shadow: var(--shadow-lg);
            display: none;
            align-items: center;
            gap: 8px;
            z-index: 2000;
            animation: slideUp 0.25s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
@endsection

@section('content')
    <!-- 1. Top Filter & Controls Header (Matches Electoral Controls Bar) -->
    <div class="assistance-controls-header">
        <div class="header-title-bar-row">
            <div class="header-title-left">
                <div>
                    <h1>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                            stroke-width="2.2" stroke="currentColor" style="color: var(--color-primary-blue);">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                        Assistance Module
                    </h1>
                    <p>Geographic monitoring and distribution of assistance across Mariveles 18 Barangays.</p>
                </div>
            </div>

            <div class="header-actions-right">
                <button type="button" class="btn-encode-data" id="btnOpenAddModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Encode / Add Assistance
                </button>
            </div>
        </div>

        <!-- Filter Controls Row -->
        <div class="filter-controls-row">
            <div class="filter-left-group">
                <!-- Jump to Barangay -->
                <div class="filter-item-wrap">
                    <label for="filterBarangay" class="filter-label">Jump to Barangay</label>
                    <select id="filterBarangay" class="custom-select-input">
                        <option value="all">-- All Barangays (18) --</option>
                        @foreach ($barangays as $bgy)
                            <option value="{{ $bgy->id }}">Brgy. {{ $bgy->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type of Assistance Filter -->
                <div class="filter-item-wrap">
                    <label for="filterType" class="filter-label">Type of Assistance</label>
                    <select id="filterType" class="custom-select-input">
                        <option value="all">All Types</option>
                        @foreach ($assistanceTypes as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range Quick Presets -->
                <div class="filter-item-wrap">
                    <label class="filter-label">Date Filter</label>
                    <div class="date-pills-wrap">
                        <button type="button" class="date-pill-opt active" data-preset="all">All Time</button>
                        <button type="button" class="date-pill-opt" data-preset="this_year">This Year</button>
                        <button type="button" class="date-pill-opt" data-preset="this_month">This Month</button>
                        <button type="button" class="date-pill-opt" data-preset="custom">Custom</button>
                    </div>
                </div>

                <!-- Custom Date Inputs -->
                <div class="filter-item-wrap" id="customDateGroup" style="display: none;">
                    <label class="filter-label">Custom Date Range</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="date" id="filterDateFrom" class="custom-select-input"
                            style="padding: 6px 10px; font-size: 0.82rem;">
                        <span style="color: var(--text-muted); font-size: 0.8rem;">to</span>
                        <input type="date" id="filterDateTo" class="custom-select-input"
                            style="padding: 6px 10px; font-size: 0.82rem;">
                    </div>
                </div>
            </div>

            <div>
                <button type="button" id="btnResetFilters"
                    style="background:none; border:none; color:var(--text-muted); font-size:0.80rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- 2. Dynamic Metric / Legend Strip (Matches Candidate Legend Strip) -->
    <div class="candidate-legend-strip">
        <div class="legend-title-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
            </svg>
            <span>Assistance Map Metric: (<span id="activeMetricLabel" style="color:var(--color-primary-blue);">Number of
                    Assistance</span>)</span>
        </div>
        <div class="legend-items-container">
            <button type="button" class="metric-pill-btn active" data-metric="count">
                <span>Number of Assistance</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="amount">
                <span>Total Amount (₱)</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="beneficiaries">
                <span>Total Beneficiaries</span>
            </button>
        </div>
    </div>

    <!-- 3. Main Dashboard: Map Visualizer on Left + Detailed Barangay Results on Right (Exact Electoral Grid) -->
    <div class="electoral-dashboard-grid">
        <!-- Map Container -->
        <div class="map-card-container">
            <div class="interactive-map-stage" id="mapStage">
                <!-- Floating Zoom Controls -->
                <div class="map-floating-controls">
                    <button type="button" class="map-btn-icon" id="btnZoomIn" title="Zoom In">+</button>
                    <button type="button" class="map-btn-icon" id="btnZoomOut" title="Zoom Out">&minus;</button>
                    <button type="button" class="map-btn-icon" id="btnResetZoom" title="Reset View">&#x21bb;</button>
                </div>

                <!-- Hover Tooltip -->
                <div id="mapHoverTooltip">
                    <div class="tip-name" id="tipName">Barangay Name</div>
                    <div class="tip-stat" id="tipStat">Total Assistance: 0</div>
                </div>

                <!-- Client Map Image with Hotspot Pins (Identical Structure) -->
                <div class="map-viewport-wrapper" id="mapViewport">
                    <img src="{{ asset('images/Map.jpg') }}" alt="Mariveles Bataan Map" class="client-map-img"
                        id="clientMapImg">

                    <!-- 18 Clickable Hotspot Pins -->
                    <div id="hotspotPinsContainer">
                        <!-- Injected dynamically via JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar Detail Card (Exact Electoral Sidebar Layout) -->
        <div class="sidebar-detail-card" id="sidebarDetailCard">
            <div class="sidebar-header-badge">
                <div>
                    <div class="detail-meta" id="summaryMetaTag">Consolidated Mariveles Overview</div>
                    <div class="detail-title" id="summaryBgyTitle">All 18 Barangays</div>
                </div>
                <div class="bgy-number-badge" id="cardNumberBadge" title="Selected Barangay Marker">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                </div>
            </div>

            <!-- 2-Column Stats Grid (Like Registered Voters & Actual Votes) -->
            <div class="stats-grid-2col">
                <div class="stat-box">
                    <div class="stat-box-label">Number of Assistance</div>
                    <div class="stat-box-val" id="summaryCountVal">0</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-label">Beneficiaries</div>
                    <div class="stat-box-val" id="summaryBeneficiariesVal">0</div>
                </div>
            </div>

            <!-- Total Amount Banner Box (Like Winning Candidate Banner Box) -->
            <div class="winner-banner-box">
                <div>
                    <div class="winner-banner-text">💰 Total Amount of Assistance</div>
                    <div class="winner-name-display" id="summaryAmountVal">PHP 0.00</div>
                </div>
            </div>

            <!-- Breakdown by Assistance Type (Like All Candidates Breakdown) -->
            <div>
                <div class="stat-box-label" style="margin-bottom: 8px;">Breakdown by Assistance Type</div>
                <div class="breakdown-list-wrap" id="breakdownListWrap">
                    <!-- Injected dynamically via JS -->
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid #EEF2F6; margin: 2px 0;">

            <!-- 18 Barangays Mini Quick-Select List (Exact Electoral 2-Col Grid) -->
            <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 6px;">
                    <div class="stat-box-label">All 18 Barangays</div>
                    <button type="button" id="btnSelectAllBgy"
                        style="background: none; border: none; font-size: 0.72rem; font-weight: 700; color: var(--color-primary-blue); cursor: pointer;">
                        Select All
                    </button>
                </div>
                <div class="barangay-pills-list" id="pillsList">
                    <!-- Injected dynamically via JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Detailed Barangay Records Table Section (Styled DataTable) -->
    <div class="records-card-section">
        <div class="records-header-row">
            <div class="records-title-group">
                <h2>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        style="color: var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    Detailed Assistance Records
                    <span
                        style="font-size: 0.78rem; font-weight: 700; background: #EFF6FF; color: var(--color-primary-blue); padding: 3px 10px; border-radius: 12px; border: 1px solid #BFDBFE;"
                        id="recordsCountBadge">0 Records</span>
                </h2>
            </div>
        </div>

        <!-- DataTable -->
        <div class="records-table-responsive">
            <table id="assistanceDataTable" class="dataTable stripe hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Barangay</th>
                        <th>Type of Assistance</th>
                        <th>Assistance Given</th>
                        <th style="text-align: right;">Amount</th>
                        <th style="text-align: center;">Beneficiaries</th>
                        <th>Notes</th>
                        <th style="text-align: center; width: 85px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="detailedRecordsTableBody">
                    <!-- Injected dynamically via JS DataTable -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- 5. Add / Edit Assistance Modal -->
    <div class="modal-backdrop-custom" id="assistModalBackdrop">
        <div class="modal-dialog-custom">
            <div class="modal-header-custom">
                <h3 id="modalFormTitle">Add Assistance Record</h3>
                <button type="button" class="modal-close-btn" id="btnCloseAssistModal">&times;</button>
            </div>
            <form id="assistDataForm">
                <input type="hidden" id="formRecordId" value="">
                <div class="modal-body-custom">
                    <!-- Barangay -->
                    <div class="form-group-custom">
                        <label for="formBarangayId">Barangay *</label>
                        <select id="formBarangayId" class="form-control-custom" required>
                            <option value="" disabled selected>-- Select Barangay --</option>
                            @foreach ($barangays as $bgy)
                                <option value="{{ $bgy->id }}">Brgy. {{ $bgy->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type of Assistance & Date -->
                    <div class="form-row-2">
                        <div class="form-group-custom">
                            <label for="formType">Type of Assistance *</label>
                            <select id="formType" class="form-control-custom" required>
                                <option value="Financial">Financial</option>
                                <option value="Burial">Burial</option>
                                <option value="Tent">Tent</option>
                                <option value="Item Donation">Item Donation</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="form-group-custom">
                            <label for="formDate">Date Provided *</label>
                            <input type="date" id="formDate" class="form-control-custom" required
                                value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <!-- Custom Type Description (for Type = Others) -->
                    <div class="form-group-custom" id="customTypeWrap" style="display: none;">
                        <label for="formCustomType">Custom Type Description *</label>
                        <input type="text" id="formCustomType" class="form-control-custom"
                            placeholder="e.g. Infrastructure, Scholarship, Livelihood">
                    </div>

                    <!-- Assistance Given (Specific Name) -->
                    <div class="form-group-custom">
                        <label for="formAssistanceGiven">Assistance Given (Specific Description / Name) *</label>
                        <input type="text" id="formAssistanceGiven" class="form-control-custom"
                            placeholder="e.g. Medical Assistance, Food Packs, Wake Tent" required>
                    </div>

                    <!-- Amount & Number of Beneficiaries -->
                    <div class="form-row-2">
                        <div class="form-group-custom">
                            <label for="formAmount">Amount (PHP ₱) *</label>
                            <input type="number" step="0.01" min="0" id="formAmount"
                                class="form-control-custom" placeholder="0.00" required>
                        </div>
                        <div class="form-group-custom">
                            <label for="formBeneficiaries">Number of Beneficiaries *</label>
                            <input type="number" min="1" id="formBeneficiaries" class="form-control-custom"
                                value="1" required>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-group-custom">
                        <label for="formNotes">Remarks / Notes (Optional)</label>
                        <textarea id="formNotes" class="form-control-custom" rows="2"
                            placeholder="Optional reference or beneficiary details..."></textarea>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelAssistModal">Cancel</button>
                    <button type="submit" class="btn-modal-save" id="btnSubmitAssist">Save Assistance</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 6. Delete Confirmation Modal -->
    <div class="modal-backdrop-custom" id="deleteModalBackdrop">
        <div class="modal-dialog-custom" style="max-width: 420px; text-align: center; padding: 24px;">
            <div
                style="width: 54px; height: 54px; background: #FEF2F2; color: #DC2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--color-deep-navy); margin-bottom: 8px;">Delete
                Assistance Record?</h3>
            <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 20px;">
                Are you sure you want to delete this assistance record? This action cannot be undone.
            </p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="btn-modal-cancel" id="btnCancelDelete">Cancel</button>
                <button type="button" class="btn-modal-save" id="btnConfirmDelete" style="background: #DC2626;">Delete
                    Record</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toastNotice">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
            stroke-width="2.5" stroke="currentColor" style="color: #34D399;">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
        </svg>
        <span id="toastMessage">Action completed successfully!</span>
    </div>
@endsection

@section('scripts')
    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup CSRF header for AJAX
            const csrfToken = '{{ csrf_token() }}';

            // Coordinates for 18 Mariveles Barangays (Identical to Electoral Data Module)
            const pinCoordinates = {
                1: { x: 71.00, y: 33.20 }, // Alion
                2: { x: 88.80, y: 33.60 }, // Batangas II
                3: { x: 63.30, y: 41.80 }, // Cabcaben
                4: { x: 78.00, y: 42.80 }, // Lucanin
                5: { x: 34.80, y: 40.50 }, // Balon-Anito
                6: { x: 52.60, y: 42.20 }, // Maligaya
                7: { x: 22.50, y: 47.20 }, // Biaan
                8: { x: 44.50, y: 44.20 }, // Malaya
                9: { x: 79.60, y: 50.10 }, // Townsite
                10: { x: 38.50, y: 51.10 }, // San Isidro
                11: { x: 77.70, y: 59.20 }, // Mt. View
                12: { x: 68.20, y: 62.20 }, // Alas-Asin
                13: { x: 43.60, y: 61.80 }, // Camaya
                14: { x: 58.90, y: 62.90 }, // Baseco Country
                15: { x: 51.70, y: 63.00 }, // San Carlos
                16: { x: 36.00, y: 67.90 }, // Poblacion
                17: { x: 58.10, y: 69.50 }, // Sisiman
                18: { x: 40.50, y: 74.70 }  // Ipag
            };

            const barangayNames = {
                1: 'Alion',
                2: 'Batangas II',
                3: 'Cabcaben',
                4: 'Lucanin',
                5: 'Balon-Anito',
                6: 'Maligaya',
                7: 'Biaan',
                8: 'Malaya',
                9: 'Townsite',
                10: 'San Isidro',
                11: 'Mt. View',
                12: 'Alas-Asin',
                13: 'Camaya',
                14: 'Baseco Country',
                15: 'San Carlos',
                16: 'Poblacion',
                17: 'Sisiman',
                18: 'Ipag'
            };

            const typeColorMap = {
                'Financial': '#2563EB',
                'Burial': '#7C3AED',
                'Tent': '#D97706',
                'Item Donation': '#0D9488',
                'Others': '#DB2777'
            };

            // Global State
            let currentMetric = 'count'; // 'count', 'amount', 'beneficiaries'
            let selectedBarangayId = 'all'; // Default to All 18 Barangays
            let activeDataset = null;
            let recordToDeleteId = null;
            let dataTableInstance = null;

            // Pan / Zoom State (Default: 1.5x - matching Electoral Data)
            const DEFAULT_SCALE = 1.5;
            let currentScale = DEFAULT_SCALE;
            let panX = 0;
            let panY = 0;
            let isDragging = false;
            let startX, startY;

            // DOM Elements
            const mapStage = document.getElementById('mapStage');
            const mapViewport = document.getElementById('mapViewport');
            const pinsContainer = document.getElementById('hotspotPinsContainer');
            const pillsList = document.getElementById('pillsList');
            const tooltip = document.getElementById('mapHoverTooltip');
            const tipName = document.getElementById('tipName');
            const tipStat = document.getElementById('tipStat');

            const filterBarangay = document.getElementById('filterBarangay');
            const filterType = document.getElementById('filterType');
            const filterDateFrom = document.getElementById('filterDateFrom');
            const filterDateTo = document.getElementById('filterDateTo');
            const customDateGroup = document.getElementById('customDateGroup');
            const btnResetFilters = document.getElementById('btnResetFilters');
            const btnSelectAllBgy = document.getElementById('btnSelectAllBgy');
            const activeMetricLabel = document.getElementById('activeMetricLabel');

            // Modal Elements
            const assistModalBackdrop = document.getElementById('assistModalBackdrop');
            const btnOpenAddModal = document.getElementById('btnOpenAddModal');
            const btnCloseAssistModal = document.getElementById('btnCloseAssistModal');
            const btnCancelAssistModal = document.getElementById('btnCancelAssistModal');
            const assistDataForm = document.getElementById('assistDataForm');
            const formType = document.getElementById('formType');
            const customTypeWrap = document.getElementById('customTypeWrap');
            const formRecordId = document.getElementById('formRecordId');

            // Delete Modal Elements
            const deleteModalBackdrop = document.getElementById('deleteModalBackdrop');
            const btnCancelDelete = document.getElementById('btnCancelDelete');
            const btnConfirmDelete = document.getElementById('btnConfirmDelete');

            // Format helpers
            function formatPeso(num) {
                return 'PHP ' + Number(num || 0).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function showToast(msg) {
                const toast = document.getElementById('toastNotice');
                document.getElementById('toastMessage').textContent = msg;
                toast.style.display = 'flex';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 3500);
            }

            // --- 1. Fetch & Render Dataset ---
            function loadData() {
                const params = new URLSearchParams();
                if (filterBarangay.value !== 'all') params.append('barangay_id', filterBarangay.value);
                if (filterType.value !== 'all') params.append('type', filterType.value);
                if (filterDateFrom.value) params.append('date_from', filterDateFrom.value);
                if (filterDateTo.value) params.append('date_to', filterDateTo.value);
                params.append('metric', currentMetric);

                fetch(`{{ route('admin.assistance.data') }}?${params.toString()}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            activeDataset = data;
                            renderMapPins(data.barangays);
                            renderSidebarPills(data.barangays);
                            renderSidebarSummary(selectedBarangayId);
                            
                            // Filter table by selected barangay if applicable
                            if (selectedBarangayId === 'all') {
                                renderDetailedRecords(data.records);
                            } else {
                                const filtered = data.records.filter(r => r.barangay_id == selectedBarangayId);
                                renderDetailedRecords(filtered);
                            }
                        }
                    })
                    .catch(err => {
                        console.error('Failed to load assistance dataset:', err);
                    });
            }

            // --- 2. Render Map Hotspot Pins (Grey Capsules with Deep Blue Active Selection) ---
            function renderMapPins(barangaysMap) {
                pinsContainer.innerHTML = '';

                for (let bgyId = 1; bgyId <= 18; bgyId++) {
                    const bgy = barangaysMap[bgyId] || {
                        count: 0,
                        amount: 0,
                        beneficiaries: 0,
                        name: barangayNames[bgyId]
                    };
                    const coords = pinCoordinates[bgyId] || { x: 50, y: 50 };

                    const pin = document.createElement('div');
                    pin.className = 'map-hotspot-pin';
                    pin.id = `hotspot-pin-${bgyId}`;
                    pin.style.left = `${coords.x}%`;
                    pin.style.top = `${coords.y}%`;

                    const isSelected = (selectedBarangayId == bgyId);
                    pin.style.backgroundColor = isSelected ? '#075998' : '#64748B';
                    pin.textContent = barangayNames[bgyId] || ('Brgy. ' + bgyId);

                    if (isSelected) {
                        pin.classList.add('active-selected');
                    }

                    // Hover Tooltip
                    pin.addEventListener('mouseenter', function() {
                        tipName.textContent = `Brgy. ${barangayNames[bgyId]}`;
                        tipStat.innerHTML = `
                            <strong>Records:</strong> ${Number(bgy.count).toLocaleString()}<br>
                            <strong>Amount:</strong> ${formatPeso(bgy.amount)}<br>
                            <strong>Beneficiaries:</strong> ${Number(bgy.beneficiaries).toLocaleString()}
                        `;
                        tooltip.style.display = 'block';
                    });

                    pin.addEventListener('mousemove', function(e) {
                        const rect = mapStage.getBoundingClientRect();
                        tooltip.style.left = (e.clientX - rect.left) + 'px';
                        tooltip.style.top = (e.clientY - rect.top) + 'px';
                    });

                    pin.addEventListener('mouseleave', function() {
                        tooltip.style.display = 'none';
                    });

                    // Click to Select
                    pin.addEventListener('click', function(e) {
                        e.stopPropagation();
                        selectBarangay(bgyId);
                    });

                    pinsContainer.appendChild(pin);
                }
            }

            // --- 3. Render Sidebar 18 Barangay Pills (2-Col Grid) ---
            function renderSidebarPills(barangaysMap) {
                pillsList.innerHTML = '';
                for (let bgyId = 1; bgyId <= 18; bgyId++) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = `bgy-pill-btn ${bgyId == selectedBarangayId ? 'active' : ''}`;
                    btn.id = `pill-bgy-${bgyId}`;
                    btn.innerHTML = `
                        <span style="background:${bgyId == selectedBarangayId ? '#FFFFFF' : '#64748B'}; width:7px; height:7px; border-radius:50%; display:inline-block; flex-shrink:0;"></span>
                        <span style="overflow:hidden; text-overflow:ellipsis;">${barangayNames[bgyId]}</span>
                    `;
                    btn.addEventListener('click', () => selectBarangay(bgyId));
                    pillsList.appendChild(btn);
                }
            }

            // --- 4. Select Barangay (Syncs Map, Sidebar & DataTable Records) ---
            function selectBarangay(bgyId) {
                selectedBarangayId = bgyId;

                // Sync Pins
                document.querySelectorAll('.map-hotspot-pin').forEach(p => {
                    p.classList.remove('active-selected');
                    p.style.backgroundColor = '#64748B';
                });

                if (bgyId !== 'all') {
                    const targetPin = document.getElementById(`hotspot-pin-${bgyId}`);
                    if (targetPin) {
                        targetPin.classList.add('active-selected');
                        targetPin.style.backgroundColor = '#075998';
                    }
                }

                // Sync Pills in Sidebar
                document.querySelectorAll('.bgy-pill-btn').forEach(p => {
                    p.classList.remove('active');
                    const dot = p.querySelector('span:first-child');
                    if (dot) dot.style.backgroundColor = '#64748B';
                });

                if (bgyId !== 'all') {
                    const targetPill = document.getElementById(`pill-bgy-${bgyId}`);
                    if (targetPill) {
                        targetPill.classList.add('active');
                        const dot = targetPill.querySelector('span:first-child');
                        if (dot) dot.style.backgroundColor = '#FFFFFF';
                    }
                }

                // Sync Filter Dropdown
                filterBarangay.value = bgyId;

                // Render Sidebar Summary
                renderSidebarSummary(bgyId);

                // Filter Detailed Table
                if (activeDataset && activeDataset.records) {
                    if (bgyId === 'all') {
                        renderDetailedRecords(activeDataset.records);
                    } else {
                        const filtered = activeDataset.records.filter(r => r.barangay_id == bgyId);
                        renderDetailedRecords(filtered);
                    }
                }
            }

            // --- 5. Render Sidebar Barangay Summary & Type Breakdown ---
            function renderSidebarSummary(bgyId) {
                if (!activeDataset) return;

                const standardTypes = ['Financial', 'Burial', 'Tent', 'Item Donation', 'Others'];

                let title = 'All 18 Barangays';
                let meta = 'Consolidated Mariveles Overview';
                let totalCount = activeDataset.kpis.total_records;
                let totalAmt = activeDataset.kpis.total_amount;
                let totalBen = activeDataset.kpis.total_beneficiaries;
                let breakdownRows = [];

                if (bgyId !== 'all' && activeDataset.barangays[bgyId]) {
                    const bgy = activeDataset.barangays[bgyId];
                    title = 'Brgy. ' + bgy.name;
                    meta = 'Assistance Monitoring';
                    totalCount = bgy.count;
                    totalAmt = bgy.amount;
                    totalBen = bgy.beneficiaries;

                    standardTypes.forEach(t => {
                        const item = bgy.types[t] || { count: 0, amount: 0, beneficiaries: 0 };
                        breakdownRows.push({
                            type: t,
                            count: item.count,
                            amount: item.amount,
                            beneficiaries: item.beneficiaries
                        });
                    });
                } else {
                    breakdownRows = activeDataset.type_breakdown || [];
                }

                document.getElementById('summaryMetaTag').textContent = meta;
                document.getElementById('summaryBgyTitle').textContent = title;
                document.getElementById('summaryCountVal').textContent = Number(totalCount || 0).toLocaleString();
                document.getElementById('summaryAmountVal').textContent = formatPeso(totalAmt);
                document.getElementById('summaryBeneficiariesVal').textContent = Number(totalBen || 0).toLocaleString();

                // Render Breakdown List
                const listWrap = document.getElementById('breakdownListWrap');
                listWrap.innerHTML = '';

                if (breakdownRows.length === 0) {
                    listWrap.innerHTML =
                        `<div style="text-align:center; padding:12px; color:var(--text-muted); font-size:0.8rem;">No assistance records found</div>`;
                    return;
                }

                breakdownRows.forEach(row => {
                    const card = document.createElement('div');
                    card.className = 'breakdown-row-card';
                    const dotColor = typeColorMap[row.type] || '#075998';

                    card.innerHTML = `
                        <div class="b-info-group">
                            <span class="b-color-dot" style="background:${dotColor};"></span>
                            <span class="b-type-name">${row.type}</span>
                        </div>
                        <div style="text-align:right;">
                            <div class="b-amount-val">${formatPeso(row.amount)}</div>
                            <div class="b-count-tag">${Number(row.count).toLocaleString()} assists &bull; ${Number(row.beneficiaries).toLocaleString()} benef.</div>
                        </div>
                    `;
                    listWrap.appendChild(card);
                });
            }

            // --- 6. Render Detailed Records DataTable ---
            function renderDetailedRecords(records) {
                const badge = document.getElementById('recordsCountBadge');
                badge.textContent = `${records.length} Records`;

                // Destroy existing DataTable instance before updating DOM
                if ($.fn.DataTable.isDataTable('#assistanceDataTable')) {
                    $('#assistanceDataTable').DataTable().clear().destroy();
                }

                const tbody = document.getElementById('detailedRecordsTableBody');
                tbody.innerHTML = '';

                records.forEach(r => {
                    const tr = document.createElement('tr');
                    const pillClass = 'type-pill-' + (r.type ? r.type.replace(/\s+/g, '') : 'Financial');

                    tr.innerHTML = `
                        <td style="white-space:nowrap; font-weight:600;" data-order="${r.date}">${r.formatted_date || r.date}</td>
                        <td><span class="table-bgy-pill">${r.barangay_name}</span></td>
                        <td><span class="type-badge-pill ${pillClass}">${r.display_type || r.type}</span></td>
                        <td style="font-weight:700; color:var(--color-deep-navy);">${r.assistance_given}</td>
                        <td style="text-align:right;" class="table-amount-val" data-order="${r.amount}">${formatPeso(r.amount)}</td>
                        <td style="text-align:center; font-weight:700;" data-order="${r.beneficiaries_count}">
                            <span style="background:#F1F5F9; padding:2px 8px; border-radius:10px;">${Number(r.beneficiaries_count).toLocaleString()}</span>
                        </td>
                        <td style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:var(--text-muted); font-size:0.8rem;" title="${r.notes || ''}">
                            ${r.notes || '--'}
                        </td>
                        <td style="text-align:center;">
                            <div class="table-actions-cell" style="justify-content:center;">
                                <button type="button" class="btn-table-action edit" data-id="${r.id}" title="Edit Record">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button type="button" class="btn-table-action delete" data-id="${r.id}" title="Delete Record">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                // Initialize DataTable
                dataTableInstance = $('#assistanceDataTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    order: [[0, 'desc']], // Sort by date descending
                    columnDefs: [
                        { orderable: false, targets: [7] } // Disable sorting on Action column
                    ],
                    language: {
                        search: "Search Records:",
                        searchPlaceholder: "Search any field...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        infoFiltered: "(filtered from _MAX_ total entries)",
                        paginate: {
                            first: "«",
                            previous: "‹",
                            next: "›",
                            last: "»"
                        }
                    },
                    drawCallback: function() {
                        // Re-attach Edit & Delete click handlers on table draw
                        $('#assistanceDataTable').find('.btn-table-action.edit').off('click').on('click', function() {
                            const id = $(this).attr('data-id');
                            openEditModal(id);
                        });

                        $('#assistanceDataTable').find('.btn-table-action.delete').off('click').on('click', function() {
                            const id = $(this).attr('data-id');
                            openDeleteModal(id);
                        });
                    }
                });
            }

            // --- 7. Event Listeners: Metric Toggle ---
            document.querySelectorAll('.metric-pill-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.metric-pill-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentMetric = this.getAttribute('data-metric');

                    if (currentMetric === 'amount') activeMetricLabel.textContent = 'Total Amount (₱)';
                    else if (currentMetric === 'beneficiaries') activeMetricLabel.textContent = 'Total Beneficiaries';
                    else activeMetricLabel.textContent = 'Number of Assistance';

                    loadData();
                });
            });

            // --- 8. Event Listeners: Filter Controls ---
            filterBarangay.addEventListener('change', function() {
                selectBarangay(this.value);
            });

            btnSelectAllBgy.addEventListener('click', function() {
                selectBarangay('all');
            });

            filterType.addEventListener('change', loadData);
            filterDateFrom.addEventListener('change', loadData);
            filterDateTo.addEventListener('change', loadData);

            // Quick Date Presets
            document.querySelectorAll('.date-pill-opt').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.date-pill-opt').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const preset = this.getAttribute('data-preset');

                    const now = new Date();
                    const year = now.getFullYear();
                    const month = String(now.getMonth() + 1).padStart(2, '0');

                    if (preset === 'all') {
                        customDateGroup.style.display = 'none';
                        filterDateFrom.value = '';
                        filterDateTo.value = '';
                    } else if (preset === 'this_year') {
                        customDateGroup.style.display = 'none';
                        filterDateFrom.value = `${year}-01-01`;
                        filterDateTo.value = `${year}-12-31`;
                    } else if (preset === 'this_month') {
                        customDateGroup.style.display = 'none';
                        filterDateFrom.value = `${year}-${month}-01`;
                        filterDateTo.value = `${year}-${month}-31`;
                    } else if (preset === 'custom') {
                        customDateGroup.style.display = 'flex';
                    }
                    loadData();
                });
            });

            btnResetFilters.addEventListener('click', function() {
                filterBarangay.value = 'all';
                filterType.value = 'all';
                filterDateFrom.value = '';
                filterDateTo.value = '';
                customDateGroup.style.display = 'none';

                document.querySelectorAll('.date-pill-opt').forEach(b => b.classList.remove('active'));
                document.querySelector('.date-pill-opt[data-preset="all"]').classList.add('active');

                selectedBarangayId = 'all';
                loadData();
            });

            // --- 9. Modal Management: Add & Edit ---
            function openAddModal() {
                formRecordId.value = '';
                document.getElementById('modalFormTitle').textContent = 'Add Assistance Record';
                document.getElementById('btnSubmitAssist').textContent = 'Save Assistance';
                assistDataForm.reset();
                document.getElementById('formDate').value = '{{ date('Y-m-d') }}';

                if (selectedBarangayId !== 'all') {
                    document.getElementById('formBarangayId').value = selectedBarangayId;
                } else {
                    document.getElementById('formBarangayId').value = '';
                }

                customTypeWrap.style.display = 'none';
                assistModalBackdrop.style.display = 'flex';
            }

            function openEditModal(id) {
                fetch(`{{ url('admin/assistance/record') }}/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.record) {
                            const r = data.record;
                            formRecordId.value = r.id;
                            document.getElementById('modalFormTitle').textContent = 'Edit Assistance Record';
                            document.getElementById('btnSubmitAssist').textContent = 'Update Record';

                            document.getElementById('formBarangayId').value = r.barangay_id;
                            formType.value = r.type;
                            document.getElementById('formDate').value = r.date;
                            document.getElementById('formAssistanceGiven').value = r.assistance_given;
                            document.getElementById('formAmount').value = r.amount;
                            document.getElementById('formBeneficiaries').value = r.beneficiaries_count;
                            document.getElementById('formNotes').value = r.notes || '';

                            if (r.type === 'Others') {
                                customTypeWrap.style.display = 'block';
                                document.getElementById('formCustomType').value = r.custom_type || '';
                            } else {
                                customTypeWrap.style.display = 'none';
                                document.getElementById('formCustomType').value = '';
                            }

                            assistModalBackdrop.style.display = 'flex';
                        }
                    });
            }

            btnOpenAddModal.addEventListener('click', () => openAddModal());

            btnCloseAssistModal.addEventListener('click', () => assistModalBackdrop.style.display = 'none');
            btnCancelAssistModal.addEventListener('click', () => assistModalBackdrop.style.display = 'none');

            formType.addEventListener('change', function() {
                if (this.value === 'Others') {
                    customTypeWrap.style.display = 'block';
                } else {
                    customTypeWrap.style.display = 'none';
                }
            });

            // Submit Add / Edit Form
            assistDataForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const id = formRecordId.value;
                const url = id ? `{{ url('admin/assistance/update') }}/${id}` : `{{ route('admin.assistance.store') }}`;

                const payload = {
                    barangay_id: document.getElementById('formBarangayId').value,
                    type: formType.value,
                    custom_type: formType.value === 'Others' ? document.getElementById('formCustomType').value : null,
                    assistance_given: document.getElementById('formAssistanceGiven').value,
                    date: document.getElementById('formDate').value,
                    amount: document.getElementById('formAmount').value,
                    beneficiaries_count: document.getElementById('formBeneficiaries').value,
                    notes: document.getElementById('formNotes').value,
                    _token: csrfToken
                };

                const btnSubmit = document.getElementById('btnSubmitAssist');
                btnSubmit.disabled = true;
                btnSubmit.textContent = 'Saving...';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    btnSubmit.disabled = false;
                    btnSubmit.textContent = id ? 'Update Record' : 'Save Assistance';

                    if (data.success) {
                        assistModalBackdrop.style.display = 'none';
                        showToast(data.message || 'Saved successfully!');
                        loadData();
                    } else {
                        alert(data.message || 'Error saving assistance record.');
                    }
                })
                .catch(err => {
                    btnSubmit.disabled = false;
                    btnSubmit.textContent = id ? 'Update Record' : 'Save Assistance';
                    console.error('Error:', err);
                    alert('An error occurred while saving.');
                });
            });

            // --- 10. Delete Modal ---
            function openDeleteModal(id) {
                recordToDeleteId = id;
                deleteModalBackdrop.style.display = 'flex';
            }

            btnCancelDelete.addEventListener('click', () => {
                deleteModalBackdrop.style.display = 'none';
                recordToDeleteId = null;
            });

            btnConfirmDelete.addEventListener('click', function() {
                if (!recordToDeleteId) return;

                btnConfirmDelete.disabled = true;
                btnConfirmDelete.textContent = 'Deleting...';

                fetch(`{{ url('admin/assistance/delete') }}/${recordToDeleteId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _token: csrfToken })
                })
                .then(res => res.json())
                .then(data => {
                    btnConfirmDelete.disabled = false;
                    btnConfirmDelete.textContent = 'Delete Record';
                    deleteModalBackdrop.style.display = 'none';
                    recordToDeleteId = null;

                    if (data.success) {
                        showToast(data.message || 'Record deleted successfully.');
                        loadData();
                    } else {
                        alert(data.message || 'Could not delete record.');
                    }
                })
                .catch(err => {
                    btnConfirmDelete.disabled = false;
                    btnConfirmDelete.textContent = 'Delete Record';
                    console.error(err);
                });
            });

            // --- 11. Interactive Map Pan & Zoom ---
            function applyTransform() {
                mapViewport.style.transform = `translate(${panX}px, ${panY}px) scale(${currentScale})`;
            }

            document.getElementById('btnZoomIn').addEventListener('click', () => {
                currentScale = Math.min(currentScale + 0.25, 3.5);
                applyTransform();
            });

            document.getElementById('btnZoomOut').addEventListener('click', () => {
                currentScale = Math.max(currentScale - 0.25, 0.75);
                applyTransform();
            });

            document.getElementById('btnResetZoom').addEventListener('click', () => {
                currentScale = DEFAULT_SCALE;
                panX = 0;
                panY = 0;
                applyTransform();
            });

            mapStage.addEventListener('mousedown', function(e) {
                if (e.target.closest('.map-btn-icon') || e.target.closest('.map-hotspot-pin')) return;
                isDragging = true;
                startX = e.clientX - panX;
                startY = e.clientY - panY;
                mapStage.classList.add('dragging');
            });

            window.addEventListener('mousemove', function(e) {
                if (!isDragging) return;
                panX = e.clientX - startX;
                panY = e.clientY - startY;
                applyTransform();
            });

            window.addEventListener('mouseup', function() {
                if (isDragging) {
                    isDragging = false;
                    mapStage.classList.remove('dragging');
                }
            });

            // Apply initial zoom transform (1.5x)
            applyTransform();

            // Initial Data Load
            loadData();
        });
    </script>
@endsection
