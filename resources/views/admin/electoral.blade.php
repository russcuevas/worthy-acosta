@extends('layouts.app')

@section('title', 'Electoral Data Dashboard - Mariveles, Bataan')
@section('user_name', 'Administrator')
@section('user_role_label', 'Admin Portal')
@section('user_initials', 'AD')

@section('styles')
    <style>
        /* Top Filter & Year Navigator Bar */
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

        /* Election Year Timeline / Ribbon */
        .electoral-year-bar-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid #EEF2F6;
        }

        .year-nav-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            flex: 1;
        }

        .year-nav-label {
            font-size: 0.76rem;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .year-pills-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .year-pill-btn {
            background: #F1F5F9;
            border: 1.5px solid #E2E8F0;
            color: var(--color-deep-navy);
            padding: 7px 16px;
            border-radius: 20px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            user-select: none;
        }

        .year-pill-btn:hover {
            background: #E2E8F0;
            border-color: #CBD5E1;
            transform: translateY(-1px);
        }

        .year-pill-btn.active {
            background: linear-gradient(135deg, var(--color-primary-blue), var(--color-deep-navy));
            color: #FFFFFF;
            border-color: var(--color-primary-blue);
            box-shadow: 0 4px 12px rgba(7, 89, 152, 0.35);
        }

        .year-pill-btn .year-tag-future {
            background: rgba(16, 185, 129, 0.2);
            color: #059669;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 8px;
            font-weight: 800;
        }

        .year-pill-btn.active .year-tag-future {
            background: rgba(255, 255, 255, 0.25);
            color: #FFFFFF;
        }

        .btn-add-year-pill {
            background: #EFF6FF;
            border: 1.5px dashed #60A5FA;
            color: var(--color-primary-blue);
            padding: 7px 16px;
            border-radius: 20px;
            font-size: 0.84rem;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all var(--transition-fast);
        }

        .btn-add-year-pill:hover {
            background: #DBEAFE;
            border-color: var(--color-primary-blue);
            transform: translateY(-1px);
        }

        .btn-edit-year-pill {
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            color: var(--color-deep-navy);
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all var(--transition-fast);
        }

        .btn-edit-year-pill:hover {
            background: #E2E8F0;
            border-color: #94A3B8;
            color: var(--color-primary-blue);
            transform: translateY(-1px);
        }

        .btn-delete-year-pill {
            background: #FEF2F2;
            border: 1.5px solid #FECACA;
            color: #DC2626;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all var(--transition-fast);
        }

        .btn-delete-year-pill:hover {
            background: #FEE2E2;
            border-color: #F87171;
            color: #B91C1C;
            transform: translateY(-1px);
        }

        .year-actions-wrap {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .db-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .db-status-dot {
            width: 8px;
            height: 8px;
            background: #10B981;
            border-radius: 50%;
            box-shadow: 0 0 6px #10B981;
        }

        /* Secondary Row: Position, Jump to Barangay, & Action */
        .controls-sub-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }

        .filter-controls-group {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
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
            padding: 9px 16px;
            border-radius: var(--radius-md);
            border: 1.5px solid #D6E4F0;
            background: #FFFFFF;
            font-size: 0.90rem;
            font-weight: 700;
            color: var(--color-deep-navy);
            outline: none;
            cursor: pointer;
            min-width: 170px;
            box-shadow: var(--shadow-sm);
            transition: all var(--transition-fast);
        }

        .custom-select-input:focus {
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.15);
        }

        .btn-encode-data {
            background: linear-gradient(135deg, var(--color-primary-blue), var(--color-deep-navy));
            color: #FFFFFF;
            border: none;
            border-radius: var(--radius-md);
            padding: 10px 18px;
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

        /* Candidate Legend Bar */
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
            gap: 12px;
        }

        .legend-candidate-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--color-deep-navy);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .legend-color-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #FFFFFF;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.25);
            flex-shrink: 0;
        }

        .legend-win-tag {
            background: #102A4E;
            color: #FFFFFF;
            font-size: 0.70rem;
            padding: 2px 7px;
            border-radius: 10px;
            font-weight: 800;
        }

        /* Main Dashboard Grid */
        .electoral-dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 22px;
            align-items: start;
        }

        @media (max-width: 1100px) {
            .electoral-dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Map Canvas Stage */
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

        /* Interactive Dynamic Hotspot Barangay Name Badge Pins (Patagilid / Slanted & Compact) */
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

        .map-watermark-badge {
            position: absolute;
            bottom: 14px;
            left: 16px;
            background: rgba(11, 25, 44, 0.85);
            backdrop-filter: blur(6px);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 800;
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
            pointer-events: none;
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
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            margin-top: 2px;
        }

        .progress-bar-wrap {
            margin-top: 6px;
        }

        .progress-bar-bg {
            height: 8px;
            background: #E2E8F0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #51B8E5, #075998);
            border-radius: 10px;
            transition: width 0.4s ease;
        }

        /* Candidates Results Breakdown List */
        .candidates-list-wrap {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 200px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .candidate-row-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .candidate-row-card.is-winner {
            border-color: #075998;
            background: #F0F7FC;
        }

        .c-info-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .c-color-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 1.5px solid #FFFFFF;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
        }

        .c-name-label {
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--color-deep-navy);
        }

        .c-vote-count {
            font-size: 0.86rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            text-align: right;
        }

        /* 18-Barangay Mini Pill List */
        .barangay-pills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            max-height: 140px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .bgy-pill-btn {
            padding: 5px 9px;
            border-radius: 6px;
            font-size: 0.74rem;
            font-weight: 700;
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            color: var(--color-deep-navy);
            cursor: pointer;
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .bgy-pill-btn:hover,
        .bgy-pill-btn.active {
            background: var(--color-primary-blue);
            color: #FFFFFF;
            border-color: var(--color-primary-blue);
        }

        .bgy-pill-num {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 800;
            color: #FFFFFF;
        }

        /* Modal Styles */
        .modal-backdrop-custom {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(10, 25, 47, 0.75);
            backdrop-filter: blur(5px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal-card-custom {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 620px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            border: 1px solid var(--card-border);
            display: flex;
            flex-direction: column;
        }

        .modal-header-custom {
            padding: 18px 24px;
            border-bottom: 1px solid #EEF2F6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header-custom h2 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--color-deep-navy);
        }

        .modal-close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-muted);
            cursor: pointer;
            line-height: 1;
        }

        .modal-body-custom {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .form-row-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .form-group-custom label {
            display: block;
            font-size: 0.78rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 6px;
        }

        .form-control-custom {
            width: 100%;
            padding: 10px 14px;
            border-radius: var(--radius-md);
            border: 1.5px solid #D6E4F0;
            font-size: 0.90rem;
            font-weight: 600;
            color: var(--color-deep-navy);
            outline: none;
            transition: all var(--transition-fast);
        }

        .form-control-custom:focus {
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.15);
        }

        .candidate-input-row {
            display: grid;
            grid-template-columns: 1fr 90px 110px 36px;
            gap: 8px;
            align-items: center;
            margin-bottom: 8px;
        }

        .btn-add-candidate-row {
            background: #F1F5F9;
            border: 1.5px dashed #CBD5E1;
            border-radius: var(--radius-md);
            padding: 8px 14px;
            color: var(--color-primary-blue);
            font-weight: 700;
            font-size: 0.82rem;
            cursor: pointer;
            width: 100%;
            transition: all var(--transition-fast);
        }

        .btn-add-candidate-row:hover {
            background: #E2E8F0;
            border-color: var(--color-primary-blue);
        }

        .btn-remove-row {
            background: #FEE2E2;
            color: #DC2626;
            border: none;
            border-radius: 6px;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: 800;
        }

        .modal-footer-custom {
            padding: 16px 24px;
            border-top: 1px solid #EEF2F6;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            background: #F8FAFC;
        }

        .btn-modal-cancel {
            background: #FFFFFF;
            border: 1.5px solid #CBD5E1;
            padding: 9px 18px;
            border-radius: var(--radius-md);
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
        }

        .btn-modal-save {
            background: var(--color-primary-blue);
            border: none;
            padding: 9px 22px;
            border-radius: var(--radius-md);
            font-weight: 700;
            color: #FFFFFF;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(7, 89, 152, 0.3);
        }

        .btn-modal-save:hover {
            background: var(--color-deep-navy);
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
        }

        .year-suggest-chip:hover {
            background: #DBEAFE;
        }

        /* Positions Checkboxes Grid */
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
        }

        .pos-check-label:hover {
            background: #F1F5F9;
        }

        .pos-check-label input[type="checkbox"] {
            cursor: pointer;
            accent-color: var(--color-primary-blue);
        }

        /* Toast notification */
        .floating-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #0B192C;
            color: #FFFFFF;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--color-wave-cyan);
            display: none;
            align-items: center;
            gap: 10px;
            z-index: 2000;
            animation: slideInToast 0.3s ease;
        }

        @keyframes slideInToast {
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

@section('role_badge')
    <span class="role-badge-pill role-badge-admin">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
        </svg>
        Admin Mode
    </span>
@endsection

@section('content')
    <!-- 1. Top Controls Bar: Electoral Year Timeline Ribbon + Position & Actions -->
    <div class="electoral-controls-header">
        <!-- Row A: Electoral Year Ribbon (Pill/Tab Navigation + Add Future Year Button) -->
        <div class="electoral-year-bar-row">
            <div class="year-nav-group">
                <div class="year-nav-label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span>Election Year:</span>
                </div>

                <!-- Dynamic Year Pills (No Dropdown) -->
                <div class="year-pills-wrapper" id="yearPillsContainer">
                    @foreach ($years as $yr)
                        <button type="button" class="year-pill-btn {{ $yr === $defaultYear ? 'active' : '' }}"
                            data-year="{{ $yr }}">
                            <span>{{ $yr }}</span>
                            @if ((int) $yr > 2025)
                                <span class="year-tag-future">Future</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                <!-- Year Actions Group (Edit, Delete, Add) -->
                <div class="year-actions-wrap">
                    <!-- Edit Active Year & Positions -->
                    <button type="button" class="btn-edit-year-pill" id="btnOpenEditYearModal"
                        title="Edit Positions to include in this Election Year">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                            stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        <span>Edit Year</span>
                    </button>

                    <!-- Delete Active Year -->
                    <button type="button" class="btn-delete-year-pill" id="btnOpenDeleteYearModal"
                        title="Delete this Election Year and all its records">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                            stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        <span>Delete Year</span>
                    </button>

                    <!-- Add Future / Custom Year Button -->
                    <button type="button" class="btn-add-year-pill" id="btnOpenAddYearModal"
                        title="Add Future / Custom Election Year">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24"
                            stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Add Year</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Row B: Position Selection, Jump to Barangay, and Encode Button -->
        <div class="controls-sub-row">
            <div class="filter-controls-group">
                <!-- Position Selection (Dynamically populated based on Year) -->
                <div class="filter-item-wrap">
                    <label for="selectPosition" class="filter-label">Electoral Position</label>
                    <select id="selectPosition" class="custom-select-input" aria-label="Position">
                        <!-- Dynamically populated based on active year -->
                    </select>
                </div>

                <!-- Jump to Barangay -->
                <div class="filter-item-wrap">
                    <label for="selectBarangay" class="filter-label">Jump to Barangay</label>
                    <select id="selectBarangay" class="custom-select-input" aria-label="Select Barangay">
                        <option value="">-- Choose Barangay (18) --</option>
                        @foreach ($barangayNames as $bId => $bName)
                            <option value="{{ $bId }}">Brgy. {{ $bName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <button type="button" class="btn-encode-data" id="btnOpenEncodeModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Encode / Edit Election Data
                </button>
            </div>
        </div>
    </div>

    <!-- 2. Dynamic Candidate Legend Strip -->
    <div class="candidate-legend-strip">
        <div class="legend-title-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
            </svg>
            <span>Candidate Legend (<span id="legendPositionLabel">Mayor</span>)</span>
        </div>
        <div class="legend-items-container" id="legendItemsContainer">
            <!-- Injected dynamically via JS -->
        </div>
    </div>

    <!-- 3. Main Dashboard: Map Visualizer on Left + Detailed Barangay Results on Right -->
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
                    <div class="tip-stat" id="tipStat">Registered Voters: 0</div>
                </div>

                <!-- Client Map Image with Hotspot Pins -->
                <div class="map-viewport-wrapper" id="mapViewport">
                    <img src="{{ asset('images/Map.jpg') }}" alt="Mariveles Bataan Map" class="client-map-img"
                        id="clientMapImg">

                    <!-- 18 Clickable Hotspot Pins colored by winner -->
                    <div id="hotspotPinsContainer">
                        <!-- Injected dynamically via JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar Detail Card -->
        <div class="sidebar-detail-card">
            <div class="sidebar-header-badge">
                <div>
                    <div class="detail-meta" id="cardYearPositionMeta">2025 Mayor Results</div>
                    <div class="detail-title" id="cardBgyName">Barangay Name</div>
                </div>
                <div class="bgy-number-badge" id="cardNumberBadge" title="Winning Candidate Color Marker">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                </div>
            </div>

            <!-- Registered Voters & Actual Votes -->
            <div class="stats-grid-2col">
                <div class="stat-box">
                    <div class="stat-box-label">Registered Voters</div>
                    <div class="stat-box-val" id="cardRegisteredVoters">0</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-label">Actual Votes</div>
                    <div class="stat-box-val" id="cardActualVotes">0</div>
                </div>
            </div>

            <!-- Voter Turnout Percentage -->
            <div>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span class="stat-box-label">Voter Turnout</span>
                    <strong style="font-size:0.90rem; color:#10B981;" id="cardTurnout">0%</strong>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" id="cardTurnoutBar" style="width: 0%;"></div>
                    </div>
                </div>
            </div>

            <!-- Winning Candidate Banner -->
            <div class="winner-banner-box" id="cardWinnerBox">
                <div>
                    <div class="winner-banner-text">🏆 Winning Candidate</div>
                    <div class="winner-name-display" id="cardWinnerName">Candidate Name</div>
                </div>
                <div class="c-vote-count" style="color: #0369A1;" id="cardWinnerVotes">0 votes</div>
            </div>

            <!-- All Candidates Breakdown List -->
            <div>
                <div class="stat-box-label" style="margin-bottom: 8px;">All Candidates Breakdown</div>
                <div class="candidates-list-wrap" id="cardCandidatesList">
                    <!-- Injected dynamically via JS -->
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid #EEF2F6; margin: 2px 0;">

            <!-- 18 Barangays Mini Quick-Select List -->
            <div>
                <div class="stat-box-label" style="margin-bottom: 6px;">All 18 Barangays</div>
                <div class="barangay-pills-list" id="pillsList">
                    <!-- Injected dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- 4. MODAL: Add New Election Year (Future / Custom Years) -->
    <div class="modal-backdrop-custom" id="addYearModalBackdrop">
        <div class="modal-card-custom" style="max-width: 500px;">
            <div class="modal-header-custom">
                <h2>Add New Election Year</h2>
                <button type="button" class="modal-close-btn" id="btnCloseAddYearModal">&times;</button>
            </div>

            <form id="addYearForm">
                @csrf
                <div class="modal-body-custom">
                    <div class="form-group-custom">
                        <label for="inputNewYear">Election Year (e.g. 2028, 2031, 2034)</label>
                        <input type="number" id="inputNewYear" name="year" class="form-control-custom"
                            placeholder="2028" min="2000" max="2100" required>
                        <div class="year-quick-suggestions">
                            <span style="font-size:0.72rem; color:var(--text-muted); font-weight:700;">Quick Pick:</span>
                            <button type="button" class="year-suggest-chip" data-suggest="2028">+ 2028</button>
                            <button type="button" class="year-suggest-chip" data-suggest="2031">+ 2031</button>
                            <button type="button" class="year-suggest-chip" data-suggest="2034">+ 2034</button>
                            <button type="button" class="year-suggest-chip" data-suggest="2037">+ 2037</button>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="inputNewYearTitle">Election Title / Description</label>
                        <input type="text" id="inputNewYearTitle" name="title" class="form-control-custom"
                            placeholder="e.g. 2028 Presidential & Local Elections">
                    </div>

                    <div class="form-group-custom">
                        <label>Electoral Positions to Include</label>
                        <div class="positions-checklist-grid">
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
                        </div>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelAddYearModal">Cancel</button>
                    <button type="submit" class="btn-modal-save" id="btnSubmitAddYear">Save to Database &amp;
                        Open</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4B. MODAL: Edit Active Election Year & Included Positions -->
    <div class="modal-backdrop-custom" id="editYearModalBackdrop">
        <div class="modal-card-custom" style="max-width: 540px;">
            <div class="modal-header-custom">
                <h2>Edit Election Year (<span id="editYearDisplayLabel">2025</span>)</h2>
                <button type="button" class="modal-close-btn" id="btnCloseEditYearModal">&times;</button>
            </div>

            <form id="editYearForm">
                @csrf
                <input type="hidden" id="inputEditYearVal" name="year" value="">
                <div class="modal-body-custom">
                    <div class="form-group-custom">
                        <label for="inputEditYearTitle">Election Title / Description</label>
                        <input type="text" id="inputEditYearTitle" name="title" class="form-control-custom"
                            placeholder="e.g. 2025 Local & National Elections" required>
                    </div>

                    <div class="form-group-custom">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                            <label style="margin:0;">Electoral Positions to Include</label>
                            <span style="font-size:0.72rem; color:var(--text-muted);">Check/uncheck positions</span>
                        </div>

                        <div class="positions-checklist-grid" id="editPositionsChecklist">
                            <!-- Injected dynamically based on year's configuration -->
                        </div>

                        <!-- Add custom position field -->
                        <div style="margin-top: 10px; display: flex; gap: 8px;">
                            <input type="text" id="inputAddCustomPos" class="form-control-custom" style="font-size:0.84rem; padding:8px 12px;"
                                placeholder="Add custom position (e.g. SK Chairman)">
                            <button type="button" class="btn-modal-cancel" id="btnAddCustomPos"
                                style="padding:8px 14px; white-space:nowrap; font-weight:800; color:var(--color-primary-blue); background:#EFF6FF; border-color:#BFDBFE;">
                                + Add
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelEditYearModal">Cancel</button>
                    <button type="submit" class="btn-modal-save" id="btnSubmitEditYear">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4C. MODAL: Delete Election Year Confirmation -->
    <div class="modal-backdrop-custom" id="deleteYearModalBackdrop">
        <div class="modal-card-custom" style="max-width: 440px;">
            <div class="modal-header-custom" style="border-bottom-color: #FEE2E2;">
                <h2 style="color: #DC2626; display:flex; align-items:center; gap:8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="#DC2626">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    Delete Election Year
                </h2>
                <button type="button" class="modal-close-btn" id="btnCloseDeleteYearModal">&times;</button>
            </div>

            <div class="modal-body-custom">
                <p style="font-size: 0.92rem; color: var(--color-deep-navy); margin-bottom: 12px; line-height: 1.5;">
                    Are you sure you want to permanently delete Election Year <strong id="deleteYearDisplayLabel" style="color: #DC2626;">2025</strong>?
                </p>
                <div style="font-size: 0.80rem; color: #991B1B; line-height: 1.4; background: #FEF2F2; padding: 10px 14px; border-radius: 8px; border: 1px solid #FECACA;">
                    ⚠️ <strong>Warning:</strong> All electoral records, barangay totals, and candidate vote counts associated with this election year will be permanently deleted from the database.
                </div>
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" id="btnCancelDeleteYearModal">Cancel</button>
                <button type="button" class="btn-modal-save" id="btnConfirmDeleteYear"
                    style="background: linear-gradient(135deg, #DC2626, #B91C1C); border: none; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">
                    Yes, Delete Year
                </button>
            </div>
        </div>
    </div>

    <!-- 5. MODAL: Encode / Edit Election Data -->
    <div class="modal-backdrop-custom" id="encodeModalBackdrop">
        <div class="modal-card-custom">
            <div class="modal-header-custom">
                <h2>Encode / Edit Election Data</h2>
                <button type="button" class="modal-close-btn" id="btnCloseEncodeModal">&times;</button>
            </div>

            <form id="encodeDataForm">
                @csrf
                <div class="modal-body-custom">
                    <div class="form-row-2col">
                        <div class="form-group-custom">
                            <label for="modalYear">Electoral Year</label>
                            <select id="modalYear" name="year" class="form-control-custom">
                                @foreach ($years as $yr)
                                    <option value="{{ $yr }}">{{ $yr }} Election</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group-custom">
                            <label for="modalPosition">Position</label>
                            <select id="modalPosition" name="position" class="form-control-custom">
                                <!-- Filled dynamically -->
                            </select>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="modalBarangay">Barangay</label>
                        <select id="modalBarangay" name="barangay_id" class="form-control-custom">
                            @foreach ($barangayNames as $bId => $bName)
                                <option value="{{ $bId }}">Brgy. {{ $bName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row-2col">
                        <div class="form-group-custom">
                            <label for="modalRegVoters">Total Registered Voters</label>
                            <input type="number" id="modalRegVoters" name="registered_voters"
                                class="form-control-custom" min="1" required>
                        </div>

                        <div class="form-group-custom">
                            <label for="modalActualVotes">Number of Actual Votes</label>
                            <input type="number" id="modalActualVotes" name="actual_votes" class="form-control-custom"
                                min="0" required>
                        </div>
                    </div>

                    <hr style="border: none; border-top: 1px solid #EEF2F6; margin: 4px 0;">

                    <div>
                        <label
                            style="font-size: 0.78rem; font-weight: 800; color: var(--color-deep-navy); text-transform: uppercase; margin-bottom: 10px; display: block;">
                            Candidates &amp; Votes Received
                        </label>

                        <div id="modalCandidatesContainer">
                            <!-- Candidate rows injected dynamically -->
                        </div>

                        <button type="button" class="btn-add-candidate-row" id="btnAddCandidateRow">
                            + Add Another Candidate
                        </button>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" id="btnCancelEncodeModal">Cancel</button>
                    <button type="submit" class="btn-modal-save" id="btnSubmitEncodeData">Save to Database &amp; Update
                        Map</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Floating Toast Notification -->
    <div class="floating-toast" id="floatingToast">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
            stroke-width="2.5" stroke="#10B981">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
        </svg>
        <span id="toastMessage">Action completed successfully!</span>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initial server-side dataset loaded directly from MySQL Database
            let fullDataset = @json($initialDataset);
            let availableYears = @json($years);
            let positionsByYear = @json($yearPositionsMap);

            // Relative hotspot coordinates aligned with numbers 1-18 on Map.jpg
            const pinCoordinates = {
                1: {
                    x: 71.0,
                    y: 33.2
                },
                2: {
                    x: 88.8,
                    y: 33.6
                },
                3: {
                    x: 63.3,
                    y: 41.8
                },
                4: {
                    x: 78.0,
                    y: 42.8
                },
                5: {
                    x: 34.8,
                    y: 40.5
                },
                6: {
                    x: 52.6,
                    y: 42.2
                },
                7: {
                    x: 22.5,
                    y: 47.2
                },
                8: {
                    x: 44.5,
                    y: 44.2
                },
                9: {
                    x: 79.6,
                    y: 50.1
                },
                10: {
                    x: 38.5,
                    y: 51.1
                },
                11: {
                    x: 77.7,
                    y: 59.2
                },
                12: {
                    x: 68.2,
                    y: 62.2
                },
                13: {
                    x: 43.6,
                    y: 61.8
                },
                14: {
                    x: 58.9,
                    y: 62.9
                },
                15: {
                    x: 51.7,
                    y: 63.0
                },
                16: {
                    x: 36.0,
                    y: 67.9
                },
                17: {
                    x: 58.1,
                    y: 69.5
                },
                18: {
                    x: 40.5,
                    y: 74.7
                }
            };

            const barangayNames = @json($barangayNames);
            const barangaysList = @json($barangays ?? []);

            // Hydrate pin coordinates from Database if available
            if (barangaysList && barangaysList.length > 0) {
                barangaysList.forEach(b => {
                    if (b.pin_x && b.pin_y) {
                        pinCoordinates[b.id] = { x: parseFloat(b.pin_x), y: parseFloat(b.pin_y) };
                    }
                });
            }

            // Current State
            let currentYear = '{{ $defaultYear }}';
            let currentPosition = '{{ $defaultPosition }}';
            let selectedBarangayId = 1;

            // DOM Elements
            const yearPillsContainer = document.getElementById('yearPillsContainer');
            const selectPosition = document.getElementById('selectPosition');
            const selectBarangayDropdown = document.getElementById('selectBarangay');
            const legendPositionLabel = document.getElementById('legendPositionLabel');
            const legendItemsContainer = document.getElementById('legendItemsContainer');
            const pinsContainer = document.getElementById('hotspotPinsContainer');
            const pillsList = document.getElementById('pillsList');
            const tooltip = document.getElementById('mapHoverTooltip');
            const tipName = document.getElementById('tipName');
            const tipStat = document.getElementById('tipStat');

            // Sidebar Elements
            const cardYearPositionMeta = document.getElementById('cardYearPositionMeta');
            const cardBgyName = document.getElementById('cardBgyName');
            const cardNumberBadge = document.getElementById('cardNumberBadge');
            const cardRegisteredVoters = document.getElementById('cardRegisteredVoters');
            const cardActualVotes = document.getElementById('cardActualVotes');
            const cardTurnout = document.getElementById('cardTurnout');
            const cardTurnoutBar = document.getElementById('cardTurnoutBar');
            const cardWinnerName = document.getElementById('cardWinnerName');
            const cardWinnerVotes = document.getElementById('cardWinnerVotes');
            const cardCandidatesList = document.getElementById('cardCandidatesList');

            // Add Year Modal Elements
            const addYearModalBackdrop = document.getElementById('addYearModalBackdrop');
            const btnOpenAddYearModal = document.getElementById('btnOpenAddYearModal');
            const btnCloseAddYearModal = document.getElementById('btnCloseAddYearModal');
            const btnCancelAddYearModal = document.getElementById('btnCancelAddYearModal');
            const addYearForm = document.getElementById('addYearForm');
            const inputNewYear = document.getElementById('inputNewYear');
            const inputNewYearTitle = document.getElementById('inputNewYearTitle');

            // Edit Year Modal Elements
            const editYearModalBackdrop = document.getElementById('editYearModalBackdrop');
            const btnOpenEditYearModal = document.getElementById('btnOpenEditYearModal');
            const btnCloseEditYearModal = document.getElementById('btnCloseEditYearModal');
            const btnCancelEditYearModal = document.getElementById('btnCancelEditYearModal');
            const editYearForm = document.getElementById('editYearForm');
            const inputEditYearVal = document.getElementById('inputEditYearVal');
            const inputEditYearTitle = document.getElementById('inputEditYearTitle');
            const editYearDisplayLabel = document.getElementById('editYearDisplayLabel');
            const editPositionsChecklist = document.getElementById('editPositionsChecklist');
            const inputAddCustomPos = document.getElementById('inputAddCustomPos');
            const btnAddCustomPos = document.getElementById('btnAddCustomPos');

            // Delete Year Modal Elements
            const deleteYearModalBackdrop = document.getElementById('deleteYearModalBackdrop');
            const btnOpenDeleteYearModal = document.getElementById('btnOpenDeleteYearModal');
            const btnCloseDeleteYearModal = document.getElementById('btnCloseDeleteYearModal');
            const btnCancelDeleteYearModal = document.getElementById('btnCancelDeleteYearModal');
            const btnConfirmDeleteYear = document.getElementById('btnConfirmDeleteYear');
            const deleteYearDisplayLabel = document.getElementById('deleteYearDisplayLabel');

            // Encode Modal Elements
            const encodeModalBackdrop = document.getElementById('encodeModalBackdrop');
            const btnOpenEncodeModal = document.getElementById('btnOpenEncodeModal');
            const btnCloseEncodeModal = document.getElementById('btnCloseEncodeModal');
            const btnCancelEncodeModal = document.getElementById('btnCancelEncodeModal');
            const modalYear = document.getElementById('modalYear');
            const modalPosition = document.getElementById('modalPosition');
            const modalBarangay = document.getElementById('modalBarangay');
            const modalRegVoters = document.getElementById('modalRegVoters');
            const modalActualVotes = document.getElementById('modalActualVotes');
            const modalCandidatesContainer = document.getElementById('modalCandidatesContainer');
            const btnAddCandidateRow = document.getElementById('btnAddCandidateRow');
            const encodeDataForm = document.getElementById('encodeDataForm');

            // Toast helper
            function showToast(msg) {
                const toast = document.getElementById('floatingToast');
                const toastMsg = document.getElementById('toastMessage');
                toastMsg.textContent = msg;
                toast.style.display = 'inline-flex';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 3500);
            }

            // Render Year Pills (Ribbon Navigation)
            function renderYearPills() {
                yearPillsContainer.innerHTML = '';
                availableYears.forEach(yr => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = `year-pill-btn ${yr === currentYear ? 'active' : ''}`;
                    btn.dataset.year = yr;

                    let futureTag = '';
                    if (Number(yr) > 2025) {
                        futureTag = '<span class="year-tag-future">Future</span>';
                    }

                    btn.innerHTML = `<span>${yr}</span>${futureTag}`;
                    btn.addEventListener('click', () => {
                        setYear(yr);
                    });
                    yearPillsContainer.appendChild(btn);
                });

                // Also update the select in the encode modal
                modalYear.innerHTML = '';
                availableYears.forEach(yr => {
                    const opt = document.createElement('option');
                    opt.value = yr;
                    opt.textContent = `${yr} Election`;
                    if (yr === currentYear) opt.selected = true;
                    modalYear.appendChild(opt);
                });
            }

            // Switch Selected Year
            function setYear(yr) {
                currentYear = yr;
                document.querySelectorAll('.year-pill-btn').forEach(b => {
                    b.classList.toggle('active', b.dataset.year === yr);
                });

                populatePositions(currentYear, selectPosition);
                currentPosition = selectPosition.value;
                refreshDashboard();
            }

            // Populate Position dropdown based on Year
            function populatePositions(year, targetSelect, selectedPos = null) {
                targetSelect.innerHTML = '';
                const posList = positionsByYear[year] || ['Mayor', 'Vice Mayor', 'Governor', 'Congressman',
                    'Councilors'
                ];
                posList.forEach(pos => {
                    const opt = document.createElement('option');
                    opt.value = pos;
                    opt.textContent = pos;
                    if (selectedPos && selectedPos === pos) {
                        opt.selected = true;
                    }
                    targetSelect.appendChild(opt);
                });
            }

            // Refresh Map, Legend & Sidebar
            function refreshDashboard() {
                const yearData = fullDataset[currentYear] || {};
                const positionData = yearData[currentPosition] || {};

                legendPositionLabel.textContent = `${currentYear} ${currentPosition}`;
                cardYearPositionMeta.textContent = `${currentYear} ${currentPosition} Election`;

                // 1. Calculate Candidates Totals & Barangays Won for Legend
                const candidateStats = {};
                for (let bgyId = 1; bgyId <= 18; bgyId++) {
                    const bgy = positionData[bgyId];
                    if (!bgy) continue;

                    // Tally candidate totals
                    (bgy.candidates || []).forEach(c => {
                        if (!candidateStats[c.name]) {
                            candidateStats[c.name] = {
                                name: c.name,
                                color: c.color,
                                totalVotes: 0,
                                bgysWon: 0
                            };
                        }
                        candidateStats[c.name].totalVotes += (c.votes || 0);
                    });

                    // Tally winner barangay
                    if (bgy.winner_name && candidateStats[bgy.winner_name]) {
                        candidateStats[bgy.winner_name].bgysWon += 1;
                    }
                }

                // Render Legend
                legendItemsContainer.innerHTML = '';
                const sortedCandidates = Object.values(candidateStats).sort((a, b) => b.totalVotes - a.totalVotes);
                if (sortedCandidates.length === 0) {
                    legendItemsContainer.innerHTML =
                        '<span style="font-size:0.78rem; color:var(--text-muted);">No candidate data encoded for this election yet.</span>';
                } else {
                    sortedCandidates.forEach(c => {
                        const badge = document.createElement('div');
                        badge.className = 'legend-candidate-badge';
                        badge.innerHTML = `
                            <span class="legend-color-dot" style="background:${c.color};"></span>
                            <span>${c.name}</span>
                            <span style="font-weight:800; color:var(--color-primary-blue);">${Number(c.totalVotes).toLocaleString()} votes</span>
                            <span class="legend-win-tag">${c.bgysWon}/18 Bgys</span>
                        `;
                        legendItemsContainer.appendChild(badge);
                    });
                }

                // 2. Render 18 Hotspot Pins with Winning Candidate Colors
                pinsContainer.innerHTML = '';
                for (let bgyId = 1; bgyId <= 18; bgyId++) {
                    const bgy = positionData[bgyId] || {
                        barangay_id: bgyId,
                        barangay_name: barangayNames[bgyId],
                        winner_color: '#075998',
                        winner_name: 'Pending',
                        registered_voters: 0,
                        actual_votes: 0,
                        turnout_percentage: 0
                    };

                    const coords = pinCoordinates[bgyId] || {
                        x: 50,
                        y: 50
                    };
                    const pin = document.createElement('div');
                    pin.className = 'map-hotspot-pin';
                    pin.id = `hotspot-pin-${bgyId}`;
                    pin.style.left = `${coords.x}%`;
                    pin.style.top = `${coords.y}%`;
                    pin.style.backgroundColor = bgy.winner_color || '#075998';
                    pin.textContent = barangayNames[bgyId] || ('Brgy. ' + bgyId);

                    if (bgyId === selectedBarangayId) {
                        pin.classList.add('active-selected');
                    }

                    // Hover Tooltip
                    pin.addEventListener('mouseenter', function(e) {
                        tipName.textContent = `Brgy. ${barangayNames[bgyId]}`;
                        tipStat.textContent =
                            `Winner: ${bgy.winner_name} | Turnout: ${bgy.turnout_percentage || 0}%`;
                        tooltip.style.display = 'block';
                    });

                    pin.addEventListener('mousemove', function(e) {
                        const rect = document.getElementById('mapStage').getBoundingClientRect();
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

                // 3. Render Sidebar Pills
                renderPills(positionData);

                // 4. Update Sidebar Details for Selected Barangay
                renderSidebarDetails(selectedBarangayId);
            }

            function renderPills(positionData) {
                pillsList.innerHTML = '';
                for (let bgyId = 1; bgyId <= 18; bgyId++) {
                    const bgy = positionData[bgyId] || {};
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = `bgy-pill-btn ${bgyId === selectedBarangayId ? 'active' : ''}`;
                    btn.id = `pill-bgy-${bgyId}`;
                    btn.innerHTML =
                        `<span class="bgy-pill-dot" style="background:${bgy.winner_color || '#075998'}; width:8px; height:8px; border-radius:50%; display:inline-block; flex-shrink:0;"></span> ${barangayNames[bgyId]}`;
                    btn.addEventListener('click', () => selectBarangay(bgyId));
                    pillsList.appendChild(btn);
                }
            }

            function selectBarangay(bgyId) {
                selectedBarangayId = Number(bgyId);

                // Highlight Pin
                document.querySelectorAll('.map-hotspot-pin').forEach(p => p.classList.remove('active-selected'));
                const targetPin = document.getElementById(`hotspot-pin-${bgyId}`);
                if (targetPin) targetPin.classList.add('active-selected');

                // Highlight Pill
                document.querySelectorAll('.bgy-pill-btn').forEach(p => p.classList.remove('active'));
                const targetPill = document.getElementById(`pill-bgy-${bgyId}`);
                if (targetPill) targetPill.classList.add('active');

                // Sync Jump Dropdown
                if (selectBarangayDropdown) selectBarangayDropdown.value = bgyId;

                // Update Sidebar Details
                renderSidebarDetails(bgyId);
            }

            function renderSidebarDetails(bgyId) {
                const yearData = fullDataset[currentYear] || {};
                const positionData = yearData[currentPosition] || {};
                const bgy = positionData[bgyId] || {
                    barangay_id: bgyId,
                    barangay_name: barangayNames[bgyId],
                    registered_voters: 0,
                    actual_votes: 0,
                    turnout_percentage: 0,
                    winner_name: 'No Data',
                    winner_color: '#075998',
                    winner_votes: 0,
                    candidates: []
                };

                cardBgyName.textContent = `Brgy. ${barangayNames[bgyId]}`;
                cardNumberBadge.style.backgroundColor = bgy.winner_color || '#075998';

                cardRegisteredVoters.textContent = Number(bgy.registered_voters || 0).toLocaleString();
                cardActualVotes.textContent = Number(bgy.actual_votes || 0).toLocaleString();

                const turnout = bgy.turnout_percentage || 0;
                cardTurnout.textContent = `${turnout}%`;
                cardTurnoutBar.style.width = `${Math.min(100, turnout)}%`;

                cardWinnerName.textContent = bgy.winner_name || 'None';
                cardWinnerVotes.textContent = `${Number(bgy.winner_votes || 0).toLocaleString()} votes`;

                // Render Candidates Breakdown List
                cardCandidatesList.innerHTML = '';
                const sortedCandidates = [...(bgy.candidates || [])].sort((a, b) => b.votes - a.votes);

                if (sortedCandidates.length === 0) {
                    cardCandidatesList.innerHTML =
                        '<div style="font-size:0.80rem; color:var(--text-muted); padding:6px 0;">No candidates encoded yet. Click "Encode / Edit" to enter candidate votes.</div>';
                } else {
                    sortedCandidates.forEach((c, idx) => {
                        const isWinner = (idx === 0 && (c.votes || 0) > 0);
                        const pct = (bgy.actual_votes > 0) ? Math.round((c.votes / bgy.actual_votes) *
                            100) : 0;
                        const row = document.createElement('div');
                        row.className = `candidate-row-card ${isWinner ? 'is-winner' : ''}`;
                        row.innerHTML = `
                            <div class="c-info-group">
                                <span class="c-color-dot" style="background:${c.color};"></span>
                                <div>
                                    <div class="c-name-label">${c.name} ${isWinner ? '🏆' : ''}</div>
                                    <div style="font-size:0.70rem; color:var(--text-muted);">${pct}% of actual votes</div>
                                </div>
                            </div>
                            <div class="c-vote-count">${Number(c.votes).toLocaleString()}</div>
                        `;
                        cardCandidatesList.appendChild(row);
                    });
                }
            }

            // Position & Barangay Dropdown Event Listeners
            selectPosition.addEventListener('change', function() {
                currentPosition = this.value;
                refreshDashboard();
            });

            if (selectBarangayDropdown) {
                selectBarangayDropdown.addEventListener('change', function() {
                    if (this.value) {
                        selectBarangay(this.value);
                    }
                });
            }

            // Map Drag & Zoom Controls (Default: 1.5x - 2 zoom levels closer)
            const DEFAULT_SCALE = 1.5;
            let currentScale = DEFAULT_SCALE;
            let panX = 0,
                panY = 0;
            let isPanning = false;
            let startX = 0,
                startY = 0;
            const mapStage = document.getElementById('mapStage');
            const mapViewport = document.getElementById('mapViewport');

            function updateTransform() {
                mapViewport.style.transform = `translate(${panX}px, ${panY}px) scale(${currentScale})`;
            }

            document.getElementById('btnZoomIn').addEventListener('click', () => {
                currentScale = Math.min(3.5, currentScale + 0.25);
                updateTransform();
            });

            document.getElementById('btnZoomOut').addEventListener('click', () => {
                currentScale = Math.max(0.75, currentScale - 0.25);
                updateTransform();
            });

            document.getElementById('btnResetZoom').addEventListener('click', () => {
                currentScale = DEFAULT_SCALE;
                panX = 0;
                panY = 0;
                updateTransform();
            });

            mapStage.addEventListener('mousedown', function(e) {
                if (e.target.closest('.map-btn-icon') || e.target.closest('.map-hotspot-pin')) return;
                isPanning = true;
                startX = e.clientX - panX;
                startY = e.clientY - panY;
                mapStage.classList.add('dragging');
            });

            window.addEventListener('mousemove', function(e) {
                if (!isPanning) return;
                panX = e.clientX - startX;
                panY = e.clientY - startY;
                updateTransform();
            });

            window.addEventListener('mouseup', function() {
                isPanning = false;
                mapStage.classList.remove('dragging');
            });

            // ==========================================
            // MODAL A: ADD NEW ELECTION YEAR (DATABASE)
            // ==========================================
            function openAddYearModal() {
                // Calculate next election year suggestion (e.g., 2028 if max is 2025)
                const numericYears = availableYears.map(y => parseInt(y, 10)).filter(n => !isNaN(n));
                const maxYear = numericYears.length > 0 ? Math.max(...numericYears) : 2025;
                const nextYear = maxYear < 2025 ? 2028 : (maxYear + 3);

                inputNewYear.value = nextYear;
                inputNewYearTitle.value = `${nextYear} National & Local Elections`;
                addYearModalBackdrop.style.display = 'flex';
            }

            function closeAddYearModal() {
                addYearModalBackdrop.style.display = 'none';
            }

            btnOpenAddYearModal.addEventListener('click', openAddYearModal);
            btnCloseAddYearModal.addEventListener('click', closeAddYearModal);
            btnCancelAddYearModal.addEventListener('click', closeAddYearModal);

            // Quick suggestions chips
            document.querySelectorAll('.year-suggest-chip').forEach(chip => {
                chip.addEventListener('click', function() {
                    const val = this.dataset.suggest;
                    inputNewYear.value = val;
                    inputNewYearTitle.value = `${val} National & Local Elections`;
                });
            });

            // Submit Add Year Form
            addYearForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const yr = inputNewYear.value.trim();
                const title = inputNewYearTitle.value.trim();
                const checkedPositions = [];
                addYearForm.querySelectorAll('input[name="positions[]"]:checked').forEach(chk => {
                    checkedPositions.push(chk.value);
                });

                if (!yr) {
                    alert('Please enter a valid election year.');
                    return;
                }

                if (checkedPositions.length === 0) {
                    alert('Please select at least one position.');
                    return;
                }

                const submitBtn = document.getElementById('btnSubmitAddYear');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Saving to Database...';

                fetch("{{ route('admin.electoral.add_year') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            year: yr,
                            title: title,
                            positions: checkedPositions
                        })
                    })
                    .then(r => r.json())
                    .then(res => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save to Database & Open';

                        if (res.success) {
                            // Add year to available list if not present
                            if (!availableYears.includes(yr)) {
                                availableYears.push(yr);
                                availableYears.sort((a, b) => parseInt(a) - parseInt(b));
                            }

                            // Store positions map & dataset
                            positionsByYear[yr] = res.positions || checkedPositions;
                            if (res.dataset && res.dataset[yr]) {
                                fullDataset[yr] = res.dataset[yr];
                            } else if (!fullDataset[yr]) {
                                fullDataset[yr] = {};
                            }

                            // Re-render year pills
                            renderYearPills();

                            // Switch to newly created year
                            setYear(yr);

                            closeAddYearModal();
                            showToast(`Election Year ${yr} created in Database successfully!`);
                        } else {
                            alert(res.message || 'Failed to add election year.');
                        }
                    })
                    .catch(err => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save to Database & Open';
                        console.error("Add Year error:", err);
                        alert("Error communicating with database. Please try again.");
                    });
            });

            // ==========================================
            // MODAL A2: EDIT ELECTION YEAR & POSITIONS
            // ==========================================
            const standardPositionsList = [
                'Mayor', 'Vice Mayor', 'Governor', 'Congressman', 'Councilors',
                'Barangay Captain', 'Barangay Kagawads', 'SK Chairman', 'SK Kagawads'
            ];

            function openEditYearModal() {
                inputEditYearVal.value = currentYear;
                editYearDisplayLabel.textContent = currentYear;
                inputEditYearTitle.value = `${currentYear} Local & National Elections`;

                // Populate positions checklist
                const activePositions = positionsByYear[currentYear] || ['Mayor', 'Vice Mayor', 'Governor', 'Congressman', 'Councilors'];
                
                // Combine standard positions with active ones to ensure everything is visible
                const combinedPositions = Array.from(new Set([...activePositions, ...standardPositionsList]));

                editPositionsChecklist.innerHTML = '';
                combinedPositions.forEach(pos => {
                    const isChecked = activePositions.includes(pos);
                    const label = document.createElement('label');
                    label.className = 'pos-check-label';
                    label.innerHTML = `<input type="checkbox" name="positions[]" value="${pos}" ${isChecked ? 'checked' : ''}> ${pos}`;
                    editPositionsChecklist.appendChild(label);
                });

                inputAddCustomPos.value = '';
                editYearModalBackdrop.style.display = 'flex';
            }

            function closeEditYearModal() {
                editYearModalBackdrop.style.display = 'none';
            }

            btnOpenEditYearModal.addEventListener('click', openEditYearModal);
            btnCloseEditYearModal.addEventListener('click', closeEditYearModal);
            btnCancelEditYearModal.addEventListener('click', closeEditYearModal);

            // Add Custom Position Tag
            btnAddCustomPos.addEventListener('click', function() {
                const val = inputAddCustomPos.value.trim();
                if (!val) return;

                const existingInputs = Array.from(editPositionsChecklist.querySelectorAll('input'));
                const found = existingInputs.find(i => i.value.toLowerCase() === val.toLowerCase());
                if (found) {
                    found.checked = true;
                    inputAddCustomPos.value = '';
                    return;
                }

                const label = document.createElement('label');
                label.className = 'pos-check-label';
                label.innerHTML = `<input type="checkbox" name="positions[]" value="${val}" checked> ${val}`;
                editPositionsChecklist.appendChild(label);
                inputAddCustomPos.value = '';
            });

            // Submit Edit Year Form
            editYearForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const yr = inputEditYearVal.value.trim();
                const title = inputEditYearTitle.value.trim();
                const checkedPositions = [];
                editPositionsChecklist.querySelectorAll('input[name="positions[]"]:checked').forEach(chk => {
                    checkedPositions.push(chk.value);
                });

                if (checkedPositions.length === 0) {
                    alert('Please select at least one electoral position to include.');
                    return;
                }

                const submitBtn = document.getElementById('btnSubmitEditYear');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Saving Changes...';

                fetch("{{ route('admin.electoral.update_year') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        year: yr,
                        title: title,
                        positions: checkedPositions
                    })
                })
                .then(r => r.json())
                .then(res => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Save Changes';

                    if (res.success) {
                        positionsByYear[yr] = res.positions || checkedPositions;
                        if (res.dataset && res.dataset[yr]) {
                            fullDataset[yr] = res.dataset[yr];
                        }

                        // Update current position if active year is being edited
                        if (currentYear === yr) {
                            if (!checkedPositions.includes(currentPosition)) {
                                currentPosition = checkedPositions[0];
                            }
                            populatePositions(currentYear, selectPosition, currentPosition);
                            refreshDashboard();
                        }

                        closeEditYearModal();
                        showToast(`Election Year ${yr} positions updated successfully!`);
                    } else {
                        alert(res.message || 'Failed to update election year.');
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Save Changes';
                    console.error("Update Year error:", err);
                    alert("Error communicating with database. Please try again.");
                });
            });

            // ==========================================
            // MODAL A3: DELETE ELECTION YEAR
            // ==========================================
            function openDeleteYearModal() {
                if (availableYears.length <= 1) {
                    alert("Cannot delete the only remaining election year in database.");
                    return;
                }
                deleteYearDisplayLabel.textContent = currentYear;
                deleteYearModalBackdrop.style.display = 'flex';
            }

            function closeDeleteYearModal() {
                deleteYearModalBackdrop.style.display = 'none';
            }

            btnOpenDeleteYearModal.addEventListener('click', openDeleteYearModal);
            btnCloseDeleteYearModal.addEventListener('click', closeDeleteYearModal);
            btnCancelDeleteYearModal.addEventListener('click', closeDeleteYearModal);

            btnConfirmDeleteYear.addEventListener('click', function() {
                const yrToDelete = currentYear;
                btnConfirmDeleteYear.disabled = true;
                btnConfirmDeleteYear.textContent = 'Deleting...';

                fetch("{{ route('admin.electoral.delete_year') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        year: yrToDelete
                    })
                })
                .then(r => r.json())
                .then(res => {
                    btnConfirmDeleteYear.disabled = false;
                    btnConfirmDeleteYear.textContent = 'Yes, Delete Year';

                    if (res.success) {
                        availableYears = availableYears.filter(y => y !== yrToDelete);
                        delete positionsByYear[yrToDelete];
                        delete fullDataset[yrToDelete];

                        // Switch to the latest available year
                        const nextYear = availableYears[availableYears.length - 1] || '2025';
                        currentYear = nextYear;
                        const newPositions = positionsByYear[currentYear] || ['Mayor'];
                        currentPosition = newPositions[0] || 'Mayor';

                        renderYearPills();
                        populatePositions(currentYear, selectPosition, currentPosition);
                        refreshDashboard();

                        closeDeleteYearModal();
                        showToast(`Election Year ${yrToDelete} deleted from database!`);
                    } else {
                        alert(res.message || 'Failed to delete election year.');
                    }
                })
                .catch(err => {
                    btnConfirmDeleteYear.disabled = false;
                    btnConfirmDeleteYear.textContent = 'Yes, Delete Year';
                    console.error("Delete Year error:", err);
                    alert("Error deleting election year from database.");
                });
            });

            // ==========================================
            // MODAL B: ENCODE / EDIT ELECTION DATA (DATABASE)
            // ==========================================
            function openEncodeModal() {
                modalYear.value = currentYear;
                populatePositions(currentYear, modalPosition, currentPosition);
                modalBarangay.value = selectedBarangayId;

                populateModalFields();
                encodeModalBackdrop.style.display = 'flex';
            }

            function closeEncodeModal() {
                encodeModalBackdrop.style.display = 'none';
            }

            btnOpenEncodeModal.addEventListener('click', openEncodeModal);
            btnCloseEncodeModal.addEventListener('click', closeEncodeModal);
            btnCancelEncodeModal.addEventListener('click', closeEncodeModal);

            modalYear.addEventListener('change', function() {
                populatePositions(this.value, modalPosition);
                populateModalFields();
            });

            modalPosition.addEventListener('change', populateModalFields);
            modalBarangay.addEventListener('change', populateModalFields);

            function populateModalFields() {
                const yr = modalYear.value;
                const pos = modalPosition.value;
                const bId = modalBarangay.value;

                const yrData = fullDataset[yr] || {};
                const posData = yrData[pos] || {};
                const bData = posData[bId] || {
                    registered_voters: 5500,
                    actual_votes: 4500,
                    candidates: [{
                            name: 'Candidate 1',
                            color: '#075998',
                            votes: 2500
                        },
                        {
                            name: 'Candidate 2',
                            color: '#E53935',
                            votes: 2000
                        }
                    ]
                };

                modalRegVoters.value = bData.registered_voters || 5500;
                modalActualVotes.value = bData.actual_votes || 4500;

                modalCandidatesContainer.innerHTML = '';
                const candidates = bData.candidates || [];
                if (candidates.length === 0) {
                    addCandidateRow('Candidate 1', '#075998', 0);
                    addCandidateRow('Candidate 2', '#E53935', 0);
                } else {
                    candidates.forEach(c => addCandidateRow(c.name, c.color, c.votes));
                }
            }

            function addCandidateRow(name = '', color = '#075998', votes = 0) {
                const row = document.createElement('div');
                row.className = 'candidate-input-row';
                row.innerHTML = `
                    <input type="text" class="form-control-custom c-name-input" placeholder="Candidate Full Name" value="${name}" required>
                    <input type="color" class="form-control-custom c-color-input" value="${color}" style="height:42px; padding:2px; cursor:pointer;">
                    <input type="number" class="form-control-custom c-votes-input" placeholder="Votes" value="${votes}" min="0" required>
                    <button type="button" class="btn-remove-row" title="Remove">&times;</button>
                `;

                row.querySelector('.btn-remove-row').addEventListener('click', function() {
                    if (modalCandidatesContainer.children.length > 1) {
                        row.remove();
                    } else {
                        alert('You must have at least one candidate record.');
                    }
                });

                modalCandidatesContainer.appendChild(row);
            }

            btnAddCandidateRow.addEventListener('click', function() {
                const defaultColors = ['#075998', '#E53935', '#2E7D32', '#FF9800', '#8E24AA', '#2196F3'];
                const count = modalCandidatesContainer.children.length;
                const pickColor = defaultColors[count % defaultColors.length];
                addCandidateRow('', pickColor, 0);
            });

            // Submit & Save Data via AJAX to MySQL Database
            encodeDataForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const yr = modalYear.value;
                const pos = modalPosition.value;
                const bId = Number(modalBarangay.value);
                const regVoters = Number(modalRegVoters.value);
                const actualVotes = Number(modalActualVotes.value);

                const candidatesPayload = [];
                const rows = modalCandidatesContainer.querySelectorAll('.candidate-input-row');
                rows.forEach(r => {
                    const cName = r.querySelector('.c-name-input').value.trim();
                    const cColor = r.querySelector('.c-color-input').value;
                    const cVotes = Number(r.querySelector('.c-votes-input').value) || 0;
                    if (cName) {
                        candidatesPayload.push({
                            name: cName,
                            color: cColor,
                            votes: cVotes
                        });
                    }
                });

                if (candidatesPayload.length === 0) {
                    alert('Please provide at least one candidate with a name.');
                    return;
                }

                const submitBtn = document.getElementById('btnSubmitEncodeData');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Saving to Database...';

                fetch("{{ route('admin.electoral.save_data') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            year: yr,
                            position: pos,
                            barangay_id: bId,
                            registered_voters: regVoters,
                            actual_votes: actualVotes,
                            candidates: candidatesPayload
                        })
                    })
                    .then(r => r.json())
                    .then(res => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save to Database & Update Map';

                        if (res.success) {
                            // Update local dataset
                            if (!fullDataset[yr]) fullDataset[yr] = {};
                            if (!fullDataset[yr][pos]) fullDataset[yr][pos] = {};
                            fullDataset[yr][pos][bId] = res.updated_record;

                            // Switch view to saved data
                            currentYear = yr;
                            renderYearPills();
                            populatePositions(yr, selectPosition, pos);
                            currentPosition = pos;
                            selectedBarangayId = bId;

                            closeEncodeModal();
                            refreshDashboard();
                            showToast(`Brgy. ${barangayNames[bId]} data saved to Database!`);
                        } else {
                            alert(res.message || 'Failed to save electoral data.');
                        }
                    })
                    .catch(err => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save to Database & Update Map';
                        console.error("Save error:", err);
                        alert("Error saving data to database. Please check connection.");
                    });
            });

            // Initialize Dashboard
            updateTransform();
            renderYearPills();
            populatePositions(currentYear, selectPosition, currentPosition);
            refreshDashboard();
            selectBarangay(1);
        });
    </script>
@endsection
