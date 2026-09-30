@extends('layouts.app')

@section('title', 'User Accounts - Worthy Acosta')
@section('user_name', 'Administrator')
@section('user_role_label', 'Admin Portal')
@section('user_initials', 'AD')

@section('styles')
    <style>
        /* Controls Header */
        .users-controls-header {
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

        .btn-create-account {
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

        .btn-create-account:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(7, 89, 152, 0.35);
        }

        /* KPI Summary Cards */
        .users-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .kpi-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all var(--transition-fast);
        }

        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .kpi-icon-badge {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .kpi-icon-all {
            background: rgba(7, 89, 152, 0.1);
            color: var(--color-primary-blue);
        }

        .kpi-icon-admin {
            background: rgba(15, 23, 42, 0.1);
            color: #0F172A;
        }

        .kpi-icon-assistant {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
        }

        .kpi-data h3 {
            margin: 0 0 2px 0;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--color-deep-navy);
            line-height: 1.1;
        }

        .kpi-data p {
            margin: 0;
            font-size: 0.80rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
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
            gap: 12px;
            flex-wrap: wrap;
            flex: 1;
        }

        .search-input-wrapper {
            position: relative;
            min-width: 260px;
            max-width: 380px;
            flex: 1;
        }

        .search-input-wrapper svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            pointer-events: none;
        }

        .users-search-input {
            width: 100%;
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            padding: 8px 14px 8px 36px;
            font-size: 0.86rem;
            color: var(--color-deep-navy);
            outline: none;
            transition: all var(--transition-fast);
            box-sizing: border-box;
        }

        .users-search-input:focus {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.12);
        }

        .filter-select {
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 0.86rem;
            color: var(--color-deep-navy);
            outline: none;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .filter-select:focus {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
        }

        .btn-refresh {
            background: #F1F5F9;
            border: 1px solid #CBD5E1;
            color: #475569;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all var(--transition-fast);
        }

        .btn-refresh:hover {
            background: #E2E8F0;
            color: #0F172A;
        }

        /* Table Container */
        .table-container-card {
            background: #FFFFFF;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .users-table th {
            background: #F8FAFC;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 13px 18px;
            border-bottom: 1.5px solid #E2E8F0;
            white-space: nowrap;
        }

        .users-table td {
            padding: 14px 18px;
            font-size: 0.88rem;
            color: #334155;
            border-bottom: 1px solid #F1F5F9;
            vertical-align: middle;
        }

        .users-table tbody tr {
            transition: background 0.15s ease;
        }

        .users-table tbody tr:hover {
            background: #F8FAFC;
        }

        /* User Profile Info in Table */
        .user-table-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-table-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-primary-blue), #38BDF8);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.84rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .user-table-avatar.avatar-admin {
            background: linear-gradient(135deg, #092C4C, #075998);
        }

        .user-table-avatar.avatar-assistant {
            background: linear-gradient(135deg, #059669, #10B981);
        }

        .user-table-name {
            font-weight: 700;
            color: #0F172A;
            font-size: 0.90rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .badge-you {
            display: inline-flex;
            align-items: center;
            padding: 1px 7px;
            background: #EFF6FF;
            color: #1D4ED8;
            border: 1px solid #BFDBFE;
            border-radius: 9999px;
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .username-pill {
            display: inline-block;
            background: #F1F5F9;
            color: #475569;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 700;
            font-family: monospace;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .role-badge-admin {
            background: #EFF6FF;
            color: #1E40AF;
            border: 1px solid #BFDBFE;
        }

        .role-badge-assistant {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        /* Actions buttons */
        .table-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action-edit {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            color: #1D4ED8;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .btn-action-edit:hover {
            background: #1D4ED8;
            color: #FFFFFF;
            border-color: #1D4ED8;
        }

        .btn-action-delete {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #DC2626;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .btn-action-delete:hover:not(:disabled) {
            background: #DC2626;
            color: #FFFFFF;
            border-color: #DC2626;
        }

        .btn-action-delete:disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }

        /* Modals Custom */
        .modal-backdrop-users {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal-dialog-users {
            background: #FFFFFF;
            border-radius: 16px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalFadeIn 0.2s ease-out;
            overflow: hidden;
            border: 1px solid #E2E8F0;
        }

        .modal-header-users {
            padding: 20px 24px;
            background: linear-gradient(135deg, var(--color-deep-navy), var(--color-primary-blue));
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header-users.header-danger {
            background: linear-gradient(135deg, #7F1D1D, #DC2626);
        }

        .modal-header-users h3 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 800;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-close-btn {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #FFFFFF;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 1.3rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            transition: all var(--transition-fast);
        }

        .modal-close-btn:hover {
            background: rgba(220, 38, 38, 0.9);
        }

        .modal-body-users {
            padding: 22px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            max-height: 75vh;
            overflow-y: auto;
        }

        .form-group-modal {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group-modal label {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--color-deep-navy);
        }

        .modal-input {
            width: 100%;
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            padding: 9px 13px;
            font-size: 0.88rem;
            color: #0F172A;
            outline: none;
            transition: all var(--transition-fast);
            box-sizing: border-box;
        }

        .modal-input:focus {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.12);
        }

        /* Role selection radio cards */
        .role-selection-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .role-radio-card {
            border: 1.5px solid #CBD5E1;
            border-radius: 10px;
            padding: 12px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 4px;
            background: #F8FAFC;
            transition: all var(--transition-fast);
            position: relative;
        }

        .role-radio-card:hover {
            border-color: var(--color-primary-blue);
            background: #FFFFFF;
        }

        .role-radio-card.selected {
            border-color: var(--color-primary-blue);
            background: #EFF6FF;
            box-shadow: 0 0 0 2px rgba(7, 89, 152, 0.2);
        }

        .role-radio-card.selected.role-assistant-selected {
            border-color: #10B981;
            background: #ECFDF5;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .role-radio-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 800;
            font-size: 0.88rem;
            color: #0F172A;
        }

        .role-radio-card p {
            margin: 0;
            font-size: 0.74rem;
            color: #64748B;
            line-height: 1.4;
        }

        .modal-footer-users {
            padding: 16px 24px;
            border-top: 1px solid #E2E8F0;
            background: #FAFBFD;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-modal-cancel {
            background: #F1F5F9;
            border: 1px solid #CBD5E1;
            color: #475569;
            border-radius: 8px;
            padding: 9px 18px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .btn-modal-cancel:hover {
            background: #E2E8F0;
            color: #0F172A;
        }

        .btn-modal-save {
            background: linear-gradient(135deg, var(--color-primary-blue), var(--color-deep-navy));
            border: none;
            color: #FFFFFF;
            border-radius: 8px;
            padding: 9px 20px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(7, 89, 152, 0.25);
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-modal-save:hover {
            box-shadow: 0 4px 12px rgba(7, 89, 152, 0.35);
            transform: translateY(-1px);
        }

        .btn-modal-danger {
            background: linear-gradient(135deg, #DC2626, #991B1B);
            border: none;
            color: #FFFFFF;
            border-radius: 8px;
            padding: 9px 20px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(220, 38, 38, 0.25);
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-modal-danger:hover {
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35);
            transform: translateY(-1px);
        }

        /* Loading Spinner */
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.85;
        }

        .spinner-sm {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.35);
            border-top-color: #FFFFFF;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Password Input Wrapper */
        .input-password-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .btn-toggle-eye {
            position: absolute;
            right: 10px;
            background: transparent;
            border: none;
            color: #64748B;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .btn-toggle-eye:hover {
            color: var(--color-primary-blue);
        }

        /* Empty state */
        .empty-state-row {
            text-align: center;
            padding: 40px 20px;
            color: #94A3B8;
        }

        .empty-state-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 10px;
            color: #CBD5E1;
        }
    </style>
@endsection

@section('content')
    <div class="events-main-wrapper">
        <!-- Top Controls Header -->
        <div class="users-controls-header">
            <div class="header-title-bar-row">
                <div class="header-title-left">
                    <div>
                        <h1>
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            User Accounts Management
                        </h1>
                        <p>Add, edit, assign roles, and manage access for Administrators and Assistant officers</p>
                    </div>
                </div>
                <div class="header-actions-right">
                    <button type="button" class="btn-create-account" id="btnOpenAddUserModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                            viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Create New Account</span>
                    </button>
                </div>
            </div>

            <!-- Filter Controls Ribbon -->
            <div class="filter-controls-row">
                <div class="filter-left-group">
                    <div class="search-input-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <input type="text" id="filterSearch" class="users-search-input"
                            placeholder="Search by name, username, or email...">
                    </div>

                    <select id="filterRole" class="filter-select">
                        <option value="all">All Roles</option>
                        <option value="admin">Administrator</option>
                        <option value="assistant">Assistant Officer</option>
                    </select>

                    <button type="button" class="btn-refresh" id="btnRefreshUsers" title="Refresh User List">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary KPI Cards -->
        <div class="users-kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon-badge kpi-icon-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.999-3.199a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
                <div class="kpi-data">
                    <h3 id="statTotalUsers">{{ $totalUsers }}</h3>
                    <p>Total User Accounts</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon-badge kpi-icon-admin">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                </div>
                <div class="kpi-data">
                    <h3 id="statAdminUsers">{{ $adminCount }}</h3>
                    <p>Administrators</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon-badge kpi-icon-assistant">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <div class="kpi-data">
                    <h3 id="statAssistantUsers">{{ $assistantCount }}</h3>
                    <p>Assistant Officers</p>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="table-container-card">
            <div style="overflow-x: auto;">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Account User</th>
                            <th>Username</th>
                            <th>Email Address</th>
                            <th>Assigned Role</th>
                            <th>Created Date</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <tr>
                            <td colspan="6" class="empty-state-row">
                                <div class="spinner-sm"
                                    style="margin: 0 auto 8px auto; border-color: #CBD5E1; border-top-color: var(--color-primary-blue);">
                                </div>
                                <span>Loading account records...</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal 1: Create New User Account -->
    <div class="modal-backdrop-users" id="modalAddUser">
        <div class="modal-dialog-users">
            <div class="modal-header-users">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                    Create New Account
                </h3>
                <button type="button" class="modal-close-btn" data-close-modal>&times;</button>
            </div>

            <form id="formAddUser">
                @csrf
                <div class="modal-body-users">
                    <div class="form-group-modal">
                        <label for="add_name">Full Name <span style="color: #EF4444;">*</span></label>
                        <input type="text" id="add_name" name="name" class="modal-input"
                            placeholder="e.g. Maria Santos" required>
                    </div>

                    <div class="form-group-modal">
                        <label for="add_username">Username <span style="color: #EF4444;">*</span></label>
                        <input type="text" id="add_username" name="username" class="modal-input"
                            placeholder="e.g. msantos" required autocomplete="off">
                    </div>

                    <div class="form-group-modal">
                        <label for="add_email">Email Address <span style="color: #EF4444;">*</span></label>
                        <input type="email" id="add_email" name="email" class="modal-input"
                            placeholder="e.g. msantos@worthyacosta.ph" required>
                    </div>

                    <div class="form-group-modal">
                        <label>Account Role & Permissions <span style="color: #EF4444;">*</span></label>
                        <div class="role-selection-grid">
                            <div class="role-radio-card selected" onclick="selectAddRole('admin', this)">
                                <div class="role-radio-header">
                                    <span>Administrator</span>
                                    <input type="radio" name="role" value="admin" checked
                                        style="accent-color: var(--color-primary-blue);">
                                </div>
                                <p>Full system access to all modules, settings, maps, and account controls.</p>
                            </div>
                            <div class="role-radio-card" onclick="selectAddRole('assistant', this)">
                                <div class="role-radio-header">
                                    <span>Assistant</span>
                                    <input type="radio" name="role" value="assistant"
                                        style="accent-color: #10B981;">
                                </div>
                                <p>Operational data encoding and record views for daily office assistance.</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-modal">
                        <label for="add_password">Account Password <span style="color: #EF4444;">*</span></label>
                        <div class="input-password-wrap">
                            <input type="password" id="add_password" name="password" class="modal-input"
                                placeholder="At least 6 characters" required autocomplete="new-password">
                            <button type="button" class="btn-toggle-eye" onclick="toggleInputEye('add_password')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group-modal">
                        <label for="add_password_confirmation">Confirm Password <span
                                style="color: #EF4444;">*</span></label>
                        <div class="input-password-wrap">
                            <input type="password" id="add_password_confirmation" name="password_confirmation"
                                class="modal-input" placeholder="Re-type password" required autocomplete="new-password">
                            <button type="button" class="btn-toggle-eye"
                                onclick="toggleInputEye('add_password_confirmation')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer-users">
                    <button type="button" class="btn-modal-cancel" data-close-modal>Cancel</button>
                    <button type="submit" class="btn-modal-save" id="btnAddSubmit">
                        <span>Create Account</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Edit User Account -->
    <div class="modal-backdrop-users" id="modalEditUser">
        <div class="modal-dialog-users">
            <div class="modal-header-users">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                    </svg>
                    Edit Account: <span id="editUserTitleName" style="font-weight: 700; opacity: 0.9;"></span>
                </h3>
                <button type="button" class="modal-close-btn" data-close-modal>&times;</button>
            </div>

            <form id="formEditUser">
                @csrf
                <input type="hidden" id="edit_user_id">

                <div class="modal-body-users">
                    <div class="form-group-modal">
                        <label for="edit_name">Full Name <span style="color: #EF4444;">*</span></label>
                        <input type="text" id="edit_name" name="name" class="modal-input" required>
                    </div>

                    <div class="form-group-modal">
                        <label for="edit_username">Username <span style="color: #EF4444;">*</span></label>
                        <input type="text" id="edit_username" name="username" class="modal-input" required
                            autocomplete="off">
                    </div>

                    <div class="form-group-modal">
                        <label for="edit_email">Email Address <span style="color: #EF4444;">*</span></label>
                        <input type="email" id="edit_email" name="email" class="modal-input" required>
                    </div>

                    <div class="form-group-modal">
                        <label>Account Role & Permissions <span style="color: #EF4444;">*</span></label>
                        <div class="role-selection-grid">
                            <div class="role-radio-card" id="editRoleAdminCard" onclick="selectEditRole('admin')">
                                <div class="role-radio-header">
                                    <span>Administrator</span>
                                    <input type="radio" name="role" id="edit_role_admin" value="admin"
                                        style="accent-color: var(--color-primary-blue);">
                                </div>
                                <p>Full system access to all modules, settings, and maps.</p>
                            </div>
                            <div class="role-radio-card" id="editRoleAssistantCard"
                                onclick="selectEditRole('assistant')">
                                <div class="role-radio-header">
                                    <span>Assistant</span>
                                    <input type="radio" name="role" id="edit_role_assistant" value="assistant"
                                        style="accent-color: #10B981;">
                                </div>
                                <p>Operational data encoding and record views.</p>
                            </div>
                        </div>
                    </div>

                    <div style="border-top: 1px dashed #CBD5E1; padding-top: 14px; margin-top: 4px;">
                        <div style="font-weight: 700; font-size: 0.82rem; color: #475569; margin-bottom: 8px;">
                            Change Password (Optional)
                        </div>
                        <p style="font-size: 0.74rem; color: #94A3B8; margin: 0 0 10px 0;">Leave password fields blank if
                            you do not want to change the existing password.</p>
                    </div>

                    <div class="form-group-modal">
                        <label for="edit_password">New Password</label>
                        <div class="input-password-wrap">
                            <input type="password" id="edit_password" name="password" class="modal-input"
                                placeholder="Leave blank to keep unchanged" autocomplete="new-password">
                            <button type="button" class="btn-toggle-eye" onclick="toggleInputEye('edit_password')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group-modal">
                        <label for="edit_password_confirmation">Confirm New Password</label>
                        <div class="input-password-wrap">
                            <input type="password" id="edit_password_confirmation" name="password_confirmation"
                                class="modal-input" placeholder="Re-type new password" autocomplete="new-password">
                            <button type="button" class="btn-toggle-eye"
                                onclick="toggleInputEye('edit_password_confirmation')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer-users">
                    <button type="button" class="btn-modal-cancel" data-close-modal>Cancel</button>
                    <button type="submit" class="btn-modal-save" id="btnEditSubmit">
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 3: Delete User Confirmation -->
    <div class="modal-backdrop-users" id="modalDeleteUser">
        <div class="modal-dialog-users">
            <div class="modal-header-users header-danger">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    Delete User Account
                </h3>
                <button type="button" class="modal-close-btn" data-close-modal>&times;</button>
            </div>

            <form id="formDeleteUser">
                @csrf
                <input type="hidden" id="delete_user_id">
                <div class="modal-body-users">
                    <div style="text-align: center; padding: 12px 0;">
                        <div
                            style="width: 56px; height: 56px; border-radius: 50%; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px auto;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <h4 style="margin: 0 0 8px 0; color: #0F172A; font-size: 1.1rem; font-weight: 800;">Are you sure?
                        </h4>
                        <p style="margin: 0; color: #64748B; font-size: 0.88rem; line-height: 1.5;">
                            You are about to permanently delete the account for <strong id="deleteUserName"
                                style="color: #0F172A;"></strong> (<span id="deleteUserRole"
                                style="font-weight: 700; text-transform: capitalize;"></span>).
                        </p>
                        <p style="margin: 8px 0 0 0; font-size: 0.76rem; color: #DC2626; font-weight: 600;">This action
                            cannot be undone.</p>
                    </div>
                </div>

                <div class="modal-footer-users">
                    <button type="button" class="btn-modal-cancel" data-close-modal>Cancel</button>
                    <button type="submit" class="btn-modal-danger" id="btnDeleteSubmit">
                        <span>Yes, Delete Account</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Floating Toast Container -->
    <div class="bottom-toast-container" id="usersToastContainer"></div>

@endsection

@section('scripts')
    <script>
        // Global User State
        let userRecords = [];

        // Helper Toast Notification
        function showUserToast(type, message) {
            const container = document.getElementById('usersToastContainer');
            if (!container) return;

            const isSuccess = (type === 'success');
            const iconSvg = isSuccess ?
                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#10B981"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>' :
                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="#EF4444"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>';

            const toast = document.createElement('div');
            toast.className = `bottom-toast toast-${isSuccess ? 'success' : 'error'}`;
            toast.innerHTML = `
            <span class="toast-icon">${iconSvg}</span>
            <span class="toast-text">${message}</span>
            <button type="button" class="toast-close-btn" onclick="this.closest('.bottom-toast').remove()">&times;</button>
        `;
            container.appendChild(toast);

            setTimeout(() => {
                if (toast && toast.parentNode) {
                    toast.classList.add('hide');
                    setTimeout(() => toast.remove(), 350);
                }
            }, 4000);
        }

        // Toggle Eye Visibility Helper
        function toggleInputEye(inputId) {
            const input = document.getElementById(inputId);
            if (input) {
                input.type = (input.type === 'password') ? 'text' : 'password';
            }
        }

        // Role Selection Helpers for Add Modal
        function selectAddRole(role, cardEl) {
            document.querySelectorAll('#modalAddUser .role-radio-card').forEach(c => {
                c.classList.remove('selected', 'role-assistant-selected');
            });
            if (role === 'assistant') {
                cardEl.classList.add('selected', 'role-assistant-selected');
            } else {
                cardEl.classList.add('selected');
            }
            const radio = cardEl.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        }

        // Role Selection Helpers for Edit Modal
        function selectEditRole(role) {
            const adminCard = document.getElementById('editRoleAdminCard');
            const assistantCard = document.getElementById('editRoleAssistantCard');
            const adminRadio = document.getElementById('edit_role_admin');
            const assistantRadio = document.getElementById('edit_role_assistant');

            if (role === 'assistant') {
                adminCard.classList.remove('selected');
                assistantCard.classList.add('selected', 'role-assistant-selected');
                assistantRadio.checked = true;
            } else {
                assistantCard.classList.remove('selected', 'role-assistant-selected');
                adminCard.classList.add('selected');
                adminRadio.checked = true;
            }
        }

        // Fetch and Render Users Data
        async function loadUsersData() {
            const searchVal = document.getElementById('filterSearch').value;
            const roleVal = document.getElementById('filterRole').value;

            const tableBody = document.getElementById('usersTableBody');
            tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="empty-state-row">
                    <div class="spinner-sm" style="margin: 0 auto 8px auto; border-color: #CBD5E1; border-top-color: var(--color-primary-blue);"></div>
                    <span>Loading account records...</span>
                </td>
            </tr>
        `;

            try {
                const params = new URLSearchParams({
                    search: searchVal,
                    role: roleVal
                });
                const response = await fetch(`{{ route('admin.users.data') }}?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const res = await response.json();

                if (res.success) {
                    userRecords = res.data;

                    // Update KPI Cards
                    if (res.stats) {
                        document.getElementById('statTotalUsers').textContent = res.stats.total;
                        document.getElementById('statAdminUsers').textContent = res.stats.admins;
                        document.getElementById('statAssistantUsers').textContent = res.stats.assistants;
                    }

                    renderUsersTable(userRecords);
                }
            } catch (error) {
                tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="empty-state-row" style="color: #EF4444;">
                        Failed to load user accounts. Please try again.
                    </td>
                </tr>
            `;
            }
        }

        // Render Table Rows
        function renderUsersTable(users) {
            const tableBody = document.getElementById('usersTableBody');
            if (!users || users.length === 0) {
                tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="empty-state-row">
                        <svg class="empty-state-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <div style="font-weight: 700; color: #475569; margin-bottom: 4px;">No user accounts found</div>
                        <div style="font-size: 0.80rem;">Try changing your search keywords or filter.</div>
                    </td>
                </tr>
            `;
                return;
            }

            let html = '';
            users.forEach(u => {
                const isAdmin = (u.role === 'admin');
                const avatarClass = isAdmin ? 'avatar-admin' : 'avatar-assistant';
                const roleBadgeClass = isAdmin ? 'role-badge-admin' : 'role-badge-assistant';
                const roleIcon = isAdmin ?
                    '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>' :
                    '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>';

                const youBadge = u.is_current_user ? '<span class="badge-you">You</span>' : '';
                const deleteDisabled = u.is_current_user ?
                    'disabled title="You cannot delete your own logged-in account"' : 'title="Delete account"';

                html += `
                <tr>
                    <td>
                        <div class="user-table-cell">
                            <div class="user-table-avatar ${avatarClass}">${u.initials}</div>
                            <div>
                                <div class="user-table-name">
                                    <span>${u.name}</span>
                                    ${youBadge}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="username-pill">@${u.username || 'user'}</span>
                    </td>
                    <td>
                        <span style="color: #334155; font-weight: 500;">${u.email}</span>
                    </td>
                    <td>
                        <span class="role-badge ${roleBadgeClass}">
                            ${roleIcon}
                            ${u.role_label}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 0.80rem; color: #64748B;">${u.created_at_formatted}</span>
                    </td>
                    <td>
                        <div class="table-actions" style="justify-content: flex-end;">
                            <button type="button" class="btn-action-edit" title="Edit account" onclick="openEditModal(${u.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                </svg>
                            </button>
                            <button type="button" class="btn-action-delete" ${deleteDisabled} onclick="openDeleteModal(${u.id}, '${escapeHtml(u.name)}', '${u.role}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            });
            tableBody.innerHTML = html;
        }

        function escapeHtml(str) {
            return (str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
        }

        // Modal Control Helpers
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        }

        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = this.closest('.modal-backdrop-users');
                if (modal) modal.style.display = 'none';
            });
        });

        document.querySelectorAll('.modal-backdrop-users').forEach(backdrop => {
            backdrop.addEventListener('click', function(e) {
                if (e.target === backdrop) {
                    backdrop.style.display = 'none';
                }
            });
        });

        // 1. Open Add User Modal
        document.getElementById('btnOpenAddUserModal')?.addEventListener('click', () => {
            const form = document.getElementById('formAddUser');
            if (form) form.reset();
            selectAddRole('admin', document.querySelector('#modalAddUser .role-radio-card'));
            openModal('modalAddUser');
            setTimeout(() => document.getElementById('add_name')?.focus(), 100);
        });

        // 2. Submit Add User Form
        document.getElementById('formAddUser')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnAddSubmit');
            if (btn.disabled) return;

            const formData = new FormData(this);

            btn.disabled = true;
            btn.classList.add('btn-loading');
            btn.innerHTML = `<div class="spinner-sm"></div><span>Creating Account...</span>`;

            try {
                const response = await fetch("{{ route('admin.users.store') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });
                const data = await response.json();

                if (response.ok && data.success) {
                    closeModal('modalAddUser');
                    showUserToast('success', data.message);
                    loadUsersData();
                } else {
                    let errorMsg = data.message || 'Failed to create account.';
                    if (data.errors) {
                        const firstKey = Object.keys(data.errors)[0];
                        errorMsg = data.errors[firstKey][0];
                    }
                    showUserToast('error', errorMsg);
                }
            } catch (err) {
                showUserToast('error', 'Network or server error while creating account.');
            } finally {
                btn.disabled = false;
                btn.classList.remove('btn-loading');
                btn.innerHTML = `<span>Create Account</span>`;
            }
        });

        // 3. Open Edit Modal
        async function openEditModal(userId) {
            try {
                const response = await fetch(`{{ url('admin/users/record') }}/${userId}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();

                if (data.success && data.user) {
                    const u = data.user;
                    document.getElementById('edit_user_id').value = u.id;
                    document.getElementById('edit_name').value = u.name;
                    document.getElementById('edit_username').value = u.username || '';
                    document.getElementById('edit_email').value = u.email;
                    document.getElementById('edit_password').value = '';
                    document.getElementById('edit_password_confirmation').value = '';
                    document.getElementById('editUserTitleName').textContent = u.name;

                    selectEditRole(u.role);
                    openModal('modalEditUser');
                }
            } catch (err) {
                showUserToast('error', 'Failed to retrieve account details.');
            }
        }

        // 4. Submit Edit User Form
        document.getElementById('formEditUser')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const userId = document.getElementById('edit_user_id').value;
            const btn = document.getElementById('btnEditSubmit');
            if (btn.disabled || !userId) return;

            const formData = new FormData(this);

            btn.disabled = true;
            btn.classList.add('btn-loading');
            btn.innerHTML = `<div class="spinner-sm"></div><span>Saving Changes...</span>`;

            try {
                const response = await fetch(`{{ url('admin/users/update') }}/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });
                const data = await response.json();

                if (response.ok && data.success) {
                    closeModal('modalEditUser');
                    showUserToast('success', data.message);
                    loadUsersData();
                } else {
                    let errorMsg = data.message || 'Failed to update account.';
                    if (data.errors) {
                        const firstKey = Object.keys(data.errors)[0];
                        errorMsg = data.errors[firstKey][0];
                    }
                    showUserToast('error', errorMsg);
                }
            } catch (err) {
                showUserToast('error', 'Network or server error while updating account.');
            } finally {
                btn.disabled = false;
                btn.classList.remove('btn-loading');
                btn.innerHTML = `<span>Save Changes</span>`;
            }
        });

        // 5. Open Delete Confirmation Modal
        function openDeleteModal(userId, userName, userRole) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteUserRole').textContent = (userRole === 'admin' ? 'Administrator' :
                'Assistant Officer');
            openModal('modalDeleteUser');
        }

        // 6. Submit Delete Form
        document.getElementById('formDeleteUser')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const userId = document.getElementById('delete_user_id').value;
            const btn = document.getElementById('btnDeleteSubmit');
            if (btn.disabled || !userId) return;

            btn.disabled = true;
            btn.classList.add('btn-loading');
            btn.innerHTML = `<div class="spinner-sm"></div><span>Deleting...</span>`;

            try {
                const response = await fetch(`{{ url('admin/users/delete') }}/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await response.json();

                if (response.ok && data.success) {
                    closeModal('modalDeleteUser');
                    showUserToast('success', data.message);
                    loadUsersData();
                } else {
                    showUserToast('error', data.message || 'Failed to delete account.');
                }
            } catch (err) {
                showUserToast('error', 'Network or server error while deleting account.');
            } finally {
                btn.disabled = false;
                btn.classList.remove('btn-loading');
                btn.innerHTML = `<span>Yes, Delete Account</span>`;
            }
        });

        // Filter Listeners
        document.getElementById('filterSearch')?.addEventListener('input', debounce(() => {
            loadUsersData();
        }, 300));

        document.getElementById('filterRole')?.addEventListener('change', () => {
            loadUsersData();
        });

        document.getElementById('btnRefreshUsers')?.addEventListener('click', () => {
            loadUsersData();
        });

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Initial Load
        document.addEventListener('DOMContentLoaded', () => {
            loadUsersData();
        });
    </script>
@endsection
