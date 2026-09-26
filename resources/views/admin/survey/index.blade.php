@extends('layouts.app')

@section('title', 'Survey Module')
@section('user_name', 'Russel Acosta')
@section('user_role_label', isset($role) && $role === 'assistant' ? 'Political Officer' : 'Administrator')

@section('styles')
    <!-- Google Fonts & DataTables CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        :root {
            --color-primary-blue: #075998;
            --color-deep-navy: #0B192C;
            --color-light-sky: #51B8E5;
            --color-mist-blue: #F0F7FB;
            --card-border: #E2E8F0;
            --text-muted: #64748B;
            --radius-lg: 14px;
            --radius-md: 10px;
            --radius-sm: 6px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px rgba(11, 25, 44, 0.08);
            --transition-fast: 0.18s ease;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: var(--color-deep-navy);
            margin: 0;
            padding: 0;
        }

        /* 1. Header & Controls Ribbon */
        .survey-controls-header {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 20px 24px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        .header-title-bar-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }

        .header-title-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #075998 0%, #0B192C 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(7, 89, 152, 0.25);
            flex-shrink: 0;
        }

        .header-title-left h1 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin: 0;
            letter-spacing: -0.01em;
        }

        .header-title-left p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin: 2px 0 0 0;
            font-weight: 500;
        }

        .header-actions-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-encode-data {
            background: var(--color-primary-blue);
            color: #FFFFFF;
            border: none;
            padding: 10px 18px;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
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
            background: #F1F5F9;
            color: var(--color-deep-navy);
            border: 1px solid #CBD5E1;
            padding: 9px 16px;
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
            background: #E2E8F0;
            border-color: #94A3B8;
        }

        /* Filter Controls Row */
        .filter-controls-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            padding-top: 12px;
            border-top: 1px solid #F1F5F9;
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
            box-sizing: border-box;
        }

        .filter-select:focus,
        .filter-input:focus {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
        }

        .btn-reset-filters {
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-size: 0.80rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            transition: all var(--transition-fast);
            align-self: flex-end;
            margin-bottom: 2px;
        }

        .btn-reset-filters:hover {
            color: #DC2626;
            background: #FEF2F2;
        }

        /* Interpretation Note Banner (Spec Section 10) */
        .interpretation-notice-banner {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-left: 4px solid #F59E0B;
            border-radius: var(--radius-md);
            padding: 12px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            box-shadow: 0 1px 3px rgba(245, 158, 11, 0.08);
        }

        .notice-icon {
            color: #D97706;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .notice-content {
            font-size: 0.82rem;
            color: #92400E;
            line-height: 1.45;
        }

        .notice-content strong {
            color: #78350F;
            font-weight: 800;
        }

        /* Headline Candidate Results Cards (Spec Section 5) */
        .headline-section-wrapper {
            margin-bottom: 20px;
        }

        .headline-section-title {
            font-size: 0.82rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--color-deep-navy);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .headline-candidates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 14px;
        }

        .candidate-headline-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            transition: all var(--transition-fast);
        }

        .candidate-headline-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .candidate-card-top-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .candidate-info-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .candidate-color-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 0.75rem;
            font-weight: 800;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            flex-shrink: 0;
        }

        .candidate-name-box {
            flex: 1;
            min-width: 0;
        }

        .candidate-name-display {
            font-size: 0.96rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .candidate-rating-badge {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            letter-spacing: -0.02em;
        }

        .candidate-substats {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.74rem;
            color: var(--text-muted);
            border-top: 1px solid #F1F5F9;
            padding-top: 8px;
            margin-top: 4px;
        }

        .bgy-lead-pill {
            background: #F1F5F9;
            color: var(--color-deep-navy);
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 10px;
        }

        /* 2. Municipal KPI Summary Cards (Matching Issues, Events & Assistance) */
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

        .kpi-card.card-leader::before { background: linear-gradient(90deg, #075998, #51B8E5); }
        .kpi-card.card-sample::before { background: linear-gradient(90deg, #10B981, #34D399); }
        .kpi-card.card-margin::before { background: linear-gradient(90deg, #6366F1, #818CF8); }
        .kpi-card.card-stronghold::before { background: linear-gradient(90deg, #F59E0B, #FBBF24); }
        .kpi-card.card-period::before { background: linear-gradient(90deg, #475569, #94A3B8); }

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

        .card-leader .kpi-icon-badge { background: #E0F2FE; color: #075998; }
        .card-sample .kpi-icon-badge { background: #D1FAE5; color: #059669; }
        .card-margin .kpi-icon-badge { background: #EEF2FF; color: #4F46E5; }
        .card-stronghold .kpi-icon-badge { background: #FEF3C7; color: #D97706; }
        .card-period .kpi-icon-badge { background: #F1F5F9; color: #475569; }

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

        /* 3. Map Legend & Metric Switcher Bar (Exact Match to Issues) */
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
            gap: 12px;
            flex-wrap: wrap;
        }

        .legend-swatch-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--color-deep-navy);
            background: #F8FAFC;
            padding: 4px 10px;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
        }

        .legend-color-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Main Dashboard Grid: Map (1fr) + Right Sidebar (380px) */
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

        /* Map Card Container */
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
            padding: 2px 6px;
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

        #mapHoverTooltip {
            position: absolute;
            pointer-events: none;
            background: rgba(16, 42, 78, 0.95);
            backdrop-filter: blur(8px);
            color: #FFFFFF;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            font-size: 0.78rem;
            border: 1px solid rgba(255, 255, 255, 0.22);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.45);
            z-index: 100;
            display: none;
            transform: translate(-50%, -120%);
            white-space: nowrap;
        }

        #mapHoverTooltip .tip-name {
            font-weight: 800;
            font-size: 0.88rem;
            margin-bottom: 4px;
            color: #51B8E5;
        }

        #mapHoverTooltip .tip-leader {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 2px;
            font-weight: 700;
        }

        #mapHoverTooltip .tip-stat {
            font-size: 0.74rem;
            color: #CBD5E1;
        }

        /* Floating Map Controls */
        .map-floating-controls {
            position: absolute;
            top: 16px;
            right: 16px;
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

        /* Right Detail Sidebar Card */
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
            box-sizing: border-box;
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

        /* Winner Banner Box */
        .winner-banner-box {
            background: #F0F9FF;
            border: 1px solid #BAE6FD;
            border-radius: 8px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .winner-banner-text {
            font-size: 0.70rem;
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
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .banner-stat-badge {
            font-size: 0.74rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.75);
            padding: 3px 8px;
            border-radius: 10px;
            color: #0369A1;
        }

        /* Stats Grid 2-Column */
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

        /* Candidate Breakdown Section */
        .candidate-breakdown-title {
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--color-deep-navy);
            margin-bottom: 8px;
        }

        .candidate-progress-item {
            margin-bottom: 12px;
        }

        .candidate-progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .candidate-progress-name {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--color-deep-navy);
        }

        .candidate-progress-rating {
            font-weight: 800;
            color: var(--color-deep-navy);
        }

        .custom-progress-bar-bg {
            background: #EEF2F6;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .custom-progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.4s ease;
        }

        .methodology-sidebar-box {
            background: #F8FAFC;
            border-left: 3px solid #CBD5E1;
            padding: 10px 12px;
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
            font-size: 0.75rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .methodology-sidebar-box strong {
            color: var(--color-deep-navy);
            display: block;
            margin-bottom: 2px;
        }

        /* Historical Records Comparison (Spec Section 7 & 2) */
        .historical-comparison-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 20px 24px;
            margin-bottom: 24px;
            width: 100%;
            box-sizing: border-box;
        }

        .historical-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .historical-section-header h3 {
            font-size: 1.05rem;
            font-weight: 800;
            margin: 0;
            color: var(--color-deep-navy);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .historical-section-header p {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin: 2px 0 0 0;
        }

        .historical-waves-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .historical-wave-card {
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: var(--radius-md);
            padding: 14px 16px;
            cursor: pointer;
            transition: all var(--transition-fast);
            position: relative;
        }

        .historical-wave-card:hover {
            border-color: #94A3B8;
            background: #FFFFFF;
            box-shadow: var(--shadow-sm);
        }

        .historical-wave-card.active-wave {
            border-color: var(--color-primary-blue);
            background: #EFF6FF;
            box-shadow: 0 4px 12px rgba(7, 89, 152, 0.12);
        }

        .wave-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .wave-badge {
            font-size: 0.70rem;
            font-weight: 800;
            text-transform: uppercase;
            padding: 2px 6px;
            border-radius: 4px;
            background: #E2E8F0;
            color: #475569;
        }

        .historical-wave-card.active-wave .wave-badge {
            background: #BFDBFE;
            color: #1E40AF;
        }

        .wave-title {
            font-size: 0.90rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin-bottom: 6px;
        }

        .wave-candidate-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 4px 0;
            border-top: 1px solid #EEF2F6;
        }

        /* 5. Searchable Survey Records DataTable */
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

        /* Custom DataTables Footer & Pagination */
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

        .table-candidate-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
            font-size: 0.84rem;
        }

        .btn-table-action {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            border: 1px solid #E2E8F0;
            background: #FFFFFF;
            color: var(--color-deep-navy);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .btn-table-action:hover {
            background: #F1F5F9;
            border-color: #CBD5E1;
            color: var(--color-primary-blue);
        }

        .btn-table-action.delete-action:hover {
            background: #FEF2F2;
            border-color: #FCA5A5;
            color: #DC2626;
        }

        /* Modals */
        .modal-backdrop-custom {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(11, 25, 44, 0.65);
            backdrop-filter: blur(4px);
            z-index: 999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-box-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 680px;
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
            box-sizing: border-box;
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

        /* Color Picker Palette */
        .color-palette-container {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 6px;
        }

        .color-choice-pill {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid #FFFFFF;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
            transition: transform var(--transition-fast);
        }

        .color-choice-pill:hover {
            transform: scale(1.15);
        }

        .color-choice-pill.selected {
            transform: scale(1.2);
            border-color: #0B192C;
            box-shadow: 0 0 0 2px #075998;
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
            z-index: 1000;
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

        /* ==========================================================================
           RESPONSIVE MOBILE STYLES (Identical to Events, Directory & Issues Modules)
           ========================================================================== */
        @media (max-width: 992px) {
            .survey-controls-header {
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

            .filter-item .filter-select,
            .filter-item .filter-input {
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

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 4px 8px !important;
                font-size: 0.76rem !important;
                margin: 1px !important;
            }
        }

        @media (max-width: 640px) {
            .survey-controls-header {
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
    <div class="survey-controls-header">
        <div class="header-title-bar-row">
            <div class="header-title-left">
                <div class="header-icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
                <div>
                    <h1>Survey Module</h1>
                    <p>Historical Survey Measurement &amp; Barangay Preference Tracking &bull; Municipality of Mariveles</p>
                </div>
            </div>

            <div class="header-actions-right">
                <button type="button" class="btn-secondary-action" id="btnOpenNewPeriodModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>New Survey Period</span>
                </button>

                <button type="button" class="btn-encode-data" id="btnOpenEncodeModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Encode Survey Result</span>
                </button>
            </div>
        </div>

        <!-- Filter Controls Row (Spec Section 8: Recommended Filters) -->
        <div class="filter-controls-row">
            <div class="filter-left-group">
                <!-- Period / Date Coverage Selector -->
                <div class="filter-item" style="min-width: 220px;">
                    <label for="filterPeriodSelect">Survey Date Coverage</label>
                    <select id="filterPeriodSelect" class="filter-select">
                        @foreach ($periods as $p)
                            <option value="{{ $p->id }}" {{ $loop->first ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->start_date->format('M d') }} - {{ $p->end_date->format('M d, Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Barangay Filter -->
                <div class="filter-item" style="min-width: 170px;">
                    <label for="filterBarangaySelect">Barangay</label>
                    <select id="filterBarangaySelect" class="filter-select">
                        <option value="all">All Barangays (18)</option>
                        @foreach ($barangays as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Candidate Filter -->
                <div class="filter-item" style="min-width: 170px;">
                    <label for="filterCandidateSelect">Candidate</label>
                    <select id="filterCandidateSelect" class="filter-select">
                        <option value="all">All Candidates</option>
                        @foreach ($candidates as $c)
                            <option value="{{ $c->candidate_name }}">{{ $c->candidate_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Live Keyword Search -->
                <div class="filter-item" style="flex: 1; min-width: 180px;">
                    <label for="filterSearchInput">Search Keywords</label>
                    <input type="text" id="filterSearchInput" class="filter-input"
                        placeholder="Filter candidate, methodology, notes...">
                </div>
            </div>

            <button type="button" class="btn-reset-filters" id="btnResetFilters">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Reset Filters</span>
            </button>
        </div>
    </div>

    <!-- 2. Municipal Level Summary KPI Cards (Exact Match to Issues & Assistance) -->
    <div class="kpi-cards-grid">
        <!-- Card 1: Municipal Leader -->
        <div class="kpi-card card-leader">
            <div class="kpi-header">
                <span class="kpi-title">Municipal Leader</span>
                <div class="kpi-icon-badge" id="kpiLeaderBadge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.004 0V5.625c0-.621-.504-1.125-1.125-1.125h-3.75c-.621 0-1.125.504-1.125 1.125v8.75" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiLeaderRating">0.0%</div>
            <div class="kpi-subtext" id="kpiLeaderName">Loading leader...</div>
        </div>

        <!-- Card 2: Total Sample Size -->
        <div class="kpi-card card-sample">
            <div class="kpi-header">
                <span class="kpi-title">Total Sample Size</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiSampleSize">0</div>
            <div class="kpi-subtext">Surveyed respondents</div>
        </div>

        <!-- Card 3: Dominant Barangays -->
        <div class="kpi-card card-margin">
            <div class="kpi-header">
                <span class="kpi-title">Barangays Led</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiBarangaysLed">0 / 18</div>
            <div class="kpi-subtext" id="kpiMarginSub">Lead Margin: +0.0%</div>
        </div>

        <!-- Card 4: Peak Stronghold -->
        <div class="kpi-card card-stronghold">
            <div class="kpi-header">
                <span class="kpi-title">Peak Stronghold</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiStrongholdVal" style="font-size: 1.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">—</div>
            <div class="kpi-subtext">Highest individual barangay rating</div>
        </div>

        <!-- Card 5: Date Coverage -->
        <div class="kpi-card card-period">
            <div class="kpi-header">
                <span class="kpi-title">Survey Period</span>
                <div class="kpi-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" id="kpiDateCoverage" style="font-size: 1.20rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">—</div>
            <div class="kpi-subtext" id="kpiMethodologySub">Standard sampling</div>
        </div>
    </div>

    <!-- 3. Map Legend & Metric Switcher Ribbon (Exact Match to Issues) -->
    <div class="map-legend-bar-container">
        <div class="legend-title-group">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" style="color:var(--color-primary-blue);">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
            </svg>
            <span>Interactive Map: (<span id="mapPeriodLabel" style="color:var(--color-primary-blue); font-weight:800;">Selected Survey Period</span>)</span>
        </div>
        <div class="legend-items-container" id="mapLegendContainer">
            <!-- Dynamically populated candidate swatches/pills with click-to-filter -->
        </div>
    </div>

    <!-- 4. Main Visual Grid: Map Canvas Stage (1fr) + Right Barangay Detail Sidebar (380px) -->
    <div class="electoral-dashboard-grid">
        <!-- Left: Interactive Map Canvas Stage (Spec Section 4: Barangay Map Visualization) -->
        <div class="map-card-container">
            <div class="interactive-map-stage" id="mapStage">
                <!-- Floating Map Controls -->
                <div class="map-floating-controls">
                    <button type="button" class="map-btn-icon" id="btnZoomIn" title="Zoom In">+</button>
                    <button type="button" class="map-btn-icon" id="btnZoomOut" title="Zoom Out">&minus;</button>
                    <button type="button" class="map-btn-icon" id="btnResetZoom" title="Reset View"
                        style="font-size: 0.95rem;">&#x21bb;</button>
                </div>

                <!-- Map Hover Tooltip -->
                <div id="mapHoverTooltip">
                    <div class="tip-name" id="tipBarangayName">Barangay Name</div>
                    <div class="tip-leader" id="tipLeaderBox">
                        <span id="tipLeaderDot" style="width: 8px; height: 8px; border-radius: 50%; display: inline-block;"></span>
                        <span id="tipLeaderName">Leading Candidate</span>
                        <span id="tipLeaderRating" style="font-weight: 800; color: #51B8E5;">(0.0%)</span>
                    </div>
                    <div class="tip-stat" id="tipMargin">Margin: +0.0%</div>
                    <div class="tip-stat" id="tipSample">Sample: 100 respondents</div>
                </div>

                <div class="map-viewport-wrapper" id="mapViewport">
                    <img src="{{ asset('images/mariveles-map.png') }}" alt="Mariveles Bataan Map" class="client-map-img"
                        id="mapImage">
                    <!-- 18 Dynamic Slanted Pins -->
                    <div id="mapPinsContainer"></div>
                </div>
            </div>
        </div>

        <!-- Right: Barangay Detail Sidebar (Spec Section 6: Barangay Detail View) -->
        <div class="sidebar-detail-card" id="barangayDetailSidebar">
            <div class="sidebar-header-badge">
                <div>
                    <div class="detail-meta">Selected Barangay Detail</div>
                    <div class="detail-title" id="sidebarBarangayTitle">Alion</div>
                </div>
                <div class="bgy-number-badge" id="sidebarBarangayNumber">1</div>
            </div>

            <!-- Winning Candidate Banner -->
            <div class="winner-banner-box" id="sidebarWinnerBanner">
                <div>
                    <div class="winner-banner-text">Leading Candidate</div>
                    <div class="winner-name-display">
                        <span id="sidebarWinnerDot" style="width: 10px; height: 10px; border-radius: 50%; display: inline-block; background: #075998;"></span>
                        <span id="sidebarWinnerName">Acosta, Russel</span>
                    </div>
                </div>
                <div class="banner-stat-badge" id="sidebarWinnerRating">48.0%</div>
            </div>

            <!-- 2-Column Stats -->
            <div class="stats-grid-2col">
                <div class="stat-box">
                    <div class="stat-box-label">Sample Size</div>
                    <div class="stat-box-val" id="sidebarSampleSize">100</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-label">Lead Margin</div>
                    <div class="stat-box-val" id="sidebarMargin">+14.0%</div>
                </div>
            </div>

            <!-- Candidate Rating Breakdown Progress Bars -->
            <div>
                <div class="candidate-breakdown-title">Candidate Survey Ratings</div>
                <div id="sidebarCandidateListContainer">
                    <!-- Dynamically filled with candidate progress bars -->
                </div>
            </div>

            <!-- Survey Methodology & Notes -->
            <div class="methodology-sidebar-box">
                <strong>Survey Methodology &amp; Notes:</strong>
                <span id="sidebarMethodologyNotes">Multi-stage cluster random sampling with face-to-face questionnaire.</span>
            </div>

            <!-- Quick Action Button to Add/Update this Barangay -->
            <button type="button" class="btn-secondary-action" id="btnQuickEncodeBarangay" style="width: 100%; justify-content: center; margin-top: 4px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>+ Add / Update Result for this Barangay</span>
            </button>
        </div>
    </div>

    <!-- 5. Headline Candidate Results Ribbon (Spec Section 5) -->
    <div class="headline-section-wrapper">
        <div class="headline-section-title">
            <span>Headline Results &bull; Municipal Aggregate (<span id="headlinePeriodLabel">Selected Survey Period</span>)</span>
            <span id="headlineSampleTotal" style="font-size: 0.74rem; font-weight: 700; color: var(--text-muted);">Sample Size: 0</span>
        </div>
        <div class="headline-candidates-grid" id="headlineCandidatesContainer">
            <!-- Dynamically populated via AJAX -->
        </div>
    </div>

    <!-- 6. Historical Records Comparison Section (Spec Section 7 & 2) -->
    <div class="historical-comparison-card">
        <div class="historical-section-header">
            <div>
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" style="color: var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Historical Survey Records &amp; Trajectory Comparison</span>
                </h3>
                <p>Review and switch among saved survey waves to track candidate ratings over time (June 5-7 &rarr; July 10-12 &rarr; August 15-17)</p>
            </div>
        </div>

        <div class="historical-waves-grid" id="historicalWavesContainer">
            <!-- Dynamically populated waves from historical_trend -->
        </div>
    </div>

    <!-- 8. Searchable Survey Records DataTable -->
    <div class="table-card-container">
        <div class="section-header-row">
            <div class="section-header-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" style="color: var(--color-primary-blue);">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
                <span>Recorded Survey Results Table (<span id="tablePeriodLabel">Current Period</span>)</span>
            </div>
        </div>

        <div class="table-responsive">
            <table id="surveyDataTable" class="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Survey Period</th>
                        <th>Barangay</th>
                        <th>Candidate</th>
                        <th>Rating (%)</th>
                        <th>Sample Size</th>
                        <th>Methodology / Notes</th>
                        <th>Recorded</th>
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

    <!-- Modal 1: Encode Survey Result (Spec Section 1 & 9) -->
    <div class="modal-backdrop-custom" id="modalEncodeResult">
        <div class="modal-box-card">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor" style="color: var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Encode Survey Result</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseEncodeModal">&times;</button>
            </div>
            <form id="formEncodeResult">
                @csrf
                <div class="modal-body-custom">
                    <div class="form-grid-2col">
                        <!-- Survey Period -->
                        <div class="form-group-custom">
                            <label for="modalSurveyPeriodId">Date Coverage / Period <span class="req">*</span></label>
                            <select id="modalSurveyPeriodId" name="survey_period_id" class="form-control-custom" required>
                                @foreach ($periods as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->name }} ({{ $p->start_date->format('M d') }} - {{ $p->end_date->format('M d, Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Barangay -->
                        <div class="form-group-custom">
                            <label for="modalBarangayId">Barangay <span class="req">*</span></label>
                            <select id="modalBarangayId" name="barangay_id" class="form-control-custom" required>
                                <option value="all_barangays">-- Apply to All 18 Barangays --</option>
                                @foreach ($barangays as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Candidate Name (With Autocomplete Datalist) -->
                        <div class="form-group-custom">
                            <label for="modalCandidateName">Candidate Name <span class="req">*</span></label>
                            <input type="text" id="modalCandidateName" name="candidate_name" list="candidateListDatalist"
                                class="form-control-custom" placeholder="e.g. Acosta, Russel" required>
                            <datalist id="candidateListDatalist">
                                @foreach ($candidates as $c)
                                    <option value="{{ $c->candidate_name }}">
                                @endforeach
                            </datalist>
                        </div>

                        <!-- Rating / Percentage Result -->
                        <div class="form-group-custom">
                            <label for="modalRating">Rating / Survey Result (%) <span class="req">*</span></label>
                            <input type="number" step="0.1" min="0" max="100" id="modalRating" name="rating"
                                class="form-control-custom" placeholder="e.g. 45.5" required>
                        </div>

                        <!-- Candidate Display Color -->
                        <div class="form-group-custom form-col-span-2">
                            <label for="modalCandidateColor">Candidate Display Color (Map, Legend, &amp; Charts) <span class="req">*</span></label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="color" id="modalCandidateColorPicker" value="#075998"
                                    style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid #CBD5E1; cursor: pointer; padding: 2px;">
                                <input type="text" id="modalCandidateColorHex" name="candidate_color" value="#075998"
                                    class="form-control-custom" style="width: 130px;" required>
                                <div class="color-palette-container">
                                    @foreach ($defaultColors as $col)
                                        <div class="color-choice-pill" data-color="{{ $col }}"
                                            style="background: {{ $col }};" title="Select {{ $col }}"></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Sample Size -->
                        <div class="form-group-custom">
                            <label for="modalSampleSize">Sample Size (Respondents)</label>
                            <input type="number" id="modalSampleSize" name="sample_size" class="form-control-custom"
                                placeholder="e.g. 100" value="100">
                        </div>

                        <!-- Methodology -->
                        <div class="form-group-custom">
                            <label for="modalMethodology">Methodology</label>
                            <input type="text" id="modalMethodology" name="methodology" class="form-control-custom"
                                placeholder="e.g. Multi-stage cluster sampling">
                        </div>

                        <!-- Notes -->
                        <div class="form-group-custom form-col-span-2">
                            <label for="modalNotes">Methodology / Notes</label>
                            <textarea id="modalNotes" name="notes" class="form-control-custom"
                                placeholder="Optional respondent demographic notes, surveyor observations..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelEncodeModal">Cancel</button>
                    <button type="submit" class="btn-modal-submit" id="btnSubmitEncode">Save Survey Result</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Create New Survey Period (Date Coverage) -->
    <div class="modal-backdrop-custom" id="modalNewPeriod">
        <div class="modal-box-card">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" style="color: var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span>Create New Survey Period</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseNewPeriodModal">&times;</button>
            </div>
            <form id="formNewPeriod">
                @csrf
                <div class="modal-body-custom">
                    <div class="form-grid-2col">
                        <div class="form-group-custom form-col-span-2">
                            <label for="periodName">Period Name / Label <span class="req">*</span></label>
                            <input type="text" id="periodName" name="name" class="form-control-custom"
                                placeholder="e.g. September 10-12, 2026 - Midterm Wave Survey" required>
                        </div>

                        <div class="form-group-custom">
                            <label for="periodStartDate">Start Date <span class="req">*</span></label>
                            <input type="date" id="periodStartDate" name="start_date" class="form-control-custom" required>
                        </div>

                        <div class="form-group-custom">
                            <label for="periodEndDate">End Date <span class="req">*</span></label>
                            <input type="date" id="periodEndDate" name="end_date" class="form-control-custom" required>
                        </div>

                        <div class="form-group-custom">
                            <label for="periodSampleSize">Total Targeted Sample Size</label>
                            <input type="number" id="periodSampleSize" name="sample_size" class="form-control-custom"
                                placeholder="e.g. 1800">
                        </div>

                        <div class="form-group-custom">
                            <label for="periodMethodology">Survey Methodology</label>
                            <input type="text" id="periodMethodology" name="methodology" class="form-control-custom"
                                placeholder="e.g. Stratified random sampling across 18 barangays">
                        </div>

                        <div class="form-group-custom form-col-span-2">
                            <label for="periodNotes">General Notes &amp; Scope</label>
                            <textarea id="periodNotes" name="notes" class="form-control-custom"
                                placeholder="Optional description of surveyor team, margins of error..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelNewPeriodModal">Cancel</button>
                    <button type="submit" class="btn-modal-submit" id="btnSubmitNewPeriod">Create Survey Period</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 3: View Record Details -->
    <div class="modal-backdrop-custom" id="modalViewRecord">
        <div class="modal-box-card">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" style="color: var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span>Survey Record Details</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseViewModal">&times;</button>
            </div>
            <div class="modal-body-custom" id="viewRecordBody">
                <!-- Dynamically populated -->
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnDismissViewModal">Close</button>
            </div>
        </div>
    </div>

    <!-- Modal 4: Edit Record Modal -->
    <div class="modal-backdrop-custom" id="modalEditRecord">
        <div class="modal-box-card">
            <div class="modal-header-custom">
                <h3 class="modal-title-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" style="color: var(--color-primary-blue);">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    <span>Edit Survey Record</span>
                </h3>
                <button type="button" class="modal-close-btn" id="btnCloseEditModal">&times;</button>
            </div>
            <form id="formEditRecord">
                @csrf
                <input type="hidden" id="editRecordId" name="id">
                <div class="modal-body-custom">
                    <div class="form-grid-2col">
                        <div class="form-group-custom">
                            <label for="editPeriodId">Survey Period <span class="req">*</span></label>
                            <select id="editPeriodId" name="survey_period_id" class="form-control-custom" required>
                                @foreach ($periods as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group-custom">
                            <label for="editBarangayId">Barangay <span class="req">*</span></label>
                            <select id="editBarangayId" name="barangay_id" class="form-control-custom" required>
                                @foreach ($barangays as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group-custom">
                            <label for="editCandidateName">Candidate Name <span class="req">*</span></label>
                            <input type="text" id="editCandidateName" name="candidate_name" class="form-control-custom" required>
                        </div>

                        <div class="form-group-custom">
                            <label for="editRating">Rating / Result (%) <span class="req">*</span></label>
                            <input type="number" step="0.1" min="0" max="100" id="editRating" name="rating"
                                class="form-control-custom" required>
                        </div>

                        <div class="form-group-custom form-col-span-2">
                            <label for="editCandidateColorHex">Candidate Display Color <span class="req">*</span></label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="color" id="editCandidateColorPicker" value="#075998"
                                    style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid #CBD5E1; cursor: pointer; padding: 2px;">
                                <input type="text" id="editCandidateColorHex" name="candidate_color" value="#075998"
                                    class="form-control-custom" style="width: 130px;" required>
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label for="editSampleSize">Sample Size</label>
                            <input type="number" id="editSampleSize" name="sample_size" class="form-control-custom">
                        </div>

                        <div class="form-group-custom">
                            <label for="editMethodology">Methodology</label>
                            <input type="text" id="editMethodology" name="methodology" class="form-control-custom">
                        </div>

                        <div class="form-group-custom form-col-span-2">
                            <label for="editNotes">Notes</label>
                            <textarea id="editNotes" name="notes" class="form-control-custom"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelEditModal">Cancel</button>
                    <button type="submit" class="btn-modal-submit" id="btnSubmitEdit">Update Result</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 5: Delete Confirmation Modal -->
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
                    Are you sure you want to delete this survey record? This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnCancelDeleteModal">Cancel</button>
                <button type="button" class="btn-modal-submit" id="btnConfirmDelete" style="background: #DC2626;">Delete Result</button>
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
                data: "{{ route(isset($role) && $role === 'assistant' ? 'assistant.survey.data' : 'admin.survey.data') }}",
                store: "{{ route(isset($role) && $role === 'assistant' ? 'assistant.survey.store' : 'admin.survey.store') }}",
                storePeriod: "{{ route(isset($role) && $role === 'assistant' ? 'assistant.survey.period.store' : 'admin.survey.period.store') }}",
                show: "{{ url(isset($role) && $role === 'assistant' ? 'assistant/survey/record' : 'admin/survey/record') }}",
                update: "{{ url(isset($role) && $role === 'assistant' ? 'assistant/survey/update' : 'admin/survey/update') }}",
                delete: "{{ url(isset($role) && $role === 'assistant' ? 'assistant/survey/delete' : 'admin/survey/delete') }}",
                export: "{{ route(isset($role) && $role === 'assistant' ? 'assistant.survey.export' : 'admin.survey.export') }}"
            };

            // 18 Mariveles Barangay Hotspot Coordinates (Identical to Electoral & Assistance Modules)
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
            let currentPeriodId = $('#filterPeriodSelect').val() || 'latest';
            let selectedBarangayId = 1;
            let currentBarangaysData = {};
            let currentHeadlineResults = [];
            let deleteTargetId = null;

            // DOM Elements
            const mapStage = document.getElementById('mapStage');
            const mapViewport = document.getElementById('mapViewport');
            const pinsContainer = document.getElementById('mapPinsContainer');
            const tooltip = document.getElementById('mapHoverTooltip');
            const tipName = document.getElementById('tipBarangayName');
            const tipLeaderBox = document.getElementById('tipLeaderBox');
            const tipLeaderDot = document.getElementById('tipLeaderDot');
            const tipLeaderName = document.getElementById('tipLeaderName');
            const tipLeaderRating = document.getElementById('tipLeaderRating');
            const tipMargin = document.getElementById('tipMargin');
            const tipSample = document.getElementById('tipSample');

            // --- Initialize Custom DataTable ---
            const surveyTable = $('#surveyDataTable').DataTable({
                responsive: false,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [[0, 'desc']],
                language: {
                    search: "Search Records:",
                    lengthMenu: "Show _MENU_ results",
                    info: "Showing _START_ to _END_ of _TOTAL_ survey entries",
                    infoEmpty: "No survey entries recorded",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    zeroRecords: "No matching survey records found",
                    paginate: {
                        first: "«",
                        previous: "‹",
                        next: "›",
                        last: "»"
                    }
                },
                columns: [
                    { data: 'id', width: '50px' },
                    { data: 'period_name' },
                    {
                        data: 'barangay_name',
                        render: function(data) {
                            return `<span class="table-bgy-pill">${data}</span>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `<div class="table-candidate-badge">
                                <span style="width:10px; height:10px; border-radius:50%; background:${row.candidate_color}; display:inline-block;"></span>
                                <span>${row.candidate_name}</span>
                            </div>`;
                        }
                    },
                    {
                        data: 'rating',
                        render: function(data, type, row) {
                            return `<div style="display:flex; align-items:center; gap:8px;">
                                <strong style="width: 44px;">${data}%</strong>
                                <div style="background:#E2E8F0; width:60px; height:6px; border-radius:3px; overflow:hidden;">
                                    <div style="background:${row.candidate_color}; width:${Math.min(data, 100)}%; height:100%;"></div>
                                </div>
                            </div>`;
                        }
                    },
                    { data: 'sample_size' },
                    {
                        data: 'methodology',
                        render: function(data, type, row) {
                            return `<div style="font-size:0.78rem; line-height:1.3;">
                                <div>${data}</div>
                                <div style="color:#64748B; font-size:0.72rem;">${row.notes}</div>
                            </div>`;
                        }
                    },
                    { data: 'created_at' },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(id) {
                            return `<div style="display:flex; align-items:center; justify-content:center; gap:6px;">
                                <button type="button" class="btn-table-action view-btn" data-id="${id}" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                                <button type="button" class="btn-table-action edit-btn" data-id="${id}" title="Edit Record">
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
            function loadSurveyData() {
                const periodId = $('#filterPeriodSelect').val();
                const barangayId = $('#filterBarangaySelect').val();
                const candidate = $('#filterCandidateSelect').val();
                const search = $('#filterSearchInput').val();

                if (barangayId && barangayId !== 'all') {
                    selectedBarangayId = parseInt(barangayId);
                }

                $.ajax({
                    url: routes.data,
                    method: 'GET',
                    data: {
                        period_id: periodId,
                        barangay_id: barangayId,
                        candidate: candidate,
                        search: search
                    },
                    success: function(res) {
                        if (!res.success) return;

                        currentPeriodId = res.active_period.id;
                        currentBarangaysData = res.barangays;
                        currentHeadlineResults = res.headline_results;

                        // 1. Dynamic Section Headers based on filters
                        const dateCoverageText = `${res.kpis.date_range}`;
                        const bgyLabel = (barangayId && barangayId !== 'all') 
                            ? (barangayNames[barangayId] || `Brgy. ${barangayId}`) 
                            : 'Municipal Aggregate';
                        const candidateLabel = (candidate && candidate !== 'all') 
                            ? ` &bull; Candidate: ${candidate}` 
                            : '';

                        $('#headlinePeriodLabel').html(`${bgyLabel} (${res.active_period.name})${candidateLabel}`);
                        $('#headlineSampleTotal').text(`Sample Size: ${Number(res.kpis.total_sample_size).toLocaleString()} respondents`);
                        $('#mapPeriodLabel').html(`${res.active_period.name} (${dateCoverageText})`);
                        $('#tablePeriodLabel').html(`${res.active_period.name} &bull; ${bgyLabel}${candidateLabel}`);
                        // 2. Render Headline Candidate Results (Spec Section 5)
                        renderHeadlineResults(res.headline_results, candidate);

                        // 3. Render Top 5 KPI Cards
                        renderKpis(res.kpis);

                        // 4. Render Map Legend Swatches (Spec Section 3)
                        renderMapLegend(res.headline_results, candidate);

                        // 5. Render Map Hotspot Pins (Spec Section 4)
                        renderMapPins(res.barangays, candidate, (barangayId !== 'all' ? parseInt(barangayId) : null));

                        // 6. Update Right Detail Sidebar (Spec Section 6)
                        updateSidebarDetail(selectedBarangayId, candidate);

                        // 7. Render Historical Waves Grid (Spec Section 7)
                        renderHistoricalWaves(res.historical_trend, currentPeriodId);

                        // 8. Refresh DataTable records
                        surveyTable.clear().rows.add(res.records).draw();
                    },
                    error: function(xhr) {
                        console.error('Failed to load survey data:', xhr);
                        showToast('Error loading survey data. Please check connection.', true);
                    }
                });
            }

            // --- Render Headline Results Ribbon ---
            function renderHeadlineResults(candidates, filteredCandidate) {
                const container = document.getElementById('headlineCandidatesContainer');
                container.innerHTML = '';

                if (!candidates || candidates.length === 0) {
                    container.innerHTML = '<div style="grid-column: 1/-1; padding: 14px; text-align: center; color: #64748B;">No candidate data recorded for this survey selection.</div>';
                    return;
                }

                candidates.forEach(c => {
                    const isFiltered = (filteredCandidate && filteredCandidate !== 'all' && c.candidate_name === filteredCandidate);
                    const card = document.createElement('div');
                    card.className = `candidate-headline-card ${isFiltered ? 'candidate-focused' : ''}`;
                    if (isFiltered) {
                        card.style.borderColor = c.color;
                        card.style.boxShadow = `0 0 0 2px ${c.color}40, 0 6px 16px rgba(0, 0, 0, 0.08)`;
                    }

                    card.innerHTML = `
                        <div class="candidate-card-top-bar" style="background: ${c.color};"></div>
                        <div class="candidate-info-row">
                            <div class="candidate-color-circle" style="background: ${c.color};">
                                ${c.candidate_name.charAt(0)}
                            </div>
                            <div class="candidate-name-box">
                                <div class="candidate-name-display" title="${c.candidate_name}">
                                    ${c.candidate_name}
                                    ${isFiltered ? '<span style="font-size:0.65rem; background:#EFF6FF; color:#075998; border:1px solid #BFDBFE; padding:1px 6px; border-radius:8px; margin-left:4px;">Selected</span>' : ''}
                                </div>
                                <div style="font-size: 0.72rem; color: #64748B;">Color: ${c.color}</div>
                            </div>
                            <div class="candidate-rating-badge" style="color: ${c.color};">
                                ${c.average_rating}%
                            </div>
                        </div>
                        <div class="candidate-substats">
                            <span class="bgy-lead-pill">
                                <strong>${c.barangays_led_count}</strong> of 18 Barangays Led
                            </span>
                            <span title="Peak Barangay: ${c.highest_barangay}">Peak: ${c.highest_barangay}</span>
                        </div>
                    `;
                    container.appendChild(card);
                });
            }

            // --- Render Top 5 KPI Cards ---
            function renderKpis(k) {
                $('#kpiLeaderDot').css('background', k.leader_color || '#075998');
                $('#kpiLeaderRating').text(k.leader_rating + '%');
                $('#kpiLeaderName').text(k.leader_name);

                $('#kpiSampleSize').text(Number(k.total_sample_size).toLocaleString());
                $('#kpiBarangaysLed').text(k.barangays_led);
                $('#kpiMarginSub').text(k.leader_margin);

                $('#kpiStrongholdVal').text(k.stronghold).attr('title', k.stronghold);
                $('#kpiDateCoverage').text(k.date_range);
                $('#kpiMethodologySub').text(k.methodology).attr('title', k.methodology);
            }

            // --- Render Map Legend Swatches & Switcher Pills (Matching Issues) ---
            function renderMapLegend(candidates, filteredCandidate) {
                const container = document.getElementById('mapLegendContainer');
                container.innerHTML = '';

                if (!candidates || candidates.length === 0) {
                    container.innerHTML = '<span style="font-size: 0.78rem; color: #64748B;">No candidates recorded</span>';
                    return;
                }

                // "All Candidates" pill matching Issues metric pills
                const isAll = (!filteredCandidate || filteredCandidate === 'all');
                const allBtn = document.createElement('button');
                allBtn.type = 'button';
                allBtn.className = `metric-pill-btn ${isAll ? 'active' : ''}`;
                allBtn.innerHTML = `<span>All Candidates</span>`;
                allBtn.addEventListener('click', function() {
                    $('#filterCandidateSelect').val('all').trigger('change');
                });
                container.appendChild(allBtn);

                candidates.forEach(c => {
                    const isFiltered = (filteredCandidate && filteredCandidate !== 'all' && c.candidate_name === filteredCandidate);
                    const pill = document.createElement('button');
                    pill.type = 'button';
                    pill.className = `metric-pill-btn ${isFiltered ? 'active' : ''}`;
                    if (isFiltered) {
                        pill.style.background = c.color;
                        pill.style.borderColor = c.color;
                        pill.style.color = '#FFFFFF';
                    }
                    pill.innerHTML = `
                        <span style="width:8px; height:8px; border-radius:50%; background:${isFiltered ? '#FFFFFF' : c.color}; display:inline-block; flex-shrink:0;"></span>
                        <span>${c.candidate_name}: <strong>${c.average_rating}%</strong> (${c.barangays_led_count} led)</span>
                    `;
                    pill.addEventListener('click', function() {
                        const target = isFiltered ? 'all' : c.candidate_name;
                        $('#filterCandidateSelect').val(target).trigger('change');
                    });
                    container.appendChild(pill);
                });
            }

            // --- Render Map Hotspot Pins (Spec Section 4) ---
            function renderMapPins(barangaysMap, candidateFilter, filteredBarangayId) {
                pinsContainer.innerHTML = '';
                const isCandidateFiltered = (candidateFilter && candidateFilter !== 'all');

                for (let bgyId = 1; bgyId <= 18; bgyId++) {
                    const bgy = barangaysMap[bgyId] || {
                        id: bgyId,
                        name: barangayNames[bgyId] || ('Brgy. ' + bgyId),
                        leading_candidate: 'No Data',
                        leading_color: '#64748B',
                        leading_rating: 0,
                        margin: 0,
                        sample_size: 100,
                        candidates: [],
                        pin_display_color: '#64748B',
                        pin_display_rating: 0,
                        candidate_is_leading: false
                    };

                    const coords = pinCoordinates[bgyId] || { x: 50, y: 50 };

                    const pin = document.createElement('div');
                    pin.className = 'map-hotspot-pin';
                    pin.id = `hotspot-pin-${bgyId}`;
                    pin.style.left = `${coords.x}%`;
                    pin.style.top = `${coords.y}%`;

                    // Dynamic Color & Label matching filter state
                    if (isCandidateFiltered) {
                        pin.style.backgroundColor = bgy.pin_display_color || '#64748B';
                        pin.textContent = `${barangayNames[bgyId]}: ${bgy.pin_display_rating}%`;
                        if (bgy.candidate_is_leading) {
                            pin.style.border = '2px solid #FDE047';
                        }
                    } else {
                        pin.style.backgroundColor = bgy.leading_color || '#64748B';
                        pin.textContent = barangayNames[bgyId] || ('Brgy. ' + bgyId);
                    }

                    // Barangay Filter dimming / focus
                    if (filteredBarangayId) {
                        if (filteredBarangayId === bgyId) {
                            pin.classList.add('active-selected');
                            pin.style.opacity = '1';
                        } else {
                            pin.style.opacity = '0.50';
                        }
                    } else if (selectedBarangayId == bgyId) {
                        pin.classList.add('active-selected');
                    }

                    // Hover Tooltip
                    pin.addEventListener('mouseenter', function() {
                        tipName.textContent = barangayNames[bgyId] || `Brgy. ${bgyId}`;
                        if (isCandidateFiltered) {
                            tipLeaderDot.style.background = bgy.pin_display_color || '#64748B';
                            tipLeaderName.textContent = `${candidateFilter}: ${bgy.candidate_rating !== null ? bgy.candidate_rating + '%' : '0%'}`;
                            tipLeaderRating.textContent = `(Rank #${bgy.candidate_rank || '—'})`;
                            tipMargin.textContent = bgy.candidate_is_leading 
                                ? `Leading this Barangay (+${bgy.margin}% margin)` 
                                : `Leader: ${bgy.leading_candidate} (${bgy.leading_rating}%)`;
                        } else {
                            tipLeaderDot.style.background = bgy.leading_color || '#64748B';
                            tipLeaderName.textContent = bgy.leading_candidate || 'No Data';
                            tipLeaderRating.textContent = `(${bgy.leading_rating}%)`;
                            tipMargin.textContent = `Margin over #2: +${bgy.margin}%`;
                        }
                        tipSample.textContent = `Sample Size: ${bgy.sample_size} respondents`;
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

                // Toggle active class on pins
                document.querySelectorAll('.map-hotspot-pin').forEach(p => {
                    p.classList.remove('active-selected');
                    p.style.opacity = '0.50';
                });
                const activePin = document.getElementById(`hotspot-pin-${bgyId}`);
                if (activePin) {
                    activePin.classList.add('active-selected');
                    activePin.style.opacity = '1';
                }

                updateSidebarDetail(bgyId, $('#filterCandidateSelect').val());

                // Pre-select in modal dropdown
                $('#modalBarangayId').val(bgyId);

                // Reload data to synchronize KPIs, headline, and DataTable
                loadSurveyData();
            }

            // --- Update Right Sidebar Details (Spec Section 6) ---
            function updateSidebarDetail(bgyId, candidateFilter) {
                const bgy = currentBarangaysData[bgyId] || {
                    id: bgyId,
                    name: barangayNames[bgyId],
                    leading_candidate: 'No Data',
                    leading_color: '#64748B',
                    leading_rating: 0,
                    margin: 0,
                    sample_size: 100,
                    candidates: []
                };

                $('#sidebarBarangayTitle').text(barangayNames[bgyId] || `Brgy. ${bgyId}`);
                $('#sidebarBarangayNumber').text(bgyId);

                $('#sidebarWinnerDot').css('background', bgy.leading_color || '#64748B');
                $('#sidebarWinnerName').text(bgy.leading_candidate || 'No Data');
                $('#sidebarWinnerRating').text(`${bgy.leading_rating}%`);

                $('#sidebarSampleSize').text(bgy.sample_size || 100);
                $('#sidebarMargin').text(`+${bgy.margin}%`);

                // Progress Bars for Candidates
                const candidateContainer = document.getElementById('sidebarCandidateListContainer');
                candidateContainer.innerHTML = '';

                if (!bgy.candidates || bgy.candidates.length === 0) {
                    candidateContainer.innerHTML = '<div style="font-size:0.78rem; color:#64748B; padding:10px 0;">No candidate ratings recorded for this barangay.</div>';
                } else {
                    bgy.candidates.forEach(c => {
                        const isFiltered = (candidateFilter && candidateFilter !== 'all' && c.candidate_name === candidateFilter);
                        const item = document.createElement('div');
                        item.className = 'candidate-progress-item';
                        if (isFiltered) {
                            item.style.background = '#EFF6FF';
                            item.style.padding = '6px 8px';
                            item.style.borderRadius = '6px';
                            item.style.border = '1px solid #BFDBFE';
                        }
                        item.innerHTML = `
                            <div class="candidate-progress-header">
                                <span class="candidate-progress-name">
                                    <span style="width:8px; height:8px; border-radius:50%; background:${c.color}; display:inline-block;"></span>
                                    <span>${c.candidate_name}</span>
                                    ${isFiltered ? '<span style="font-size:0.65rem; color:#075998; font-weight:800;">(Selected)</span>' : ''}
                                </span>
                                <span class="candidate-progress-rating">${c.rating}%</span>
                            </div>
                            <div class="custom-progress-bar-bg" style="${isFiltered ? 'height: 10px;' : ''}">
                                <div class="custom-progress-fill" style="width: ${Math.min(c.rating, 100)}%; background: ${c.color};"></div>
                            </div>
                        `;
                        candidateContainer.appendChild(item);
                    });
                }

                // Methodology / Notes
                const firstWithNote = bgy.candidates.find(c => c.notes && c.notes !== '—');
                const noteText = firstWithNote ? firstWithNote.notes : 'Standard cluster random sampling across household units.';
                $('#sidebarMethodologyNotes').text(noteText);

                $('#btnQuickEncodeBarangay span').text(`+ Add / Update Result for ${barangayNames[bgyId]}`);
            }

            // --- Render Historical Waves Comparison (Spec Section 7) ---
            function renderHistoricalWaves(historicalTrend, activePeriodId) {
                const container = document.getElementById('historicalWavesContainer');
                container.innerHTML = '';

                if (!historicalTrend || historicalTrend.length === 0) {
                    container.innerHTML = '<div style="color: #64748B; font-size: 0.85rem;">No historical survey periods saved.</div>';
                    return;
                }

                historicalTrend.forEach((wave, idx) => {
                    const card = document.createElement('div');
                    const isActive = (wave.period_id == activePeriodId);
                    card.className = `historical-wave-card ${isActive ? 'active-wave' : ''}`;
                    card.setAttribute('data-period-id', wave.period_id);

                    let candidatesHtml = '';
                    wave.candidates.forEach(c => {
                        candidatesHtml += `
                            <div class="wave-candidate-row">
                                <span style="display:flex; align-items:center; gap:5px;">
                                    <span style="width:8px; height:8px; border-radius:50%; background:${c.color};"></span>
                                    <span>${c.candidate}</span>
                                </span>
                                <strong style="color:${c.color};">${c.average}%</strong>
                            </div>
                        `;
                    });

                    card.innerHTML = `
                        <div class="wave-card-header">
                            <span class="wave-badge">${isActive ? 'Active View' : `Wave ${idx + 1}`}</span>
                            <span style="font-size:0.72rem; color:#64748B;">${wave.start_date}</span>
                        </div>
                        <div class="wave-title">${wave.period_name}</div>
                        <div>${candidatesHtml}</div>
                        <button type="button" class="btn-secondary-action" style="width:100%; justify-content:center; margin-top:10px; font-size:0.76rem; padding:5px;">
                            ${isActive ? 'Currently Displaying' : 'Switch to this Period'}
                        </button>
                    `;

                    // Click to switch period immediately (Spec Section 2 & 7)
                    card.addEventListener('click', function() {
                        $('#filterPeriodSelect').val(wave.period_id).trigger('change');
                    });

                    container.appendChild(card);
                });
            }

            // --- Interactive Map Zoom & Pan Setup (Default Scale: 1.5x - Matching Electoral & Assistance) ---
            const DEFAULT_SCALE = 1.5;
            let zoomScale = DEFAULT_SCALE;
            let panX = 0;
            let panY = 0;
            let isDragging = false;
            let startDragX = 0;
            let startDragY = 0;

            function applyMapTransform() {
                mapViewport.style.transform = `translate(${panX}px, ${panY}px) scale(${zoomScale})`;
            }

            // Apply default zoom immediately on load
            applyMapTransform();

            document.getElementById('btnZoomIn').addEventListener('click', () => {
                zoomScale = Math.min(zoomScale + 0.25, 4.5);
                applyMapTransform();
            });

            document.getElementById('btnZoomOut').addEventListener('click', () => {
                zoomScale = Math.max(zoomScale - 0.25, 0.75);
                applyMapTransform();
            });

            document.getElementById('btnResetZoom').addEventListener('click', () => {
                zoomScale = DEFAULT_SCALE;
                panX = 0;
                panY = 0;
                applyMapTransform();
            });

            // Double click map to zoom in
            mapStage.addEventListener('dblclick', function(e) {
                if (e.target.closest('.map-hotspot-pin') || e.target.closest('.map-floating-controls')) return;
                zoomScale = zoomScale >= 3.0 ? DEFAULT_SCALE : zoomScale + 0.5;
                applyMapTransform();
            });

            // Mouse wheel scroll to zoom
            mapStage.addEventListener('wheel', function(e) {
                e.preventDefault();
                const delta = e.deltaY < 0 ? 0.15 : -0.15;
                zoomScale = Math.min(Math.max(zoomScale + delta, 0.75), 4.5);
                applyMapTransform();
            }, { passive: false });

            mapStage.addEventListener('mousedown', function(e) {
                if (e.target.closest('.map-hotspot-pin') || e.target.closest('.map-floating-controls')) return;
                isDragging = true;
                mapStage.classList.add('dragging');
                startDragX = e.clientX - panX;
                startDragY = e.clientY - panY;
            });

            window.addEventListener('mousemove', function(e) {
                if (!isDragging) return;
                panX = e.clientX - startDragX;
                panY = e.clientY - startDragY;
                applyMapTransform();
            });

            window.addEventListener('mouseup', function() {
                if (isDragging) {
                    isDragging = false;
                    mapStage.classList.remove('dragging');
                }
            });

            // --- Filter Triggers ---
            $('#filterPeriodSelect').on('change', function() {
                loadSurveyData();
            });

            $('#filterBarangaySelect, #filterCandidateSelect').on('change', function() {
                loadSurveyData();
            });

            let searchDebounce = null;
            $('#filterSearchInput').on('input', function() {
                clearTimeout(searchDebounce);
                searchDebounce = setTimeout(loadSurveyData, 300);
            });

            $('#btnResetFilters').on('click', function() {
                $('#filterBarangaySelect').val('all');
                $('#filterCandidateSelect').val('all');
                $('#filterSearchInput').val('');
                loadSurveyData();
            });

            // --- Color Picker Synchronization in Modal ---
            $('#modalCandidateColorPicker').on('input change', function() {
                const col = $(this).val();
                $('#modalCandidateColorHex').val(col);
                highlightColorPill(col);
            });

            $('#modalCandidateColorHex').on('input change', function() {
                const col = $(this).val();
                if (/^#[0-9A-F]{6}$/i.test(col)) {
                    $('#modalCandidateColorPicker').val(col);
                    highlightColorPill(col);
                }
            });

            $('.color-choice-pill').on('click', function() {
                const col = $(this).data('color');
                $('#modalCandidateColorPicker').val(col);
                $('#modalCandidateColorHex').val(col);
                highlightColorPill(col);
            });

            function highlightColorPill(color) {
                $('.color-choice-pill').each(function() {
                    if ($(this).data('color').toLowerCase() === color.toLowerCase()) {
                        $(this).addClass('selected');
                    } else {
                        $(this).removeClass('selected');
                    }
                });
            }

            // Edit Modal Color Sync
            $('#editCandidateColorPicker').on('input change', function() {
                $('#editCandidateColorHex').val($(this).val());
            });
            $('#editCandidateColorHex').on('input change', function() {
                const col = $(this).val();
                if (/^#[0-9A-F]{6}$/i.test(col)) {
                    $('#editCandidateColorPicker').val(col);
                }
            });

            // Autocomplete candidate color if candidate already entered
            $('#modalCandidateName').on('input change', function() {
                const entered = $(this).val().trim();
                const matched = currentHeadlineResults.find(c => c.candidate_name.toLowerCase() === entered.toLowerCase());
                if (matched) {
                    $('#modalCandidateColorPicker').val(matched.color);
                    $('#modalCandidateColorHex').val(matched.color);
                    highlightColorPill(matched.color);
                }
            });

            // --- Modal Controls: Encode Survey Result ---
            $('#btnOpenEncodeModal').on('click', function() {
                $('#modalSurveyPeriodId').val(currentPeriodId);
                $('#modalBarangayId').val(selectedBarangayId || 'all_barangays');
                $('#modalEncodeResult').css('display', 'flex');
            });

            $('#btnQuickEncodeBarangay').on('click', function() {
                $('#modalSurveyPeriodId').val(currentPeriodId);
                $('#modalBarangayId').val(selectedBarangayId);
                $('#modalEncodeResult').css('display', 'flex');
            });

            $('#btnCloseEncodeModal, #btnCancelEncodeModal').on('click', function() {
                $('#modalEncodeResult').hide();
                $('#formEncodeResult')[0].reset();
            });

            $('#formEncodeResult').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: routes.store,
                    method: 'POST',
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            $('#modalEncodeResult').hide();
                            $('#formEncodeResult')[0].reset();
                            showToast(res.message);
                            loadSurveyData();
                        }
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to save survey result.';
                        showToast(err, true);
                    }
                });
            });

            // --- Modal Controls: Create New Survey Period ---
            $('#btnOpenNewPeriodModal').on('click', function() {
                $('#modalNewPeriod').css('display', 'flex');
            });

            $('#btnCloseNewPeriodModal, #btnCancelNewPeriodModal').on('click', function() {
                $('#modalNewPeriod').hide();
                $('#formNewPeriod')[0].reset();
            });

            $('#formNewPeriod').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: routes.storePeriod,
                    method: 'POST',
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            $('#modalNewPeriod').hide();
                            $('#formNewPeriod')[0].reset();
                            showToast(res.message);

                            // Add new period to selects and switch to it
                            const newP = res.period;
                            const opt = `<option value="${newP.id}" selected>${newP.name}</option>`;
                            $('#filterPeriodSelect').prepend(opt);
                            $('#modalSurveyPeriodId').prepend(opt);
                            $('#editPeriodId').prepend(opt);

                            loadSurveyData();
                        }
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to create period.';
                        showToast(err, true);
                    }
                });
            });

            // --- Table Actions: View Details ---
            $(document).on('click', '.view-btn', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: `${routes.show}/${id}`,
                    method: 'GET',
                    success: function(res) {
                        if (res.success) {
                            const r = res.record;
                            $('#viewRecordBody').html(`
                                <div style="display:flex; flex-direction:column; gap:12px;">
                                    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #EEF2F6; padding-bottom:10px;">
                                        <div>
                                            <span style="font-size:0.74rem; font-weight:800; color:#075998; text-transform:uppercase;">Survey Period</span>
                                            <div style="font-size:1.05rem; font-weight:800;">${r.period_name}</div>
                                        </div>
                                        <div class="table-bgy-pill" style="font-size:0.90rem; padding:4px 10px;">${r.barangay_name}</div>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="width:28px; height:28px; border-radius:50%; background:${r.candidate_color};"></div>
                                        <div>
                                            <div style="font-size:1.05rem; font-weight:800;">${r.candidate_name}</div>
                                            <div style="font-size:0.78rem; color:#64748B;">Assigned Display Color: ${r.candidate_color}</div>
                                        </div>
                                        <div style="margin-left:auto; font-size:1.45rem; font-weight:800; color:${r.candidate_color};">${r.rating}%</div>
                                    </div>
                                    <div class="stats-grid-2col">
                                        <div class="stat-box">
                                            <div class="stat-box-label">Sample Size</div>
                                            <div class="stat-box-val">${r.sample_size} respondents</div>
                                        </div>
                                        <div class="stat-box">
                                            <div class="stat-box-label">Recorded On</div>
                                            <div class="stat-box-val" style="font-size:0.95rem;">${r.created_at}</div>
                                        </div>
                                    </div>
                                    <div class="methodology-sidebar-box">
                                        <strong>Methodology:</strong>
                                        <span>${r.methodology || 'Standard random cluster sampling.'}</span>
                                    </div>
                                    <div class="methodology-sidebar-box">
                                        <strong>Notes &amp; Observations:</strong>
                                        <span>${r.notes || 'No specific notes recorded.'}</span>
                                    </div>
                                </div>
                            `);
                            $('#modalViewRecord').css('display', 'flex');
                        }
                    }
                });
            });

            $('#btnCloseViewModal, #btnDismissViewModal').on('click', function() {
                $('#modalViewRecord').hide();
            });

            // --- Table Actions: Edit Record ---
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: `${routes.show}/${id}`,
                    method: 'GET',
                    success: function(res) {
                        if (res.success) {
                            const r = res.record;
                            $('#editRecordId').val(r.id);
                            $('#editPeriodId').val(r.survey_period_id);
                            $('#editBarangayId').val(r.barangay_id);
                            $('#editCandidateName').val(r.candidate_name);
                            $('#editRating').val(r.rating);
                            $('#editCandidateColorPicker').val(r.candidate_color);
                            $('#editCandidateColorHex').val(r.candidate_color);
                            $('#editSampleSize').val(r.sample_size);
                            $('#editMethodology').val(r.methodology);
                            $('#editNotes').val(r.notes);

                            $('#modalEditRecord').css('display', 'flex');
                        }
                    }
                });
            });

            $('#btnCloseEditModal, #btnCancelEditModal').on('click', function() {
                $('#modalEditRecord').hide();
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
                            $('#modalEditRecord').hide();
                            showToast(res.message);
                            loadSurveyData();
                        }
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to update record.';
                        showToast(err, true);
                    }
                });
            });

            // --- Table Actions: Delete Record ---
            $(document).on('click', '.delete-btn', function() {
                deleteTargetId = $(this).data('id');
                $('#modalDeleteRecord').css('display', 'flex');
            });

            $('#btnCloseDeleteModal, #btnCancelDeleteModal').on('click', function() {
                $('#modalDeleteRecord').hide();
                deleteTargetId = null;
            });

            $('#btnConfirmDelete').on('click', function() {
                if (!deleteTargetId) return;

                $.ajax({
                    url: `${routes.delete}/${deleteTargetId}`,
                    method: 'POST',
                    data: { _token: csrfToken },
                    success: function(res) {
                        if (res.success) {
                            $('#modalDeleteRecord').hide();
                            deleteTargetId = null;
                            showToast(res.message);
                            loadSurveyData();
                        }
                    },
                    error: function(xhr) {
                        showToast('Failed to delete record.', true);
                    }
                });
            });

            // --- Toast Notification Helper ---
            function showToast(message, isError = false) {
                const toast = document.getElementById('toastNotice');
                const toastMsg = document.getElementById('toastMessage');
                toastMsg.textContent = message;
                toast.style.background = isError ? '#DC2626' : '#0B192C';
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3500);
            }

            // Close modal on click outside
            $('.modal-backdrop-custom').on('click', function(e) {
                if (e.target === this) {
                    $(this).hide();
                }
            });

            // Initial Data Load
            loadSurveyData();
        });
    </script>
@endsection
