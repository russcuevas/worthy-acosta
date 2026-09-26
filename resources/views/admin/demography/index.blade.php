@extends('layouts.app')

@section('title', 'Demography Module - Mariveles, Bataan')
@section('user_name', 'Russel Acosta')
@section('user_role_label', isset($role) && $role === 'assistant' ? 'Political Officer' : 'Administrator')
@section('user_initials', 'WA')

@section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        /* Top Filter & Controls Header (Consistent with Electoral, Assistance, Events, Directory & Issues) */
        .demography-controls-header {
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

        .btn-secondary-action {
            background: #F8FAFC;
            color: var(--color-deep-navy);
            border: 1.5px solid #CBD5E1;
            padding: 8px 16px;
            border-radius: var(--radius-md);
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all var(--transition-fast);
        }

        .btn-secondary-action:hover {
            background: #F1F5F9;
            border-color: #94A3B8;
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

        .filter-select:focus,
        .filter-input:focus {
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.12);
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

        /* 2. Municipal KPI Summary Cards (Identical Styling to Issues, Events & Assistance) */
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
        .kpi-card.card-sector::before { background: linear-gradient(90deg, #F59E0B, #FBBF24); }
        .kpi-card.card-concentration::before { background: linear-gradient(90deg, #6366F1, #818CF8); }
        .kpi-card.card-sectors-count::before { background: linear-gradient(90deg, #10B981, #34D399); }
        .kpi-card.card-coverage::before { background: linear-gradient(90deg, #0284C7, #38BDF8); }

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
        .card-sector .kpi-icon-badge { background: #FEF3C7; color: #D97706; }
        .card-concentration .kpi-icon-badge { background: #EEF2FF; color: #4F46E5; }
        .card-sectors-count .kpi-icon-badge { background: #D1FAE5; color: #059669; }
        .card-coverage .kpi-icon-badge { background: #E0F2FE; color: #0284C7; }

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
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* 3. Map Legend & Sector Switcher Ribbon (Exact Match to Issues) */
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

        /* 4. Main Visual Grid: Map Canvas Stage (1fr) + Right Barangay Detail Sidebar (380px) */
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

        /* Map Canvas Stage (Exact Electoral, Assistance & Issues Modules) */
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

        /* Dynamic Hotspot Pins (Slanted & Compact Capsule) */
        .map-hotspot-pin {
            position: absolute;
            padding: 2.5px 7px;
            border-radius: 8px;
            transform: translate(-50%, -50%) rotate(-48deg);
            transform-origin: center center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.52rem;
            font-weight: 800;
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
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.7), 0 8px 25px rgba(0, 0, 0, 0.85) !important;
            z-index: 60;
        }

        /* Floating Tooltip */
        #mapHoverTooltip {
            position: absolute;
            display: none;
            background: rgba(16, 42, 78, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 8px;
            padding: 10px 16px;
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
            font-size: 0.92rem;
            font-weight: 800;
            color: #FFFFFF;
        }

        #mapHoverTooltip .tip-detail {
            font-size: 0.78rem;
            color: #D6E8F6;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        #mapHoverTooltip .tip-stat {
            font-size: 0.74rem;
            color: #94A3B8;
            margin-top: 2px;
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

        /* Right: Barangay Detail Sidebar (Spec Section 3 & 5) */
        .sidebar-detail-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            box-sizing: border-box;
        }

        .sidebar-header-badge {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            border-bottom: 1px solid #EEF2F6;
        }

        .sidebar-header-badge .detail-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin: 0;
        }

        .sidebar-header-badge .detail-meta {
            font-size: 0.74rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .bgy-number-badge {
            background: #EFF6FF;
            color: var(--color-primary-blue);
            font-size: 0.85rem;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 20px;
            border: 1px solid #BFDBFE;
        }

        /* Total Barangay Population Pill Box */
        .bgy-total-box {
            background: linear-gradient(135deg, #075998 0%, #0B192C 100%);
            border-radius: var(--radius-md);
            padding: 14px 18px;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 14px rgba(7, 89, 152, 0.25);
        }

        .bgy-total-label {
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #BAE6FD;
            font-weight: 700;
        }

        .bgy-total-val {
            font-size: 1.6rem;
            font-weight: 800;
            color: #FFFFFF;
            line-height: 1.1;
        }

        /* Sector Breakdown Progress Bars (Spec Section 3) */
        .sector-breakdown-section {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sector-breakdown-title {
            font-size: 0.76rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sector-progress-row {
            background: #F8FAFC;
            border: 1px solid #EEF2F6;
            border-radius: var(--radius-sm);
            padding: 8px 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            transition: all var(--transition-fast);
        }

        .sector-progress-row:hover {
            background: #FFFFFF;
            border-color: #CBD5E1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .sector-progress-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--color-deep-navy);
        }

        .sector-name-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .sector-color-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .sector-count-badge {
            font-weight: 800;
            color: var(--color-deep-navy);
        }

        .progress-bar-track {
            height: 6px;
            background: #E2E8F0;
            border-radius: 3px;
            overflow: hidden;
            width: 100%;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.4s ease;
        }

        /* Program Planning Scope Box (Spec Section 5) */
        .planning-scope-box {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-left: 4px solid #F59E0B;
            border-radius: var(--radius-sm);
            padding: 12px 14px;
            font-size: 0.78rem;
            color: #92400E;
            line-height: 1.45;
        }

        .planning-scope-box strong {
            color: #78350F;
            display: block;
            margin-bottom: 2px;
        }

        /* 5. Program Planning Reference Banner (Spec Section 5 & 10) */
        .planning-notice-banner {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-left: 4px solid #2563EB;
            border-radius: var(--radius-md);
            padding: 12px 18px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            box-shadow: 0 1px 3px rgba(37, 99, 235, 0.08);
        }

        .notice-icon {
            color: #2563EB;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .notice-content {
            font-size: 0.82rem;
            color: #1E40AF;
            line-height: 1.45;
        }

        .notice-content strong {
            color: #1E3A8A;
            font-weight: 800;
        }

        /* 6. Municipal-Wide Sector Summary Cards (Spec Section 6) */
        .municipal-summary-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 20px 24px;
            margin-bottom: 24px;
            width: 100%;
            box-sizing: border-box;
        }

        .municipal-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .municipal-section-header h3 {
            font-size: 1.05rem;
            font-weight: 800;
            margin: 0;
            color: var(--color-deep-navy);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .municipal-section-header p {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin: 2px 0 0 0;
        }

        .municipal-sectors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 14px;
        }

        .sector-summary-card {
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: var(--radius-md);
            padding: 14px 16px;
            transition: all var(--transition-fast);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sector-summary-card:hover {
            border-color: #94A3B8;
            background: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .sector-card-top-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .sector-summary-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .sector-summary-name {
            font-size: 0.90rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sector-summary-val {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .sector-summary-meta {
            font-size: 0.74rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #EEF2F6;
            padding-top: 6px;
        }

        /* 7. Searchable Records DataTable Section */
        .table-card-container {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 22px 24px;
            margin-bottom: 24px;
            width: 100%;
            box-sizing: border-box;
        }

        .section-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 16px;
        }

        .section-header-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--color-deep-navy);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
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
            font-size: 0.85rem;
        }

        table.dataTable tbody tr:hover {
            background-color: #F8FAFC !important;
        }

        .table-sector-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 10px;
            border-radius: 14px;
            font-size: 0.78rem;
            font-weight: 700;
            background: #F1F5F9;
        }

        .btn-table-action {
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

        .btn-table-action:hover {
            background: #F1F5F9;
            color: var(--color-primary-blue);
            border-color: #CBD5E1;
        }

        .btn-table-action.delete-action:hover {
            background: #FEF2F2;
            color: #DC2626;
            border-color: #FECACA;
        }

        /* Custom DataTables Pagination Matching Events, Directory & Issues */
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
            max-width: 640px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            border: 1px solid var(--card-border);
            animation: modalFadeIn 0.2s ease-out;
            display: flex;
            flex-direction: column;
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
            font-size: 1.2rem;
            cursor: pointer;
            color: #64748B;
            display: flex;
            align-items: center;
            justify-content: center;
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
            border: 1.5px solid #CBD5E1;
            border-radius: var(--radius-sm);
            font-size: 0.88rem;
            color: var(--color-deep-navy);
            outline: none;
            transition: all var(--transition-fast);
            box-sizing: border-box;
            width: 100%;
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

        .modal-footer-custom {
            padding: 16px 24px;
            border-top: 1px solid #EEF2F6;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            background: #F8FAFC;
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            flex-shrink: 0;
        }

        .btn-modal-cancel {
            background: #FFFFFF;
            border: 1.5px solid #CBD5E1;
            color: #475569;
            padding: 9px 18px;
            border-radius: var(--radius-md);
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .btn-modal-cancel:hover {
            background: #F1F5F9;
            border-color: #94A3B8;
        }

        .btn-modal-submit {
            background: var(--color-primary-blue);
            border: none;
            color: #FFFFFF;
            padding: 9px 20px;
            border-radius: var(--radius-md);
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(7, 89, 152, 0.3);
            transition: all var(--transition-fast);
        }

        .btn-modal-submit:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        /* Toast Notice */
        #toastNotice {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #0B192C;
            color: #FFFFFF;
            padding: 12px 20px;
            border-radius: var(--radius-md);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            z-index: 10000;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }

        #toastNotice.show {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        /* Sector Manager List Styles */
        .sectors-manager-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sector-manager-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: var(--radius-md);
        }

        .sector-manager-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sector-manager-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Responsive Breakpoints Matching Events, Directory, Issues, Survey */
        @media (max-width: 992px) {
            .demography-controls-header {
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

            .header-actions-right .btn-encode-data,
            .header-actions-right .btn-secondary-action {
                flex: 1;
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

            .filter-item {
                width: 100%;
                min-width: 0;
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
            .form-grid-2col {
                grid-template-columns: 1fr;
            }

            .form-col-span-2 {
                grid-column: span 1;
            }

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
        }

        @media (max-width: 640px) {
            .demography-controls-header {
                padding: 14px 16px;
            }

            .header-title-left h1 {
                font-size: 1.15rem;
            }

            .header-title-left p {
                font-size: 0.76rem;
            }

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
                text-align: center;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <!-- 1. Header & Controls Ribbon (Consistent with Electoral, Assistance & Events) -->
    <div class="demography-controls-header">
        <div class="header-title-bar-row">
            <div class="header-title-left">
                <div class="header-title-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.999-3.199a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
                <div>
                    <h1>Demography Module</h1>
                    <p>Barangay-Level Demographic Analysis &amp; Community Sector Mapping &bull; Municipality of Mariveles</p>
                </div>
            </div>

            <div class="header-actions-right">
                <button type="button" class="btn-secondary-action" id="btnOpenAddSectorModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>+ Add Sector</span>
                </button>

                <button type="button" class="btn-secondary-action" id="btnOpenManageSectorsModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                    </svg>
                    <span>Manage Sectors</span>
                </button>

                <button type="button" class="btn-encode-data" id="btnOpenEncodeModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>+ Encode Demographic Data</span>
                </button>
            </div>
        </div>

        <!-- Filter Controls Row (Spec Section 7: Recommended Filters) -->
        <div class="filter-controls-row">
            <div class="filter-left-group">
                <!-- Sector Filter -->
                <div class="filter-item" style="min-width: 200px;">
                    <label for="filterSectorSelect">Community Sector</label>
                    <select id="filterSectorSelect" class="filter-select">
                        <option value="all">All Community Sectors</option>
                        @foreach ($sectors as $sec)
                            <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Barangay Filter -->
                <div class="filter-item" style="min-width: 180px;">
                    <label for="filterBarangaySelect">Barangay Scope</label>
                    <select id="filterBarangaySelect" class="filter-select">
                        <option value="all">All 18 Barangays</option>
                        @foreach ($barangays as $bgy)
                            <option value="{{ $bgy->id }}">{{ $bgy->id }}. {{ $bgy->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Live Keyword Search -->
                <div class="filter-item" style="flex: 1; min-width: 180px;">
                    <label for="filterSearchInput">Search Records</label>
                    <input type="text" id="filterSearchInput" class="filter-input"
                        placeholder="Search sector, barangay, validation source, notes...">
                </div>
            </div>

            <button type="button" class="btn-reset-filters" id="btnResetFilters" title="Clear all active filters">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Reset Filters</span>
            </button>
        </div>
    </div>

    <!-- 2. Municipal Level Summary KPI Cards (Exact Styling to Issues, Events & Assistance) -->
    <div class="kpi-cards-grid">
        <!-- Card 1: Total Sector Members -->
        <div class="kpi-card card-total">
            <div class="kpi-header">
                <span class="kpi-title" id="kpiPopulationTitle">Total Sector Members</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.999-3.199a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiTotalPopulation">0</div>
            <div class="kpi-subtext" id="kpiPopulationSub">Across Mariveles Municipality</div>
        </div>

        <!-- Card 2: Dominant / Selected Sector -->
        <div class="kpi-card card-sector">
            <div class="kpi-header">
                <span class="kpi-title" id="kpiDominantTitle">Dominant Sector</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiDominantVal" style="color: #D97706;">—</div>
            <div class="kpi-subtext" id="kpiDominantSub">Highest recorded population</div>
        </div>

        <!-- Card 3: Peak Concentration Barangay -->
        <div class="kpi-card card-concentration">
            <div class="kpi-header">
                <span class="kpi-title" id="kpiConcentrationTitle">Peak Concentration</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiTopBarangayVal" style="font-size: 1.45rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #4F46E5;">—</div>
            <div class="kpi-subtext" id="kpiTopBarangaySub">Highest concentration</div>
        </div>

        <!-- Card 4: Configured Community Sectors -->
        <div class="kpi-card card-sectors-count">
            <div class="kpi-header">
                <span class="kpi-title" id="kpiActiveSectorsTitle">Active Sectors</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiSectorsCount" style="color: #059669;">0</div>
            <div class="kpi-subtext" id="kpiActiveSectorsSub">Configurable community groups</div>
        </div>

        <!-- Card 5: Barangay Planning Reach -->
        <div class="kpi-card card-coverage">
            <div class="kpi-header">
                <span class="kpi-title" id="kpiCoverageTitle">Barangay Coverage</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiBarangaysCovered" style="font-size: 1.55rem; color: #0284C7;">18 / 18</div>
            <div class="kpi-subtext" id="kpiCoverageSub">Validated Barangay Baseline</div>
        </div>
    </div>

    <!-- 3. Map Legend & Sector Switcher Ribbon (Exact Match to Issues) -->
    <div class="map-legend-bar-container">
        <div class="legend-title-group">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" style="color:var(--color-primary-blue);">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            <span>Sector Geographic Concentration: (<span id="activeSectorLabel" style="color:var(--color-primary-blue); font-weight:800;">All Sectors</span>)</span>
        </div>
        <div class="legend-items-container" id="sectorLegendContainer">
            <!-- Dynamically populated clickable sector pills -->
        </div>
    </div>

    <!-- 4. Main Visual Grid: Map Canvas Stage (1fr) + Right Barangay Detail Sidebar (380px) -->
    <div class="electoral-dashboard-grid">
        <!-- Left: Interactive Map Canvas Stage (Spec Section 4: Sector-Based Map View) -->
        <div class="map-card-container">
            <div class="interactive-map-stage" id="mapStage">
                <!-- Floating Map Controls -->
                <div class="map-floating-controls">
                    <button type="button" class="map-btn-icon" id="btnZoomIn" title="Zoom In">+</button>
                    <button type="button" class="map-btn-icon" id="btnZoomOut" title="Zoom Out">&minus;</button>
                    <button type="button" class="map-btn-icon" id="btnResetZoom" title="Reset View"
                        style="font-size: 0.95rem;">&#x21bb;</button>
                </div>

                <!-- Hover Tooltip -->
                <div id="mapHoverTooltip">
                    <div class="tip-name" id="tipBarangayName">Barangay Name</div>
                    <div class="tip-detail" id="tipDetailText">
                        <span id="tipSectorDot" style="width: 8px; height: 8px; border-radius: 50%; display: inline-block;"></span>
                        <span id="tipSectorInfo">Sector Info</span>
                    </div>
                    <div class="tip-stat" id="tipTotalCount">Total: 0 members</div>
                    <div class="tip-stat" id="tipShareText">Municipal Share: 0%</div>
                </div>

                <div class="map-viewport-wrapper" id="mapViewport">
                    <img src="{{ asset('images/mariveles-map.png') }}" alt="Mariveles Bataan Map" class="client-map-img"
                        id="mapImage">
                    <!-- 18 Dynamic Slanted Pins -->
                    <div id="mapPinsContainer"></div>
                </div>
            </div>
        </div>

        <!-- Right: Barangay / Municipal Demographic View Sidebar (Spec Section 3 & 5) -->
        <div class="sidebar-detail-card" id="barangayDetailSidebar">
            <div class="sidebar-header-badge">
                <div>
                    <div class="detail-meta" id="sidebarMetaLabel">Municipal Demographic Overview</div>
                    <div class="detail-title" id="sidebarBarangayTitle">Mariveles, Bataan</div>
                </div>
                <div class="bgy-number-badge" id="sidebarBarangayNumber" style="font-size: 0.72rem; padding: 4px 8px;">18 Brgys</div>
            </div>

            <!-- Total Members Pill Box -->
            <div class="bgy-total-box">
                <div>
                    <div class="bgy-total-label" id="sidebarTotalLabel">Total Recorded Sector Population</div>
                    <div class="bgy-total-val" id="sidebarTotalMembers">144,765</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.8" stroke="currentColor" style="opacity:0.85;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.999-3.199a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>

            <!-- Sector Breakdown Progress Bars (Spec Section 3) -->
            <div class="sector-breakdown-section">
                <div class="sector-breakdown-title">
                    <span id="sidebarBreakdownTitle">Community Sector Breakdown</span>
                    <span id="sidebarSectorsCountBadge" style="font-size:0.70rem; color:var(--text-muted);">7 Sectors</span>
                </div>
                <div id="sidebarSectorListContainer" style="display:flex; flex-direction:column; gap:8px;">
                    <!-- Dynamically populated progress rows -->
                </div>
            </div>

            <!-- Program Planning Scope Box (Spec Section 5) -->
            <div class="planning-scope-box" id="sidebarPlanningScope">
                <strong>Program Planning Demographic Reach:</strong>
                <span id="sidebarPlanningText">Showing combined demographic baseline of 144,765 members across 18 barangays. Click any barangay on the map to view localized numbers.</span>
            </div>

            <!-- Last Updated Info -->
            <div style="font-size: 0.74rem; color: var(--text-muted); display:flex; align-items:center; justify-content:space-between; border-top: 1px solid #EEF2F6; padding-top: 8px;">
                <span>Scope: <strong id="sidebarLastUpdated" style="color:var(--color-deep-navy);">All 18 Barangays</strong></span>
                <span style="font-weight: 700; color: #10B981;" id="sidebarBaselineStatus">&bull; Validated Baseline</span>
            </div>

            <!-- Quick Action Button to Add/Update this Barangay -->
            <button type="button" class="btn-secondary-action" id="btnQuickEncodeBarangay" style="width: 100%; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span id="btnQuickEncodeText">+ Encode Demographic Data</span>
            </button>
        </div>
    </div>

    <!-- 5. Municipal-Wide Sector Summary Grid (Spec Section 6) -->
    <div class="municipal-summary-card">
        <div class="municipal-section-header">
            <div>
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" style="color: var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                    </svg>
                    <span id="municipalSummaryHeaderTitle">Municipal-Wide Sector Summary &amp; Distribution Overview</span>
                </h3>
                <p id="municipalSummaryHeaderSub">Consolidated total members, percentage distribution, and peak concentration barangay for each community sector across Mariveles</p>
            </div>
        </div>

        <div class="municipal-sectors-grid" id="municipalSectorsContainer">
            <!-- Dynamically populated via AJAX -->
        </div>
    </div>

    <!-- 6. Program Planning Reference Notice (Spec Section 5) -->
    <div class="planning-notice-banner">
        <div class="notice-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
            </svg>
        </div>
        <div class="notice-content">
            <strong>Program Planning Reference Notice:</strong> Demographic data presented in this module provides an empirical baseline
            for estimating potential community reach when planning programs, interventions, and resource allocation. Recorded figures represent
            the estimated community sector population in each barangay, not automatically the number of actual program beneficiaries.
        </div>
    </div>

    <!-- 7. Searchable Demographic Records DataTable (Spec Section 8) -->
    <div class="table-card-container">
        <div class="section-header-row">
            <div class="section-header-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" style="color: var(--color-primary-blue);">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
                <span>Barangay Sector Demographic Records Table</span>
            </div>
        </div>

        <div class="table-responsive">
            <table id="demographyDataTable" class="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Barangay</th>
                        <th>Community Sector</th>
                        <th>Number of Members</th>
                        <th>Municipal Share (%)</th>
                        <th>Validation Notes / Source</th>
                        <th>Last Updated</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Populated by DataTables via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- =========================================================================
         MODALS
         ========================================================================= -->

    <!-- Modal 1: Encode Demographic Record (Spec Section 1 & 9) -->
    <div class="modal-backdrop-custom" id="modalEncodeRecord">
        <div class="modal-box-card">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor" style="color: var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Encode Demographic Data</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseEncodeModal">&times;</button>
            </div>
            <form id="formEncodeRecord">
                @csrf
                <div class="modal-body-custom">
                    <div class="form-grid-2col">
                        <!-- Barangay -->
                        <div class="form-group-custom">
                            <label for="modalBarangayId">Barangay <span class="req">*</span></label>
                            <select id="modalBarangayId" name="barangay_id" class="form-control-custom" required>
                                <option value="all_barangays">-- Apply to All 18 Barangays --</option>
                                @foreach ($barangays as $b)
                                    <option value="{{ $b->id }}">{{ $b->id }}. {{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Community Sector -->
                        <div class="form-group-custom">
                            <label for="modalSectorId">Community Sector <span class="req">*</span></label>
                            <select id="modalSectorId" name="sector_id" class="form-control-custom" required>
                                @foreach ($sectors as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Number of Members -->
                        <div class="form-group-custom form-col-span-2">
                            <label for="modalMembersCount">Number of Members <span class="req">*</span></label>
                            <input type="number" min="0" id="modalMembersCount" name="members_count"
                                class="form-control-custom" placeholder="e.g. 420" required>
                            <span style="font-size: 0.72rem; color: var(--text-muted);">Enter the estimated or validated population belonging to this sector.</span>
                        </div>

                        <!-- Notes / Methodology / Validation Source -->
                        <div class="form-group-custom form-col-span-2">
                            <label for="modalNotes">Validation Source / Notes</label>
                            <textarea id="modalNotes" name="notes" class="form-control-custom"
                                placeholder="e.g. Barangay Registry, Municipal Agriculture Office survey, PSA 2026 profiling..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelEncodeModal">Cancel</button>
                    <button type="submit" class="btn-modal-submit" id="btnSubmitEncode">Save Demographic Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Edit Demographic Record -->
    <div class="modal-backdrop-custom" id="modalEditRecord">
        <div class="modal-box-card">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" style="color: var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    <span>Update Demographic Record</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseEditModal">&times;</button>
            </div>
            <form id="formEditRecord">
                @csrf
                <input type="hidden" id="editRecordId">
                <div class="modal-body-custom">
                    <div class="form-grid-2col">
                        <div class="form-group-custom">
                            <label>Barangay</label>
                            <input type="text" id="editBarangayName" class="form-control-custom" readonly style="background:#F1F5F9; color:#64748B;">
                        </div>

                        <div class="form-group-custom">
                            <label>Community Sector</label>
                            <input type="text" id="editSectorName" class="form-control-custom" readonly style="background:#F1F5F9; color:#64748B;">
                        </div>

                        <div class="form-group-custom form-col-span-2">
                            <label for="editMembersCount">Number of Members <span class="req">*</span></label>
                            <input type="number" min="0" id="editMembersCount" name="members_count" class="form-control-custom" required>
                        </div>

                        <div class="form-group-custom form-col-span-2">
                            <label for="editNotes">Validation Source / Notes</label>
                            <textarea id="editNotes" name="notes" class="form-control-custom"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelEditModal">Cancel</button>
                    <button type="submit" class="btn-modal-submit" id="btnSubmitEdit">Update Record</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 3: Add Configurable Community Sector (Spec Section 2) -->
    <div class="modal-backdrop-custom" id="modalAddSector">
        <div class="modal-box-card">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" style="color: var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Add New Community Sector</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseAddSectorModal">&times;</button>
            </div>
            <form id="formAddSector">
                @csrf
                <div class="modal-body-custom">
                    <div class="form-grid-2col">
                        <div class="form-group-custom form-col-span-2">
                            <label for="modalNewSectorName">Sector Name <span class="req">*</span></label>
                            <input type="text" id="modalNewSectorName" name="name" class="form-control-custom"
                                placeholder="e.g. Solo Parents, Persons with Disabilities (PWD), Tricycle Drivers..." required>
                        </div>

                        <div class="form-group-custom form-col-span-2">
                            <label for="modalNewSectorColorHex">Sector Display Color <span class="req">*</span></label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="color" id="modalNewSectorColorPicker" value="#0284C7"
                                    style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid #CBD5E1; cursor: pointer; padding: 2px;">
                                <input type="text" id="modalNewSectorColorHex" name="color" value="#0284C7"
                                    class="form-control-custom" style="width: 140px;" required>
                            </div>
                        </div>

                        <div class="form-group-custom form-col-span-2">
                            <label for="modalNewSectorDescription">Sector Description / Target Scope</label>
                            <textarea id="modalNewSectorDescription" name="description" class="form-control-custom"
                                placeholder="Describe the population criteria or program relevance of this sector..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelAddSectorModal">Cancel</button>
                    <button type="submit" class="btn-modal-submit" id="btnSubmitAddSector">Create Community Sector</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 4: Manage Sectors Modal (Edit / Delete) -->
    <div class="modal-backdrop-custom" id="modalManageSectors">
        <div class="modal-box-card" style="max-width: 680px;">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" style="color: var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                    </svg>
                    <span>Configured Community Sectors</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseManageSectorsModal">&times;</button>
            </div>
            <div class="modal-body-custom">
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0; margin-bottom: 16px;">
                    Configured sectors are dynamically displayed across all 18 Mariveles barangays without code changes.
                </p>
                <div class="sectors-manager-list" id="sectorsManagerList">
                    <!-- Populated via AJAX -->
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnDoneManageSectors">Done</button>
            </div>
        </div>
    </div>

    <!-- Modal 5: Delete Record Confirmation Modal -->
    <div class="modal-backdrop-custom" id="modalDeleteRecord">
        <div class="modal-box-card" style="max-width: 440px;">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom" style="color: #DC2626;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span>Confirm Delete</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseDeleteModal">&times;</button>
            </div>
            <div class="modal-body-custom">
                <p style="font-size: 0.90rem; color: #475569; margin: 0;">
                    Are you sure you want to delete this demographic record? This action will remove the recorded count for this barangay.
                </p>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnCancelDeleteModal">Cancel</button>
                <button type="button" class="btn-modal-submit" id="btnConfirmDelete" style="background: #DC2626;">Delete Record</button>
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
            // CSRF Token
            const csrfToken = '{{ csrf_token() }}';
            const role = '{{ isset($role) && $role === 'assistant' ? 'assistant' : 'admin' }}';

            // Route endpoints
            const routes = {
                data: "{{ route(isset($role) && $role === 'assistant' ? 'assistant.demography.data' : 'admin.demography.data') }}",
                store: "{{ route(isset($role) && $role === 'assistant' ? 'assistant.demography.store' : 'admin.demography.store') }}",
                show: "{{ url(isset($role) && $role === 'assistant' ? 'assistant/demography/record' : 'admin/demography/record') }}",
                update: "{{ url(isset($role) && $role === 'assistant' ? 'assistant/demography/update' : 'admin/demography/update') }}",
                delete: "{{ url(isset($role) && $role === 'assistant' ? 'assistant/demography/delete' : 'admin/demography/delete') }}",
                sectorStore: "{{ route(isset($role) && $role === 'assistant' ? 'assistant.demography.sector.store' : 'admin.demography.sector.store') }}",
                sectorUpdate: "{{ url(isset($role) && $role === 'assistant' ? 'assistant/demography/sector/update' : 'admin/demography/sector/update') }}",
                sectorDelete: "{{ url(isset($role) && $role === 'assistant' ? 'assistant/demography/sector/delete' : 'admin/demography/sector/delete') }}"
            };

            // 18 Mariveles Barangay Hotspot Coordinates (Identical to Electoral, Assistance, Events, Directory & Issues)
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
                1: 'Alion', 2: 'Batangas II', 3: 'Cabcaben', 4: 'Lucanin',
                5: 'Balon-Anito', 6: 'Maligaya', 7: 'Biaan', 8: 'Malaya',
                9: 'Townsite', 10: 'San Isidro', 11: 'Mt. View', 12: 'Alas-Asin',
                13: 'Camaya', 14: 'Baseco Country', 15: 'San Carlos',
                16: 'Poblacion', 17: 'Sisiman', 18: 'Ipag'
            };

            // Global State
            let currentSectorId = 'all';
            let selectedBarangayId = 'all'; // Default to All Barangays
            let currentBarangaysData = {};
            let currentSectorsData = [];
            let currentSectorSummaries = [];
            let deleteTargetId = null;

            // DOM Elements
            const mapStage = document.getElementById('mapStage');
            const mapViewport = document.getElementById('mapViewport');
            const pinsContainer = document.getElementById('mapPinsContainer');
            const tooltip = document.getElementById('mapHoverTooltip');
            const tipName = document.getElementById('tipBarangayName');
            const tipSectorDot = document.getElementById('tipSectorDot');
            const tipSectorInfo = document.getElementById('tipSectorInfo');
            const tipTotalCount = document.getElementById('tipTotalCount');
            const tipShareText = document.getElementById('tipShareText');

            // --- Initialize Custom DataTable (Matching Events, Directory & Issues) ---
            const demographyTable = $('#demographyDataTable').DataTable({
                responsive: false,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [[1, 'asc'], [3, 'desc']],
                language: {
                    search: "Search Records:",
                    lengthMenu: "Show _MENU_ results",
                    info: "Showing _START_ to _END_ of _TOTAL_ demographic entries",
                    infoEmpty: "No demographic entries recorded",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    zeroRecords: "No matching demographic records found",
                    paginate: {
                        first: "«",
                        previous: "‹",
                        next: "›",
                        last: "»"
                    }
                },
                columns: [
                    { data: 'id', width: '50px' },
                    {
                        data: 'barangay_name',
                        render: function(data, type, row) {
                            return `<strong>${row.barangay_id}. ${data}</strong>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `<div class="table-sector-badge">
                                <span style="width:8px; height:8px; border-radius:50%; background:${row.sector_color}; display:inline-block;"></span>
                                <span>${row.sector_name}</span>
                            </div>`;
                        }
                    },
                    {
                        data: 'members_display',
                        render: function(data) {
                            return `<strong>${data}</strong> members`;
                        }
                    },
                    {
                        data: 'municipal_share',
                        render: function(data) {
                            return `<div style="display:flex; align-items:center; gap:8px;">
                                <span style="font-weight:700; width:45px;">${data}%</span>
                                <div style="background:#E2E8F0; width:60px; height:6px; border-radius:3px; overflow:hidden;">
                                    <div style="background:var(--color-primary-blue); width:${Math.min(data, 100)}%; height:100%;"></div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'notes',
                        render: function(data) {
                            return `<span style="font-size:0.78rem; color:#64748B;">${data}</span>`;
                        }
                    },
                    { data: 'last_updated' },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(id) {
                            return `<div style="display:flex; align-items:center; justify-content:center; gap:6px;">
                                <button type="button" class="btn-table-action edit-btn" data-id="${id}" title="Edit Demographic Count">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button type="button" class="btn-table-action delete-action delete-btn" data-id="${id}" title="Delete Record">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>`;
                        }
                    }
                ]
            });

            // --- Load All Data via AJAX ---
            function loadDemographyData() {
                const sectorId = $('#filterSectorSelect').val();
                const barangayId = $('#filterBarangaySelect').val();
                const search = $('#filterSearchInput').val();

                currentSectorId = sectorId;

                $.ajax({
                    url: routes.data,
                    method: 'GET',
                    data: {
                        sector_id: sectorId,
                        barangay_id: barangayId,
                        search: search
                    },
                    success: function(res) {
                        if (!res.success) return;

                        currentBarangaysData = res.barangays;
                        currentSectorsData = res.sectors;
                        currentSectorSummaries = res.sector_summaries;

                        const bgyName = res.active_barangay ? res.active_barangay.name : null;

                        // 1. Update Top 5 KPI Cards dynamically
                        renderKpis(res.kpis);

                        // 2. Update Sector Switcher Ribbon
                        renderSectorLegend(res.sector_summaries, sectorId, res.is_barangay_filtered, bgyName);

                        // 3. Render Map Hotspot Pins
                        renderMapPins(res.barangays, sectorId, (barangayId !== 'all' ? parseInt(barangayId) : null));

                        // 4. Update Right Barangay Detail Sidebar
                        const targetSidebarBgyId = (barangayId !== 'all' ? parseInt(barangayId) : selectedBarangayId);
                        updateSidebarDetail(targetSidebarBgyId, sectorId);

                        // 5. Update Municipal-Wide / Barangay Sector Summary Grid
                        renderMunicipalSummaries(res.sector_summaries, res.is_barangay_filtered, bgyName);

                        // 6. Refresh DataTable records
                        demographyTable.clear().rows.add(res.records).draw();
                    },
                    error: function(xhr) {
                        console.error('Failed to load demography data:', xhr);
                        showToast('Error loading demographic data. Please check connection.', true);
                    }
                });
            }

            // --- Render Top 5 KPI Cards ---
            function renderKpis(k) {
                if (!k) return;
                $('#kpiPopulationTitle').text(k.selected_sector_title || 'Total Sector Members');
                $('#kpiTotalPopulation').text(k.total_population || '0');
                $('#kpiPopulationSub').text(k.selected_sector_sub || 'Across Mariveles Municipality');

                $('#kpiDominantTitle').text(k.dominant_title || 'Dominant Sector');
                $('#kpiDominantVal').text(k.dominant_sector_name + (k.dominant_sector_count ? ' (' + k.dominant_sector_count + ')' : ''));
                if (k.dominant_sector_color) $('#kpiDominantVal').css('color', k.dominant_sector_color);
                $('#kpiDominantSub').text(k.dominant_sub || 'Highest recorded population');

                $('#kpiConcentrationTitle').text(k.concentration_title || 'Peak Concentration');
                $('#kpiTopBarangayVal').text(k.top_barangay_name + (k.top_barangay_count ? ' (' + k.top_barangay_count + ')' : ''));
                $('#kpiTopBarangaySub').text(k.top_barangay_sub || 'Highest concentration');

                $('#kpiActiveSectorsTitle').text(k.active_sectors_title || 'Active Sectors');
                $('#kpiSectorsCount').text(k.active_sectors_count || '0');
                $('#kpiActiveSectorsSub').text(k.active_sectors_sub || 'Configurable community groups');

                $('#kpiCoverageTitle').text(k.coverage_title || 'Barangay Coverage');
                $('#kpiBarangaysCovered').text(k.barangays_covered || '18 / 18');
                $('#kpiCoverageSub').text(k.planning_coverage_sub || 'Validated Barangay Baseline');
            }

            // --- Render Sector Switcher Legend Ribbon (Matching Issues) ---
            function renderSectorLegend(sectorSummaries, activeSectorId, isBarangayFiltered, barangayName) {
                const container = document.getElementById('sectorLegendContainer');
                container.innerHTML = '';

                // Active label in header
                let activeName = 'All Sectors';
                if (activeSectorId !== 'all') {
                    const match = sectorSummaries.find(s => s.id == activeSectorId);
                    if (match) activeName = match.name;
                }

                if (isBarangayFiltered && barangayName) {
                    $('#activeSectorLabel').text(`${activeName} in Brgy. ${barangayName}`);
                } else {
                    $('#activeSectorLabel').text(activeName);
                }

                // All Sectors Pill
                const isAll = (activeSectorId === 'all');
                const allBtn = document.createElement('button');
                allBtn.type = 'button';
                allBtn.className = `metric-pill-btn ${isAll ? 'active' : ''}`;
                allBtn.innerHTML = `<span>All Sectors</span>`;
                allBtn.addEventListener('click', function() {
                    $('#filterSectorSelect').val('all').trigger('change');
                });
                container.appendChild(allBtn);

                // Each Sector Pill with Color Dot & Total Members
                sectorSummaries.forEach(s => {
                    const isActive = (activeSectorId != 'all' && s.id == activeSectorId);
                    const pill = document.createElement('button');
                    pill.type = 'button';
                    pill.className = `metric-pill-btn ${isActive ? 'active' : ''}`;
                    if (isActive) {
                        pill.style.background = s.color;
                        pill.style.borderColor = s.color;
                        pill.style.color = '#FFFFFF';
                    }
                    pill.innerHTML = `
                        <span style="width:8px; height:8px; border-radius:50%; background:${isActive ? '#FFFFFF' : s.color}; display:inline-block; flex-shrink:0;"></span>
                        <span>${s.name}: <strong>${Number(s.total_members).toLocaleString()}</strong></span>
                    `;
                    pill.addEventListener('click', function() {
                        const targetVal = isActive ? 'all' : s.id;
                        $('#filterSectorSelect').val(targetVal).trigger('change');
                    });
                    container.appendChild(pill);
                });
            }

            // --- Render Map Hotspot Pins (Spec Section 4: Sector-Based Map View) ---
            function renderMapPins(barangaysMap, sectorFilter, filteredBarangayId) {
                pinsContainer.innerHTML = '';
                const isSectorFiltered = (sectorFilter && sectorFilter !== 'all');

                for (let bgyId = 1; bgyId <= 18; bgyId++) {
                    const bgy = barangaysMap[bgyId] || {
                        id: bgyId,
                        name: barangayNames[bgyId] || ('Brgy. ' + bgyId),
                        total_members: 0,
                        display_count: 0,
                        display_color: '#075998',
                        dominant_sector: null,
                        sectors: []
                    };

                    const coords = pinCoordinates[bgyId] || { x: 50, y: 50 };

                    const pin = document.createElement('div');
                    pin.className = 'map-hotspot-pin';
                    pin.id = `hotspot-pin-${bgyId}`;
                    pin.style.left = `${coords.x}%`;
                    pin.style.top = `${coords.y}%`;
                    pin.style.backgroundColor = bgy.display_color || '#075998';

                    if (isSectorFiltered) {
                        pin.textContent = `${barangayNames[bgyId]}: ${Number(bgy.display_count).toLocaleString()}`;
                    } else {
                        pin.textContent = barangayNames[bgyId] || ('Brgy. ' + bgyId);
                    }

                    // Selection / Filter Dimming
                    if (filteredBarangayId) {
                        if (filteredBarangayId === bgyId) {
                            pin.classList.add('active-selected');
                            pin.style.opacity = '1';
                        } else {
                            pin.style.opacity = '0.35';
                        }
                    } else {
                        pin.style.opacity = '1';
                    }

                    // Hover Tooltip
                    pin.addEventListener('mouseenter', function() {
                        tipName.textContent = `${bgyId}. ${barangayNames[bgyId]}`;
                        tipSectorDot.style.background = bgy.display_color || '#075998';

                        if (isSectorFiltered) {
                            const secMatch = bgy.sectors.find(s => s.sector_id == sectorFilter);
                            const secName = secMatch ? secMatch.sector_name : 'Sector';
                            const secCount = secMatch ? secMatch.count : 0;
                            const secShare = secMatch ? secMatch.share_in_bgy_pct : 0;
                            tipSectorInfo.textContent = `${secName}: ${Number(secCount).toLocaleString()} members`;
                            tipTotalCount.textContent = `Total Barangay Population: ${Number(bgy.total_members).toLocaleString()}`;
                            tipShareText.textContent = `Share in Barangay: ${secShare}%`;
                        } else {
                            const dom = bgy.dominant_sector;
                            tipSectorInfo.textContent = dom ? `Dominant: ${dom.name} (${Number(dom.count).toLocaleString()})` : 'All Sectors Overview';
                            tipTotalCount.textContent = `Total Sector Members: ${Number(bgy.total_members).toLocaleString()}`;
                            tipShareText.textContent = `Covering ${bgy.sectors.length} community sectors`;
                        }
                        tooltip.style.display = 'block';
                    });

                    pin.addEventListener('mousemove', function(e) {
                        const rect = mapStage.getBoundingClientRect();
                        tooltip.style.left = `${e.clientX - rect.left}px`;
                        tooltip.style.top = `${e.clientY - rect.top}px`;
                    });

                    pin.addEventListener('mouseleave', function() {
                        tooltip.style.display = 'none';
                    });

                    // Click to Select Barangay
                    pin.addEventListener('click', function(e) {
                        e.stopPropagation();
                        selectBarangay(bgyId);
                    });

                    pinsContainer.appendChild(pin);
                }
            }

            // --- Select Barangay & Update Sidebar ---
            function selectBarangay(bgyId) {
                selectedBarangayId = bgyId;

                // Sync Filter Dropdown
                $('#filterBarangaySelect').val(bgyId);

                // Reload data to synchronize table, map pins, KPI cards, and sidebar
                loadDemographyData();
            }

            // --- Update Right Sidebar Details (Spec Section 3 & 5) ---
            function updateSidebarDetail(bgyId, sectorFilter) {
                const isMunicipal = (bgyId === 'all' || !bgyId);
                const container = document.getElementById('sidebarSectorListContainer');
                container.innerHTML = '';

                if (isMunicipal) {
                    // MUNICIPAL OVERVIEW (All 18 Barangays)
                    $('#sidebarMetaLabel').text('Municipal Demographic Overview');
                    $('#sidebarBarangayTitle').text('Mariveles, Bataan');
                    $('#sidebarBarangayNumber').text('18 Brgys').css('font-size', '0.70rem');
                    $('#sidebarTotalLabel').text('Total Recorded Municipality Population');

                    const totalMunicipalMembers = currentSectorSummaries.reduce((sum, s) => sum + (s.municipal_total || s.total_members || 0), 0);
                    $('#sidebarTotalMembers').text(Number(totalMunicipalMembers).toLocaleString());
                    $('#sidebarLastUpdated').text('All 18 Barangays');
                    $('#sidebarSectorsCountBadge').text(`${currentSectorSummaries.length} Sectors`);
                    $('#btnQuickEncodeText').text('+ Encode Demographic Data');

                    const sortedSectors = [...currentSectorSummaries].sort((a, b) => {
                        const aVal = a.municipal_total || a.total_members || 0;
                        const bVal = b.municipal_total || b.total_members || 0;
                        return bVal - aVal;
                    });

                    sortedSectors.forEach(s => {
                        const sCount = s.municipal_total || s.total_members || 0;
                        const sPct = totalMunicipalMembers > 0 ? ((sCount / totalMunicipalMembers) * 100).toFixed(1) : 0;
                        const isFiltered = (sectorFilter !== 'all' && s.id == sectorFilter);

                        const row = document.createElement('div');
                        row.className = 'sector-progress-row';
                        if (isFiltered) {
                            row.style.borderColor = s.color;
                            row.style.background = '#EFF6FF';
                            row.style.boxShadow = `0 0 0 1.5px ${s.color}40`;
                        }

                        row.innerHTML = `
                            <div class="sector-progress-header">
                                <span class="sector-name-pill">
                                    <span class="sector-color-dot" style="background:${s.color};"></span>
                                    <span>${s.name}</span>
                                    ${isFiltered ? '<span style="font-size:0.65rem; background:#DBEAFE; color:#1E40AF; padding:1px 5px; border-radius:6px; font-weight:800;">Target</span>' : ''}
                                </span>
                                <span class="sector-count-badge">${Number(sCount).toLocaleString()} (${sPct}%)</span>
                            </div>
                            <div class="progress-bar-track">
                                <div class="progress-bar-fill" style="background:${s.color}; width:${Math.min(sPct, 100)}%;"></div>
                            </div>
                        `;
                        container.appendChild(row);
                    });

                    if (sectorFilter !== 'all') {
                        const match = currentSectorSummaries.find(s => s.id == sectorFilter);
                        const secName = match ? match.name : 'Target Sector';
                        const count = match ? Number(match.municipal_total || match.total_members).toLocaleString() : '0';
                        $('#sidebarPlanningText').html(`For a municipality-wide program for <strong>${secName}</strong>, Mariveles has approximately <strong>${count} recorded members</strong> across all 18 barangays.`);
                    } else {
                        $('#sidebarPlanningText').html(`Showing combined demographic baseline of <strong>${Number(totalMunicipalMembers).toLocaleString()} members</strong> across 18 barangays. Click any barangay on the map to view localized numbers.`);
                    }

                } else {
                    // SPECIFIC BARANGAY VIEW (e.g. San Carlos)
                    const bgyIdNum = parseInt(bgyId);
                    const bgy = currentBarangaysData[bgyIdNum] || {
                        id: bgyIdNum,
                        name: barangayNames[bgyIdNum] || `Brgy. ${bgyIdNum}`,
                        total_members: 0,
                        sectors: [],
                        last_updated: 'Recently validated'
                    };

                    $('#sidebarMetaLabel').text('Selected Barangay Demographic View');
                    $('#sidebarBarangayTitle').text(bgy.name);
                    $('#sidebarBarangayNumber').text(bgy.id).css('font-size', '0.85rem');
                    $('#sidebarTotalLabel').text('Total Recorded Sector Population');
                    $('#sidebarTotalMembers').text(Number(bgy.total_members).toLocaleString());
                    $('#sidebarLastUpdated').text(bgy.last_updated || 'Recently validated');
                    $('#sidebarSectorsCountBadge').text(`${bgy.sectors ? bgy.sectors.length : 0} Sectors`);
                    $('#btnQuickEncodeText').text(`+ Update Demographics for ${bgy.name}`);

                    if (!bgy.sectors || bgy.sectors.length === 0) {
                        container.innerHTML = '<div style="font-size:0.80rem; color:#64748B; padding:10px 0;">No demographic records encoded for this barangay.</div>';
                    } else {
                        bgy.sectors.forEach(s => {
                            const isFiltered = (sectorFilter !== 'all' && s.sector_id == sectorFilter);
                            const row = document.createElement('div');
                            row.className = 'sector-progress-row';
                            if (isFiltered) {
                                row.style.borderColor = s.sector_color;
                                row.style.background = '#EFF6FF';
                                row.style.boxShadow = `0 0 0 1.5px ${s.sector_color}40`;
                            }

                            row.innerHTML = `
                                <div class="sector-progress-header">
                                    <span class="sector-name-pill">
                                        <span class="sector-color-dot" style="background:${s.sector_color};"></span>
                                        <span>${s.sector_name}</span>
                                        ${isFiltered ? '<span style="font-size:0.65rem; background:#DBEAFE; color:#1E40AF; padding:1px 5px; border-radius:6px; font-weight:800;">Target</span>' : ''}
                                    </span>
                                    <span class="sector-count-badge">${Number(s.count).toLocaleString()} (${s.share_in_bgy_pct}%)</span>
                                </div>
                                <div class="progress-bar-track">
                                    <div class="progress-bar-fill" style="background:${s.sector_color}; width:${Math.min(s.share_in_bgy_pct, 100)}%;"></div>
                                </div>
                            `;
                            container.appendChild(row);
                        });
                    }

                    if (sectorFilter !== 'all') {
                        const match = bgy.sectors.find(s => s.sector_id == sectorFilter);
                        const secName = match ? match.sector_name : 'Target Sector';
                        const count = match ? Number(match.count).toLocaleString() : '0';
                        $('#sidebarPlanningText').html(`For a program tailored for <strong>${secName}</strong>, Barangay ${bgy.name} has approximately <strong>${count} recorded sector members</strong> who may be affected or served.`);
                    } else {
                        const dom = bgy.dominant_sector;
                        if (dom) {
                            $('#sidebarPlanningText').html(`The largest community sector in Barangay ${bgy.name} is <strong>${dom.name}</strong> with <strong>${Number(dom.count).toLocaleString()} members</strong>. Select a specific sector to focus program reach.`);
                        } else {
                            $('#sidebarPlanningText').text('Select a community sector to calculate potential beneficiaries and service capacity in this barangay.');
                        }
                    }
                }
            }

            // --- Render Municipal-Wide / Barangay Sector Summary Grid (Spec Section 6) ---
            function renderMunicipalSummaries(summaries, isBarangayFiltered, bgyName) {
                const container = document.getElementById('municipalSectorsContainer');
                container.innerHTML = '';

                if (isBarangayFiltered && bgyName) {
                    $('#municipalSummaryHeaderTitle').text(`Barangay Sector Summary: ${bgyName}`);
                    $('#municipalSummaryHeaderSub').text(`Sector distribution and percentage share within Barangay ${bgyName}`);
                } else {
                    $('#municipalSummaryHeaderTitle').text('Municipal-Wide Sector Summary & Distribution Overview');
                    $('#municipalSummaryHeaderSub').text('Consolidated total members, percentage distribution, and peak concentration barangay for each community sector across Mariveles');
                }

                summaries.forEach(s => {
                    const card = document.createElement('div');
                    card.className = 'sector-summary-card';
                    card.innerHTML = `
                        <div class="sector-card-top-bar" style="background:${s.color};"></div>
                        <div class="sector-summary-header">
                            <span class="sector-summary-name">
                                <span style="width:8px; height:8px; border-radius:50%; background:${s.color};"></span>
                                ${s.name}
                            </span>
                            <span style="font-size:0.72rem; font-weight:800; color:var(--text-muted);">${s.share_pct}% Share</span>
                        </div>
                        <div class="sector-summary-val" style="color:${s.color};">
                            ${Number(s.total_members).toLocaleString()}
                        </div>
                        <div class="sector-summary-meta">
                            <span>${isBarangayFiltered ? 'Municipal Total: <strong>' + Number(s.municipal_total).toLocaleString() + '</strong>' : 'Peak: <strong>' + s.top_barangay + '</strong>'}</span>
                            <span>${isBarangayFiltered ? '' : '<strong>' + Number(s.top_bgy_count).toLocaleString() + '</strong> members'}</span>
                        </div>
                    `;
                    container.appendChild(card);
                });
            }

            // --- Render Sectors List in Manager Modal ---
            function renderSectorsManagerList() {
                const list = document.getElementById('sectorsManagerList');
                list.innerHTML = '';

                currentSectorSummaries.forEach(s => {
                    const item = document.createElement('div');
                    item.className = 'sector-manager-item';
                    item.innerHTML = `
                        <div class="sector-manager-info">
                            <span style="width:12px; height:12px; border-radius:50%; background:${s.color};"></span>
                            <div>
                                <strong style="font-size:0.90rem; color:var(--color-deep-navy);">${s.name}</strong>
                                <div style="font-size:0.74rem; color:var(--text-muted);">${Number(s.total_members).toLocaleString()} total members &bull; ${s.description || 'Configured sector'}</div>
                            </div>
                        </div>
                        <div class="sector-manager-actions">
                            ${!s.is_system ? `
                                <button type="button" class="btn-table-action delete-sector-btn" data-id="${s.id}" data-name="${s.name}" title="Delete Sector">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            ` : '<span style="font-size:0.72rem; color:#64748B; font-weight:700; background:#E2E8F0; padding:2px 8px; border-radius:10px;">Core Sector</span>'}
                        </div>
                    `;
                    list.appendChild(item);
                });

                // Attach delete sector handlers
                $('.delete-sector-btn').on('click', function() {
                    const secId = $(this).data('id');
                    const secName = $(this).data('name');
                    if (confirm(`Are you sure you want to delete sector '${secName}'? All associated barangay demographic counts will also be removed.`)) {
                        $.ajax({
                            url: `${routes.sectorDelete}/${secId}`,
                            method: 'POST',
                            data: { _token: csrfToken },
                            success: function(res) {
                                if (res.success) {
                                    showToast(res.message);
                                    loadDemographyData();
                                    $('#modalManageSectors').fadeOut(180);
                                }
                            },
                            error: function() {
                                showToast('Error deleting sector.', true);
                            }
                        });
                    }
                });
            }

            // --- Interactive Map Zoom & Drag Logic (Exact Match to Electoral & Assistance) ---
            let currentScale = 1.5; // Standard 1.5x zoom
            const DEFAULT_SCALE = 1.5;
            let currentTranslateX = 0;
            let currentTranslateY = 0;
            let isDragging = false;
            let startX, startY;

            function updateMapTransform() {
                mapViewport.style.transform = `translate(${currentTranslateX}px, ${currentTranslateY}px) scale(${currentScale})`;
            }

            // Initial zoom applied
            updateMapTransform();

            $('#btnZoomIn').on('click', function(e) {
                e.stopPropagation();
                if (currentScale < 3.5) {
                    currentScale += 0.25;
                    updateMapTransform();
                }
            });

            $('#btnZoomOut').on('click', function(e) {
                e.stopPropagation();
                if (currentScale > 0.8) {
                    currentScale -= 0.25;
                    updateMapTransform();
                }
            });

            $('#btnResetZoom').on('click', function(e) {
                e.stopPropagation();
                currentScale = DEFAULT_SCALE;
                currentTranslateX = 0;
                currentTranslateY = 0;
                updateMapTransform();
            });

            mapStage.addEventListener('mousedown', function(e) {
                if (e.target.closest('.map-hotspot-pin') || e.target.closest('.map-floating-controls')) return;
                isDragging = true;
                mapStage.classList.add('dragging');
                startX = e.clientX - currentTranslateX;
                startY = e.clientY - currentTranslateY;
            });

            window.addEventListener('mousemove', function(e) {
                if (!isDragging) return;
                currentTranslateX = e.clientX - startX;
                currentTranslateY = e.clientY - startY;
                updateMapTransform();
            });

            window.addEventListener('mouseup', function() {
                isDragging = false;
                mapStage.classList.remove('dragging');
            });

            // --- Filter Event Listeners ---
            $('#filterSectorSelect').on('change', function() {
                loadDemographyData();
            });

            $('#filterBarangaySelect').on('change', function() {
                const bgyVal = $(this).val();
                if (bgyVal !== 'all') {
                    selectedBarangayId = parseInt(bgyVal);
                }
                loadDemographyData();
            });

            let searchDebounceTimer;
            $('#filterSearchInput').on('input', function() {
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(loadDemographyData, 350);
            });

            $('#btnResetFilters').on('click', function() {
                $('#filterSectorSelect').val('all');
                $('#filterBarangaySelect').val('all');
                $('#filterSearchInput').val('');
                selectedBarangayId = 'all';
                loadDemographyData();
            });

            // --- Form Submissions & Modals ---

            // Modal 1: Encode Record
            $('#btnOpenEncodeModal').on('click', function() {
                const bgyVal = $('#filterBarangaySelect').val();
                $('#modalBarangayId').val(bgyVal !== 'all' ? bgyVal : (selectedBarangayId !== 'all' ? selectedBarangayId : 1));
                $('#modalMembersCount').val('');
                $('#modalNotes').val('');
                $('#modalEncodeRecord').css('display', 'flex').hide().fadeIn(200);
            });

            $('#btnQuickEncodeBarangay').on('click', function() {
                const bgyVal = $('#filterBarangaySelect').val();
                $('#modalBarangayId').val(bgyVal !== 'all' ? bgyVal : (selectedBarangayId !== 'all' ? selectedBarangayId : 1));
                $('#modalMembersCount').val('');
                $('#modalNotes').val('');
                $('#modalEncodeRecord').css('display', 'flex').hide().fadeIn(200);
            });

            $('#btnCloseEncodeModal, #btnCancelEncodeModal').on('click', function() {
                $('#modalEncodeRecord').fadeOut(180);
            });

            $('#formEncodeRecord').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: routes.store,
                    method: 'POST',
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            showToast(res.message);
                            $('#modalEncodeRecord').fadeOut(180);
                            loadDemographyData();
                        }
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON?.message || 'Error recording demographic data.';
                        showToast(err, true);
                    }
                });
            });

            // Modal 2: Edit Record
            $(document).on('click', '.edit-btn', function() {
                const recordId = $(this).data('id');
                $.ajax({
                    url: `${routes.show}/${recordId}`,
                    method: 'GET',
                    success: function(res) {
                        if (res.success) {
                            $('#editRecordId').val(res.record.id);
                            $('#editBarangayName').val(`${res.record.barangay_id}. ${res.record.barangay_name}`);
                            $('#editSectorName').val(res.record.sector_name);
                            $('#editMembersCount').val(res.record.members_count);
                            $('#editNotes').val(res.record.notes || '');
                            $('#modalEditRecord').css('display', 'flex').hide().fadeIn(200);
                        }
                    },
                    error: function() {
                        showToast('Error loading record details.', true);
                    }
                });
            });

            $('#btnCloseEditModal, #btnCancelEditModal').on('click', function() {
                $('#modalEditRecord').fadeOut(180);
            });

            $('#formEditRecord').on('submit', function(e) {
                e.preventDefault();
                const id = $('#editRecordId').val();
                const formData = $(this).serialize();

                $.ajax({
                    url: `${routes.update}/${id}`,
                    method: 'POST',
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            showToast(res.message);
                            $('#modalEditRecord').fadeOut(180);
                            loadDemographyData();
                        }
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON?.message || 'Error updating record.';
                        showToast(err, true);
                    }
                });
            });

            // Modal 3: Add Sector
            $('#btnOpenAddSectorModal').on('click', function() {
                $('#formAddSector')[0].reset();
                $('#modalNewSectorColorPicker').val('#0284C7');
                $('#modalNewSectorColorHex').val('#0284C7');
                $('#modalAddSector').css('display', 'flex').hide().fadeIn(200);
            });

            $('#btnCloseAddSectorModal, #btnCancelAddSectorModal').on('click', function() {
                $('#modalAddSector').fadeOut(180);
            });

            $('#modalNewSectorColorPicker').on('input', function() {
                $('#modalNewSectorColorHex').val($(this).val());
            });

            $('#modalNewSectorColorHex').on('input', function() {
                $('#modalNewSectorColorPicker').val($(this).val());
            });

            $('#formAddSector').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: routes.sectorStore,
                    method: 'POST',
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            showToast(res.message);
                            $('#modalAddSector').fadeOut(180);

                            // Dynamically update dropdowns
                            const newOption = `<option value="${res.sector.id}">${res.sector.name}</option>`;
                            $('#filterSectorSelect').append(newOption);
                            $('#modalSectorId').append(newOption);

                            loadDemographyData();
                        }
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON?.message || 'Error adding sector.';
                        showToast(err, true);
                    }
                });
            });

            // Modal 4: Manage Sectors
            $('#btnOpenManageSectorsModal').on('click', function() {
                renderSectorsManagerList();
                $('#modalManageSectors').css('display', 'flex').hide().fadeIn(200);
            });

            $('#btnCloseManageSectorsModal, #btnDoneManageSectors').on('click', function() {
                $('#modalManageSectors').fadeOut(180);
            });

            // Modal 5: Delete Record
            $(document).on('click', '.delete-btn', function() {
                deleteTargetId = $(this).data('id');
                $('#modalDeleteRecord').css('display', 'flex').hide().fadeIn(200);
            });

            $('#btnCloseDeleteModal, #btnCancelDeleteModal').on('click', function() {
                deleteTargetId = null;
                $('#modalDeleteRecord').fadeOut(180);
            });

            $('#btnConfirmDelete').on('click', function() {
                if (!deleteTargetId) return;

                $.ajax({
                    url: `${routes.delete}/${deleteTargetId}`,
                    method: 'POST',
                    data: { _token: csrfToken },
                    success: function(res) {
                        if (res.success) {
                            showToast(res.message);
                            $('#modalDeleteRecord').fadeOut(180);
                            deleteTargetId = null;
                            loadDemographyData();
                        }
                    },
                    error: function() {
                        showToast('Error deleting demographic record.', true);
                    }
                });
            });

            // Toast helper
            function showToast(message, isError = false) {
                const toast = $('#toastNotice');
                $('#toastMessage').text(message);
                if (isError) {
                    toast.css('border-left', '4px solid #EF4444');
                } else {
                    toast.css('border-left', '4px solid #10B981');
                }
                toast.addClass('show');
                setTimeout(() => toast.removeClass('show'), 3500);
            }

            // Close modals when clicking backdrop
            $('.modal-backdrop-custom').on('click', function(e) {
                if ($(e.target).hasClass('modal-backdrop-custom')) {
                    $(this).fadeOut(180);
                }
            });

            // Initial Data Load
            loadDemographyData();
        });
    </script>
@endsection
