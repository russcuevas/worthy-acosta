@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('user_name', 'Administrator')
@section('user_role_label', 'Admin Portal')
@section('user_initials', 'AD')

@section('role_badge')
    <span class="role-badge-pill role-badge-admin">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
        </svg>
        Admin Mode
    </span>
@endsection

@section('content')
    <!-- Dashboard content space -->
@endsection
