@extends('layouts.app')

@section('title', 'Issues Module - Mariveles, Bataan')
@section('user_name', 'Administrator')
@section('user_role_label', 'Admin Portal')
@section('user_initials', 'AD')

@section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        /* Top Filter & Controls Header (Consistent with Electoral, Assistance & Events) */
        .issues-controls-header {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 18px 24px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
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

        .header-title-icon-badge {
            background: #EFF6FF;
            padding: 10px;
            border-radius: 12px;
            color: var(--color-primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
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


        /* Filter Controls Ribbon */
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

        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .filter-item label {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }

        .filter-select,
        .filter-input {
            height: 38px;
            padding: 0 12px;
            border: 1.5px solid #CBD5E1;
            border-radius: var(--radius-md);
            background: #FFFFFF;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--color-deep-navy);
            outline: none;
            transition: border-color var(--transition-fast);
            box-sizing: border-box;
            width: 100%;
            max-width: 100%;
        }

        .filter-search-item {
            flex: 1;
            min-width: 170px;
        }

        .filter-select:focus,
        .filter-input:focus {
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.12);
        }

        /* Status Filter Pills */
        .status-pill-group {
            display: flex;
            align-items: center;
            background: #F1F5F9;
            padding: 4px;
            border-radius: var(--radius-md);
            border: 1px solid #E2E8F0;
            gap: 4px;
            flex-wrap: wrap;
            max-width: 100%;
            box-sizing: border-box;
        }

        .status-pill-btn {
            border: none;
            background: transparent;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            white-space: nowrap;
            box-sizing: border-box;
            flex: 1 1 auto;
        }

        .status-pill-btn:hover {
            color: var(--color-deep-navy);
            background: rgba(255, 255, 255, 0.6);
        }

        .status-pill-btn.active {
            background: #FFFFFF;
            color: var(--color-primary-blue);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .btn-reset-filters {
            border: 1.5px solid #E2E8F0;
            background: #F8FAFC;
            color: #64748B;
            border-radius: var(--radius-md);
            padding: 8px 14px;
            font-size: 0.80rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            align-self: flex-end;
            transition: all var(--transition-fast);
        }

        .btn-reset-filters:hover {
            background: #F1F5F9;
            color: #DC2626;
            border-color: #FECACA;
        }

        /* 2. Municipal KPI Summary Cards (Section 8: Total, Ongoing, For Action, Resolved, Urgent) */
        .kpi-cards-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 22px;
            width: 100%;
        }

        @media (max-width: 1200px) {
            .kpi-cards-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .kpi-cards-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .kpi-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        .kpi-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 18px 20px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform var(--transition-fast), box-shadow var(--transition-fast);
        }

        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .kpi-card.card-total::before { background: linear-gradient(90deg, #075998, #51B8E5); }
        .kpi-card.card-ongoing::before { background: linear-gradient(90deg, #F59E0B, #FBBF24); }
        .kpi-card.card-for-action::before { background: linear-gradient(90deg, #6366F1, #818CF8); }
        .kpi-card.card-resolved::before { background: linear-gradient(90deg, #10B981, #34D399); }
        .kpi-card.card-urgent::before { background: linear-gradient(90deg, #DC2626, #EF4444); }

        .kpi-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .kpi-title {
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }

        .kpi-icon-badge {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-total .kpi-icon-badge { background: #E0F2FE; color: #075998; }
        .card-ongoing .kpi-icon-badge { background: #FEF3C7; color: #D97706; }
        .card-for-action .kpi-icon-badge { background: #EEF2FF; color: #4F46E5; }
        .card-resolved .kpi-icon-badge { background: #D1FAE5; color: #059669; }
        .card-urgent .kpi-icon-badge { background: #FEE2E2; color: #DC2626; }

        .kpi-value {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            line-height: 1.15;
            margin-bottom: 4px;
        }

        .kpi-subtext {
            font-size: 0.76rem;
            color: var(--text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .badge-urgent-pulse {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 2px 7px;
            background: #FEF2F2;
            border: 1px solid #FCA5A5;
            color: #DC2626;
            border-radius: 12px;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.03em;
            animation: urgentPulse 2s infinite;
        }

        @keyframes urgentPulse {
            0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(220, 38, 38, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
        }

        /* 3. Map Legend & Metric Switcher Bar */
        .map-legend-bar-container {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 12px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            width: 100%;
            box-sizing: border-box;
        }

        .legend-title-group {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--color-deep-navy);
        }

        .legend-items-container {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .metric-pill-btn {
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            color: var(--color-deep-navy);
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .metric-pill-btn:hover {
            background: #EDF2F7;
            border-color: #CBD5E1;
        }

        .metric-pill-btn.active {
            background: var(--color-primary-blue);
            color: #FFFFFF;
            border-color: var(--color-primary-blue);
            box-shadow: 0 3px 8px rgba(7, 89, 152, 0.35);
        }

        /* 4. Main Dashboard Grid (Exact Electoral, Assistance & Events: 1fr 380px) */
        .electoral-dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 22px;
            align-items: start;
            margin-bottom: 24px;
            width: 100%;
            box-sizing: border-box;
        }

        @media (max-width: 1100px) {
            .electoral-dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Map Canvas Stage (Identical to Electoral & Assistance Modules) */
        .map-card-container {
            background: #0B192C;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            box-sizing: border-box;
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

        /* Dynamic Hotspot Barangay Pins (Patagilid / Slanted & Compact) */
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
            0% { transform: scale(1); opacity: 0.8; }
            70% { transform: scale(1.3); opacity: 0; }
            100% { transform: scale(1.3); opacity: 0; }
        }

        .map-hotspot-pin:hover {
            transform: translate(-50%, -50%) rotate(0deg) scale(1.30);
            z-index: 50;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.75), 0 0 12px rgba(255, 255, 255, 0.9);
            border-color: #FFFFFF;
        }

        .map-hotspot-pin.active-selected {
            transform: translate(-50%, -50%) rotate(0deg) scale(1.35);
            border-color: #FFFFFF !important;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.6), 0 8px 25px rgba(0, 0, 0, 0.85) !important;
            z-index: 60;
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

        /* Right Sidebar Detail Panel */
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
            padding-bottom: 12px;
            border-bottom: 1px solid #EEF2F6;
        }

        .detail-meta {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
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
            background: #EFF6FF;
            color: var(--color-primary-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.90rem;
            border: 1.5px solid #BFDBFE;
        }

        .stats-grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .stat-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: var(--radius-md);
            padding: 12px 14px;
        }

        .stat-box-label {
            font-size: 0.70rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .stat-box-val {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--color-deep-navy);
        }

        /* Breakdown Table (Matches User Spec Section 3: Total, Political, Community, Municipal-Wide, Policy, Infrastructure) */
        .breakdown-section-title {
            font-size: 0.76rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--color-deep-navy);
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 4px;
        }

        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        .breakdown-table th {
            text-align: left;
            padding: 8px 6px;
            color: var(--text-muted);
            font-weight: 700;
            font-size: 0.72rem;
            text-transform: uppercase;
            border-bottom: 1px solid #EEF2F6;
        }

        .breakdown-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #F1F5F9;
            color: var(--color-deep-navy);
            font-weight: 600;
        }

        .breakdown-table .num-col {
            text-align: right;
            font-weight: 800;
        }

        .btn-encode-for-bgy {
            background: #EFF6FF;
            color: var(--color-primary-blue);
            border: 1.5px solid #BFDBFE;
            padding: 10px;
            border-radius: var(--radius-md);
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all var(--transition-fast);
            margin-top: 6px;
        }

        .btn-encode-for-bgy:hover {
            background: #DBEAFE;
            border-color: var(--color-primary-blue);
        }

        /* 5. Detailed Issue Table Section (Section 4) */
        .table-card-container {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 22px 24px;
            width: 100%;
            box-sizing: border-box;
        }

        .table-nav-ribbon {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid #EEF2F6;
        }

        .table-nav-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-nav-title h2 {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin: 0;
        }

        .table-tab-buttons {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #F1F5F9;
            padding: 4px;
            border-radius: 10px;
        }

        .tab-btn {
            border: none;
            background: transparent;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.80rem;
            font-weight: 700;
            color: #64748B;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .tab-btn.active {
            background: #FFFFFF;
            color: var(--color-deep-navy);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        /* Type Badges */
        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .type-badge-infrastructure { background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; }
        .type-badge-community { background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; }
        .type-badge-political { background: #F3E8FF; color: #6D28D9; border: 1px solid #DDD6FE; }
        .type-badge-policy { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
        .type-badge-municipal-wide { background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; }

        /* Priority Badges */
        .priority-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.72rem;
            font-weight: 800;
        }

        .priority-badge-urgent { background: #FEE2E2; color: #B91C1C; border: 1px solid #FCA5A5; }
        .priority-badge-high { background: #FFEDD5; color: #C2410C; border: 1px solid #FDBA74; }
        .priority-badge-medium { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
        .priority-badge-low { background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 9px;
            border-radius: 14px;
            font-size: 0.72rem;
            font-weight: 800;
        }

        .status-badge-new { background: #E0F2FE; color: #0284C7; }
        .status-badge-ongoing { background: #FEF3C7; color: #D97706; }
        .status-badge-for-action { background: #EEF2FF; color: #4F46E5; }
        .status-badge-resolved { background: #D1FAE5; color: #059669; }

        /* Custom DataTables Styling */
        .dataTables_wrapper {
            font-family: inherit;
            color: var(--color-deep-navy);
            font-size: 0.84rem;
            width: 100%;
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
        }

        table.dataTable {
            width: 100% !important;
            border-collapse: collapse !important;
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
        }

        table.dataTable tbody td {
            padding: 12px 14px !important;
            border-bottom: 1px solid #F1F5F9 !important;
            vertical-align: middle !important;
        }

        table.dataTable tbody tr:hover {
            background-color: #F8FAFC !important;
        }

        .action-btns-wrap {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 1px solid #E2E8F0;
            background: #FFFFFF;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .btn-action-icon:hover {
            background: #F1F5F9;
            color: var(--color-primary-blue);
            border-color: #CBD5E1;
        }

        .btn-action-icon.btn-delete:hover {
            background: #FEF2F2;
            color: #DC2626;
            border-color: #FECACA;
        }

        /* Modals */
        .modal-backdrop-custom {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-y: auto;
        }

        .modal-box-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 680px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            border: 1px solid #E2E8F0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: modalFadeIn 0.2s ease-out;
            max-height: 90vh;
        }

        .modal-box-card form {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
            overflow: hidden;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.96) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-header-custom {
            padding: 18px 24px;
            border-bottom: 1px solid #EEF2F6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            background: #FFFFFF;
        }

        .modal-title-custom {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-close-btn {
            background: #F1F5F9;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #64748B;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .modal-close-btn:hover {
            background: #E2E8F0;
            color: var(--color-deep-navy);
        }

        .modal-body-custom {
            padding: 22px 24px;
            overflow-y: auto;
            flex: 1;
            min-height: 0;
        }

        .modal-body-custom::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body-custom::-webkit-scrollbar-track {
            background: #F1F5F9;
        }

        .modal-body-custom::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }

        .modal-body-custom::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        .form-grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-col-span-2 {
            grid-column: span 2;
        }

        .form-group-custom {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group-custom label {
            font-size: 0.78rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-group-custom label .req {
            color: #DC2626;
        }

        .form-control-custom {
            height: 42px;
            padding: 0 14px;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: var(--radius-sm);
            font-size: 0.88rem;
            color: var(--color-deep-navy);
            outline: none;
            transition: all var(--transition-fast);
        }

        .form-control-custom:focus {
            background: #FFFFFF;
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.15);
        }

        textarea.form-control-custom {
            height: auto;
            min-height: 80px;
            padding: 10px 14px;
            resize: vertical;
        }

        /* Scope Selector Card */
        .scope-selector-box {
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: var(--radius-md);
            padding: 14px;
            margin-bottom: 16px;
        }

        .scope-toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #CBD5E1;
        }

        .municipal-wide-toggle-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 0.86rem;
            color: var(--color-deep-navy);
            cursor: pointer;
        }

        .multi-bgy-pills-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            max-height: 180px;
            overflow-y: auto;
            padding-right: 4px;
        }

        @media (max-width: 600px) {
            .multi-bgy-pills-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .bgy-checkbox-label {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #FFFFFF;
            border: 1px solid #CBD5E1;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--color-deep-navy);
            cursor: pointer;
            transition: all var(--transition-fast);
            user-select: none;
        }

        .bgy-checkbox-label:hover {
            background: #F1F5F9;
            border-color: #94A3B8;
        }

        .bgy-checkbox-label:has(input:checked) {
            background: #EFF6FF;
            border-color: var(--color-primary-blue);
            color: var(--color-primary-blue);
        }

        .modal-footer-custom {
            padding: 16px 24px;
            border-top: 1px solid #EEF2F6;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex-shrink: 0;
            background: #FFFFFF;
        }

        .btn-modal-cancel {
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            padding: 9px 18px;
            border-radius: var(--radius-sm);
            font-size: 0.86rem;
            font-weight: 700;
            color: #64748B;
            cursor: pointer;
        }

        .btn-modal-submit {
            background: linear-gradient(135deg, var(--color-primary-blue), #0b4575);
            color: #FFFFFF;
            border: none;
            padding: 9px 22px;
            border-radius: var(--radius-sm);
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(7, 89, 152, 0.25);
        }

        /* Toast Notification */
        #toastNotice {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #102A4E;
            color: #FFFFFF;
            border-radius: var(--radius-md);
            padding: 14px 22px;
            font-size: 0.88rem;
            font-weight: 700;
            display: none;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border-left: 5px solid #10B981;
            z-index: 100000;
        }

        /* Custom DataTables Footer & Pagination (Exact Match to Events Module) */
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

        /* Responsive Breakpoints Matching Events Module */
        @media (max-width: 992px) {
            .issues-controls-header {
                padding: 16px 20px;
                gap: 14px;
            }

            .header-title-bar-row {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .header-actions-right {
                width: 100%;
            }

            .header-actions-right .btn-encode-data {
                width: 100%;
                justify-content: center;
            }

            .filter-controls-row {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .filter-left-group {
                flex-direction: column;
                align-items: stretch;
                width: 100%;
                gap: 12px;
            }

            .filter-item,
            .filter-search-item {
                width: 100% !important;
                min-width: 0 !important;
                max-width: 100% !important;
                flex: none !important;
            }

            .filter-item .filter-select,
            .filter-item .filter-input {
                width: 100% !important;
                min-width: 0 !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            .status-pill-group {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                box-sizing: border-box;
                gap: 4px;
            }

            .status-pill-btn {
                flex: 1 1 auto;
                text-align: center;
                justify-content: center;
                white-space: nowrap;
                padding: 6px 10px;
            }

            .btn-reset-filters {
                align-self: flex-start;
                margin-top: 4px;
            }

            .interactive-map-stage {
                height: 480px;
                min-height: 400px;
            }

            .client-map-img {
                max-height: 440px;
            }
        }

        @media (max-width: 768px) {
            .map-legend-bar-container {
                flex-direction: column;
                align-items: flex-start;
                padding: 14px 16px;
                gap: 10px;
            }

            .legend-items-container {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
            }

            .metric-pill-btn {
                padding: 6px 12px;
                font-size: 0.76rem;
            }

            .interactive-map-stage {
                height: 380px;
                min-height: 300px;
            }

            .client-map-img {
                max-height: 340px;
            }

            .table-card-container {
                padding: 16px;
            }

            .table-nav-ribbon {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .table-tab-buttons {
                width: 100%;
                flex-wrap: wrap;
                gap: 4px;
            }

            .tab-btn {
                flex: 1 1 auto;
                text-align: center;
                padding: 6px 10px;
                font-size: 0.78rem;
            }

            /* Responsive DataTables Layout */
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                float: none !important;
                width: 100% !important;
                text-align: left !important;
                margin-bottom: 10px !important;
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
                padding: 4px 8px !important;
                font-size: 0.76rem !important;
                margin: 1px !important;
            }
        }

        @media (max-width: 640px) {
            .issues-controls-header {
                padding: 14px 16px;
            }

            .header-title-left h1 {
                font-size: 1.15rem;
            }

            .header-title-left p {
                font-size: 0.76rem;
            }

            /* Responsive Modals */
            .modal-backdrop-custom {
                padding: 12px;
            }

            .modal-box-card {
                max-height: 94vh;
                margin: 0;
                border-radius: var(--radius-md);
            }

            .modal-header-custom {
                padding: 14px 18px;
            }

            .modal-body-custom {
                padding: 16px 18px;
            }

            .modal-footer-custom {
                padding: 14px 18px;
                flex-direction: column-reverse;
                align-items: stretch;
                gap: 8px;
            }

            .btn-modal-cancel,
            .btn-modal-submit {
                width: 100%;
                justify-content: center;
                text-align: center;
            }

            .form-grid-2col {
                grid-template-columns: 1fr !important;
                gap: 12px;
            }

            .form-col-span-2 {
                grid-column: span 1 !important;
            }
        }

        @media (max-width: 480px) {
            .interactive-map-stage {
                height: 320px;
                min-height: 270px;
            }

            .client-map-img {
                max-height: 270px;
            }

            .sidebar-detail-card {
                padding: 16px;
            }

            .kpi-card {
                padding: 14px 16px;
            }

            .kpi-value {
                font-size: 1.6rem;
            }

            .kpi-subtext {
                font-size: 0.74rem;
            }

            .status-pill-btn {
                font-size: 0.72rem;
                padding: 5px 6px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- 1. Top Controls & Filter Header -->
    <div class="issues-controls-header">
        <div class="header-title-bar-row">
            <div class="header-title-left">
                <div class="header-title-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <div>
                    <h1>Issues Module</h1>
                    <p>Record, monitor, prioritize, and geographically map concerns affecting Mariveles barangays & municipality</p>
                </div>
            </div>

            <div class="header-actions-right">

                <button type="button" class="btn-encode-data" id="btnOpenAddModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Encode Issue</span>
                </button>
            </div>
        </div>

        <!-- Filter Controls Ribbon (Section 6) -->
        <div class="filter-controls-row">
            <div class="filter-left-group">
                <!-- Status Filter Pills -->
                <div class="filter-item">
                    <label>Status</label>
                    <div class="status-pill-group">
                        <button type="button" class="status-pill-btn active" data-status="all" id="pillStatusAll">All Issues</button>
                        <button type="button" class="status-pill-btn" data-status="New" id="pillStatusNew">New (<span id="pillNewCount">0</span>)</button>
                        <button type="button" class="status-pill-btn" data-status="Ongoing" id="pillStatusOngoing">Ongoing (<span id="pillOngoingCount">0</span>)</button>
                        <button type="button" class="status-pill-btn" data-status="For Action" id="pillStatusAction">For Action (<span id="pillActionCount">0</span>)</button>
                        <button type="button" class="status-pill-btn" data-status="Resolved" id="pillStatusResolved">Resolved (<span id="pillResolvedCount">0</span>)</button>
                    </div>
                </div>

                <!-- Barangay Filter Dropdown -->
                <div class="filter-item">
                    <label for="filterBarangay">Barangay Scope</label>
                    <select id="filterBarangay" class="filter-select">
                        <option value="all">All 18 Barangays</option>
                        <option value="municipal_wide">Municipal-Wide Only</option>
                        @foreach ($barangays as $bgy)
                            <option value="{{ $bgy->id }}">{{ $bgy->id }}. {{ $bgy->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type of Issue Filter -->
                <div class="filter-item">
                    <label for="filterType">Type of Issue</label>
                    <select id="filterType" class="filter-select">
                        <option value="all">All Types</option>
                        <option value="Political">Political</option>
                        <option value="Community">Community</option>
                        <option value="Municipal-Wide">Municipal-Wide</option>
                        <option value="Policy">Policy</option>
                        <option value="Infrastructure">Infrastructure</option>
                    </select>
                </div>

                <!-- Priority Filter -->
                <div class="filter-item">
                    <label for="filterPriority">Priority</label>
                    <select id="filterPriority" class="filter-select">
                        <option value="all">All Priorities</option>
                        <option value="Urgent">Urgent</option>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div class="filter-item">
                    <label>Date From</label>
                    <input type="date" id="filterDateFrom" class="filter-input">
                </div>
                <div class="filter-item">
                    <label>Date To</label>
                    <input type="date" id="filterDateTo" class="filter-input">
                </div>

                <!-- Search -->
                <div class="filter-item filter-search-item">
                    <label for="filterSearch">Search</label>
                    <input type="text" id="filterSearch" class="filter-input" placeholder="Search issue, affected, details...">
                </div>
            </div>

            <button type="button" class="btn-reset-filters" id="btnResetFilters" title="Clear all filters">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Reset</span>
            </button>
        </div>
    </div>

    <!-- 2. Municipal Level Summary KPI Cards (User Spec Section 8) -->
    <div class="kpi-cards-grid">
        <!-- Total Issues -->
        <div class="kpi-card card-total">
            <div class="kpi-header">
                <span class="kpi-title">Total Issues</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiTotalIssues">0</div>
            <div class="kpi-subtext" id="kpiTotalSub">Across Mariveles Municipality</div>
        </div>

        <!-- Ongoing Issues -->
        <div class="kpi-card card-ongoing">
            <div class="kpi-header">
                <span class="kpi-title">Ongoing Issues</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiOngoingIssues" style="color:#D97706;">0</div>
            <div class="kpi-subtext">In-progress & monitoring</div>
        </div>

        <!-- For Action Issues -->
        <div class="kpi-card card-for-action">
            <div class="kpi-header">
                <span class="kpi-title">For Action</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiForActionIssues" style="color:#4F46E5;">0</div>
            <div class="kpi-subtext">Pending department action</div>
        </div>

        <!-- Resolved Issues -->
        <div class="kpi-card card-resolved">
            <div class="kpi-header">
                <span class="kpi-title">Resolved</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiResolvedIssues" style="color:#059669;">0</div>
            <div class="kpi-subtext">Successfully addressed</div>
        </div>

        <!-- Urgent Issues -->
        <div class="kpi-card card-urgent">
            <div class="kpi-header">
                <span class="kpi-title">Urgent Issues</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiUrgentIssues" style="color:#DC2626;">0</div>
            <div class="kpi-subtext">
                <span class="badge-urgent-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                    </svg>
                    HIGH PRIORITY
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Map Legend & Metric Switcher Ribbon -->
    <div class="map-legend-bar-container">
        <div class="legend-title-group">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                stroke-width="2.2" stroke="currentColor" style="color:var(--color-primary-blue);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            <span>Interactive Map Metric: (<span id="activeMetricLabel" style="color:var(--color-primary-blue); font-weight:800;">Total Issues</span>)</span>
        </div>
        <div class="legend-items-container">
            <button type="button" class="metric-pill-btn active" data-metric="total_issues">
                <span>Total Issues</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="urgent_issues">
                <span>Urgent Issues</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="ongoing_issues">
                <span>Ongoing Issues</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="for_action_issues">
                <span>For Action</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="resolved_issues">
                <span>Resolved</span>
            </button>
        </div>
    </div>

    <!-- 4. Main Interactive Map & Barangay Summary Grid (Sections 3 & 5) -->
    <div class="electoral-dashboard-grid">
        <!-- Left: Interactive Mariveles Map -->
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
                    <div class="tip-stat" id="tipStat">Total Issues: 0</div>
                </div>

                <!-- Map Viewport Wrapper -->
                <div class="map-viewport-wrapper" id="mapViewport">
                    <img src="{{ asset('images/mariveles-map.png') }}" alt="Mariveles Bataan Map" class="client-map-img"
                        id="clientMapImg">

                    <!-- 18 Clickable Hotspot Pins -->
                    <div id="hotspotPinsContainer">
                        <!-- Injected via JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Barangay Issue Summary (User Spec Section 3: Summary Metric Example) -->
        <div class="sidebar-detail-card" id="sidebarDetailCard">
            <div class="sidebar-header-badge">
                <div>
                    <div class="detail-meta" id="summaryMetaTag">Consolidated Mariveles Overview</div>
                    <div class="detail-title" id="summaryBgyTitle">All 18 Barangays</div>
                </div>
                <div class="bgy-number-badge" id="cardNumberBadge" title="Selected Barangay Marker">18</div>
            </div>

            <!-- Stats 2-col Grid for Selected Barangay -->
            <div class="stats-grid-2col">
                <div class="stat-box">
                    <div class="stat-box-label">Total Issues</div>
                    <div class="stat-box-val" id="summaryTotalIssues">0</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-label">Urgent Priority</div>
                    <div class="stat-box-val" id="summaryUrgentIssues" style="color:#DC2626;">0</div>
                </div>
            </div>

            <div class="stats-grid-2col">
                <div class="stat-box">
                    <div class="stat-box-label">Ongoing</div>
                    <div class="stat-box-val" id="summaryOngoingIssues" style="color:#D97706;">0</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-label">Resolved</div>
                    <div class="stat-box-val" id="summaryResolvedIssues" style="color:#059669;">0</div>
                </div>
            </div>

            <!-- Breakdown by Type (Section 3: Summary Metric Example) -->
            <div>
                <div class="breakdown-section-title">
                    <span>Breakdown by Issue Type</span>
                    <span style="font-size:0.70rem; color:var(--text-muted); font-weight:700;">Records</span>
                </div>
                <table class="breakdown-table" style="margin-top:6px;">
                    <thead>
                        <tr>
                            <th>Type of Issue</th>
                            <th class="num-col">Total</th>
                            <th class="num-col">Active</th>
                        </tr>
                    </thead>
                    <tbody id="summaryTypeBreakdownTbody">
                        <tr>
                            <td><span class="type-badge type-badge-political">Political</span></td>
                            <td class="num-col" id="bgyCountPolitical">0</td>
                            <td class="num-col" id="bgyActivePolitical">0</td>
                        </tr>
                        <tr>
                            <td><span class="type-badge type-badge-community">Community</span></td>
                            <td class="num-col" id="bgyCountCommunity">0</td>
                            <td class="num-col" id="bgyActiveCommunity">0</td>
                        </tr>
                        <tr>
                            <td><span class="type-badge type-badge-municipal-wide">Municipal-Wide</span></td>
                            <td class="num-col" id="bgyCountMunicipalWide">0</td>
                            <td class="num-col" id="bgyActiveMunicipalWide">0</td>
                        </tr>
                        <tr>
                            <td><span class="type-badge type-badge-policy">Policy</span></td>
                            <td class="num-col" id="bgyCountPolicy">0</td>
                            <td class="num-col" id="bgyActivePolicy">0</td>
                        </tr>
                        <tr>
                            <td><span class="type-badge type-badge-infrastructure">Infrastructure</span></td>
                            <td class="num-col" id="bgyCountInfrastructure">0</td>
                            <td class="num-col" id="bgyActiveInfrastructure">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn-encode-for-bgy" id="btnQuickAddForBgy">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                    stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span id="quickAddBgyLabel">Encode Issue for this Area</span>
            </button>
        </div>
    </div>

    <!-- 5. Detailed Issue List Table (Section 4) -->
    <div class="table-card-container">
        <div class="table-nav-ribbon">
            <div class="table-nav-title">
                <div style="background:#EFF6FF; padding:8px; border-radius:10px; color:var(--color-primary-blue);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                    </svg>
                </div>
                <div>
                    <h2>Detailed Issue Records</h2>
                    <p id="tableRecordsSubtitle" style="font-size:0.78rem; color:var(--text-muted); margin:0;">Complete log of recorded concerns, affected sectors, and resolution progress</p>
                </div>
            </div>

            <div class="table-tab-buttons">
                <button type="button" class="tab-btn active" data-tab="all" id="tabAllIssues">All Records</button>
                <button type="button" class="tab-btn" data-tab="Urgent" id="tabUrgentIssues">Urgent Only</button>
                <button type="button" class="tab-btn" data-tab="For Action" id="tabActionIssues">Action Needed</button>
                <button type="button" class="tab-btn" data-tab="Resolved" id="tabResolvedIssues">Resolved</button>
            </div>
        </div>

        <div style="overflow-x: auto; width:100%;">
            <table id="issuesDataTable" class="dataTable display" style="width:100%;">
                <thead>
                    <tr>
                        <th style="width:70px;">Ref ID</th>
                        <th>Type</th>
                        <th>Issue</th>
                        <th>Barangay(s) / Scope</th>
                        <th>Who Are Affected</th>
                        <th>Other Details</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Date Reported</th>
                        <th style="width:90px; text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="issuesTableBody">
                    <!-- Populated via DataTables AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL 1: Create / Encode Issue Modal -->
    <div class="modal-backdrop-custom" id="issueModalBackdrop">
        <div class="modal-box-card">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom" id="issueModalTitle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" style="color:var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Encode New Issue</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseIssueModal">&times;</button>
            </div>

            <form id="issueForm">
                @csrf
                <input type="hidden" id="formIssueId" name="issue_id" value="">

                <div class="modal-body-custom">
                    <!-- Scope & Affected Barangays Selection -->
                    <div class="scope-selector-box">
                        <div class="scope-toggle-row">
                            <label class="municipal-wide-toggle-label">
                                <input type="checkbox" id="formIsMunicipalWide" name="is_municipal_wide" value="1"
                                    style="width:18px; height:18px; accent-color:var(--color-primary-blue); cursor:pointer;">
                                <span>Municipal-Wide / All 18 Barangays</span>
                            </label>
                            <div style="display:flex; gap:8px;">
                                <button type="button" class="btn-modal-cancel" id="btnSelectAllBgy" style="padding:4px 8px; font-size:0.72rem;">Select All</button>
                                <button type="button" class="btn-modal-cancel" id="btnClearAllBgy" style="padding:4px 8px; font-size:0.72rem;">Clear</button>
                            </div>
                        </div>

                        <div style="font-size:0.72rem; font-weight:800; text-transform:uppercase; color:var(--text-muted); margin-bottom:8px;">
                            Or Select Specific Barangay(s) Affected:
                        </div>

                        <div class="multi-bgy-pills-grid" id="bgyCheckboxesContainer">
                            @foreach ($barangays as $bgy)
                                <label class="bgy-checkbox-label">
                                    <input type="checkbox" name="barangay_ids[]" value="{{ $bgy->id }}" class="bgy-check-item">
                                    <span>{{ $bgy->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 2-Col Form Grid -->
                    <div class="form-grid-2col">
                        <!-- Type of Issue -->
                        <div class="form-group-custom">
                            <label for="formIssueType">Type of Issue <span class="req">*</span></label>
                            <select id="formIssueType" name="issue_type" class="form-control-custom" required>
                                <option value="">Select Type...</option>
                                <option value="Political">Political</option>
                                <option value="Community">Community</option>
                                <option value="Municipal-Wide">Municipal-Wide</option>
                                <option value="Policy">Policy</option>
                                <option value="Infrastructure">Infrastructure</option>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div class="form-group-custom">
                            <label for="formPriority">Priority <span class="req">*</span></label>
                            <select id="formPriority" name="priority" class="form-control-custom" required>
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                        </div>

                        <!-- Issue Title -->
                        <div class="form-group-custom form-col-span-2">
                            <label for="formTitle">Issue Title / Concise Description <span class="req">*</span></label>
                            <input type="text" id="formTitle" name="title" class="form-control-custom" required
                                placeholder="e.g. Damaged Road along Coastal Access; Intermittent Water Supply">
                        </div>

                        <!-- Who Are Affected -->
                        <div class="form-group-custom form-col-span-2">
                            <label for="formWhoAffected">Who Are Affected <span class="req">*</span></label>
                            <input type="text" id="formWhoAffected" name="who_affected" class="form-control-custom" required
                                placeholder="e.g. Residents and motorists; 350 households in upland sitios; Local vendors">
                        </div>

                        <!-- Status & Date Reported -->
                        <div class="form-group-custom">
                            <label for="formStatus">Status <span class="req">*</span></label>
                            <select id="formStatus" name="status" class="form-control-custom" required>
                                <option value="New" selected>New</option>
                                <option value="Ongoing">Ongoing</option>
                                <option value="For Action">For Action</option>
                                <option value="Resolved">Resolved</option>
                            </select>
                        </div>

                        <div class="form-group-custom">
                            <label for="formDateReported">Date Reported <span class="req">*</span></label>
                            <input type="date" id="formDateReported" name="date_reported" class="form-control-custom" required
                                value="{{ date('Y-m-d') }}">
                        </div>

                        <!-- Other Details -->
                        <div class="form-group-custom form-col-span-2">
                            <label for="formDetails">Other Details / Background Information</label>
                            <textarea id="formDetails" name="details" class="form-control-custom" rows="3"
                                placeholder="Provide background, explanation, developments, specific concerns, and relevant history..."></textarea>
                        </div>

                        <!-- Action Taken / Developments -->
                        <div class="form-group-custom form-col-span-2">
                            <label for="formActionTaken">Action Taken / Developments (Optional)</label>
                            <textarea id="formActionTaken" name="action_taken" class="form-control-custom" rows="2"
                                placeholder="Updates on inspections, referrals, resolutions or ongoing initiatives..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelIssueModal">Cancel</button>
                    <button type="submit" class="btn-modal-submit" id="btnSubmitIssue">Save Issue Record</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: View Issue Details Modal -->
    <div class="modal-backdrop-custom" id="viewIssueModalBackdrop">
        <div class="modal-box-card" style="max-width:680px;">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" style="color:var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span>Issue Record Details</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseViewModal">&times;</button>
            </div>

            <div class="modal-body-custom" id="viewModalContent">
                <!-- Dynamically rendered via JS -->
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnCloseViewBtn">Close</button>
                <button type="button" class="btn-modal-submit" id="btnEditFromView">Edit Issue</button>
            </div>
        </div>
    </div>

    <!-- MODAL 3: Delete Confirmation Modal -->
    <div class="modal-backdrop-custom" id="deleteModalBackdrop">
        <div class="modal-box-card" style="max-width:440px;">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom" style="color:#DC2626;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <span>Confirm Delete Issue</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCancelDeleteX">&times;</button>
            </div>
            <div class="modal-body-custom">
                <p style="font-size:0.92rem; color:var(--text-main); margin-bottom:10px;">
                    Are you sure you want to permanently delete this issue record?
                </p>
                <div style="background:#FEF2F2; border:1px solid #FECACA; padding:10px 14px; border-radius:8px; font-size:0.84rem; color:#991B1B;"
                    id="deleteIssueSummaryText">
                    Issue details will be removed from the system.
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnCancelDeleteModal">Cancel</button>
                <button type="button" class="btn-modal-submit" id="btnConfirmDelete" style="background:#DC2626;">Delete Issue</button>
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
            const csrfToken = '{{ csrf_token() }}';

            // Coordinates for 18 Mariveles Barangays (Identical to Electoral, Assistance & Events)
            const pinCoordinates = {
                1:  { x: 71.00, y: 33.20 }, // Alion
                2:  { x: 88.80, y: 33.60 }, // Batangas II
                3:  { x: 63.30, y: 41.80 }, // Cabcaben
                4:  { x: 78.00, y: 42.80 }, // Lucanin
                5:  { x: 34.80, y: 40.50 }, // Balon-Anito
                6:  { x: 52.60, y: 42.20 }, // Maligaya
                7:  { x: 22.50, y: 47.20 }, // Biaan
                8:  { x: 44.50, y: 44.20 }, // Malaya
                9:  { x: 79.60, y: 50.10 }, // Townsite
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

            // State
            let currentStatus = 'all'; // 'all', 'New', 'Ongoing', 'For Action', 'Resolved'
            let currentMetric = 'total_issues'; // 'total_issues', 'urgent_issues', 'ongoing_issues', 'for_action_issues', 'resolved_issues'
            let selectedBarangayId = 'all'; // 'all' or 1..18
            let activeDataset = null;
            let dataTableInstance = null;
            let issueToDeleteId = null;
            let activeViewIssueId = null;

            // Pan & Zoom State (Default 1.5x identical to existing map modules)
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
            const tooltip = document.getElementById('mapHoverTooltip');
            const tipName = document.getElementById('tipName');
            const tipStat = document.getElementById('tipStat');

            const filterBarangay = document.getElementById('filterBarangay');
            const filterType = document.getElementById('filterType');
            const filterPriority = document.getElementById('filterPriority');
            const filterDateFrom = document.getElementById('filterDateFrom');
            const filterDateTo = document.getElementById('filterDateTo');
            const filterSearch = document.getElementById('filterSearch');
            const btnResetFilters = document.getElementById('btnResetFilters');
            const activeMetricLabel = document.getElementById('activeMetricLabel');

            // Modals
            const issueModalBackdrop = document.getElementById('issueModalBackdrop');
            const btnOpenAddModal = document.getElementById('btnOpenAddModal');
            const btnCloseIssueModal = document.getElementById('btnCloseIssueModal');
            const btnCancelIssueModal = document.getElementById('btnCancelIssueModal');
            const issueForm = document.getElementById('issueForm');
            const formIssueId = document.getElementById('formIssueId');
            const formIsMunicipalWide = document.getElementById('formIsMunicipalWide');
            const formIssueType = document.getElementById('formIssueType');
            const btnSelectAllBgy = document.getElementById('btnSelectAllBgy');
            const btnClearAllBgy = document.getElementById('btnClearAllBgy');
            const btnQuickAddForBgy = document.getElementById('btnQuickAddForBgy');

            const viewIssueModalBackdrop = document.getElementById('viewIssueModalBackdrop');
            const btnCloseViewModal = document.getElementById('btnCloseViewModal');
            const btnCloseViewBtn = document.getElementById('btnCloseViewBtn');
            const btnEditFromView = document.getElementById('btnEditFromView');

            const deleteModalBackdrop = document.getElementById('deleteModalBackdrop');
            const btnCancelDeleteX = document.getElementById('btnCancelDeleteX');
            const btnCancelDeleteModal = document.getElementById('btnCancelDeleteModal');
            const btnConfirmDelete = document.getElementById('btnConfirmDelete');

            function showToast(msg) {
                const toast = document.getElementById('toastNotice');
                document.getElementById('toastMessage').textContent = msg;
                toast.style.display = 'flex';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 3500);
            }

            // Sync Municipal-Wide toggle behavior
            formIsMunicipalWide.addEventListener('change', function() {
                const checked = this.checked;
                document.querySelectorAll('.bgy-check-item').forEach(cb => {
                    cb.checked = checked;
                });
            });

            formIssueType.addEventListener('change', function() {
                if (this.value === 'Municipal-Wide') {
                    formIsMunicipalWide.checked = true;
                    document.querySelectorAll('.bgy-check-item').forEach(cb => {
                        cb.checked = true;
                    });
                }
            });

            btnSelectAllBgy.addEventListener('click', function() {
                document.querySelectorAll('.bgy-check-item').forEach(cb => cb.checked = true);
            });

            btnClearAllBgy.addEventListener('click', function() {
                formIsMunicipalWide.checked = false;
                document.querySelectorAll('.bgy-check-item').forEach(cb => cb.checked = false);
            });

            // 1. Load Data via AJAX
            function loadData() {
                const params = new URLSearchParams();
                if (filterType.value !== 'all') params.append('issue_type', filterType.value);
                if (filterPriority.value !== 'all') params.append('priority', filterPriority.value);
                if (filterDateFrom.value) params.append('date_from', filterDateFrom.value);
                if (filterDateTo.value) params.append('date_to', filterDateTo.value);
                if (filterSearch.value.trim()) params.append('search', filterSearch.value.trim());
                params.append('metric', currentMetric);

                fetch(`{{ route('admin.issues.data') }}?${params.toString()}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            activeDataset = data;
                            renderMapPins(data.barangays);
                            selectBarangay(selectedBarangayId);
                        }
                    })
                    .catch(err => {
                        console.error('Failed to load issues dataset:', err);
                    });
            }

            // 2. Update KPI Summary Cards
            function updateKpis(kpis) {
                document.getElementById('kpiTotalIssues').textContent = Number(kpis.total_issues || 0).toLocaleString();
                document.getElementById('kpiOngoingIssues').textContent = Number(kpis.ongoing_issues || 0).toLocaleString();
                document.getElementById('kpiForActionIssues').textContent = Number(kpis.for_action_issues || 0).toLocaleString();
                document.getElementById('kpiResolvedIssues').textContent = Number(kpis.resolved_issues || 0).toLocaleString();
                document.getElementById('kpiUrgentIssues').textContent = Number(kpis.urgent_issues || 0).toLocaleString();

                // Pill counters
                document.getElementById('pillNewCount').textContent = kpis.new_issues || 0;
                document.getElementById('pillOngoingCount').textContent = kpis.ongoing_issues || 0;
                document.getElementById('pillActionCount').textContent = kpis.for_action_issues || 0;
                document.getElementById('pillResolvedCount').textContent = kpis.resolved_issues || 0;
            }

            // 3. Centralized Select Barangay (Syncs map highlight, top KPIs, sidebar detail summary & DataTable)
            function selectBarangay(bgyId) {
                selectedBarangayId = bgyId;

                // Sync Filter Dropdown
                if (filterBarangay && filterBarangay.value !== bgyId) {
                    filterBarangay.value = bgyId;
                }

                updateSelectedPinVisual();

                if (!activeDataset) return;

                const isAll = (bgyId === 'all');
                const isMunicipalWide = (bgyId === 'municipal_wide');
                const bgyData = (!isAll && !isMunicipalWide && activeDataset.barangays) ? activeDataset.barangays[bgyId] : null;

                const kpiTotalSub = document.getElementById('kpiTotalSub');
                const tableSubtitle = document.getElementById('tableRecordsSubtitle');

                if (bgyData) {
                    // Update Top KPI Cards & Status Pills
                    updateKpis(bgyData);
                    if (kpiTotalSub) {
                        kpiTotalSub.textContent = `Filtered for Brgy. ${bgyData.name}`;
                    }

                    // Update Sidebar Detail Card
                    document.getElementById('summaryMetaTag').textContent = 'Selected Barangay Overview';
                    document.getElementById('summaryBgyTitle').textContent = bgyData.name;
                    document.getElementById('cardNumberBadge').textContent = bgyData.id;
                    document.getElementById('quickAddBgyLabel').textContent = `Encode Issue for ${bgyData.name}`;

                    document.getElementById('summaryTotalIssues').textContent = bgyData.total_issues;
                    document.getElementById('summaryUrgentIssues').textContent = bgyData.urgent_issues;
                    document.getElementById('summaryOngoingIssues').textContent = bgyData.ongoing_issues;
                    document.getElementById('summaryResolvedIssues').textContent = bgyData.resolved_issues;

                    // Update Breakdown by Type table for selected barangay
                    const types = bgyData.types || {};
                    document.getElementById('bgyCountPolitical').textContent = types['Political'] ? types['Political'].count : 0;
                    document.getElementById('bgyActivePolitical').textContent = types['Political'] ? (types['Political'].ongoing + types['Political'].for_action) : 0;

                    document.getElementById('bgyCountCommunity').textContent = types['Community'] ? types['Community'].count : 0;
                    document.getElementById('bgyActiveCommunity').textContent = types['Community'] ? (types['Community'].ongoing + types['Community'].for_action) : 0;

                    document.getElementById('bgyCountMunicipalWide').textContent = types['Municipal-Wide'] ? types['Municipal-Wide'].count : 0;
                    document.getElementById('bgyActiveMunicipalWide').textContent = types['Municipal-Wide'] ? (types['Municipal-Wide'].ongoing + types['Municipal-Wide'].for_action) : 0;

                    document.getElementById('bgyCountPolicy').textContent = types['Policy'] ? types['Policy'].count : 0;
                    document.getElementById('bgyActivePolicy').textContent = types['Policy'] ? (types['Policy'].ongoing + types['Policy'].for_action) : 0;

                    document.getElementById('bgyCountInfrastructure').textContent = types['Infrastructure'] ? types['Infrastructure'].count : 0;
                    document.getElementById('bgyActiveInfrastructure').textContent = types['Infrastructure'] ? (types['Infrastructure'].ongoing + types['Infrastructure'].for_action) : 0;

                    if (tableSubtitle) {
                        tableSubtitle.textContent = `Showing recorded concerns affecting Brgy. ${bgyData.name}`;
                    }
                } else if (isMunicipalWide) {
                    const mwRecords = (activeDataset.records || []).filter(r => r.is_municipal_wide);
                    const mwKpis = {
                        total_issues: mwRecords.length,
                        ongoing_issues: mwRecords.filter(r => r.status === 'Ongoing').length,
                        for_action_issues: mwRecords.filter(r => r.status === 'For Action').length,
                        resolved_issues: mwRecords.filter(r => r.status === 'Resolved').length,
                        urgent_issues: mwRecords.filter(r => r.priority === 'Urgent').length,
                        new_issues: mwRecords.filter(r => r.status === 'New').length,
                    };

                    updateKpis(mwKpis);
                    if (kpiTotalSub) {
                        kpiTotalSub.textContent = 'Municipal-Wide Scope Only';
                    }

                    document.getElementById('summaryMetaTag').textContent = 'Consolidated Overview';
                    document.getElementById('summaryBgyTitle').textContent = 'Municipal-Wide Issues';
                    document.getElementById('cardNumberBadge').textContent = 'MW';
                    document.getElementById('quickAddBgyLabel').textContent = 'Encode Municipal-Wide Issue';

                    document.getElementById('summaryTotalIssues').textContent = mwKpis.total_issues;
                    document.getElementById('summaryUrgentIssues').textContent = mwKpis.urgent_issues;
                    document.getElementById('summaryOngoingIssues').textContent = mwKpis.ongoing_issues;
                    document.getElementById('summaryResolvedIssues').textContent = mwKpis.resolved_issues;

                    ['Political', 'Community', 'Municipal-Wide', 'Policy', 'Infrastructure'].forEach(type => {
                        const matching = mwRecords.filter(r => r.issue_type === type);
                        const countEl = document.getElementById(`bgyCount${type.replace(/[^a-zA-Z]/g, '')}`);
                        const activeEl = document.getElementById(`bgyActive${type.replace(/[^a-zA-Z]/g, '')}`);
                        if (countEl) countEl.textContent = matching.length;
                        if (activeEl) activeEl.textContent = matching.filter(r => r.status === 'Ongoing' || r.status === 'For Action').length;
                    });

                    if (tableSubtitle) {
                        tableSubtitle.textContent = 'Showing recorded concerns with Municipal-Wide scope';
                    }
                } else {
                    updateKpis(activeDataset.kpis);
                    if (kpiTotalSub) {
                        kpiTotalSub.textContent = 'Across Mariveles Municipality';
                    }

                    document.getElementById('summaryMetaTag').textContent = 'Consolidated Mariveles Overview';
                    document.getElementById('summaryBgyTitle').textContent = 'All 18 Barangays';
                    document.getElementById('cardNumberBadge').textContent = '18';
                    document.getElementById('quickAddBgyLabel').textContent = 'Encode Issue for Mariveles';

                    document.getElementById('summaryTotalIssues').textContent = activeDataset.kpis.total_issues;
                    document.getElementById('summaryUrgentIssues').textContent = activeDataset.kpis.urgent_issues;
                    document.getElementById('summaryOngoingIssues').textContent = activeDataset.kpis.ongoing_issues;
                    document.getElementById('summaryResolvedIssues').textContent = activeDataset.kpis.resolved_issues;

                    // Overall type breakdown
                    const typeMap = {};
                    (activeDataset.type_breakdown || []).forEach(t => { typeMap[t.type] = t; });

                    document.getElementById('bgyCountPolitical').textContent = typeMap['Political'] ? typeMap['Political'].count : 0;
                    document.getElementById('bgyActivePolitical').textContent = typeMap['Political'] ? (typeMap['Political'].ongoing + typeMap['Political'].for_action) : 0;

                    document.getElementById('bgyCountCommunity').textContent = typeMap['Community'] ? typeMap['Community'].count : 0;
                    document.getElementById('bgyActiveCommunity').textContent = typeMap['Community'] ? (typeMap['Community'].ongoing + typeMap['Community'].for_action) : 0;

                    document.getElementById('bgyCountMunicipalWide').textContent = typeMap['Municipal-Wide'] ? typeMap['Municipal-Wide'].count : 0;
                    document.getElementById('bgyActiveMunicipalWide').textContent = typeMap['Municipal-Wide'] ? (typeMap['Municipal-Wide'].ongoing + typeMap['Municipal-Wide'].for_action) : 0;

                    document.getElementById('bgyCountPolicy').textContent = typeMap['Policy'] ? typeMap['Policy'].count : 0;
                    document.getElementById('bgyActivePolicy').textContent = typeMap['Policy'] ? (typeMap['Policy'].ongoing + typeMap['Policy'].for_action) : 0;

                    document.getElementById('bgyCountInfrastructure').textContent = typeMap['Infrastructure'] ? typeMap['Infrastructure'].count : 0;
                    document.getElementById('bgyActiveInfrastructure').textContent = typeMap['Infrastructure'] ? (typeMap['Infrastructure'].ongoing + typeMap['Infrastructure'].for_action) : 0;

                    if (tableSubtitle) {
                        tableSubtitle.textContent = 'Complete log of recorded concerns, affected sectors, and resolution progress';
                    }
                }

                // Filter records for table
                filterAndPopulateTable();
            }

            // 4. Render Map Hotspot Pins
            function renderMapPins(barangays) {
                pinsContainer.innerHTML = '';

                Object.keys(pinCoordinates).forEach(id => {
                    const coords = pinCoordinates[id];
                    const bgy = barangays[id];
                    const name = bgy ? bgy.name : (barangayNames[id] || `Barangay ${id}`);
                    const metricVal = bgy ? (bgy.metric_value || 0) : 0;

                    const pin = document.createElement('div');
                    pin.className = 'map-hotspot-pin';
                    pin.id = `hotspot-pin-${id}`;
                    pin.dataset.bgyId = id;
                    pin.style.left = `${coords.x}%`;
                    pin.style.top = `${coords.y}%`;

                    // Dynamic color according to intensity & metric
                    if (currentMetric === 'urgent_issues') {
                        pin.style.background = metricVal > 0 ? '#DC2626' : '#64748B';
                    } else if (currentMetric === 'resolved_issues') {
                        pin.style.background = metricVal > 0 ? '#059669' : '#64748B';
                    } else if (currentMetric === 'ongoing_issues') {
                        pin.style.background = metricVal > 0 ? '#D97706' : '#64748B';
                    } else {
                        pin.style.background = metricVal > 3 ? '#075998' : (metricVal > 0 ? '#0284C7' : '#475569');
                    }

                    pin.innerHTML = `<span>${name}</span> <span style="background:rgba(0,0,0,0.28); padding:0 3px; border-radius:4px; margin-left:3px; font-weight:800;">${metricVal}</span>`;

                    // Hover events for tooltip
                    pin.addEventListener('mouseenter', function(e) {
                        tipName.textContent = name;
                        const labelText = activeMetricLabel.textContent;
                        tipStat.textContent = `${labelText}: ${metricVal}`;
                        if (bgy) {
                            tipStat.innerHTML = `Total Issues: <strong>${bgy.total_issues}</strong> &bull; Urgent: <strong style="color:#F87171;">${bgy.urgent_issues}</strong> &bull; Ongoing: <strong style="color:#FBBF24;">${bgy.ongoing_issues}</strong>`;
                        }

                        tooltip.style.display = 'block';
                        positionTooltip(e);
                    });

                    pin.addEventListener('mousemove', function(e) {
                        positionTooltip(e);
                    });

                    pin.addEventListener('mouseleave', function() {
                        tooltip.style.display = 'none';
                    });

                    // Click to focus and select barangay (toggles back to all if clicked again)
                    pin.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (selectedBarangayId == id) {
                            selectBarangay('all');
                        } else {
                            selectBarangay(id);
                        }
                    });

                    pinsContainer.appendChild(pin);
                });

                updateSelectedPinVisual();
            }

            function positionTooltip(e) {
                const stageRect = mapStage.getBoundingClientRect();
                const x = e.clientX - stageRect.left;
                const y = e.clientY - stageRect.top;
                tooltip.style.left = `${x}px`;
                tooltip.style.top = `${y}px`;
            }

            function updateSelectedPinVisual() {
                document.querySelectorAll('.map-hotspot-pin').forEach(pin => {
                    if (selectedBarangayId !== 'all' && selectedBarangayId !== 'municipal_wide' && pin.dataset.bgyId === selectedBarangayId.toString()) {
                        pin.classList.add('active-selected');
                    } else {
                        pin.classList.remove('active-selected');
                    }
                });
            }

            // 5. Filter & Populate DataTable
            function filterAndPopulateTable() {
                if (!activeDataset || !activeDataset.records) return;

                let records = activeDataset.records;

                // Filter by selected barangay if applicable
                if (selectedBarangayId !== 'all') {
                    if (selectedBarangayId === 'municipal_wide') {
                        records = records.filter(r => r.is_municipal_wide);
                    } else {
                        const numId = parseInt(selectedBarangayId);
                        records = records.filter(r => r.is_municipal_wide || (r.barangay_ids && r.barangay_ids.includes(numId)));
                    }
                }

                // Filter by status pill if applicable
                if (currentStatus !== 'all') {
                    records = records.filter(r => r.status === currentStatus);
                }

                // Check active table tab
                const activeTab = document.querySelector('.table-tab-buttons .tab-btn.active');
                if (activeTab) {
                    const tabVal = activeTab.dataset.tab;
                    if (tabVal === 'Urgent') {
                        records = records.filter(r => r.priority === 'Urgent');
                    } else if (tabVal === 'For Action') {
                        records = records.filter(r => r.status === 'For Action');
                    } else if (tabVal === 'Resolved') {
                        records = records.filter(r => r.status === 'Resolved');
                    }
                }

                // Destroy previous DataTable instance
                if (dataTableInstance) {
                    dataTableInstance.destroy();
                }

                const tbody = document.getElementById('issuesTableBody');
                tbody.innerHTML = '';

                records.forEach(item => {
                    const tr = document.createElement('tr');

                    // Badges
                    const typeClass = `type-badge-${item.issue_type.toLowerCase().replace(/[^a-z0-9]/g, '-')}`;
                    const priorityClass = `priority-badge-${item.priority.toLowerCase()}`;
                    const statusClass = `status-badge-${item.status.toLowerCase().replace(/[^a-z0-9]/g, '-')}`;

                    tr.innerHTML = `
                        <td style="font-weight:700; color:var(--text-muted);">#${String(item.id).padStart(4, '0')}</td>
                        <td><span class="type-badge ${typeClass}">${item.issue_type}</span></td>
                        <td>
                            <strong style="color:var(--color-deep-navy); font-size:0.90rem;">${escapeHtml(item.title)}</strong>
                        </td>
                        <td>
                            <span style="font-weight:700; color:${item.is_municipal_wide ? 'var(--color-primary-blue)' : 'inherit'};">
                                ${escapeHtml(item.scope_text)}
                            </span>
                        </td>
                        <td>${escapeHtml(item.who_affected)}</td>
                        <td style="max-width:260px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="${escapeHtml(item.details)}">
                            ${escapeHtml(item.details || '—')}
                        </td>
                        <td><span class="status-badge ${statusClass}">${item.status}</span></td>
                        <td><span class="priority-badge ${priorityClass}">${item.priority}</span></td>
                        <td style="white-space:nowrap;">${item.formatted_date}</td>
                        <td style="text-align:center;">
                            <div class="action-btns-wrap">
                                <button type="button" class="btn-action-icon btn-view-issue" data-id="${item.id}" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                                <button type="button" class="btn-action-icon btn-edit-issue" data-id="${item.id}" title="Edit Issue">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button type="button" class="btn-action-icon btn-delete btn-delete-issue" data-id="${item.id}" data-title="${escapeHtml(item.title)}" title="Delete Issue">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    `;

                    tbody.appendChild(tr);
                });

                // Initialize DataTable (Matching Events Module)
                dataTableInstance = $('#issuesDataTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    order: [[8, 'desc']], // Sort by date reported desc
                    columnDefs: [
                        { orderable: false, targets: [9] } // Action column
                    ],
                    language: {
                        search: "Search Records:",
                        searchPlaceholder: "Search any field in table...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ issues",
                        infoEmpty: "Showing 0 to 0 of 0 issues",
                        infoFiltered: "(filtered from _MAX_ total issues)",
                        paginate: {
                            first: "«",
                            previous: "‹",
                            next: "›",
                            last: "»"
                        }
                    }
                });

                attachActionListeners();
            }

            function attachActionListeners() {
                // View Issue
                document.querySelectorAll('.btn-view-issue').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        viewIssueDetails(id);
                    });
                });

                // Edit Issue
                document.querySelectorAll('.btn-edit-issue').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        openEditModal(id);
                    });
                });

                // Delete Issue
                document.querySelectorAll('.btn-delete-issue').forEach(btn => {
                    btn.addEventListener('click', function() {
                        issueToDeleteId = this.dataset.id;
                        const title = this.dataset.title || 'this issue';
                        document.getElementById('deleteIssueSummaryText').textContent = `Are you sure you want to delete: "${title}"?`;
                        deleteModalBackdrop.style.display = 'flex';
                    });
                });
            }

            function escapeHtml(str) {
                if (!str) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            // 6. View Issue Details Modal
            function viewIssueDetails(id) {
                activeViewIssueId = id;
                fetch(`{{ url('admin/issues/record') }}/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const issue = data.issue;
                            const typeClass = `type-badge-${issue.issue_type.toLowerCase().replace(/[^a-z0-9]/g, '-')}`;
                            const priorityClass = `priority-badge-${issue.priority.toLowerCase()}`;
                            const statusClass = `status-badge-${issue.status.toLowerCase().replace(/[^a-z0-9]/g, '-')}`;

                            const content = `
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:8px;">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="type-badge ${typeClass}">${issue.issue_type}</span>
                                        <span class="priority-badge ${priorityClass}">${issue.priority} Priority</span>
                                        <span class="status-badge ${statusClass}">${issue.status}</span>
                                    </div>
                                    <span style="font-size:0.80rem; font-weight:700; color:var(--text-muted);">Ref: #${String(issue.id).padStart(4, '0')}</span>
                                </div>

                                <h2 style="font-size:1.25rem; font-weight:800; color:var(--color-deep-navy); margin-bottom:14px; line-height:1.3;">
                                    ${escapeHtml(issue.title)}
                                </h2>

                                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:14px; margin-bottom:16px;">
                                    <div style="font-size:0.72rem; font-weight:800; text-transform:uppercase; color:var(--text-muted); margin-bottom:4px;">
                                        Geographic Scope / Affected Area
                                    </div>
                                    <div style="font-size:0.92rem; font-weight:800; color:var(--color-primary-blue);">
                                        ${escapeHtml(issue.scope_text)}
                                    </div>
                                </div>

                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
                                    <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:12px;">
                                        <div style="font-size:0.70rem; font-weight:800; text-transform:uppercase; color:var(--text-muted); margin-bottom:4px;">
                                            Who Are Affected
                                        </div>
                                        <div style="font-size:0.88rem; font-weight:700; color:var(--color-deep-navy);">
                                            ${escapeHtml(issue.who_affected)}
                                        </div>
                                    </div>

                                    <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:12px;">
                                        <div style="font-size:0.70rem; font-weight:800; text-transform:uppercase; color:var(--text-muted); margin-bottom:4px;">
                                            Date First Reported
                                        </div>
                                        <div style="font-size:0.88rem; font-weight:700; color:var(--color-deep-navy);">
                                            ${issue.formatted_date}
                                        </div>
                                    </div>
                                </div>

                                <div style="margin-bottom:16px;">
                                    <div style="font-size:0.75rem; font-weight:800; text-transform:uppercase; color:var(--text-muted); margin-bottom:6px;">
                                        Background & Other Details
                                    </div>
                                    <div style="background:#FFFFFF; border:1px solid #E2E8F0; border-radius:8px; padding:12px; font-size:0.88rem; line-height:1.5; color:var(--color-deep-navy); white-space:pre-wrap;">
                                        ${escapeHtml(issue.details || 'No additional details provided.')}
                                    </div>
                                </div>

                                ${issue.action_taken ? `
                                    <div style="margin-bottom:16px;">
                                        <div style="font-size:0.75rem; font-weight:800; text-transform:uppercase; color:#047857; margin-bottom:6px;">
                                            Developments & Action Taken
                                        </div>
                                        <div style="background:#ECFDF5; border:1px solid #A7F3D0; border-radius:8px; padding:12px; font-size:0.88rem; line-height:1.5; color:#065F46; white-space:pre-wrap;">
                                            ${escapeHtml(issue.action_taken)}
                                        </div>
                                    </div>
                                ` : ''}

                                <div style="font-size:0.72rem; color:var(--text-muted); display:flex; justify-content:space-between; border-top:1px solid #EEF2F6; padding-top:10px;">
                                    <span>Created: ${issue.created_at}</span>
                                    <span>Last Updated: ${issue.updated_at}</span>
                                </div>
                            `;

                            document.getElementById('viewModalContent').innerHTML = content;
                            viewIssueModalBackdrop.style.display = 'flex';
                        }
                    });
            }

            btnEditFromView.addEventListener('click', function() {
                viewIssueModalBackdrop.style.display = 'none';
                if (activeViewIssueId) {
                    openEditModal(activeViewIssueId);
                }
            });

            // 7. Open Add / Edit Modal
            function openAddModal(preselectedBgyId = null) {
                issueForm.reset();
                formIssueId.value = '';
                document.getElementById('issueModalTitle').querySelector('span').textContent = 'Encode New Issue';
                document.getElementById('btnSubmitIssue').textContent = 'Save Issue Record';

                document.querySelectorAll('.bgy-check-item').forEach(cb => cb.checked = false);

                if (preselectedBgyId && preselectedBgyId !== 'all' && preselectedBgyId !== 'municipal_wide') {
                    const cb = document.querySelector(`.bgy-check-item[value="${preselectedBgyId}"]`);
                    if (cb) cb.checked = true;
                    formIsMunicipalWide.checked = false;
                } else if (preselectedBgyId === 'municipal_wide') {
                    formIsMunicipalWide.checked = true;
                    document.querySelectorAll('.bgy-check-item').forEach(cb => cb.checked = true);
                    formIssueType.value = 'Municipal-Wide';
                }

                issueModalBackdrop.style.display = 'flex';
            }

            function openEditModal(id) {
                issueForm.reset();
                formIssueId.value = id;
                document.getElementById('issueModalTitle').querySelector('span').textContent = 'Edit Issue Record';
                document.getElementById('btnSubmitIssue').textContent = 'Update Issue Record';

                fetch(`{{ url('admin/issues/record') }}/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const issue = data.issue;
                            document.getElementById('formTitle').value = issue.title;
                            document.getElementById('formIssueType').value = issue.issue_type;
                            document.getElementById('formWhoAffected').value = issue.who_affected;
                            document.getElementById('formDetails').value = issue.details;
                            document.getElementById('formStatus').value = issue.status;
                            document.getElementById('formPriority').value = issue.priority;
                            document.getElementById('formDateReported').value = issue.date_reported;
                            document.getElementById('formActionTaken').value = issue.action_taken;

                            formIsMunicipalWide.checked = issue.is_municipal_wide;

                            const attachedIds = issue.barangay_ids || [];
                            document.querySelectorAll('.bgy-check-item').forEach(cb => {
                                cb.checked = attachedIds.includes(parseInt(cb.value));
                            });

                            issueModalBackdrop.style.display = 'flex';
                        }
                    });
            }

            btnOpenAddModal.addEventListener('click', () => openAddModal());

            btnQuickAddForBgy.addEventListener('click', () => {
                openAddModal(selectedBarangayId);
            });

            btnCloseIssueModal.addEventListener('click', () => { issueModalBackdrop.style.display = 'none'; });
            btnCancelIssueModal.addEventListener('click', () => { issueModalBackdrop.style.display = 'none'; });

            btnCloseViewModal.addEventListener('click', () => { viewIssueModalBackdrop.style.display = 'none'; });
            btnCloseViewBtn.addEventListener('click', () => { viewIssueModalBackdrop.style.display = 'none'; });

            btnCancelDeleteX.addEventListener('click', () => { deleteModalBackdrop.style.display = 'none'; });
            btnCancelDeleteModal.addEventListener('click', () => { deleteModalBackdrop.style.display = 'none'; });

            // 8. Submit Add / Edit Form
            issueForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const id = formIssueId.value;
                const url = id ? `{{ url('admin/issues/update') }}/${id}` : `{{ route('admin.issues.store') }}`;

                const formData = new FormData(issueForm);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        issueModalBackdrop.style.display = 'none';
                        showToast(data.message || 'Issue saved successfully!');
                        loadData();
                    } else {
                        alert(data.message || 'An error occurred while saving the issue.');
                    }
                })
                .catch(err => {
                    console.error('Save issue failed:', err);
                    alert('Failed to save issue. Please check your inputs.');
                });
            });

            // 9. Confirm Delete Issue
            btnConfirmDelete.addEventListener('click', function() {
                if (!issueToDeleteId) return;

                fetch(`{{ url('admin/issues/delete') }}/${issueToDeleteId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    deleteModalBackdrop.style.display = 'none';
                    if (data.success) {
                        showToast(data.message || 'Issue deleted successfully!');
                        loadData();
                    } else {
                        alert(data.message || 'Failed to delete issue.');
                    }
                })
                .catch(err => {
                    deleteModalBackdrop.style.display = 'none';
                    console.error('Delete issue failed:', err);
                });
            });

            // 10. Filter Change Handlers
            filterBarangay.addEventListener('change', function() {
                selectBarangay(this.value);
            });

            filterType.addEventListener('change', loadData);
            filterPriority.addEventListener('change', loadData);
            filterDateFrom.addEventListener('change', loadData);
            filterDateTo.addEventListener('change', loadData);

            let searchTimeout = null;
            filterSearch.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(loadData, 300);
            });

            // Status Filter Pills
            document.querySelectorAll('.status-pill-group .status-pill-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.status-pill-group .status-pill-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentStatus = this.dataset.status;
                    filterAndPopulateTable();
                });
            });

            // Metric Switcher Pills
            document.querySelectorAll('.metric-pill-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.metric-pill-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentMetric = this.dataset.metric;
                    activeMetricLabel.textContent = this.querySelector('span').textContent;
                    loadData();
                });
            });

            // Reset Filters
            btnResetFilters.addEventListener('click', function() {
                currentStatus = 'all';
                currentMetric = 'total_issues';
                selectedBarangayId = 'all';

                document.querySelectorAll('.status-pill-group .status-pill-btn').forEach(b => b.classList.remove('active'));
                document.getElementById('pillStatusAll').classList.add('active');

                document.querySelectorAll('.metric-pill-btn').forEach(b => b.classList.remove('active'));
                document.querySelector('.metric-pill-btn[data-metric="total_issues"]').classList.add('active');
                activeMetricLabel.textContent = 'Total Issues';

                filterBarangay.value = 'all';
                filterType.value = 'all';
                filterPriority.value = 'all';
                filterDateFrom.value = '';
                filterDateTo.value = '';
                filterSearch.value = '';

                document.querySelectorAll('.table-tab-buttons .tab-btn').forEach(b => b.classList.remove('active'));
                document.getElementById('tabAllIssues').classList.add('active');

                loadData();
            });

            // Table Navigation Tabs
            document.querySelectorAll('.table-tab-buttons .tab-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.table-tab-buttons .tab-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    filterAndPopulateTable();
                });
            });


            // 11. Interactive Map Pan & Zoom Controls (Exact Match to Electoral & Assistance Modules)
            function applyTransform() {
                mapViewport.style.transform = `translate(${panX}px, ${panY}px) scale(${currentScale})`;
            }

            document.getElementById('btnZoomIn').addEventListener('click', function() {
                currentScale = Math.min(currentScale + 0.3, 3.5);
                applyTransform();
            });

            document.getElementById('btnZoomOut').addEventListener('click', function() {
                currentScale = Math.max(currentScale - 0.3, 0.8);
                applyTransform();
            });

            document.getElementById('btnResetZoom').addEventListener('click', function() {
                currentScale = DEFAULT_SCALE;
                panX = 0;
                panY = 0;
                applyTransform();
            });

            mapStage.addEventListener('mousedown', function(e) {
                if (e.target.closest('.map-floating-controls') || e.target.closest('.map-hotspot-pin')) return;
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
                isDragging = false;
                mapStage.classList.remove('dragging');
            });

            // Wheel zoom
            mapStage.addEventListener('wheel', function(e) {
                e.preventDefault();
                const delta = e.deltaY > 0 ? -0.15 : 0.15;
                currentScale = Math.min(Math.max(currentScale + delta, 0.8), 3.5);
                applyTransform();
            }, { passive: false });

            // Initialize Pan & Zoom transform
            applyTransform();

            // Click on map empty background to reset barangay selection to 'all'
            mapStage.addEventListener('click', function(e) {
                if (!e.target.closest('.map-hotspot-pin') && !e.target.closest('.map-floating-controls')) {
                    selectBarangay('all');
                }
            });

            // Initial Data Load
            loadData();
        });
    </script>
@endsection
