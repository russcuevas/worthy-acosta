@extends('layouts.app')

@section('title', 'Events Module - Mariveles, Bataan')
@section('user_name', 'Administrator')
@section('user_role_label', 'Admin Portal')
@section('user_initials', 'AD')

@section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        /* Top Filter & Controls Header (Exact Match to Electoral & Assistance) */
        .events-controls-header {
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
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: var(--radius-sm);
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--color-deep-navy);
            outline: none;
            transition: border-color var(--transition-fast);
        }

        .filter-select:focus,
        .filter-input:focus {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
        }

        /* Status Classification Toggle Pills (All / Upcoming / Past) */
        .classification-pill-group {
            display: inline-flex;
            background: #F1F5F9;
            padding: 3px;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
        }

        .status-pill-btn {
            background: transparent;
            border: none;
            padding: 6px 14px;
            border-radius: 16px;
            font-size: 0.82rem;
            font-weight: 700;
            color: #64748B;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-pill-btn.active {
            background: var(--color-deep-navy);
            color: #FFFFFF;
            box-shadow: 0 2px 8px rgba(16, 42, 78, 0.25);
        }

        .status-pill-btn.active.upcoming-active {
            background: linear-gradient(135deg, #0284C7, #075998);
        }

        .status-pill-btn.active.past-active {
            background: linear-gradient(135deg, #475569, #1E293B);
        }

        .btn-reset-filters {
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 10px;
            border-radius: var(--radius-sm);
            transition: all var(--transition-fast);
            align-self: flex-end;
            margin-bottom: 2px;
        }

        .btn-reset-filters:hover {
            color: #DC2626;
            background: #FEF2F2;
        }

        /* 1. Municipal KPI Summary Cards Grid */
        .kpi-cards-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        @media (max-width: 1400px) {
            .kpi-cards-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 900px) {
            .kpi-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
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
        .kpi-card.card-upcoming::before { background: linear-gradient(90deg, #0284C7, #38BDF8); }
        .kpi-card.card-past::before { background: linear-gradient(90deg, #475569, #94A3B8); }
        .kpi-card.card-attendance::before { background: linear-gradient(90deg, #10B981, #059669); }
        .kpi-card.card-speech::before { background: linear-gradient(90deg, #F59E0B, #DC2626); }

        .kpi-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .kpi-title {
            font-size: 0.76rem;
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

        .card-total .kpi-icon-badge { background: #E0F2FE; color: #0284C7; }
        .card-upcoming .kpi-icon-badge { background: #E0F2FE; color: #075998; }
        .card-past .kpi-icon-badge { background: #F1F5F9; color: #475569; }
        .card-attendance .kpi-icon-badge { background: #D1FAE5; color: #059669; }
        .card-speech .kpi-icon-badge { background: #FEF3C7; color: #D97706; }

        .kpi-value {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            line-height: 1.15;
            margin-bottom: 6px;
        }

        .kpi-subtext {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .badge-speech-pulse {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 2px 8px;
            background: #FEF2F2;
            border: 1px solid #FCA5A5;
            color: #DC2626;
            border-radius: 12px;
            font-size: 0.70rem;
            font-weight: 800;
            letter-spacing: 0.03em;
            animation: urgentPulse 2s infinite;
        }

        @keyframes urgentPulse {
            0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(220, 38, 38, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
        }

        /* 2. Map Legend & Metric Switcher Bar */
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

        /* 3. Main Dashboard Grid (Exact Electoral & Assistance Grid: 1fr 380px) */
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

        /* Dynamic Hotspot Barangay Name Badge Pins (Patagilid / Slanted & Compact - Identical) */
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

        #mapHoverTooltip {
            position: absolute;
            pointer-events: none;
            background: rgba(16, 42, 78, 0.94);
            backdrop-filter: blur(8px);
            color: #FFFFFF;
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            font-size: 0.78rem;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
            z-index: 100;
            display: none;
            transform: translate(-50%, -120%);
            white-space: nowrap;
        }

        #mapHoverTooltip .tip-name {
            font-weight: 800;
            font-size: 0.86rem;
            margin-bottom: 2px;
            color: #51B8E5;
        }

        #mapHoverTooltip .tip-stat {
            font-size: 0.76rem;
            color: #E2E8F0;
        }

        /* Floating Map Zoom Controls */
        .map-floating-controls {
            position: absolute;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            z-index: 40;
        }

        .map-btn-icon {
            width: 36px;
            height: 36px;
            background: #FFFFFF;
            border: 1px solid rgba(226, 238, 248, 0.8);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--color-deep-navy);
            font-size: 1.15rem;
            font-weight: 800;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.18);
            transition: all var(--transition-fast);
        }

        .map-btn-icon:hover {
            background: var(--color-mist-blue);
            color: var(--color-primary-blue);
            transform: translateY(-1px);
        }

        /* Right Detail Sidebar Card (Exact Electoral & Assistance Structure) */
        .sidebar-detail-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .sidebar-header-badge {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1.5px solid #EEF2F6;
            padding-bottom: 12px;
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
            flex-shrink: 0;
        }

        /* 2-Column Stats Grid (Matches Electoral & Assistance) */
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
            letter-spacing: 0.03em;
        }

        .stat-box-val {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin-top: 2px;
        }

        /* Winner / Total Banner Box (Matching Electoral & Assistance) */
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
            font-size: 1.15rem;
            font-weight: 800;
            color: #0c4a6e;
            margin-top: 2px;
        }

        .banner-stat-badge {
            font-size: 0.74rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.65);
            padding: 3px 8px;
            border-radius: 10px;
            color: #0369A1;
        }

        /* Breakdown by Event Type Table (Matches User Spec Section 6) */
        .breakdown-section-title {
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--color-deep-navy);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.80rem;
        }

        .breakdown-table th {
            text-align: left;
            padding: 6px 8px;
            background: #F1F5F9;
            color: var(--text-muted);
            font-weight: 800;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1.5px solid #CBD5E1;
        }

        .breakdown-table th.num-col,
        .breakdown-table td.num-col {
            text-align: right;
        }

        .breakdown-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #EEF2F6;
            color: var(--color-deep-navy);
            font-weight: 600;
        }

        .breakdown-table tr:hover td {
            background: #F8FAFC;
        }

        .breakdown-table tr.total-row td {
            font-weight: 800;
            background: #F8FAFC;
            border-top: 1.5px solid #CBD5E1;
            border-bottom: none;
            color: var(--color-deep-navy);
        }

        /* 4. Upcoming Events Calendar / Planning Cards Section */
        .section-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .section-header-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-header-title h2 {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .upcoming-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 30px;
        }

        @media (max-width: 1200px) {
            .upcoming-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .upcoming-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        .upcoming-event-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 14px;
            position: relative;
            overflow: hidden;
            transition: all var(--transition-fast);
        }

        .upcoming-event-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: #CBD5E1;
        }

        .upcoming-event-card.speech-required-card {
            border-left: 4px solid #DC2626;
        }

        .event-card-top-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Date Ribbon Capsule */
        .event-datetime-capsule {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #F1F5F9;
            color: var(--color-deep-navy);
            padding: 4px 10px;
            border-radius: 14px;
            font-size: 0.74rem;
            font-weight: 800;
        }

        /* SPEECH REQUIRED Highlight Badge */
        .badge-speech-required {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FCA5A5;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.72rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            letter-spacing: 0.04em;
        }

        .badge-speech-not-required {
            background: #F8FAFC;
            color: #64748B;
            border: 1px solid #E2E8F0;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.70rem;
            font-weight: 700;
        }

        /* Attendance Status Badges */
        .attendance-badge-confirmed { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
        .attendance-badge-tentative { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }
        .attendance-badge-declined { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
        .attendance-badge-for-confirmation { background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; }

        .attendance-status-pill {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.70rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .event-card-name {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            line-height: 1.3;
            margin: 4px 0 2px 0;
        }

        .event-card-theme {
            font-size: 0.78rem;
            font-style: italic;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .event-card-info-list {
            display: flex;
            flex-direction: column;
            gap: 7px;
            font-size: 0.80rem;
            color: var(--text-muted);
            background: #F8FAFC;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid #EEF2F6;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.35;
        }

        .info-row svg {
            flex-shrink: 0;
            margin-top: 2px;
            color: #64748B;
        }

        .info-row strong {
            color: var(--color-deep-navy);
        }

        .organizer-request-box {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: var(--radius-sm);
            padding: 8px 12px;
            font-size: 0.78rem;
            color: #92400E;
            display: flex;
            align-items: flex-start;
            gap: 7px;
        }

        .organizer-request-box strong {
            color: #78350F;
        }

        .event-card-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 10px;
            border-top: 1px solid #EEF2F6;
            gap: 8px;
        }

        .btn-mark-past {
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            color: #059669;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-size: 0.76rem;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all var(--transition-fast);
        }

        .btn-mark-past:hover {
            background: #D1FAE5;
            color: #047857;
            transform: translateY(-1px);
        }

        .card-icon-btn {
            width: 30px;
            height: 30px;
            border-radius: var(--radius-sm);
            border: 1px solid #E2E8F0;
            background: #F8FAFC;
            color: var(--color-deep-navy);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .card-icon-btn:hover {
            background: #EEF2F6;
            color: var(--color-primary-blue);
        }

        /* 5. Master Events Data Table Section */
        .table-card-container {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 22px;
            margin-bottom: 24px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .table-nav-ribbon {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .table-tab-buttons {
            display: inline-flex;
            background: #F1F5F9;
            padding: 4px;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            gap: 4px;
        }

        .tab-btn {
            background: transparent;
            border: none;
            padding: 6px 16px;
            border-radius: 16px;
            font-size: 0.82rem;
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

        /* Custom DataTables Styling (Exact Match to Assistance Module) */
        .dataTables_wrapper {
            font-family: inherit;
            color: var(--color-deep-navy);
            font-size: 0.84rem;
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

        /* Custom DataTables Footer & Pagination (Exact Match to Assistance Module) */
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

        .table-bgy-pill {
            background: #EFF6FF;
            color: var(--color-primary-blue);
            font-weight: 700;
            font-size: 0.78rem;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .status-badge-upcoming {
            background: #E0F2FE;
            color: #0369A1;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.72rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-badge-past {
            background: #F1F5F9;
            color: #475569;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.72rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .action-icon-group {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-action-sm {
            width: 28px;
            height: 28px;
            border-radius: var(--radius-sm);
            border: 1px solid #E2E8F0;
            background: #FFFFFF;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--color-deep-navy);
            transition: all var(--transition-fast);
        }

        .btn-action-sm:hover {
            background: #F1F5F9;
            color: var(--color-primary-blue);
        }

        .btn-action-sm.delete-btn:hover {
            background: #FEF2F2;
            color: #DC2626;
            border-color: #FCA5A5;
        }

        /* Modals Architecture */
        .modal-backdrop-custom {
            position: fixed;
            inset: 0;
            background: rgba(10, 26, 48, 0.65);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-box-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 780px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            border: 1px solid var(--card-border);
            animation: modalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalPop {
            0% { transform: scale(0.95); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .modal-header-custom {
            padding: 18px 24px;
            border-bottom: 1px solid #EEF2F6;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
        }

        /* Step 1: Big Choice Pills for Classification */
        .classification-choice-container {
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: var(--radius-md);
            padding: 14px;
            margin-bottom: 20px;
        }

        .choice-title {
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .choice-buttons-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .choice-btn {
            border: 2px solid #E2E8F0;
            background: #FFFFFF;
            border-radius: var(--radius-md);
            padding: 14px 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all var(--transition-fast);
            text-align: left;
        }

        .choice-btn:hover {
            border-color: #CBD5E1;
            background: #F1F5F9;
        }

        .choice-btn.selected {
            border-color: var(--color-primary-blue);
            background: #EFF6FF;
            box-shadow: 0 4px 14px rgba(7, 89, 152, 0.15);
        }

        .choice-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #F1F5F9;
            color: #64748B;
        }

        .choice-btn.selected .choice-icon {
            background: var(--color-primary-blue);
            color: #FFFFFF;
        }

        .choice-text-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--color-deep-navy);
        }

        .choice-text-desc {
            font-size: 0.74rem;
            color: var(--text-muted);
        }

        /* Form Grid */
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

        /* Speech Required Radio Buttons */
        .speech-radio-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .speech-radio-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 0.86rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .speech-radio-label:has(input:checked) {
            border-color: #DC2626;
            background: #FEF2F2;
            color: #DC2626;
        }

        .modal-footer-custom {
            padding: 16px 24px;
            border-top: 1px solid #EEF2F6;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
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

        /* Toast Notice */
        #toastNotice {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #102A4E;
            color: #FFFFFF;
            padding: 14px 20px;
            border-radius: var(--radius-md);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            border-left: 4px solid #10B981;
            font-size: 0.88rem;
            font-weight: 700;
            display: none;
            align-items: center;
            gap: 10px;
            z-index: 2000;
            animation: slideInUp 0.3s ease-out;
        }

        @keyframes slideInUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
@endsection

@section('content')
    <!-- 1. Top Filter & Controls Header -->
    <div class="events-controls-header">
        <div class="header-title-bar-row">
            <div class="header-title-left">
                <div style="background:#EFF6FF; padding:10px; border-radius:12px; color:var(--color-primary-blue);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </div>
                <div>
                    <h1>Events Module</h1>
                    <p>Planning tool for upcoming events & historical database for past events in Mariveles, Bataan</p>
                </div>
            </div>

            <div class="header-actions-right">
                <button type="button" class="btn-encode-data" id="btnOpenAddModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Encode Event</span>
                </button>
            </div>
        </div>

        <!-- Filter Controls Row -->
        <div class="filter-controls-row">
            <div class="filter-left-group">
                <!-- Status Classification Filter Pills -->
                <div class="filter-item">
                    <label>Event Classification</label>
                    <div class="classification-pill-group">
                        <button type="button" class="status-pill-btn active" data-status="all" id="pillStatusAll">
                            All Events
                        </button>
                        <button type="button" class="status-pill-btn" data-status="Upcoming" id="pillStatusUpcoming">
                            Upcoming (<span id="pillUpcomingCount">0</span>)
                        </button>
                        <button type="button" class="status-pill-btn" data-status="Past" id="pillStatusPast">
                            Past (<span id="pillPastCount">0</span>)
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

                <!-- Event Type Filter -->
                <div class="filter-item">
                    <label for="filterType">Type of Event</label>
                    <select id="filterType" class="filter-select">
                        <option value="all">All Types</option>
                        <option value="Municipal">Municipal Event</option>
                        <option value="Barangay">Barangay Event</option>
                        <option value="Sectoral">Sectoral</option>
                        <option value="Political">Political</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                <!-- Speech Required Filter -->
                <div class="filter-item">
                    <label for="filterSpeech">Speech Filter</label>
                    <select id="filterSpeech" class="filter-select">
                        <option value="all">All Speeches</option>
                        <option value="1">Speech Required Only</option>
                        <option value="0">No Speech Required</option>
                    </select>
                </div>

                <!-- Date Range Filter -->
                <div class="filter-item">
                    <label>Date From</label>
                    <input type="date" id="filterDateFrom" class="filter-input">
                </div>
                <div class="filter-item">
                    <label>Date To</label>
                    <input type="date" id="filterDateTo" class="filter-input">
                </div>

                <!-- Keyword Search -->
                <div class="filter-item" style="flex:1; min-width: 180px;">
                    <label for="filterSearch">Search</label>
                    <input type="text" id="filterSearch" class="filter-input" placeholder="Search event, venue, who invited...">
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

    <!-- 2. Municipal KPI Summary Cards (User Spec Section 8: TOTAL EVENTS | UPCOMING | PAST | TOTAL ATTENDANCE) -->
    <div class="kpi-cards-grid">
        <!-- Total Events -->
        <div class="kpi-card card-total">
            <div class="kpi-header">
                <span class="kpi-title">Total Events</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiTotalEvents">0</div>
            <div class="kpi-subtext" id="kpiTotalSub">Across all 18 Mariveles Barangays</div>
        </div>

        <!-- Upcoming Events -->
        <div class="kpi-card card-upcoming">
            <div class="kpi-header">
                <span class="kpi-title">Upcoming Events</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiUpcomingEvents" style="color:#0284C7;">0</div>
            <div class="kpi-subtext" id="kpiUpcomingSub">Scheduled & In Planning</div>
        </div>

        <!-- Past Events -->
        <div class="kpi-card card-past">
            <div class="kpi-header">
                <span class="kpi-title">Past Events</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiPastEvents">0</div>
            <div class="kpi-subtext">Concluded historical database</div>
        </div>

        <!-- Total Attendance -->
        <div class="kpi-card card-attendance">
            <div class="kpi-header">
                <span class="kpi-title">Total Attendance</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiTotalAttendance" style="color:#059669;">0</div>
            <div class="kpi-subtext" id="kpiExpectedSub">Expected: 0</div>
        </div>

        <!-- Speech Required Alert Counter -->
        <div class="kpi-card card-speech">
            <div class="kpi-header">
                <span class="kpi-title">Speeches Required</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiSpeechRequired" style="color:#DC2626;">0</div>
            <div class="kpi-subtext">
                <span class="badge-speech-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                    </svg>
                    SPEECH REQUIRED
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Map Legend & Metric Switcher -->
    <div class="map-legend-bar-container">
        <div class="legend-title-group">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                stroke-width="2.2" stroke="currentColor" style="color:var(--color-primary-blue);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            <span>Interactive Map Metric: (<span id="activeMetricLabel" style="color:var(--color-primary-blue); font-weight:800;">Total Events</span>)</span>
        </div>
        <div class="legend-items-container">
            <button type="button" class="metric-pill-btn active" data-metric="total_events">
                <span>Total Events</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="upcoming_events">
                <span>Upcoming Events</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="past_events">
                <span>Past Events</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="actual_attendees">
                <span>Actual Attendees</span>
            </button>
            <button type="button" class="metric-pill-btn" data-metric="expected_attendees">
                <span>Expected Attendees</span>
            </button>
        </div>
    </div>

    <!-- 3. Main Dashboard: Map Visualizer on Left + Detailed Barangay Results on Right (Exact Electoral & Assistance Grid) -->
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
                    <div class="tip-stat" id="tipStat">Total Events: 0</div>
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

        <!-- Right: Events Summary per Barangay Card (User Spec Section 5 & 6) -->
        <div class="sidebar-detail-card" id="sidebarDetailCard">
            <div class="sidebar-header-badge">
                <div>
                    <div class="detail-meta" id="summaryMetaTag">Consolidated Mariveles Overview</div>
                    <div class="detail-title" id="summaryBgyTitle">All 18 Barangays</div>
                </div>
                <div class="bgy-number-badge" id="cardNumberBadge" title="Selected Barangay Marker">18</div>
            </div>

            <!-- Stats Grid for Barangay: Total, Upcoming, Past, Expected -->
            <div class="stats-grid-2col">
                <div class="stat-box">
                    <div class="stat-box-label">Total Events</div>
                    <div class="stat-box-val" id="summaryTotalEvents">0</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-label">Upcoming Events</div>
                    <div class="stat-box-val" id="summaryUpcomingEvents" style="color:var(--color-primary-blue);">0</div>
                </div>
            </div>

            <div class="stats-grid-2col">
                <div class="stat-box">
                    <div class="stat-box-label">Past Events</div>
                    <div class="stat-box-val" id="summaryPastEvents">0</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-label">Expected Attendees</div>
                    <div class="stat-box-val" id="summaryExpectedAttendees">0</div>
                </div>
            </div>

            <!-- Total Actual Attendance Banner with Comparison (Matching Electoral & Assistance) -->
            <div class="winner-banner-box">
                <div>
                    <div class="winner-banner-text">👥 Total Actual Attendance</div>
                    <div class="winner-name-display" id="summaryActualAttendees">0</div>
                </div>
                <div class="banner-stat-badge" id="summaryVarianceBadge">
                    Variance: 0.0%
                </div>
            </div>

            <!-- Breakdown by Event Type (User Spec Section 6) -->
            <div>
                <div class="breakdown-section-title">
                    <span>Breakdown by Event Type</span>
                    <span style="font-size:0.70rem; color:var(--text-muted); font-weight:700;">No. of Events / Actual</span>
                </div>
                <table class="breakdown-table" style="margin-top: 6px;">
                    <thead>
                        <tr>
                            <th>Event Type</th>
                            <th class="num-col">No. of Events</th>
                            <th class="num-col">Actual Attendance</th>
                        </tr>
                    </thead>
                    <tbody id="breakdownTableBody">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>

            <!-- Action Button to Filter by this Barangay -->
            <button type="button" class="btn-modal-cancel" id="btnFilterByThisBarangay" style="width:100%; text-align:center; padding: 7px 12px; font-size: 0.78rem;">
                View All Records for this Barangay &darr;
            </button>
        </div>
    </div>

    <!-- 5. Upcoming Events Highlights & Planning Section (User Spec Section 3 & 8) -->
    <div id="upcomingSectionWrap">
        <div class="section-header-row">
            <div class="section-header-title">
                <h2>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor" style="color:#0284C7;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span>Upcoming Events Planning & Agenda</span>
                </h2>
                <div id="upcomingBgyFilterNotice" style="display:none; align-items:center; gap:8px; background:#EFF6FF; border:1px solid #BFDBFE; color:#1E40AF; padding:4px 12px; border-radius:20px; font-size:0.80rem; font-weight:700;">
                    <span>Filtered: <strong id="upcomingBgyFilterName"></strong></span>
                    <button type="button" id="btnClearUpcomingBgy" title="Clear barangay filter" style="background:none; border:none; color:#2563EB; font-weight:800; font-size:1.05rem; cursor:pointer; line-height:1; padding:0 2px;">&times;</button>
                </div>
                <span class="badge-speech-pulse" id="upcomingSpeechAlertBanner" style="display:none;">
                    🎙️ <span id="upcomingSpeechAlertCount">0</span> Speech Required
                </span>
            </div>
        </div>

        <!-- Upcoming Cards Container -->
        <div class="upcoming-cards-grid" id="upcomingCardsGrid">
            <!-- Dynamically populated via JS -->
        </div>
    </div>

    <!-- 6. Master Events Database Table -->
    <div class="table-card-container">
        <div class="table-nav-ribbon">
            <div class="section-header-title">
                <h2>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                    </svg>
                    <span>Events Records Database</span>
                    <span style="font-size: 0.78rem; font-weight: 700; background: #EFF6FF; color: var(--color-primary-blue); padding: 3px 10px; border-radius: 12px; border: 1px solid #BFDBFE;" id="recordsCountBadge">0 Records</span>
                </h2>
                <div id="tableBgyFilterNotice" style="display:none; align-items:center; gap:8px; background:#EFF6FF; border:1px solid #BFDBFE; color:#1E40AF; padding:4px 12px; border-radius:20px; font-size:0.80rem; font-weight:700;">
                    <span>Filtered: <strong id="tableBgyFilterName"></strong></span>
                    <button type="button" id="btnClearTableBgy" title="Clear barangay filter" style="background:none; border:none; color:#2563EB; font-weight:800; font-size:1.05rem; cursor:pointer; line-height:1; padding:0 2px;">&times;</button>
                </div>
            </div>

            <div class="table-tab-buttons">
                <button type="button" class="tab-btn active" data-tab-status="all">All Events</button>
                <button type="button" class="tab-btn" data-tab-status="Upcoming">Upcoming Only</button>
                <button type="button" class="tab-btn" data-tab-status="Past">Past Only</button>
                <button type="button" class="tab-btn" data-tab-status="speech">Speech Required Only</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="eventsMasterTable" class="dataTable stripe hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Date & Time</th>
                        <th>Barangay</th>
                        <th>Event Name & Theme</th>
                        <th>Type</th>
                        <th>Who Invited</th>
                        <th>Speech</th>
                        <th>Attendance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="eventsTableBody">
                    <!-- Populated via DataTables -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== MODALS ==================== -->

    <!-- 1. Add / Edit Event Modal -->
    <div class="modal-backdrop-custom" id="eventModalBackdrop">
        <div class="modal-box-card">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom" id="modalTitle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor" style="color:var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span id="modalTitleText">Encode New Event</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseEventModal">&times;</button>
            </div>

            <form id="eventForm">
                <input type="hidden" id="formEventId" value="">

                <div class="modal-body-custom">
                    <!-- User Spec Section 1: Choose Classification before encoding -->
                    <div class="classification-choice-container">
                        <div class="choice-title">1. Event Classification <span class="req" style="color:#DC2626;">*</span></div>
                        <div class="choice-buttons-grid">
                            <button type="button" class="choice-btn selected" id="btnChooseUpcoming" data-choice="Upcoming">
                                <div class="choice-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="choice-text-title">Upcoming Event</div>
                                    <div class="choice-text-desc">Scheduled for planning & preparation</div>
                                </div>
                            </button>

                            <button type="button" class="choice-btn" id="btnChoosePast" data-choice="Past">
                                <div class="choice-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="choice-text-title">Past Event</div>
                                    <div class="choice-text-desc">Concluded event for historical record</div>
                                </div>
                            </button>
                        </div>
                        <input type="hidden" id="formStatus" name="status" value="Upcoming">
                    </div>

                    <!-- Event Data Entry Form (User Spec Section 2) -->
                    <div class="form-grid-2col">
                        <!-- Barangay -->
                        <div class="form-group-custom">
                            <label for="formBarangay">Barangay <span class="req">*</span></label>
                            <select id="formBarangay" name="barangay_id" class="form-control-custom" required>
                                <option value="">-- Select Barangay --</option>
                                @foreach ($barangays as $bgy)
                                    <option value="{{ $bgy->id }}">{{ $bgy->id }}. {{ $bgy->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date and Time -->
                        <div class="form-group-custom">
                            <label for="formDatetime">Date and Time <span class="req">*</span></label>
                            <input type="datetime-local" id="formDatetime" name="event_datetime" class="form-control-custom" required>
                        </div>

                        <!-- Event Name -->
                        <div class="form-group-custom form-col-span-2">
                            <label for="formName">Event Name / Title <span class="req">*</span></label>
                            <input type="text" id="formName" name="name" class="form-control-custom"
                                placeholder="e.g. Barangay General Assembly, Job Fair, Cultural Fiesta" required>
                        </div>

                        <!-- Theme -->
                        <div class="form-group-custom form-col-span-2">
                            <label for="formTheme">Official Theme (if applicable)</label>
                            <input type="text" id="formTheme" name="theme" class="form-control-custom"
                                placeholder="Official slogan or banner theme">
                        </div>

                        <!-- Venue -->
                        <div class="form-group-custom">
                            <label for="formVenue">Venue / Location <span class="req">*</span></label>
                            <input type="text" id="formVenue" name="venue" class="form-control-custom"
                                placeholder="e.g. Barangay Covered Court, Multi-Purpose Hall" required>
                        </div>

                        <!-- Type of Event -->
                        <div class="form-group-custom">
                            <label for="formEventType">Type of Event <span class="req">*</span></label>
                            <select id="formEventType" name="event_type" class="form-control-custom" required>
                                <option value="Municipal">Municipal Event</option>
                                <option value="Barangay">Barangay Event</option>
                                <option value="Sectoral">Sectoral</option>
                                <option value="Political">Political</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <!-- Custom Type for Others -->
                        <div class="form-group-custom form-col-span-2" id="customTypeContainer" style="display:none;">
                            <label for="formCustomType">Specify Other Event Type <span class="req">*</span></label>
                            <input type="text" id="formCustomType" name="custom_type" class="form-control-custom"
                                placeholder="e.g. Religious, NGO Advocacy, Alumni Homecoming">
                        </div>

                        <!-- Who Invited -->
                        <div class="form-group-custom">
                            <label for="formWhoInvited">Who Invited <span class="req">*</span></label>
                            <input type="text" id="formWhoInvited" name="who_invited" class="form-control-custom"
                                placeholder="Person, organization, office, or group" required>
                        </div>

                        <!-- Contact Information -->
                        <div class="form-group-custom">
                            <label for="formContactInfo">Contact Information</label>
                            <input type="text" id="formContactInfo" name="contact_info" class="form-control-custom"
                                placeholder="Contact person, phone number, email">
                        </div>

                        <!-- Speech Required Radio (Yes / No) -->
                        <div class="form-group-custom">
                            <label>Speech Required? <span class="req">*</span></label>
                            <div class="speech-radio-group">
                                <label class="speech-radio-label">
                                    <input type="radio" name="speech_required" id="speechYes" value="1">
                                    <span>YES (SPEECH REQUIRED)</span>
                                </label>
                                <label class="speech-radio-label">
                                    <input type="radio" name="speech_required" id="speechNo" value="0" checked>
                                    <span>NO</span>
                                </label>
                            </div>
                        </div>

                        <!-- Attendance Status (Optional for Upcoming) -->
                        <div class="form-group-custom" id="attendanceStatusContainer">
                            <label for="formAttendanceStatus">Attendance Status</label>
                            <select id="formAttendanceStatus" name="attendance_status" class="form-control-custom">
                                <option value="For Confirmation">For Confirmation</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Tentative">Tentative</option>
                                <option value="Declined">Declined</option>
                            </select>
                        </div>

                        <!-- Expected Number of Attendees -->
                        <div class="form-group-custom">
                            <label for="formExpectedAttendees">Number of Attendees (Expected) <span class="req">*</span></label>
                            <input type="number" id="formExpectedAttendees" name="expected_attendees" class="form-control-custom"
                                min="0" placeholder="e.g. 250" required>
                        </div>

                        <!-- Total Number Who Attended (Actual Attendance for Past Events) -->
                        <div class="form-group-custom" id="actualAttendeesContainer">
                            <label for="formActualAttendees" id="lblActualAttendees">Total Number Who Attended (Actual)</label>
                            <input type="number" id="formActualAttendees" name="actual_attendees" class="form-control-custom"
                                min="0" placeholder="e.g. 275">
                        </div>

                        <!-- Organizer Request -->
                        <div class="form-group-custom form-col-span-2">
                            <label for="formRequest">Request from Organizer</label>
                            <textarea id="formRequest" name="request" class="form-control-custom" rows="2"
                                placeholder="Any logistical request, financial sponsorship, guest speaker message, medals, etc."></textarea>
                        </div>

                        <!-- Event Details / Description -->
                        <div class="form-group-custom form-col-span-2">
                            <label for="formDetails">Details & Important Information</label>
                            <textarea id="formDetails" name="details" class="form-control-custom" rows="3"
                                placeholder="Event objectives, agenda, key VIPs attending, instructions"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelEventModal">Cancel</button>
                    <button type="submit" class="btn-modal-submit" id="btnSubmitEvent">Save Event</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. Mark as Past & Record Attendance Modal (User Spec Section 4) -->
    <div class="modal-backdrop-custom" id="markPastModalBackdrop">
        <div class="modal-box-card" style="max-width: 520px;">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor" style="color:#059669;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Record Actual Attendance</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseMarkPastModal">&times;</button>
            </div>

            <form id="markPastForm">
                <input type="hidden" id="markPastEventId" value="">

                <div class="modal-body-custom">
                    <div style="background:#F8FAFC; border:1px solid #E2E8F0; padding:12px; border-radius:8px; margin-bottom:16px;">
                        <div style="font-size:0.76rem; font-weight:800; color:var(--text-muted); text-transform:uppercase;">Event</div>
                        <div style="font-size:1rem; font-weight:800; color:var(--color-deep-navy);" id="markPastEventName">Event Title</div>
                        <div style="font-size:0.80rem; color:var(--text-muted); margin-top:4px;">
                            Scheduled Expected Attendees: <strong id="markPastExpectedCount" style="color:var(--color-deep-navy);">0</strong>
                        </div>
                    </div>

                    <div class="form-group-custom" style="margin-bottom:16px;">
                        <label for="markPastActualAttendees">Total Number Who Attended (Actual Attendance) <span class="req" style="color:#DC2626;">*</span></label>
                        <input type="number" id="markPastActualAttendees" class="form-control-custom" min="0" required
                            placeholder="Enter verified attendance count">
                        <small style="color:var(--text-muted); font-size:0.75rem;">
                            Original expected attendance will remain stored so the system compares expected vs actual attendance.
                        </small>
                    </div>

                    <div class="form-group-custom" style="margin-bottom:16px;">
                        <label for="markPastAttendanceStatus">Attendance Outcome Status</label>
                        <select id="markPastAttendanceStatus" class="form-control-custom">
                            <option value="Confirmed">Completed / Attended</option>
                            <option value="Tentative">Partially Attended</option>
                            <option value="Declined">Did Not Attend / Cancelled</option>
                        </select>
                    </div>

                    <div class="form-group-custom">
                        <label for="markPastPostNotes">Post-Event Notes / Remarks (Optional)</label>
                        <textarea id="markPastPostNotes" class="form-control-custom" rows="2"
                            placeholder="Highlights, summary of discussions, speech outcome..."></textarea>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelMarkPast">Cancel</button>
                    <button type="submit" class="btn-modal-submit" style="background:#059669;">Save & Mark as Past</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. View Event Details Modal -->
    <div class="modal-backdrop-custom" id="viewEventModalBackdrop">
        <div class="modal-box-card" style="max-width: 680px;">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" style="color:var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span>Event Details</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseViewModal">&times;</button>
            </div>

            <div class="modal-body-custom" id="viewModalBody">
                <!-- Dynamically filled via JS -->
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnCloseViewBtn">Close</button>
            </div>
        </div>
    </div>

    <!-- 4. Delete Confirmation Modal -->
    <div class="modal-backdrop-custom" id="deleteModalBackdrop">
        <div class="modal-box-card" style="max-width: 440px;">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom" style="color:#DC2626;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <span>Confirm Delete Event</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCancelDelete">&times;</button>
            </div>
            <div class="modal-body-custom">
                <p style="font-size:0.92rem; color:var(--text-main); margin-bottom:10px;">
                    Are you sure you want to permanently delete this event record?
                </p>
                <div style="background:#FEF2F2; border:1px solid #FECACA; padding:10px 14px; border-radius:8px; font-size:0.84rem; color:#991B1B;"
                    id="deleteEventSummaryText">
                    Event record details will be removed.
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnCancelDeleteModal">Cancel</button>
                <button type="button" class="btn-modal-submit" id="btnConfirmDelete" style="background:#DC2626;">Delete Event</button>
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

            // Coordinates for 18 Mariveles Barangays (Identical to Electoral & Assistance Modules)
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

            // State
            let currentClassification = 'all'; // 'all', 'Upcoming', 'Past'
            let currentMetric = 'total_events'; // 'total_events', 'upcoming_events', 'past_events', 'actual_attendees', 'expected_attendees'
            let selectedBarangayId = 'all'; // 'all' or 1..18
            let activeDataset = null;
            let dataTableInstance = null;
            let eventToDeleteId = null;

            // Pan & Zoom State (Matching Electoral & Assistance: Default 1.5x)
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
            const filterSpeech = document.getElementById('filterSpeech');
            const filterDateFrom = document.getElementById('filterDateFrom');
            const filterDateTo = document.getElementById('filterDateTo');
            const filterSearch = document.getElementById('filterSearch');
            const btnResetFilters = document.getElementById('btnResetFilters');
            const activeMetricLabel = document.getElementById('activeMetricLabel');

            // Modals
            const eventModalBackdrop = document.getElementById('eventModalBackdrop');
            const btnOpenAddModal = document.getElementById('btnOpenAddModal');
            const btnCloseEventModal = document.getElementById('btnCloseEventModal');
            const btnCancelEventModal = document.getElementById('btnCancelEventModal');
            const eventForm = document.getElementById('eventForm');
            const formEventId = document.getElementById('formEventId');
            const formStatus = document.getElementById('formStatus');
            const btnChooseUpcoming = document.getElementById('btnChooseUpcoming');
            const btnChoosePast = document.getElementById('btnChoosePast');
            const formEventType = document.getElementById('formEventType');
            const customTypeContainer = document.getElementById('customTypeContainer');
            const formCustomType = document.getElementById('formCustomType');
            const actualAttendeesContainer = document.getElementById('actualAttendeesContainer');
            const formActualAttendees = document.getElementById('formActualAttendees');

            // Mark Past Modal
            const markPastModalBackdrop = document.getElementById('markPastModalBackdrop');
            const markPastForm = document.getElementById('markPastForm');
            const btnCloseMarkPastModal = document.getElementById('btnCloseMarkPastModal');
            const btnCancelMarkPast = document.getElementById('btnCancelMarkPast');

            // Delete Modal
            const deleteModalBackdrop = document.getElementById('deleteModalBackdrop');
            const btnCancelDelete = document.getElementById('btnCancelDelete');
            const btnCancelDeleteModal = document.getElementById('btnCancelDeleteModal');
            const btnConfirmDelete = document.getElementById('btnConfirmDelete');

            // View Modal
            const viewEventModalBackdrop = document.getElementById('viewEventModalBackdrop');
            const btnCloseViewModal = document.getElementById('btnCloseViewModal');
            const btnCloseViewBtn = document.getElementById('btnCloseViewBtn');

            function showToast(msg) {
                const toast = document.getElementById('toastNotice');
                document.getElementById('toastMessage').textContent = msg;
                toast.style.display = 'flex';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 3500);
            }

            // --- 1. Load Data via AJAX ---
            function loadData() {
                const params = new URLSearchParams();
                if (currentClassification !== 'all') params.append('status', currentClassification);
                if (filterType.value !== 'all') params.append('event_type', filterType.value);
                if (filterSpeech.value !== 'all') params.append('speech_required', filterSpeech.value);
                if (filterDateFrom.value) params.append('date_from', filterDateFrom.value);
                if (filterDateTo.value) params.append('date_to', filterDateTo.value);
                if (filterSearch.value.trim()) params.append('search', filterSearch.value.trim());
                params.append('metric', currentMetric);

                fetch(`{{ route('admin.events.data') }}?${params.toString()}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            activeDataset = data;
                            renderMapPins(data.barangays);
                            selectBarangay(selectedBarangayId);
                        }
                    })
                    .catch(err => {
                        console.error('Failed to load events dataset:', err);
                    });
            }

            // --- 2. Update KPI Cards ---
            function updateKpis(kpis) {
                document.getElementById('kpiTotalEvents').textContent = Number(kpis.total_events || 0).toLocaleString();
                document.getElementById('kpiUpcomingEvents').textContent = Number(kpis.upcoming_events || 0).toLocaleString();
                document.getElementById('kpiPastEvents').textContent = Number(kpis.past_events || 0).toLocaleString();
                document.getElementById('kpiTotalAttendance').textContent = Number(kpis.actual_attendees || 0).toLocaleString();
                document.getElementById('kpiExpectedSub').textContent = `Expected: ${Number(kpis.expected_attendees || 0).toLocaleString()}`;
                document.getElementById('kpiSpeechRequired').textContent = Number(kpis.speech_required_count || 0).toLocaleString();

                // Pills counters
                document.getElementById('pillUpcomingCount').textContent = kpis.upcoming_events || 0;
                document.getElementById('pillPastCount').textContent = kpis.past_events || 0;

                // Upcoming Speech Alert Pill
                const speechBanner = document.getElementById('upcomingSpeechAlertBanner');
                if (speechBanner) {
                    if (kpis.speech_required_count > 0) {
                        speechBanner.style.display = 'inline-flex';
                        document.getElementById('upcomingSpeechAlertCount').textContent = kpis.speech_required_count;
                    } else {
                        speechBanner.style.display = 'none';
                    }
                }
            }

            // --- 3. Centralized Select Barangay (Syncs Map, Top KPIs, Upcoming Cards, Sidebar & DataTable) ---
            function selectBarangay(bgyId) {
                selectedBarangayId = bgyId;

                // Sync Filter Dropdown
                if (filterBarangay) {
                    filterBarangay.value = bgyId;
                }

                // Update Map Pins Visual Highlighting
                updateSelectedPinVisual();

                if (!activeDataset) return;

                const isAll = (bgyId === 'all');
                const bgyName = !isAll ? (barangayNames[bgyId] || `Brgy. ${bgyId}`) : null;

                // 1) Update Top KPI Cards & Subtext
                const kpiSub = document.getElementById('kpiTotalSub');
                if (isAll) {
                    updateKpis(activeDataset.kpis);
                    if (kpiSub) kpiSub.textContent = 'Across all 18 Mariveles Barangays';
                } else {
                    const bgyData = (activeDataset.barangays && activeDataset.barangays[bgyId]) ? activeDataset.barangays[bgyId] : {
                        total_events: 0,
                        upcoming_events: 0,
                        past_events: 0,
                        expected_attendees: 0,
                        actual_attendees: 0,
                        speech_required_count: 0
                    };
                    updateKpis(bgyData);
                    if (kpiSub) kpiSub.textContent = `Filtered for Brgy. ${bgyName}`;
                }

                // 2) Render Sidebar Summary & Breakdown Table
                renderSidebarSummary(bgyId);

                // 3) Filter & Render Upcoming Events Highlights Cards
                const upcomingNotice = document.getElementById('upcomingBgyFilterNotice');
                const upcomingNameEl = document.getElementById('upcomingBgyFilterName');
                if (isAll) {
                    renderUpcomingCards(activeDataset.upcoming_highlights);
                    if (upcomingNotice) upcomingNotice.style.display = 'none';
                } else {
                    const filteredUpcoming = (activeDataset.upcoming_highlights || []).filter(item => item.barangay_id == bgyId);
                    renderUpcomingCards(filteredUpcoming, bgyName);
                    if (upcomingNotice) {
                        upcomingNotice.style.display = 'inline-flex';
                        if (upcomingNameEl) upcomingNameEl.textContent = `Brgy. ${bgyName}`;
                    }
                }

                // 4) Filter & Render Master DataTable Records
                const tableNotice = document.getElementById('tableBgyFilterNotice');
                const tableNameEl = document.getElementById('tableBgyFilterName');
                if (isAll) {
                    renderDataTable(activeDataset.records);
                    if (tableNotice) tableNotice.style.display = 'none';
                } else {
                    const filteredRecords = (activeDataset.records || []).filter(r => r.barangay_id == bgyId);
                    renderDataTable(filteredRecords);
                    if (tableNotice) {
                        tableNotice.style.display = 'inline-flex';
                        if (tableNameEl) tableNameEl.textContent = `Brgy. ${bgyName}`;
                    }
                }

                // 5) Pre-fill Barangay field in Encode Event form if filtered
                const formBarangaySelect = document.getElementById('formBarangay');
                if (formBarangaySelect && !isAll) {
                    formBarangaySelect.value = bgyId;
                }
            }

            // --- 4. Render Map Hotspot Pins (Identical Capsule Pin System) ---
            function renderMapPins(barangaysMap) {
                pinsContainer.innerHTML = '';

                for (let bgyId = 1; bgyId <= 18; bgyId++) {
                    const bgy = barangaysMap[bgyId] || {
                        total_events: 0,
                        upcoming_events: 0,
                        past_events: 0,
                        expected_attendees: 0,
                        actual_attendees: 0,
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
                    pin.addEventListener('mouseenter', function(e) {
                        let statVal = 0;
                        let metricTxt = 'Total Events';

                        if (currentMetric === 'upcoming_events') {
                            statVal = bgy.upcoming_events;
                            metricTxt = 'Upcoming Events';
                        } else if (currentMetric === 'past_events') {
                            statVal = bgy.past_events;
                            metricTxt = 'Past Events';
                        } else if (currentMetric === 'actual_attendees') {
                            statVal = Number(bgy.actual_attendees).toLocaleString();
                            metricTxt = 'Actual Attendance';
                        } else if (currentMetric === 'expected_attendees') {
                            statVal = Number(bgy.expected_attendees).toLocaleString();
                            metricTxt = 'Expected Attendance';
                        } else {
                            statVal = bgy.total_events;
                        }

                        tipName.textContent = barangayNames[bgyId] || `Brgy. ${bgyId}`;
                        tipStat.textContent = `${metricTxt}: ${statVal}`;
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

                    // Click to Select Barangay (Toggle if already selected)
                    pin.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (selectedBarangayId == bgyId) {
                            selectBarangay('all');
                        } else {
                            selectBarangay(bgyId);
                        }
                    });

                    pinsContainer.appendChild(pin);
                }
            }

            function updateSelectedPinVisual() {
                for (let bgyId = 1; bgyId <= 18; bgyId++) {
                    const pin = document.getElementById(`hotspot-pin-${bgyId}`);
                    if (pin) {
                        if (selectedBarangayId == bgyId) {
                            pin.classList.add('active-selected');
                            pin.style.backgroundColor = '#075998';
                        } else {
                            pin.classList.remove('active-selected');
                            pin.style.backgroundColor = '#64748B';
                        }
                    }
                }
            }

            // --- 5. Render Sidebar Summary & Breakdown Table (User Spec Section 5 & 6) ---
            function renderSidebarSummary(bgyId) {
                if (!activeDataset) return;

                const standardTypes = ['Municipal', 'Barangay', 'Sectoral', 'Political', 'Others'];
                const breakdownTbody = document.getElementById('breakdownTableBody');
                breakdownTbody.innerHTML = '';

                if (bgyId === 'all') {
                    // Consolidated All 18 Barangays Overview
                    document.getElementById('summaryMetaTag').textContent = 'Consolidated Mariveles Overview';
                    document.getElementById('summaryBgyTitle').textContent = 'All 18 Barangays';
                    document.getElementById('cardNumberBadge').textContent = '18';

                    const k = activeDataset.kpis;
                    document.getElementById('summaryTotalEvents').textContent = Number(k.total_events || 0).toLocaleString();
                    document.getElementById('summaryUpcomingEvents').textContent = Number(k.upcoming_events || 0).toLocaleString();
                    document.getElementById('summaryPastEvents').textContent = Number(k.past_events || 0).toLocaleString();
                    document.getElementById('summaryExpectedAttendees').textContent = Number(k.expected_attendees || 0).toLocaleString();
                    document.getElementById('summaryActualAttendees').textContent = Number(k.actual_attendees || 0).toLocaleString();

                    // Variance computation: (Actual - Expected) / Expected
                    const exp = k.expected_attendees || 0;
                    const act = k.actual_attendees || 0;
                    const diff = act - exp;
                    const variancePct = exp > 0 ? ((diff / exp) * 100).toFixed(1) : '0.0';
                    const sign = diff >= 0 ? '+' : '';
                    document.getElementById('summaryVarianceBadge').textContent = `Variance: ${sign}${variancePct}% (${sign}${Number(diff).toLocaleString()})`;

                    // Overall Type Breakdown rows
                    activeDataset.type_breakdown.forEach(item => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${item.type}</td>
                            <td class="num-col">${item.count}</td>
                            <td class="num-col">${Number(item.actual).toLocaleString()}</td>
                        `;
                        breakdownTbody.appendChild(tr);
                    });

                    // TOTAL Row
                    const totalRow = document.createElement('tr');
                    totalRow.className = 'total-row';
                    totalRow.innerHTML = `
                        <td><strong>TOTAL</strong></td>
                        <td class="num-col"><strong>${k.total_events}</strong></td>
                        <td class="num-col"><strong>${Number(k.actual_attendees).toLocaleString()}</strong></td>
                    `;
                    breakdownTbody.appendChild(totalRow);

                    document.getElementById('btnFilterByThisBarangay').textContent = 'View All Municipal Records ↓';
                } else {
                    // Specific Barangay Selected
                    const bgyData = activeDataset.barangays[bgyId] || {
                        total_events: 0,
                        upcoming_events: 0,
                        past_events: 0,
                        expected_attendees: 0,
                        actual_attendees: 0,
                        types: {}
                    };

                    document.getElementById('summaryMetaTag').textContent = `Barangay #${bgyId} Profile`;
                    document.getElementById('summaryBgyTitle').textContent = barangayNames[bgyId] || `Brgy. ${bgyId}`;
                    document.getElementById('cardNumberBadge').textContent = bgyId;

                    document.getElementById('summaryTotalEvents').textContent = Number(bgyData.total_events || 0).toLocaleString();
                    document.getElementById('summaryUpcomingEvents').textContent = Number(bgyData.upcoming_events || 0).toLocaleString();
                    document.getElementById('summaryPastEvents').textContent = Number(bgyData.past_events || 0).toLocaleString();
                    document.getElementById('summaryExpectedAttendees').textContent = Number(bgyData.expected_attendees || 0).toLocaleString();
                    document.getElementById('summaryActualAttendees').textContent = Number(bgyData.actual_attendees || 0).toLocaleString();

                    const exp = bgyData.expected_attendees || 0;
                    const act = bgyData.actual_attendees || 0;
                    const diff = act - exp;
                    const variancePct = exp > 0 ? ((diff / exp) * 100).toFixed(1) : '0.0';
                    const sign = diff >= 0 ? '+' : '';
                    document.getElementById('summaryVarianceBadge').textContent = `Variance: ${sign}${variancePct}% (${sign}${Number(diff).toLocaleString()})`;

                    // Barangay Type Breakdown rows
                    standardTypes.forEach(st => {
                        const typeInfo = (bgyData.types && bgyData.types[st]) ? bgyData.types[st] : { count: 0, actual: 0 };
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${st}</td>
                            <td class="num-col">${typeInfo.count}</td>
                            <td class="num-col">${Number(typeInfo.actual).toLocaleString()}</td>
                        `;
                        breakdownTbody.appendChild(tr);
                    });

                    // TOTAL Row
                    const totalRow = document.createElement('tr');
                    totalRow.className = 'total-row';
                    totalRow.innerHTML = `
                        <td><strong>TOTAL</strong></td>
                        <td class="num-col"><strong>${bgyData.total_events}</strong></td>
                        <td class="num-col"><strong>${Number(bgyData.actual_attendees).toLocaleString()}</strong></td>
                    `;
                    breakdownTbody.appendChild(totalRow);

                    document.getElementById('btnFilterByThisBarangay').textContent = `View All ${barangayNames[bgyId]} Records ↓`;
                }
            }

            // --- 6. Render Upcoming Event Highlights Cards (User Spec Section 3 & 8) ---
            function renderUpcomingCards(upcomingList, bgyName) {
                const grid = document.getElementById('upcomingCardsGrid');
                grid.innerHTML = '';

                if (!upcomingList || upcomingList.length === 0) {
                    const targetName = bgyName ? `for Brgy. ${bgyName}` : 'under active filters';
                    grid.innerHTML = `
                        <div style="grid-column: 1 / -1; padding: 36px 20px; text-align: center; background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px dashed #CBD5E1; color: var(--text-muted);">
                            <div style="font-size: 1.6rem; margin-bottom: 8px;">📅</div>
                            <p style="font-weight: 700; font-size: 0.96rem; color: var(--color-deep-navy); margin-bottom: 6px;">No upcoming events currently scheduled ${targetName}.</p>
                            <span style="font-size: 0.84rem; color: #64748B;">Click "+ Encode Event" to add a new upcoming event for staff planning.</span>
                        </div>
                    `;
                    return;
                }

                upcomingList.forEach(item => {
                    const card = document.createElement('div');
                    card.className = `upcoming-event-card ${item.speech_required ? 'speech-required-card' : ''}`;

                    // Speech badge
                    const speechBadgeHtml = item.speech_required 
                        ? `<span class="badge-speech-required">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z" />
                            </svg>
                            SPEECH REQUIRED
                           </span>`
                        : `<span class="badge-speech-not-required">No Speech</span>`;

                    // Attendance Status badge
                    let statusClass = 'attendance-badge-for-confirmation';
                    if (item.attendance_status === 'Confirmed') statusClass = 'attendance-badge-confirmed';
                    else if (item.attendance_status === 'Tentative') statusClass = 'attendance-badge-tentative';
                    else if (item.attendance_status === 'Declined') statusClass = 'attendance-badge-declined';

                    const statusBadgeHtml = `<span class="attendance-status-pill ${statusClass}">${item.attendance_status}</span>`;

                    // Organizer Request Box
                    const requestBoxHtml = item.request ? `
                        <div class="organizer-request-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="flex-shrink:0; margin-top:1px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            <div><strong>Organizer Request:</strong> ${item.request}</div>
                        </div>
                    ` : '';

                    card.innerHTML = `
                        <div>
                            <div class="event-card-top-meta">
                                <div class="event-datetime-capsule">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span>${item.event_datetime}</span>
                                </div>
                                <div style="display:flex; align-items:center; gap:6px;">
                                    ${statusBadgeHtml}
                                    ${speechBadgeHtml}
                                </div>
                            </div>

                            <div class="event-card-name">${item.name}</div>
                            ${item.theme ? `<div class="event-card-theme">"${item.theme}"</div>` : ''}

                            <div class="event-card-info-list">
                                <div class="info-row">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    <div><strong>Venue:</strong> ${item.venue} (Brgy. ${item.barangay_name})</div>
                                </div>
                                <div class="info-row">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    <div><strong>Invited By:</strong> ${item.who_invited}</div>
                                </div>
                                ${item.contact_info ? `
                                <div class="info-row">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                    </svg>
                                    <div><strong>Contact:</strong> ${item.contact_info}</div>
                                </div>` : ''}
                                <div class="info-row">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>
                                    <div><strong>Expected Attendees:</strong> ${Number(item.expected_attendees).toLocaleString()}</div>
                                </div>
                            </div>

                            ${requestBoxHtml}
                        </div>

                        <div class="event-card-actions">
                            <button type="button" class="btn-mark-past" data-event-id="${item.id}" data-event-name="${item.name}" data-expected="${item.expected_attendees}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <span>Mark as Past & Record Attendance</span>
                            </button>

                            <div style="display:flex; align-items:center; gap:5px;">
                                <button type="button" class="card-icon-btn btn-view-event" data-event-id="${item.id}" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                                <button type="button" class="card-icon-btn btn-edit-event" data-event-id="${item.id}" title="Edit Event">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    `;

                    grid.appendChild(card);
                });

                // Attach card button handlers
                grid.querySelectorAll('.btn-mark-past').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const eventId = this.dataset.eventId;
                        const eventName = this.dataset.eventName;
                        const expected = this.dataset.expected;
                        openMarkPastModal(eventId, eventName, expected);
                    });
                });

                grid.querySelectorAll('.btn-view-event').forEach(btn => {
                    btn.addEventListener('click', function() {
                        openViewModal(this.dataset.eventId);
                    });
                });

                grid.querySelectorAll('.btn-edit-event').forEach(btn => {
                    btn.addEventListener('click', function() {
                        openEditModal(this.dataset.eventId);
                    });
                });
            }

            // --- 6. Render Master DataTables Table ---
            function renderDataTable(records) {
                const countBadge = document.getElementById('recordsCountBadge');
                if (countBadge) {
                    countBadge.textContent = `${(records || []).length} Records`;
                }

                // Destroy existing DataTable instance before updating DOM (Matching Assistance Module)
                if ($.fn.DataTable.isDataTable('#eventsMasterTable')) {
                    $('#eventsMasterTable').DataTable().clear().destroy();
                }

                const tbody = document.getElementById('eventsTableBody');
                tbody.innerHTML = '';

                records.forEach(r => {
                    const tr = document.createElement('tr');

                    // Status Badge
                    const statusBadge = r.status === 'Upcoming' 
                        ? `<span class="status-badge-upcoming">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                            Upcoming
                           </span>`
                        : `<span class="status-badge-past">Past</span>`;

                    // Speech Badge
                    const speechBadge = r.speech_required
                        ? `<span class="badge-speech-required" style="font-size:0.68rem; padding:2px 6px;">🎙️ REQUIRED</span>`
                        : `<span style="color:#94A3B8; font-size:0.75rem;">No</span>`;

                    // Attendance display
                    let attendanceDisplay = '';
                    if (r.status === 'Upcoming') {
                        attendanceDisplay = `<div><strong>${Number(r.expected_attendees).toLocaleString()}</strong> <small style="color:var(--text-muted);">(Expected)</small></div>
                                             <div style="font-size:0.72rem; color:var(--text-muted);">${r.attendance_status}</div>`;
                    } else {
                        const act = r.actual_attendees !== null ? Number(r.actual_attendees).toLocaleString() : 'N/A';
                        attendanceDisplay = `<div><strong>${act}</strong> <small style="color:var(--text-muted);">(Actual)</small></div>
                                             <div style="font-size:0.70rem; color:var(--text-muted);">vs ${Number(r.expected_attendees).toLocaleString()} exp</div>`;
                    }

                    // Actions
                    let markPastBtn = '';
                    if (r.status === 'Upcoming') {
                        markPastBtn = `
                            <button type="button" class="btn-action-sm btn-table-mark-past" data-event-id="${r.id}" data-event-name="${r.name}" data-expected="${r.expected_attendees}" title="Mark as Past & Record Attendance" style="color:#059669;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </button>
                        `;
                    }

                    tr.innerHTML = `
                        <td>${statusBadge}</td>
                        <td>
                            <div style="font-weight:700;">${r.formatted_date}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">${r.formatted_time}</div>
                        </td>
                        <td><span class="table-bgy-pill">${r.barangay_name}</span></td>
                        <td>
                            <div style="font-weight:800; color:var(--color-deep-navy);">${r.name}</div>
                            ${r.theme ? `<div style="font-size:0.75rem; font-style:italic; color:var(--text-muted);">${r.theme}</div>` : ''}
                            <div style="font-size:0.72rem; color:var(--text-muted);">📍 ${r.venue}</div>
                        </td>
                        <td><span style="font-weight:700;">${r.display_type}</span></td>
                        <td>
                            <div>${r.who_invited}</div>
                            ${r.contact_info ? `<div style="font-size:0.72rem; color:var(--text-muted);">${r.contact_info}</div>` : ''}
                        </td>
                        <td>${speechBadge}</td>
                        <td>${attendanceDisplay}</td>
                        <td>
                            <div class="action-icon-group">
                                ${markPastBtn}
                                <button type="button" class="btn-action-sm btn-table-view" data-event-id="${r.id}" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                                <button type="button" class="btn-action-sm btn-table-edit" data-event-id="${r.id}" title="Edit Event">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button type="button" class="btn-action-sm delete-btn btn-table-delete" data-event-id="${r.id}" data-event-name="${r.name}" title="Delete Event">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    `;

                    tbody.appendChild(tr);
                });

                // Initialize DataTable (Matching Assistance Module)
                dataTableInstance = $('#eventsMasterTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    order: [[1, 'desc']], // Order by Date & Time descending
                    columnDefs: [
                        { orderable: false, targets: [8] } // Disable sorting on Action column
                    ],
                    language: {
                        search: "Search Records:",
                        searchPlaceholder: "Search any field in table...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ events",
                        infoEmpty: "Showing 0 to 0 of 0 events",
                        infoFiltered: "(filtered from _MAX_ total events)",
                        paginate: {
                            first: "«",
                            previous: "‹",
                            next: "›",
                            last: "»"
                        }
                    },
                    drawCallback: function() {
                        // Re-attach action buttons on every table draw / page change
                        $('#eventsMasterTable').find('.btn-table-mark-past').off('click').on('click', function() {
                            openMarkPastModal($(this).data('eventId'), $(this).data('eventName'), $(this).data('expected'));
                        });

                        $('#eventsMasterTable').find('.btn-table-view').off('click').on('click', function() {
                            openViewModal($(this).data('eventId'));
                        });

                        $('#eventsMasterTable').find('.btn-table-edit').off('click').on('click', function() {
                            openEditModal($(this).data('eventId'));
                        });

                        $('#eventsMasterTable').find('.btn-table-delete').off('click').on('click', function() {
                            openDeleteModal($(this).data('eventId'), $(this).data('eventName'));
                        });
                    }
                });
            }

            // --- 7. Modals Functionality ---

            // Open Add Event Modal
            btnOpenAddModal.addEventListener('click', function() {
                formEventId.value = '';
                eventForm.reset();
                document.getElementById('modalTitleText').textContent = 'Encode New Event';
                setClassificationChoice('Upcoming');

                // Default datetime to tomorrow at 9:00 AM
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                tomorrow.setHours(9, 0, 0, 0);
                const tzOffset = tomorrow.getTimezoneOffset() * 60000;
                document.getElementById('formDatetime').value = (new Date(tomorrow - tzOffset)).toISOString().slice(0, 16);

                customTypeContainer.style.display = 'none';
                eventModalBackdrop.style.display = 'flex';
            });

            function closeModal(modal) {
                modal.style.display = 'none';
            }

            btnCloseEventModal.addEventListener('click', () => closeModal(eventModalBackdrop));
            btnCancelEventModal.addEventListener('click', () => closeModal(eventModalBackdrop));

            // Classification Choice Toggle in Add/Edit modal
            btnChooseUpcoming.addEventListener('click', function() {
                setClassificationChoice('Upcoming');
            });

            btnChoosePast.addEventListener('click', function() {
                setClassificationChoice('Past');
            });

            function setClassificationChoice(choice) {
                formStatus.value = choice;
                if (choice === 'Upcoming') {
                    btnChooseUpcoming.classList.add('selected');
                    btnChoosePast.classList.remove('selected');
                    actualAttendeesContainer.style.display = 'none';
                    document.getElementById('attendanceStatusContainer').style.display = 'flex';
                } else {
                    btnChoosePast.classList.add('selected');
                    btnChooseUpcoming.classList.remove('selected');
                    actualAttendeesContainer.style.display = 'flex';
                    document.getElementById('attendanceStatusContainer').style.display = 'none';
                }
            }

            formEventType.addEventListener('change', function() {
                if (this.value === 'Others') {
                    customTypeContainer.style.display = 'flex';
                    formCustomType.setAttribute('required', 'required');
                } else {
                    customTypeContainer.style.display = 'none';
                    formCustomType.removeAttribute('required');
                }
            });

            // Submit Add / Edit Form
            eventForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const isEdit = Boolean(formEventId.value);
                const url = isEdit
                    ? `{{ url('admin/events/update') }}/${formEventId.value}`
                    : `{{ route('admin.events.store') }}`;

                const payload = {
                    status: formStatus.value,
                    attendance_status: document.getElementById('formAttendanceStatus').value,
                    barangay_id: document.getElementById('formBarangay').value,
                    name: document.getElementById('formName').value.trim(),
                    event_datetime: document.getElementById('formDatetime').value,
                    venue: document.getElementById('formVenue').value.trim(),
                    event_type: document.getElementById('formEventType').value,
                    custom_type: formEventType.value === 'Others' ? formCustomType.value.trim() : null,
                    who_invited: document.getElementById('formWhoInvited').value.trim(),
                    contact_info: document.getElementById('formContactInfo').value.trim(),
                    details: document.getElementById('formDetails').value.trim(),
                    request: document.getElementById('formRequest').value.trim(),
                    speech_required: document.getElementById('speechYes').checked ? 1 : 0,
                    theme: document.getElementById('formTheme').value.trim(),
                    expected_attendees: document.getElementById('formExpectedAttendees').value,
                    actual_attendees: formStatus.value === 'Past' ? (formActualAttendees.value || document.getElementById('formExpectedAttendees').value) : null,
                };

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        closeModal(eventModalBackdrop);
                        showToast(data.message || 'Event saved successfully!');
                        loadData();
                    } else {
                        alert(data.message || 'Validation failed. Please verify form entries.');
                    }
                })
                .catch(err => {
                    console.error('Error saving event:', err);
                    alert('An error occurred while saving the event record.');
                });
            });

            // Open Edit Modal
            function openEditModal(id) {
                fetch(`{{ url('admin/events/record') }}/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.record) {
                            const r = data.record;
                            formEventId.value = r.id;
                            document.getElementById('modalTitleText').textContent = `Edit Event: ${r.name}`;

                            setClassificationChoice(r.status);
                            document.getElementById('formBarangay').value = r.barangay_id;
                            document.getElementById('formName').value = r.name;
                            document.getElementById('formDatetime').value = r.event_datetime;
                            document.getElementById('formVenue').value = r.venue;
                            document.getElementById('formEventType').value = r.event_type;
                            document.getElementById('formWhoInvited').value = r.who_invited;
                            document.getElementById('formContactInfo').value = r.contact_info || '';
                            document.getElementById('formDetails').value = r.details || '';
                            document.getElementById('formRequest').value = r.request || '';
                            document.getElementById('formTheme').value = r.theme || '';
                            document.getElementById('formExpectedAttendees').value = r.expected_attendees;
                            document.getElementById('formAttendanceStatus').value = r.attendance_status || 'For Confirmation';

                            if (r.speech_required) {
                                document.getElementById('speechYes').checked = true;
                            } else {
                                document.getElementById('speechNo').checked = true;
                            }

                            if (r.event_type === 'Others') {
                                customTypeContainer.style.display = 'flex';
                                formCustomType.value = r.custom_type || '';
                            } else {
                                customTypeContainer.style.display = 'none';
                                formCustomType.value = '';
                            }

                            if (r.status === 'Past') {
                                formActualAttendees.value = r.actual_attendees !== null ? r.actual_attendees : '';
                            }

                            eventModalBackdrop.style.display = 'flex';
                        }
                    });
            }

            // Open Mark as Past Modal
            function openMarkPastModal(id, name, expected) {
                document.getElementById('markPastEventId').value = id;
                document.getElementById('markPastEventName').textContent = name;
                document.getElementById('markPastExpectedCount').textContent = Number(expected || 0).toLocaleString();
                document.getElementById('markPastActualAttendees').value = expected || '';
                document.getElementById('markPastPostNotes').value = '';
                markPastModalBackdrop.style.display = 'flex';
            }

            btnCloseMarkPastModal.addEventListener('click', () => closeModal(markPastModalBackdrop));
            btnCancelMarkPast.addEventListener('click', () => closeModal(markPastModalBackdrop));

            markPastForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const id = document.getElementById('markPastEventId').value;
                const actual = document.getElementById('markPastActualAttendees').value;
                const attendanceStatus = document.getElementById('markPastAttendanceStatus').value;
                const notes = document.getElementById('markPastPostNotes').value;

                fetch(`{{ url('admin/events/mark-past') }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        actual_attendees: actual,
                        attendance_status: attendanceStatus,
                        details: notes
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        closeModal(markPastModalBackdrop);
                        showToast(data.message || 'Event marked as Past successfully!');
                        loadData();
                    } else {
                        alert(data.message || 'Failed to update event.');
                    }
                })
                .catch(err => {
                    console.error('Error updating status:', err);
                });
            });

            // Open View Modal
            function openViewModal(id) {
                fetch(`{{ url('admin/events/record') }}/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.record) {
                            const r = data.record;
                            const isUpcoming = r.status === 'Upcoming';

                            const speechHtml = r.speech_required
                                ? `<span class="badge-speech-pulse" style="font-size:0.78rem; padding:4px 10px;">🎙️ SPEECH REQUIRED</span>`
                                : `<span style="color:#64748B; font-weight:700;">No Speech Required</span>`;

                            const body = document.getElementById('viewModalBody');
                            body.innerHTML = `
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:8px;">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="${isUpcoming ? 'status-badge-upcoming' : 'status-badge-past'}" style="font-size:0.80rem; padding:4px 10px;">${r.status}</span>
                                        <span class="attendance-status-pill attendance-badge-confirmed">${r.attendance_status}</span>
                                    </div>
                                    <div>${speechHtml}</div>
                                </div>

                                <h2 style="font-size:1.35rem; font-weight:800; color:var(--color-deep-navy); margin-bottom:4px;">${r.name}</h2>
                                ${r.theme ? `<div style="font-size:0.86rem; font-style:italic; color:var(--text-muted); margin-bottom:16px;">"${r.theme}"</div>` : '<div style="margin-bottom:14px;"></div>'}

                                <div class="form-grid-2col" style="background:#F8FAFC; border:1px solid #E2E8F0; padding:16px; border-radius:12px; margin-bottom:16px;">
                                    <div>
                                        <div style="font-size:0.72rem; font-weight:800; color:var(--text-muted); text-transform:uppercase;">Scheduled Date & Time</div>
                                        <div style="font-size:0.95rem; font-weight:700; color:var(--color-deep-navy);">${r.formatted_datetime}</div>
                                    </div>
                                    <div>
                                        <div style="font-size:0.72rem; font-weight:800; color:var(--text-muted); text-transform:uppercase;">Barangay & Venue</div>
                                        <div style="font-size:0.95rem; font-weight:700; color:var(--color-deep-navy);">Brgy. ${r.barangay_name} &bull; ${r.venue}</div>
                                    </div>
                                    <div>
                                        <div style="font-size:0.72rem; font-weight:800; color:var(--text-muted); text-transform:uppercase;">Type of Event</div>
                                        <div style="font-size:0.95rem; font-weight:700; color:var(--color-deep-navy);">${r.display_type}</div>
                                    </div>
                                    <div>
                                        <div style="font-size:0.72rem; font-weight:800; color:var(--text-muted); text-transform:uppercase;">Attendance Comparison</div>
                                        <div style="font-size:0.95rem; font-weight:800; color:var(--color-deep-navy);">
                                            ${r.actual_attendees !== null ? Number(r.actual_attendees).toLocaleString() : 'Pending'} <small style="color:var(--text-muted); font-weight:600;">(Actual)</small> / ${Number(r.expected_attendees).toLocaleString()} <small style="color:var(--text-muted); font-weight:600;">(Expected)</small>
                                        </div>
                                    </div>
                                </div>

                                <div style="display:flex; flex-direction:column; gap:12px;">
                                    <div>
                                        <div style="font-size:0.74rem; font-weight:800; color:var(--text-muted); text-transform:uppercase;">Who Invited</div>
                                        <div style="font-weight:700; color:var(--color-deep-navy); font-size:0.90rem;">${r.who_invited}</div>
                                    </div>
                                    ${r.contact_info ? `
                                    <div>
                                        <div style="font-size:0.74rem; font-weight:800; color:var(--text-muted); text-transform:uppercase;">Contact Information</div>
                                        <div style="font-size:0.86rem; color:var(--color-deep-navy);">${r.contact_info}</div>
                                    </div>` : ''}
                                    ${r.request ? `
                                    <div class="organizer-request-box">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                        </svg>
                                        <div><strong>Organizer Request:</strong> ${r.request}</div>
                                    </div>` : ''}
                                    ${r.details ? `
                                    <div>
                                        <div style="font-size:0.74rem; font-weight:800; color:var(--text-muted); text-transform:uppercase;">Event Details & Background</div>
                                        <div style="font-size:0.86rem; line-height:1.45; color:var(--color-deep-navy);">${r.details}</div>
                                    </div>` : ''}
                                </div>
                            `;

                            viewEventModalBackdrop.style.display = 'flex';
                        }
                    });
            }

            btnCloseViewModal.addEventListener('click', () => closeModal(viewEventModalBackdrop));
            btnCloseViewBtn.addEventListener('click', () => closeModal(viewEventModalBackdrop));

            // Open Delete Modal
            function openDeleteModal(id, name) {
                eventToDeleteId = id;
                document.getElementById('deleteEventSummaryText').textContent = `Are you sure you want to delete event "${name}"?`;
                deleteModalBackdrop.style.display = 'flex';
            }

            btnCancelDelete.addEventListener('click', () => closeModal(deleteModalBackdrop));
            btnCancelDeleteModal.addEventListener('click', () => closeModal(deleteModalBackdrop));

            btnConfirmDelete.addEventListener('click', function() {
                if (!eventToDeleteId) return;

                fetch(`{{ url('admin/events/delete') }}/${eventToDeleteId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        closeModal(deleteModalBackdrop);
                        showToast(data.message || 'Event deleted successfully!');
                        loadData();
                    } else {
                        alert(data.message || 'Failed to delete event.');
                    }
                })
                .catch(err => {
                    console.error('Error deleting event:', err);
                });
            });

            // --- 8. Filter Ribbon & Navigation Events ---

            // Status Classification Buttons (All / Upcoming / Past)
            document.querySelectorAll('.status-pill-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.status-pill-btn').forEach(b => {
                        b.classList.remove('active', 'upcoming-active', 'past-active');
                    });
                    this.classList.add('active');

                    const status = this.dataset.status;
                    currentClassification = status;
                    if (status === 'Upcoming') this.classList.add('upcoming-active');
                    if (status === 'Past') this.classList.add('past-active');

                    // Synchronize table tabs as well
                    document.querySelectorAll('.tab-btn').forEach(tb => {
                        tb.classList.toggle('active', tb.dataset.tabStatus === status);
                    });

                    loadData();
                });
            });

            // Table Tabs
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const tabStatus = this.dataset.tabStatus;
                    if (tabStatus === 'speech') {
                        filterSpeech.value = '1';
                        currentClassification = 'all';
                    } else {
                        currentClassification = tabStatus;
                        filterSpeech.value = 'all';
                    }

                    // Sync top pills
                    document.querySelectorAll('.status-pill-btn').forEach(pb => {
                        pb.classList.toggle('active', pb.dataset.status === currentClassification);
                    });

                    loadData();
                });
            });

            // Dropdowns & Date inputs change
            filterBarangay.addEventListener('change', function() {
                selectBarangay(this.value);
            });

            const btnClearUpcomingBgy = document.getElementById('btnClearUpcomingBgy');
            if (btnClearUpcomingBgy) {
                btnClearUpcomingBgy.addEventListener('click', function() {
                    selectBarangay('all');
                });
            }

            const btnClearTableBgy = document.getElementById('btnClearTableBgy');
            if (btnClearTableBgy) {
                btnClearTableBgy.addEventListener('click', function() {
                    selectBarangay('all');
                });
            }

            filterType.addEventListener('change', loadData);
            filterSpeech.addEventListener('change', loadData);
            filterDateFrom.addEventListener('change', loadData);
            filterDateTo.addEventListener('change', loadData);

            let searchTimeout = null;
            filterSearch.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(loadData, 350);
            });

            btnResetFilters.addEventListener('click', function() {
                currentClassification = 'all';
                selectedBarangayId = 'all';
                filterBarangay.value = 'all';
                filterType.value = 'all';
                filterSpeech.value = 'all';
                filterDateFrom.value = '';
                filterDateTo.value = '';
                filterSearch.value = '';

                document.querySelectorAll('.status-pill-btn').forEach(b => {
                    b.classList.remove('active', 'upcoming-active', 'past-active');
                });
                document.getElementById('pillStatusAll').classList.add('active');

                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelector('.tab-btn[data-tab-status="all"]').classList.add('active');

                updateSelectedPinVisual();
                loadData();
            });

            // Map Metric Switcher Buttons
            document.querySelectorAll('.metric-pill-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.metric-pill-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentMetric = this.dataset.metric;
                    activeMetricLabel.textContent = this.textContent.trim();
                    loadData();
                });
            });

            // Scroll to records button in sidebar
            document.getElementById('btnFilterByThisBarangay').addEventListener('click', function() {
                document.getElementById('eventsMasterTable').scrollIntoView({ behavior: 'smooth' });
            });

            // --- 9. Pan & Zoom Canvas Engine (Exact Implementation from Electoral & Assistance) ---
            function updateMapTransform() {
                mapViewport.style.transform = `translate(${panX}px, ${panY}px) scale(${currentScale})`;
            }

            document.getElementById('btnZoomIn').addEventListener('click', function() {
                currentScale = Math.min(currentScale + 0.25, 4.0);
                updateMapTransform();
            });

            document.getElementById('btnZoomOut').addEventListener('click', function() {
                currentScale = Math.max(currentScale - 0.25, 0.75);
                updateMapTransform();
            });

            document.getElementById('btnResetZoom').addEventListener('click', function() {
                currentScale = DEFAULT_SCALE;
                panX = 0;
                panY = 0;
                updateMapTransform();
            });

            mapStage.addEventListener('wheel', function(e) {
                e.preventDefault();
                const delta = e.deltaY < 0 ? 0.15 : -0.15;
                currentScale = Math.min(Math.max(currentScale + delta, 0.75), 4.0);
                updateMapTransform();
            }, { passive: false });

            mapStage.addEventListener('mousedown', function(e) {
                if (e.target.closest('.map-hotspot-pin') || e.target.closest('.map-floating-controls')) return;
                isDragging = true;
                mapStage.classList.add('dragging');
                startX = e.clientX - panX;
                startY = e.clientY - panY;
            });

            window.addEventListener('mousemove', function(e) {
                if (!isDragging) return;
                panX = e.clientX - startX;
                panY = e.clientY - startY;
                updateMapTransform();
            });

            window.addEventListener('mouseup', function() {
                if (isDragging) {
                    isDragging = false;
                    mapStage.classList.remove('dragging');
                }
            });

            // Initial Map Transform & Data Load
            updateMapTransform();
            loadData();
        });
    </script>
@endsection
