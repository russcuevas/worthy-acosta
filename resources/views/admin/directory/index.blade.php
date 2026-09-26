@extends('layouts.app')

@section('title', 'Directory Module - Mariveles, Bataan')
@section('user_name', 'Administrator')
@section('user_role_label', 'Admin Portal')
@section('user_initials', 'AD')

@section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        /* Top Filter & Controls Header (Identical to Electoral, Assistance & Events) */
        .directory-controls-header {
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
            gap: 5px;
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
            min-width: 160px;
        }

        .filter-select:focus,
        .filter-input:focus {
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.12);
        }

        /* Label Filter Pills */
        .label-pill-group {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #F1F5F9;
            padding: 4px;
            border-radius: var(--radius-md);
            border: 1px solid #E2E8F0;
            overflow-x: auto;
            flex-wrap: nowrap;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
        }

        .label-pill-btn {
            border: none;
            background: transparent;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.80rem;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .label-pill-btn:hover {
            color: var(--color-deep-navy);
            background: rgba(255, 255, 255, 0.6);
        }

        .label-pill-btn.active {
            background: #FFFFFF;
            color: var(--color-deep-navy);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .label-pill-btn.pill-saint.active {
            color: #1D4ED8;
            font-weight: 800;
        }

        .label-pill-btn.pill-sinner.active {
            color: #15803D;
            font-weight: 800;
        }

        .label-pill-btn.pill-savable.active {
            color: #A16207;
            font-weight: 800;
        }

        .pill-badge-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 2px 7px;
            border-radius: 12px;
            font-size: 0.70rem;
            font-weight: 800;
            background: #E2E8F0;
            color: #475569;
            line-height: 1;
        }

        .pill-saint.active .pill-badge-count {
            background: #DBEAFE;
            color: #1D4ED8;
        }

        .pill-sinner.active .pill-badge-count {
            background: #DCFCE7;
            color: #15803D;
        }

        .pill-savable.active .pill-badge-count {
            background: #FEF9C3;
            color: #A16207;
        }

        .btn-reset-filters {
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
            margin-top: 18px;
        }

        .btn-reset-filters:hover {
            color: #DC2626;
            background: #FEE2E2;
        }

        /* 2. Municipal KPI Summary Cards */
        .kpi-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 22px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        @media (max-width: 1024px) {
            .kpi-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
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
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: relative;
            overflow: hidden;
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
            width: 4px;
            height: 100%;
        }

        .kpi-card.card-total::before { background: var(--color-primary-blue); }
        .kpi-card.card-saint::before { background: #2563EB; }
        .kpi-card.card-sinner::before { background: #16A34A; }
        .kpi-card.card-savable::before { background: #EAB308; }

        .kpi-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .kpi-title {
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }

        .kpi-icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-total .kpi-icon-badge { background: #EFF6FF; color: var(--color-primary-blue); }
        .card-saint .kpi-icon-badge { background: #DBEAFE; color: #1D4ED8; }
        .card-sinner .kpi-icon-badge { background: #DCFCE7; color: #15803D; }
        .card-savable .kpi-icon-badge { background: #FEF9C3; color: #A16207; }

        .kpi-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            line-height: 1.1;
        }

        .kpi-subtext {
            font-size: 0.76rem;
            color: var(--text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* 3. Map Legend & Metric Switcher Ribbon (Exact Electoral & Assistance) */
        .map-legend-bar-container {
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
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .legend-title-group {
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

        /* 4. Main Dashboard Grid (Map on Left + Barangay Directory Summary on Right) */
        .electoral-dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 390px;
            gap: 22px;
            align-items: start;
            margin-bottom: 24px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        @media (max-width: 1100px) {
            .electoral-dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Left: Map Canvas Stage (Identical to Electoral & Assistance) */
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
            max-width: 100%;
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

        /* Hotspot Barangay Capsule Pins (Identical slanted format) */
        .map-hotspot-pin {
            position: absolute;
            padding: 1.5px 6px;
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
            background-color: var(--color-primary-blue) !important;
            border-color: #FFFFFF !important;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.6), 0 8px 25px rgba(0, 0, 0, 0.85) !important;
            z-index: 60;
        }

        .map-hotspot-pin.active-selected::after {
            animation: pinPulseActive 1.4s infinite;
        }

        @keyframes pinPulseActive {
            0% { transform: scale(1); opacity: 1; }
            80% { transform: scale(1.5); opacity: 0; }
            100% { transform: scale(1.5); opacity: 0; }
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
            line-height: 1.4;
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

        /* Right Detail Sidebar Card (Barangay Directory Summary - Spec Section 3) */
        .sidebar-detail-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
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

        .summary-total-banner {
            background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
            border: 1px solid #BFDBFE;
            border-radius: var(--radius-md);
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .summary-total-label {
            font-size: 0.75rem;
            font-weight: 800;
            color: #1E40AF;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .summary-total-val {
            font-size: 1.45rem;
            font-weight: 800;
            color: #1E3A8A;
        }

        /* Internal Label Counts Box in Sidebar (Saint, Sinner, Savable) */
        .label-aggregate-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: var(--radius-md);
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .box-section-title {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .label-cards-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .label-mini-card {
            border-radius: 8px;
            padding: 8px 10px;
            text-align: center;
            border: 1.5px solid transparent;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .label-mini-card.card-saint-mini {
            background: #EFF6FF;
            border-color: #BFDBFE;
            color: #1D4ED8;
        }

        .label-mini-card.card-sinner-mini {
            background: #F0FDF4;
            border-color: #BBF7D0;
            color: #15803D;
        }

        .label-mini-card.card-savable-mini {
            background: #FEFCE8;
            border-color: #FEF08A;
            color: #A16207;
        }

        .label-mini-name {
            font-size: 0.70rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .label-mini-val {
            font-size: 1.15rem;
            font-weight: 800;
        }

        /* Breakdown by Contact Type (Spec Section 3) */
        .breakdown-list-wrap {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 220px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .breakdown-row-item {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 8px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            transition: all var(--transition-fast);
        }

        .breakdown-row-item:hover {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
        }

        .breakdown-header-line {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .breakdown-type-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--color-deep-navy);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .breakdown-type-val {
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--color-primary-blue);
        }

        .progress-bar-container {
            width: 100%;
            height: 6px;
            background: #E2E8F0;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--color-primary-blue), #0ea5e9);
            border-radius: 3px;
            transition: width 0.4s ease;
        }

        /* 18 Barangay Pills Grid in Sidebar */
        .pills-grid-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            max-height: 140px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .bgy-pill-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            padding: 5px 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.74rem;
            font-weight: 600;
            color: var(--color-deep-navy);
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .bgy-pill-card:hover {
            border-color: var(--color-primary-blue);
            background: #F0F7FF;
        }

        .bgy-pill-card.active {
            background: var(--color-primary-blue);
            color: #FFFFFF;
            border-color: var(--color-primary-blue);
            font-weight: 700;
        }

        .bgy-pill-card .pill-count-tag {
            font-size: 0.68rem;
            font-weight: 800;
            background: #E2E8F0;
            color: #475569;
            padding: 1px 5px;
            border-radius: 10px;
        }

        .bgy-pill-card.active .pill-count-tag {
            background: rgba(255, 255, 255, 0.25);
            color: #FFFFFF;
        }

        .btn-add-for-bgy {
            background: #FFFFFF;
            border: 1.5px solid var(--color-primary-blue);
            color: var(--color-primary-blue);
            border-radius: var(--radius-md);
            padding: 8px 12px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all var(--transition-fast);
        }

        .btn-add-for-bgy:hover {
            background: var(--color-primary-blue);
            color: #FFFFFF;
        }

        /* 5. Detailed Searchable Directory Table */
        .directory-table-container {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 22px 24px;
            margin-bottom: 24px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .table-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1.5px solid #EEF2F6;
        }

        .table-title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-title-wrap h2 {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin: 0;
        }

        .table-records-counter {
            font-size: 0.74rem;
            font-weight: 800;
            background: #EFF6FF;
            color: var(--color-primary-blue);
            padding: 3px 8px;
            border-radius: 12px;
        }

        /* Badges for Contact Type & Internal Labels */
        .contact-type-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            background: #F1F5F9;
            color: #334155;
            border: 1px solid #CBD5E1;
            white-space: nowrap;
        }

        .label-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        .label-badge.badge-saint {
            background: #EFF6FF;
            color: #1D4ED8;
            border: 1px solid #BFDBFE;
        }

        .label-badge.badge-sinner {
            background: #F0FDF4;
            color: #15803D;
            border: 1px solid #BBF7D0;
        }

        .label-badge.badge-savable {
            background: #FEFCE8;
            color: #A16207;
            border: 1px solid #FEF08A;
        }

        .label-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }

        .badge-saint .label-dot { background: #2563EB; }
        .badge-sinner .label-dot { background: #16A34A; }
        .badge-savable .label-dot { background: #EAB308; }

        /* Contact Name Cell with Avatar */
        .contact-name-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contact-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #075998, #0B192C);
            color: #FFFFFF;
            font-size: 0.74rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .contact-name-cell .name-val {
            font-weight: 700;
            color: var(--color-deep-navy);
            font-size: 0.88rem;
        }

        /* Action Buttons */
        .btn-table-action {
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #475569;
            transition: all var(--transition-fast);
        }

        .btn-table-action:hover {
            background: #F1F5F9;
            color: var(--color-deep-navy);
        }

        .btn-table-action.view:hover {
            color: #0284C7;
            border-color: #0284C7;
            background: #F0F9FF;
        }

        .btn-table-action.edit:hover {
            color: var(--color-primary-blue);
            border-color: var(--color-primary-blue);
            background: #EFF6FF;
        }

        .btn-table-action.delete:hover {
            color: #DC2626;
            border-color: #DC2626;
            background: #FEF2F2;
        }

        /* Modals (Identical custom styles) */
        .modal-backdrop-custom {
            position: fixed;
            inset: 0;
            background: rgba(11, 25, 44, 0.65);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            padding: 16px;
        }

        .modal-box-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            border: 1px solid var(--card-border);
            display: flex;
            flex-direction: column;
        }

        .modal-header-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            border-bottom: 1.5px solid #EEF2F6;
        }

        .modal-header-custom h3 {
            font-size: 1.18rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin: 0;
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
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media (max-width: 600px) {
            .form-grid-2col {
                grid-template-columns: 1fr;
            }
        }

        .form-group-custom {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group-custom label {
            font-size: 0.76rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
        }

        .form-control-custom {
            border: 1.5px solid #CBD5E1;
            border-radius: var(--radius-md);
            padding: 9px 14px;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--color-deep-navy);
            outline: none;
            transition: all var(--transition-fast);
            width: 100%;
            box-sizing: border-box;
        }

        .form-control-custom:focus {
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.12);
        }

        /* Internal Label Radio Cards */
        .label-choice-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .label-choice-card {
            border: 2px solid #E2E8F0;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            user-select: none;
        }

        .label-choice-card input[type="radio"] {
            display: none;
        }

        .label-choice-card.card-saint:hover {
            border-color: #93C5FD;
            background: #F0F7FF;
        }

        .label-choice-card.card-saint.selected {
            border-color: #2563EB;
            background: #EFF6FF;
            box-shadow: 0 3px 10px rgba(37, 99, 235, 0.2);
        }

        .label-choice-card.card-sinner:hover {
            border-color: #86EFAC;
            background: #F0FDF4;
        }

        .label-choice-card.card-sinner.selected {
            border-color: #16A34A;
            background: #F0FDF4;
            box-shadow: 0 3px 10px rgba(22, 163, 74, 0.2);
        }

        .label-choice-card.card-savable:hover {
            border-color: #FDE047;
            background: #FEFCE8;
        }

        .label-choice-card.card-savable.selected {
            border-color: #EAB308;
            background: #FEFCE8;
            box-shadow: 0 3px 10px rgba(234, 179, 8, 0.2);
        }

        .label-choice-title {
            font-size: 0.85rem;
            font-weight: 800;
        }

        .card-saint .label-choice-title { color: #1D4ED8; }
        .card-sinner .label-choice-title { color: #15803D; }
        .card-savable .label-choice-title { color: #A16207; }

        .label-choice-desc {
            font-size: 0.68rem;
            color: var(--text-muted);
            line-height: 1.2;
        }

        .label-privacy-note {
            font-size: 0.72rem;
            color: #64748B;
            background: #F8FAFC;
            border: 1px dashed #CBD5E1;
            padding: 8px 12px;
            border-radius: 6px;
            line-height: 1.4;
        }

        .modal-footer-custom {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px 24px;
            border-top: 1.5px solid #EEF2F6;
            background: #F8FAFC;
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        }

        .btn-modal-cancel {
            background: #FFFFFF;
            border: 1.5px solid #CBD5E1;
            color: var(--text-muted);
            padding: 8px 18px;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .btn-modal-cancel:hover {
            background: #F1F5F9;
            color: var(--color-deep-navy);
        }

        .btn-modal-save {
            background: var(--color-primary-blue);
            color: #FFFFFF;
            border: none;
            padding: 9px 22px;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition-fast);
            box-shadow: 0 3px 10px rgba(7, 89, 152, 0.25);
        }

        .btn-modal-save:hover {
            background: #0b4575;
            box-shadow: 0 5px 14px rgba(7, 89, 152, 0.35);
        }

        /* View Contact Card Modal Details */
        .contact-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .detail-item-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 10px 14px;
        }

        .detail-item-label {
            font-size: 0.70rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 3px;
        }

        .detail-item-val {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--color-deep-navy);
        }

        .audit-trail-footer {
            font-size: 0.72rem;
            color: #64748B;
            border-top: 1px solid #E2E8F0;
            padding-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        /* Toast Notice */
        #toastNotice {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #0B192C;
            color: #FFFFFF;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            display: none;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 2000;
            border: 1px solid var(--color-wave-cyan);
            animation: toastSlideUp 0.3s ease;
        }

        @keyframes toastSlideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
@endsection

@section('content')
    <!-- 1. Top Filter & Controls Header -->
    <div class="directory-controls-header">
        <div class="header-title-bar-row">
            <div class="header-title-left">
                <div class="header-title-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <div>
                    <h1>Directory Module</h1>
                    <p>Centralized contact directory & barangay summary for Mariveles, Bataan</p>
                </div>
            </div>

            <div class="header-actions-right">
                <button type="button" class="btn-encode-data" id="btnOpenAddModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Encode Contact</span>
                </button>
            </div>
        </div>

        <!-- Filter Controls Row -->
        <div class="filter-controls-row">
            <div class="filter-left-group">
                <!-- Internal Label Filter Pills -->
                <div class="filter-item">
                    <label>Internal Directory Label</label>
                    <div class="label-pill-group">
                        <button type="button" class="label-pill-btn active" data-label="all" id="pillLabelAll">
                            <span>All Labels</span>
                            <span class="pill-badge-count" id="pillCountAll">0</span>
                        </button>
                        <button type="button" class="label-pill-btn pill-saint" data-label="Saint" id="pillLabelSaint">
                            <span>Saint</span>
                            <span class="pill-badge-count" id="pillCountSaint">0</span>
                        </button>
                        <button type="button" class="label-pill-btn pill-sinner" data-label="Sinner" id="pillLabelSinner">
                            <span>Sinner</span>
                            <span class="pill-badge-count" id="pillCountSinner">0</span>
                        </button>
                        <button type="button" class="label-pill-btn pill-savable" data-label="Savable" id="pillLabelSavable">
                            <span>Savable</span>
                            <span class="pill-badge-count" id="pillCountSavable">0</span>
                        </button>
                    </div>
                </div>

                <!-- Barangay Filter -->
                <div class="filter-item">
                    <label for="filterBarangay">Barangay</label>
                    <select id="filterBarangay" class="filter-select">
                        <option value="all">All 18 Barangays</option>
                        @foreach ($barangays as $bgy)
                            <option value="{{ $bgy->id }}">{{ $bgy->id }}. {{ $bgy->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Contact Type Filter -->
                <div class="filter-item">
                    <label for="filterType">Type of Contact</label>
                    <select id="filterType" class="filter-select">
                        <option value="all">All Types</option>
                        @foreach ($contactTypes as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Input -->
                <div class="filter-item" style="flex:1; min-width: 180px;">
                    <label for="filterSearch">Search Directory</label>
                    <input type="text" id="filterSearch" class="filter-input" placeholder="Search name, position, number, notes...">
                </div>
            </div>

            <button type="button" class="btn-reset-filters" id="btnResetFilters" title="Clear all filters">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Reset Filters</span>
            </button>
        </div>
    </div>

    <!-- 2. Municipal KPI Summary Cards -->
    <div class="kpi-cards-grid">
        <!-- Total Contacts -->
        <div class="kpi-card card-total">
            <div class="kpi-header">
                <span class="kpi-title">Total Contacts</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiTotalContacts">0</div>
            <div class="kpi-subtext" id="kpiTotalSubtext">Across Mariveles 18 Barangays</div>
        </div>

        <!-- Saint Contacts -->
        <div class="kpi-card card-saint">
            <div class="kpi-header">
                <span class="kpi-title">Saint Records</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiSaintContacts" style="color:#1D4ED8;">0</div>
            <div class="kpi-subtext">Blue • User-defined internal category</div>
        </div>

        <!-- Sinner Contacts -->
        <div class="kpi-card card-sinner">
            <div class="kpi-header">
                <span class="kpi-title">Sinner Records</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiSinnerContacts" style="color:#15803D;">0</div>
            <div class="kpi-subtext">Green • User-defined internal category</div>
        </div>

        <!-- Savable Contacts -->
        <div class="kpi-card card-savable">
            <div class="kpi-header">
                <span class="kpi-title">Savable Records</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiSavableContacts" style="color:#A16207;">0</div>
            <div class="kpi-subtext">Yellow • User-defined internal category</div>
        </div>
    </div>

    <!-- 3. Interactive Map Legend & Metric Switcher -->
    <div class="map-legend-bar-container">
        <div class="legend-title-group">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                stroke-width="2.2" stroke="currentColor" style="color:var(--color-primary-blue);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            <span>Interactive Map Metric: (<span id="activeMetricLabel" style="color:var(--color-primary-blue); font-weight:800;">Total Contacts</span>)</span>
        </div>
        <div class="legend-items-container">
            <button type="button" class="metric-pill-btn active" data-metric="total_contacts">
                <span>Total Contacts</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="saint">
                <span>Saint (Blue)</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="sinner">
                <span>Sinner (Green)</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="savable">
                <span>Savable (Yellow)</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="Barangay Officials">
                <span>Officials</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="Leader">
                <span>Leaders</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="Coordinator">
                <span>Coordinators</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="Supporter">
                <span>Supporters</span>
            </button>
        </div>
    </div>

    <!-- 4. Main Dashboard Grid (Map on Left + Barangay Directory Summary on Right) -->
    <div class="electoral-dashboard-grid">
        <!-- Left: Map Canvas Stage (Identical to Electoral & Assistance) -->
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
                    <div class="tip-stat" id="tipStat">Total Contacts: 0</div>
                </div>

                <!-- Map Image with Hotspot Pins -->
                <div class="map-viewport-wrapper" id="mapViewport">
                    <img src="{{ asset('images/mariveles-map.png') }}" alt="Mariveles Bataan Map" class="client-map-img"
                        id="clientMapImg">

                    <!-- 18 Clickable Hotspot Pins -->
                    <div id="hotspotPinsContainer">
                        <!-- Injected dynamically via JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Barangay Directory Summary Card (User Spec Section 3) -->
        <div class="sidebar-detail-card" id="sidebarDetailCard">
            <div class="sidebar-header-badge">
                <div>
                    <div class="detail-meta" id="summaryMetaTag">Consolidated Mariveles Overview</div>
                    <div class="detail-title" id="summaryBgyTitle">All 18 Barangays</div>
                </div>
                <div class="bgy-number-badge" id="cardNumberBadge" title="Selected Barangay Marker">18</div>
            </div>

            <!-- Total Contacts Banner -->
            <div class="summary-total-banner">
                <div>
                    <div class="summary-total-label">Total Directory Records</div>
                    <div style="font-size: 0.72rem; color: #475569;">Registered contacts for this scope</div>
                </div>
                <div class="summary-total-val" id="summaryTotalVal">0</div>
            </div>

            <!-- Aggregate Internal Label Counts (Saint, Sinner, Savable) -->
            <div class="label-aggregate-box">
                <div class="box-section-title">
                    <span>Internal Directory Labels</span>
                    <span style="font-size: 0.65rem; color: #94A3B8;">Administrative</span>
                </div>
                <div class="label-cards-row">
                    <div class="label-mini-card card-saint-mini">
                        <span class="label-mini-name">Saint</span>
                        <span class="label-mini-val" id="summarySaintVal">0</span>
                    </div>
                    <div class="label-mini-card card-sinner-mini">
                        <span class="label-mini-name">Sinner</span>
                        <span class="label-mini-val" id="summarySinnerVal">0</span>
                    </div>
                    <div class="label-mini-card card-savable-mini">
                        <span class="label-mini-name">Savable</span>
                        <span class="label-mini-val" id="summarySavableVal">0</span>
                    </div>
                </div>
            </div>

            <!-- Breakdown by Contact Type (Spec Section 3) -->
            <div style="display:flex; flex-direction:column; gap:6px;">
                <div class="box-section-title">Breakdown by Contact Type</div>
                <div class="breakdown-list-wrap" id="sidebarTypeBreakdown">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Quick Barangay Switcher Pills -->
            <div style="display:flex; flex-direction:column; gap:6px;">
                <div class="box-section-title">
                    <span>Mariveles 18 Barangays</span>
                    <button type="button" id="btnSelectAllBgy" style="border:none; background:transparent; color:var(--color-primary-blue); font-size:0.72rem; font-weight:700; cursor:pointer;">Select All</button>
                </div>
                <div class="pills-grid-wrap" id="pillsList">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Quick Add Action Button -->
            <button type="button" class="btn-add-for-bgy" id="btnAddForBarangay">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                    stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span id="btnAddForBgyLabel">Encode Contact for this Barangay</span>
            </button>
        </div>
    </div>

    <!-- 5. Searchable Detailed Contact Directory Table -->
    <div class="directory-table-container">
        <div class="table-header-row">
            <div class="table-title-wrap">
                <h2>Searchable Contact Directory</h2>
                <span class="table-records-counter" id="tableRecordsCounter">0 Records Found</span>
            </div>
            <div style="font-size:0.78rem; color:var(--text-muted);">
                Audit tracked • Authorized staff access only
            </div>
        </div>

        <table id="directoryDataTable" class="dataTable stripe hover" style="width: 100%;">
            <thead>
                <tr>
                    <th>Contact Name</th>
                    <th>Position / Designation</th>
                    <th>Barangay</th>
                    <th>Contact Type</th>
                    <th>Contact Number</th>
                    <th>Internal Label</th>
                    <th>Other Info</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Populated dynamically via DataTables -->
            </tbody>
        </table>
    </div>

    <!-- 6. Add / Edit Contact Modal -->
    <div class="modal-backdrop-custom" id="contactModalBackdrop">
        <div class="modal-box-card">
            <div class="modal-header-custom">
                <h3 id="modalFormTitle">Encode Directory Contact</h3>
                <button type="button" class="modal-close-btn" id="btnCloseContactModal">&times;</button>
            </div>

            <form id="contactDataForm">
                @csrf
                <input type="hidden" id="formRecordId" value="">

                <div class="modal-body-custom">
                    <!-- 1. Barangay & Contact Type -->
                    <div class="form-grid-2col">
                        <div class="form-group-custom">
                            <label for="formBarangayId">Barangay *</label>
                            <select id="formBarangayId" class="form-control-custom" required>
                                <option value="" disabled selected>Select Barangay</option>
                                @foreach ($barangays as $bgy)
                                    <option value="{{ $bgy->id }}">{{ $bgy->id }}. {{ $bgy->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group-custom">
                            <label for="formContactType">Type of Contact *</label>
                            <select id="formContactType" class="form-control-custom" required>
                                <option value="" disabled selected>Select Contact Type</option>
                                @foreach ($contactTypes as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- 2. Full Name & Position -->
                    <div class="form-grid-2col">
                        <div class="form-group-custom">
                            <label for="formName">Full Name *</label>
                            <input type="text" id="formName" class="form-control-custom" placeholder="e.g. Juan Dela Cruz" required>
                        </div>

                        <div class="form-group-custom">
                            <label for="formPosition">Position / Designation / Role *</label>
                            <input type="text" id="formPosition" class="form-control-custom" placeholder="e.g. Barangay Kagawad, TODA Head" required>
                        </div>
                    </div>

                    <!-- 3. Primary Contact / Mobile Number -->
                    <div class="form-group-custom">
                        <label for="formContactNumber">Primary Contact / Mobile Number</label>
                        <input type="text" id="formContactNumber" class="form-control-custom" placeholder="e.g. 0917-123-4567">
                    </div>

                    <!-- 4. Internal Label Selector -->
                    <div class="form-group-custom">
                        <label>Internal Directory Label *</label>
                        <div class="label-choice-grid">
                            <label class="label-choice-card card-saint selected" id="choiceSaint">
                                <input type="radio" name="internal_label" value="Saint" checked>
                                <span class="label-choice-title">Saint (Blue)</span>
                                <span class="label-choice-desc">User-defined internal directory category</span>
                            </label>

                            <label class="label-choice-card card-sinner" id="choiceSinner">
                                <input type="radio" name="internal_label" value="Sinner">
                                <span class="label-choice-title">Sinner (Green)</span>
                                <span class="label-choice-desc">User-defined internal directory category</span>
                            </label>

                            <label class="label-choice-card card-savable" id="choiceSavable">
                                <input type="radio" name="internal_label" value="Savable">
                                <span class="label-choice-title">Savable (Yellow)</span>
                                <span class="label-choice-desc">User-defined internal directory category</span>
                            </label>
                        </div>
                        <div class="label-privacy-note">
                            <strong>Note on Privacy:</strong> Stored strictly as an administrative category without political persuasion scoring.
                        </div>
                    </div>

                    <!-- 5. Additional Other Info -->
                    <div class="form-group-custom">
                        <label for="formOtherInfo">Other Relevant Notes / Directory Info</label>
                        <textarea id="formOtherInfo" rows="3" class="form-control-custom" style="resize:vertical;"
                            placeholder="Additional background notes, sitio location, preferred coordination schedules..."></textarea>
                    </div>

                    <!-- Audit Note (When Editing) -->
                    <div class="audit-trail-footer" id="formAuditInfo" style="display:none;">
                        <div>Encoded by: <span id="auditCreatedBy">-</span> on <span id="auditCreatedAt">-</span></div>
                        <div>Last updated by: <span id="auditUpdatedBy">-</span> on <span id="auditUpdatedAt">-</span></div>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelContactModal">Cancel</button>
                    <button type="submit" class="btn-modal-save" id="btnSubmitContact">Save Contact</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 7. View Contact Details Modal -->
    <div class="modal-backdrop-custom" id="viewModalBackdrop">
        <div class="modal-box-card">
            <div class="modal-header-custom">
                <div>
                    <h3 id="viewName">Contact Profile</h3>
                    <p style="font-size:0.78rem; color:var(--text-muted); margin:0;" id="viewPosition">Designation / Role</p>
                </div>
                <button type="button" class="modal-close-btn" id="btnCloseViewModal">&times;</button>
            </div>

            <div class="modal-body-custom">
                <!-- Label Banner -->
                <div id="viewLabelBanner" style="padding:10px 14px; border-radius:8px; display:flex; align-items:center; justify-content:space-between;">
                    <span style="font-size:0.75rem; font-weight:800; text-transform:uppercase;">Internal Directory Label</span>
                    <span id="viewLabelBadge" class="label-badge">Label</span>
                </div>

                <!-- Detail Grid -->
                <div class="contact-detail-grid">
                    <div class="detail-item-box">
                        <div class="detail-item-label">Barangay</div>
                        <div class="detail-item-val" id="viewBarangay">-</div>
                    </div>
                    <div class="detail-item-box">
                        <div class="detail-item-label">Contact Type</div>
                        <div class="detail-item-val" id="viewContactType">-</div>
                    </div>
                    <div class="detail-item-box" style="grid-column: span 2;">
                        <div class="detail-item-label">Primary Contact Number</div>
                        <div class="detail-item-val" id="viewContactNumber">-</div>
                    </div>
                </div>

                <!-- Other Info -->
                <div class="detail-item-box">
                    <div class="detail-item-label">Other Information / Notes</div>
                    <div class="detail-item-val" id="viewOtherInfo" style="font-size:0.85rem; font-weight:500; line-height:1.5;">-</div>
                </div>

                <!-- Audit Trail -->
                <div class="audit-trail-footer">
                    <div><strong>Created By:</strong> <span id="viewAuditCreator">-</span> on <span id="viewAuditCreated">-</span></div>
                    <div><strong>Last Updated By:</strong> <span id="viewAuditEditor">-</span> on <span id="viewAuditUpdated">-</span></div>
                </div>
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnCloseViewModalBtn">Close</button>
                <button type="button" class="btn-modal-save" id="btnEditFromView">Edit Contact</button>
            </div>
        </div>
    </div>

    <!-- 8. Delete Confirmation Modal -->
    <div class="modal-backdrop-custom" id="deleteModalBackdrop">
        <div class="modal-box-card" style="max-width: 440px;">
            <div class="modal-header-custom" style="border-bottom:none;">
                <h3 style="color:#DC2626;">Delete Directory Contact?</h3>
                <button type="button" class="modal-close-btn" id="btnCancelDelete">&times;</button>
            </div>
            <div class="modal-body-custom" style="padding-top:0;">
                <p style="font-size:0.88rem; color:var(--text-muted); line-height:1.5; margin:0;">
                    Are you sure you want to delete <strong id="deleteContactName" style="color:var(--color-deep-navy);">this contact</strong>? This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnCancelDeleteBtn">Cancel</button>
                <button type="button" class="btn-modal-save" id="btnConfirmDelete" style="background:#DC2626;">Delete Contact</button>
            </div>
        </div>
    </div>

    <!-- Floating Toast Notice -->
    <div id="toastNotice">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
            stroke-width="2.5" stroke="var(--color-wave-cyan)">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
        </svg>
        <span id="toastMessage">Action completed successfully.</span>
    </div>
@endsection

@section('scripts')
    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = '{{ csrf_token() }}';

            // Coordinates for 18 Mariveles Barangays (Identical to Electoral & Assistance)
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

            const contactTypesList = [
                'Sectoral',
                'Barangay Officials',
                'Neighborhood Association',
                'Coordinator',
                'Leader',
                'Supporter',
                'Others'
            ];

            // Global State
            let currentMetric = 'total_contacts'; // 'total_contacts', 'saint', 'sinner', 'savable', or contact type
            let currentLabelFilter = 'all'; // 'all', 'Saint', 'Sinner', 'Savable'
            let selectedBarangayId = 'all'; // 'all' or integer 1-18
            let activeDataset = null;
            let recordToDeleteId = null;
            let currentViewingRecordId = null;
            let dataTableInstance = null;

            // Pan / Zoom State (Default 1.5x matching Electoral & Assistance)
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
            const filterSearch = document.getElementById('filterSearch');
            const btnResetFilters = document.getElementById('btnResetFilters');
            const btnSelectAllBgy = document.getElementById('btnSelectAllBgy');
            const activeMetricLabel = document.getElementById('activeMetricLabel');

            // Modal Elements
            const contactModalBackdrop = document.getElementById('contactModalBackdrop');
            const btnOpenAddModal = document.getElementById('btnOpenAddModal');
            const btnCloseContactModal = document.getElementById('btnCloseContactModal');
            const btnCancelContactModal = document.getElementById('btnCancelContactModal');
            const contactDataForm = document.getElementById('contactDataForm');
            const formRecordId = document.getElementById('formRecordId');

            // View Modal Elements
            const viewModalBackdrop = document.getElementById('viewModalBackdrop');
            const btnCloseViewModal = document.getElementById('btnCloseViewModal');
            const btnCloseViewModalBtn = document.getElementById('btnCloseViewModalBtn');
            const btnEditFromView = document.getElementById('btnEditFromView');

            // Delete Modal Elements
            const deleteModalBackdrop = document.getElementById('deleteModalBackdrop');
            const btnCancelDelete = document.getElementById('btnCancelDelete');
            const btnCancelDeleteBtn = document.getElementById('btnCancelDeleteBtn');
            const btnConfirmDelete = document.getElementById('btnConfirmDelete');

            // Helper: Toast
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
                if (filterType.value !== 'all') params.append('contact_type', filterType.value);
                if (filterSearch.value.trim()) params.append('search', filterSearch.value.trim());
                params.append('metric', currentMetric);

                fetch(`{{ route(isset($role) && $role === 'assistant' ? 'assistant.directory.data' : 'admin.directory.data') }}?${params.toString()}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            activeDataset = data;

                            // Render Map Pins & Sidebar
                            renderMapPins(data.barangays);
                            renderSidebarPills(data.barangays);

                            // Update Top KPIs & Internal Label Filter Pills for selected barangay
                            updateTopKpisAndPills(selectedBarangayId);

                            // Update Sidebar Summary
                            renderSidebarSummary(selectedBarangayId);

                            // Filter table by selected barangay & label
                            let filtered = data.records;
                            if (selectedBarangayId !== 'all') {
                                filtered = filtered.filter(r => r.barangay_id == selectedBarangayId);
                            }
                            if (currentLabelFilter !== 'all') {
                                filtered = filtered.filter(r => r.internal_label === currentLabelFilter);
                            }
                            renderDetailedRecords(filtered);
                        }
                    })
                    .catch(err => {
                        console.error('Failed to load directory dataset:', err);
                    });
            }

            // --- 2. Render Map Hotspot Pins ---
            function renderMapPins(barangaysMap) {
                pinsContainer.innerHTML = '';

                for (let bgyId = 1; bgyId <= 18; bgyId++) {
                    const bgy = barangaysMap[bgyId] || {
                        total_contacts: 0,
                        saint_count: 0,
                        sinner_count: 0,
                        savable_count: 0,
                        metric_value: 0,
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
                    pin.textContent = `${barangayNames[bgyId]} (${bgy.metric_value ?? bgy.total_contacts})`;

                    if (isSelected) {
                        pin.classList.add('active-selected');
                    }

                    // Hover Tooltip
                    pin.addEventListener('mouseenter', function() {
                        tipName.textContent = `Brgy. ${barangayNames[bgyId]}`;
                        tipStat.innerHTML = `
                            <strong>Total Contacts:</strong> ${Number(bgy.total_contacts).toLocaleString()}<br>
                            <strong>Saint:</strong> ${Number(bgy.saint_count).toLocaleString()} | 
                            <strong>Sinner:</strong> ${Number(bgy.sinner_count).toLocaleString()} | 
                            <strong>Savable:</strong> ${Number(bgy.savable_count).toLocaleString()}
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

                    // Click to Select Barangay
                    pin.addEventListener('click', function(e) {
                        e.stopPropagation();
                        selectBarangay(bgyId);
                    });

                    pinsContainer.appendChild(pin);
                }
            }

            // --- 3. Render Sidebar 18 Barangay Pills ---
            function renderSidebarPills(barangaysMap) {
                pillsList.innerHTML = '';
                for (let bgyId = 1; bgyId <= 18; bgyId++) {
                    const bgy = barangaysMap[bgyId] || { total_contacts: 0 };
                    const card = document.createElement('div');
                    card.className = 'bgy-pill-card' + (selectedBarangayId == bgyId ? ' active' : '');
                    card.innerHTML = `
                        <span>${bgyId}. ${barangayNames[bgyId]}</span>
                        <span class="pill-count-tag">${Number(bgy.total_contacts).toLocaleString()}</span>
                    `;
                    card.addEventListener('click', () => selectBarangay(bgyId));
                    pillsList.appendChild(card);
                }
            }

            // --- 4. Update Top 4 KPI Cards & Internal Label Filter Pills ---
            function updateTopKpisAndPills(bgyId) {
                if (!activeDataset) return;

                let total = 0;
                let saint = 0;
                let sinner = 0;
                let savable = 0;
                let subtext = 'Across Mariveles 18 Barangays';

                if (bgyId === 'all') {
                    total = activeDataset.kpis.overall_total ?? activeDataset.kpis.total_contacts;
                    saint = activeDataset.kpis.overall_saint ?? activeDataset.kpis.saint_count;
                    sinner = activeDataset.kpis.overall_sinner ?? activeDataset.kpis.sinner_count;
                    savable = activeDataset.kpis.overall_savable ?? activeDataset.kpis.savable_count;
                    subtext = 'Across Mariveles 18 Barangays';
                } else {
                    const bgyData = activeDataset.barangays[bgyId];
                    if (bgyData) {
                        total = bgyData.total_contacts;
                        saint = bgyData.saint_count;
                        sinner = bgyData.sinner_count;
                        savable = bgyData.savable_count;
                    }
                    subtext = `Brgy. ${barangayNames[bgyId]}`;
                }

                // 4 Top KPI Cards
                document.getElementById('kpiTotalContacts').textContent = Number(total).toLocaleString();
                document.getElementById('kpiSaintContacts').textContent = Number(saint).toLocaleString();
                document.getElementById('kpiSinnerContacts').textContent = Number(sinner).toLocaleString();
                document.getElementById('kpiSavableContacts').textContent = Number(savable).toLocaleString();

                const kpiTotalSub = document.getElementById('kpiTotalSubtext');
                if (kpiTotalSub) kpiTotalSub.textContent = subtext;

                // Internal Directory Label Filter Pills
                document.getElementById('pillCountAll').textContent = Number(total).toLocaleString();
                document.getElementById('pillCountSaint').textContent = Number(saint).toLocaleString();
                document.getElementById('pillCountSinner').textContent = Number(sinner).toLocaleString();
                document.getElementById('pillCountSavable').textContent = Number(savable).toLocaleString();
            }

            // --- 5. Render Right Sidebar Summary (Fulfilling User Spec Section 3) ---
            function renderSidebarSummary(bgyId) {
                if (!activeDataset) return;

                const summaryMetaTag = document.getElementById('summaryMetaTag');
                const summaryBgyTitle = document.getElementById('summaryBgyTitle');
                const cardNumberBadge = document.getElementById('cardNumberBadge');
                const summaryTotalVal = document.getElementById('summaryTotalVal');
                const summarySaintVal = document.getElementById('summarySaintVal');
                const summarySinnerVal = document.getElementById('summarySinnerVal');
                const summarySavableVal = document.getElementById('summarySavableVal');
                const typeContainer = document.getElementById('sidebarTypeBreakdown');
                const btnAddForBgyLabel = document.getElementById('btnAddForBgyLabel');

                let total = 0;
                let saint = 0;
                let sinner = 0;
                let savable = 0;
                let types = [];

                if (bgyId === 'all') {
                    summaryMetaTag.textContent = 'Consolidated Mariveles Overview';
                    summaryBgyTitle.textContent = 'All 18 Barangays';
                    cardNumberBadge.textContent = '18';
                    btnAddForBgyLabel.textContent = 'Encode New Contact';

                    total = activeDataset.kpis.overall_total ?? activeDataset.kpis.total_contacts;
                    saint = activeDataset.kpis.overall_saint ?? activeDataset.kpis.saint_count;
                    sinner = activeDataset.kpis.overall_sinner ?? activeDataset.kpis.sinner_count;
                    savable = activeDataset.kpis.overall_savable ?? activeDataset.kpis.savable_count;
                    types = activeDataset.type_breakdown;
                } else {
                    const bgyData = activeDataset.barangays[bgyId];
                    summaryMetaTag.textContent = `Barangay #${bgyId} Summary`;
                    summaryBgyTitle.textContent = `Brgy. ${barangayNames[bgyId]}`;
                    cardNumberBadge.textContent = bgyId;
                    btnAddForBgyLabel.textContent = `Encode for ${barangayNames[bgyId]}`;

                    if (bgyData) {
                        total = bgyData.total_contacts;
                        saint = bgyData.saint_count;
                        sinner = bgyData.sinner_count;
                        savable = bgyData.savable_count;
                        types = Object.values(bgyData.types);
                    }
                }

                summaryTotalVal.textContent = Number(total).toLocaleString();
                summarySaintVal.textContent = Number(saint).toLocaleString();
                summarySinnerVal.textContent = Number(sinner).toLocaleString();
                summarySavableVal.textContent = Number(savable).toLocaleString();

                // Render Type Breakdown with progress bars
                typeContainer.innerHTML = '';
                if (!types || types.length === 0) {
                    typeContainer.innerHTML = `<div style="text-align:center; padding:12px; color:var(--text-muted); font-size:0.8rem;">No contacts registered</div>`;
                } else {
                    types.forEach(t => {
                        const count = t.count || 0;
                        const pct = total > 0 ? Math.round((count / total) * 100) : 0;
                        const row = document.createElement('div');
                        row.className = 'breakdown-row-item';
                        row.innerHTML = `
                            <div class="breakdown-header-line">
                                <span class="breakdown-type-name">${t.type}</span>
                                <span class="breakdown-type-val">${Number(count).toLocaleString()} (${pct}%)</span>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" style="width: ${pct}%"></div>
                            </div>
                        `;
                        typeContainer.appendChild(row);
                    });
                }
            }

            // --- 6. Select Barangay Handler ---
            function selectBarangay(bgyId) {
                selectedBarangayId = bgyId;
                filterBarangay.value = bgyId;

                // Update active pin visual
                document.querySelectorAll('.map-hotspot-pin').forEach(pin => {
                    pin.classList.remove('active-selected');
                    pin.style.backgroundColor = '#64748B';
                });

                if (bgyId !== 'all') {
                    const activePin = document.getElementById(`hotspot-pin-${bgyId}`);
                    if (activePin) {
                        activePin.classList.add('active-selected');
                        activePin.style.backgroundColor = '#075998';
                    }
                }

                // Update sidebar pills
                document.querySelectorAll('.bgy-pill-card').forEach(p => p.classList.remove('active'));
                if (bgyId !== 'all') {
                    const idx = parseInt(bgyId, 10) - 1;
                    const pillEl = pillsList.children[idx];
                    if (pillEl) pillEl.classList.add('active');
                }

                // Update Top 4 KPI Cards & Filter Pills immediately!
                updateTopKpisAndPills(bgyId);

                // Update right sidebar summary
                renderSidebarSummary(bgyId);

                // Update Table records based on selected barangay & label
                if (activeDataset) {
                    let filtered = activeDataset.records;
                    if (bgyId !== 'all') {
                        filtered = filtered.filter(r => r.barangay_id == bgyId);
                    }
                    if (currentLabelFilter !== 'all') {
                        filtered = filtered.filter(r => r.internal_label === currentLabelFilter);
                    }
                    renderDetailedRecords(filtered);
                }
            }

            // --- 6. Render DataTables Records ---
            function renderDetailedRecords(records) {
                document.getElementById('tableRecordsCounter').textContent = `${Number(records.length).toLocaleString()} Records Found`;

                if ($.fn.DataTable.isDataTable('#directoryDataTable')) {
                    $('#directoryDataTable').DataTable().clear().destroy();
                }

                const tbody = document.querySelector('#directoryDataTable tbody');
                tbody.innerHTML = '';

                records.forEach(r => {
                    const initials = r.name ? r.name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase() : 'NA';
                    const labelClass = r.internal_label ? `badge-${r.internal_label.toLowerCase()}` : 'badge-saint';

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>
                            <div class="contact-name-cell">
                                <div class="contact-avatar">${initials}</div>
                                <div>
                                    <div class="name-val">${r.name}</div>
                                </div>
                            </div>
                        </td>
                        <td><strong>${r.position}</strong></td>
                        <td>${r.barangay_name}</td>
                        <td><span class="contact-type-badge">${r.contact_type}</span></td>
                        <td>
                            <span style="font-family:monospace; font-weight:700;">${r.contact_number || '<span style="color:#94A3B8;">None</span>'}</span>
                        </td>
                        <td>
                            <span class="label-badge ${labelClass}">
                                <span class="label-dot"></span>
                                ${r.internal_label}
                            </span>
                        </td>
                        <td>
                            <span style="color:#64748B; font-size:0.80rem;" title="${r.other_info || ''}">
                                ${r.other_info ? (r.other_info.length > 40 ? r.other_info.substring(0, 40) + '...' : r.other_info) : '<span style="color:#CBD5E1;">-</span>'}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:6px;">
                                <button type="button" class="btn-table-action view" data-id="${r.id}" title="View Contact Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                                <button type="button" class="btn-table-action edit" data-id="${r.id}" title="Edit Contact">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button type="button" class="btn-table-action delete" data-id="${r.id}" data-name="${r.name}" title="Delete Contact">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                dataTableInstance = $('#directoryDataTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    order: [[0, 'asc']],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search table records...",
                        paginate: {
                            next: '&raquo;',
                            previous: '&laquo;'
                        }
                    },
                    drawCallback: function() {
                        // Re-bind Action Buttons
                        $('#directoryDataTable').find('.btn-table-action.view').off('click').on('click', function() {
                            openViewModal($(this).data('id'));
                        });
                        $('#directoryDataTable').find('.btn-table-action.edit').off('click').on('click', function() {
                            openEditModal($(this).data('id'));
                        });
                        $('#directoryDataTable').find('.btn-table-action.delete').off('click').on('click', function() {
                            openDeleteModal($(this).data('id'), $(this).data('name'));
                        });
                    }
                });
            }

            // --- 7. Event Listeners: Metric Switcher ---
            document.querySelectorAll('.metric-pill-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.metric-pill-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentMetric = this.getAttribute('data-metric');
                    activeMetricLabel.textContent = this.textContent.trim();
                    loadData();
                });
            });

            // --- 8. Event Listeners: Filter Controls ---
            document.querySelectorAll('.label-pill-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.label-pill-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentLabelFilter = this.getAttribute('data-label');

                    if (activeDataset) {
                        let filtered = activeDataset.records;
                        if (selectedBarangayId !== 'all') {
                            filtered = filtered.filter(r => r.barangay_id == selectedBarangayId);
                        }
                        if (currentLabelFilter !== 'all') {
                            filtered = filtered.filter(r => r.internal_label === currentLabelFilter);
                        }
                        renderDetailedRecords(filtered);
                    }
                });
            });

            filterBarangay.addEventListener('change', function() {
                selectBarangay(this.value);
            });

            btnSelectAllBgy.addEventListener('click', function() {
                selectBarangay('all');
            });

            filterType.addEventListener('change', loadData);

            let searchDebounce = null;
            filterSearch.addEventListener('input', function() {
                clearTimeout(searchDebounce);
                searchDebounce = setTimeout(loadData, 300);
            });

            btnResetFilters.addEventListener('click', function() {
                filterBarangay.value = 'all';
                filterType.value = 'all';
                filterSearch.value = '';
                currentLabelFilter = 'all';
                document.querySelectorAll('.label-pill-btn').forEach(b => b.classList.remove('active'));
                document.getElementById('pillLabelAll').classList.add('active');

                selectedBarangayId = 'all';
                loadData();
            });

            // --- 9. Modal Management: Add & Edit ---
            function setupLabelRadios(selectedValue) {
                document.querySelectorAll('.label-choice-card').forEach(card => {
                    const radio = card.querySelector('input[type="radio"]');
                    if (radio.value === selectedValue) {
                        radio.checked = true;
                        card.classList.add('selected');
                    } else {
                        card.classList.remove('selected');
                    }
                });
            }

            document.querySelectorAll('.label-choice-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.label-choice-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    this.querySelector('input[type="radio"]').checked = true;
                });
            });

            function openAddModal() {
                formRecordId.value = '';
                document.getElementById('modalFormTitle').textContent = 'Encode Directory Contact';
                document.getElementById('btnSubmitContact').textContent = 'Save Contact';
                contactDataForm.reset();
                setupLabelRadios('Saint');
                document.getElementById('formAuditInfo').style.display = 'none';

                if (selectedBarangayId !== 'all') {
                    document.getElementById('formBarangayId').value = selectedBarangayId;
                } else {
                    document.getElementById('formBarangayId').value = '';
                }

                contactModalBackdrop.style.display = 'flex';
            }

            function openEditModal(id) {
                fetch(`{{ url('admin/directory/record') }}/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const r = data.record;
                            formRecordId.value = r.id;
                            document.getElementById('modalFormTitle').textContent = 'Edit Directory Contact';
                            document.getElementById('btnSubmitContact').textContent = 'Update Contact';

                            document.getElementById('formBarangayId').value = r.barangay_id;
                            document.getElementById('formContactType').value = r.contact_type;
                            document.getElementById('formName').value = r.name;
                            document.getElementById('formPosition').value = r.position;
                            document.getElementById('formContactNumber').value = r.contact_number || '';
                            document.getElementById('formOtherInfo').value = r.other_info || '';
                            setupLabelRadios(r.internal_label || 'Saint');

                            // Audit Info
                            document.getElementById('auditCreatedBy').textContent = r.created_by;
                            document.getElementById('auditCreatedAt').textContent = r.created_at_formatted;
                            document.getElementById('auditUpdatedBy').textContent = r.updated_by;
                            document.getElementById('auditUpdatedAt').textContent = r.updated_at_formatted;
                            document.getElementById('formAuditInfo').style.display = 'flex';

                            contactModalBackdrop.style.display = 'flex';
                        }
                    })
                    .catch(err => console.error(err));
            }

            btnOpenAddModal.addEventListener('click', openAddModal);
            document.getElementById('btnAddForBarangay').addEventListener('click', openAddModal);

            btnCloseContactModal.addEventListener('click', () => {
                contactModalBackdrop.style.display = 'none';
            });
            btnCancelContactModal.addEventListener('click', () => {
                contactModalBackdrop.style.display = 'none';
            });

            // Form Submit (Add / Edit)
            contactDataForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const recordId = formRecordId.value;
                const isEdit = Boolean(recordId);
                const url = isEdit ? `{{ url('admin/directory/update') }}/${recordId}` : `{{ route(isset($role) && $role === 'assistant' ? 'assistant.directory.store' : 'admin.directory.store') }}`;

                const selectedRadio = document.querySelector('input[name="internal_label"]:checked');
                const internalLabel = selectedRadio ? selectedRadio.value : 'Saint';

                const payload = {
                    _token: csrfToken,
                    barangay_id: document.getElementById('formBarangayId').value,
                    contact_type: document.getElementById('formContactType').value,
                    name: document.getElementById('formName').value,
                    position: document.getElementById('formPosition').value,
                    contact_number: document.getElementById('formContactNumber').value,
                    other_info: document.getElementById('formOtherInfo').value,
                    internal_label: internalLabel
                };

                const btn = document.getElementById('btnSubmitContact');
                btn.disabled = true;
                btn.textContent = 'Saving...';

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
                    btn.disabled = false;
                    btn.textContent = isEdit ? 'Update Contact' : 'Save Contact';

                    if (data.success) {
                        contactModalBackdrop.style.display = 'none';
                        showToast(data.message || 'Contact saved successfully.');
                        loadData();
                    } else {
                        alert(data.message || 'Error occurred while saving.');
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.textContent = isEdit ? 'Update Contact' : 'Save Contact';
                    console.error('Error:', err);
                    alert('An error occurred while saving.');
                });
            });

            // --- 10. View Modal Management ---
            function openViewModal(id) {
                currentViewingRecordId = id;
                fetch(`{{ url('admin/directory/record') }}/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const r = data.record;
                            document.getElementById('viewName').textContent = r.name;
                            document.getElementById('viewPosition').textContent = r.position;
                            document.getElementById('viewBarangay').textContent = r.barangay_name;
                            document.getElementById('viewContactType').textContent = r.contact_type;
                            document.getElementById('viewContactNumber').textContent = r.contact_number || 'None provided';
                            document.getElementById('viewOtherInfo').textContent = r.other_info || 'No additional notes provided.';

                            // Label badge
                            const badge = document.getElementById('viewLabelBadge');
                            const banner = document.getElementById('viewLabelBanner');
                            badge.textContent = r.internal_label;
                            badge.className = `label-badge badge-${r.internal_label.toLowerCase()}`;

                            if (r.internal_label === 'Saint') {
                                banner.style.background = '#EFF6FF';
                                banner.style.border = '1px solid #BFDBFE';
                                banner.style.color = '#1D4ED8';
                            } else if (r.internal_label === 'Sinner') {
                                banner.style.background = '#F0FDF4';
                                banner.style.border = '1px solid #BBF7D0';
                                banner.style.color = '#15803D';
                            } else {
                                banner.style.background = '#FEFCE8';
                                banner.style.border = '1px solid #FEF08A';
                                banner.style.color = '#A16207';
                            }

                            document.getElementById('viewAuditCreator').textContent = r.created_by;
                            document.getElementById('viewAuditCreated').textContent = r.created_at_formatted;
                            document.getElementById('viewAuditEditor').textContent = r.updated_by;
                            document.getElementById('viewAuditUpdated').textContent = r.updated_at_formatted;

                            viewModalBackdrop.style.display = 'flex';
                        }
                    })
                    .catch(err => console.error(err));
            }

            btnCloseViewModal.addEventListener('click', () => {
                viewModalBackdrop.style.display = 'none';
            });
            btnCloseViewModalBtn.addEventListener('click', () => {
                viewModalBackdrop.style.display = 'none';
            });

            btnEditFromView.addEventListener('click', () => {
                viewModalBackdrop.style.display = 'none';
                if (currentViewingRecordId) {
                    openEditModal(currentViewingRecordId);
                }
            });

            // --- 11. Delete Modal Management ---
            function openDeleteModal(id, name) {
                recordToDeleteId = id;
                document.getElementById('deleteContactName').textContent = name || 'this contact';
                deleteModalBackdrop.style.display = 'flex';
            }

            btnCancelDelete.addEventListener('click', () => {
                deleteModalBackdrop.style.display = 'none';
                recordToDeleteId = null;
            });
            btnCancelDeleteBtn.addEventListener('click', () => {
                deleteModalBackdrop.style.display = 'none';
                recordToDeleteId = null;
            });

            btnConfirmDelete.addEventListener('click', function() {
                if (!recordToDeleteId) return;

                btnConfirmDelete.disabled = true;
                btnConfirmDelete.textContent = 'Deleting...';

                fetch(`{{ url('admin/directory/delete') }}/${recordToDeleteId}`, {
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
                    btnConfirmDelete.textContent = 'Delete Contact';
                    deleteModalBackdrop.style.display = 'none';
                    recordToDeleteId = null;

                    if (data.success) {
                        showToast(data.message || 'Contact deleted successfully.');
                        loadData();
                    } else {
                        alert(data.message || 'Could not delete contact.');
                    }
                })
                .catch(err => {
                    btnConfirmDelete.disabled = false;
                    btnConfirmDelete.textContent = 'Delete Contact';
                    console.error(err);
                });
            });

            // --- 12. Interactive Map Pan & Zoom (Exact match to Electoral & Assistance) ---
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

            // Initial zoom transform (1.5x)
            applyTransform();

            // Initial Data Load
            loadData();
        });
    </script>
@endsection
