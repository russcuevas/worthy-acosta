@extends('layouts.app')

@section('title', 'Assistant Dashboard')
@section('user_name', 'Assistant User')
@section('user_role_label', 'Assistant Portal')
@section('user_initials', 'AS')

@section('role_badge')
    <span class="role-badge-pill role-badge-assistant">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
        </svg>
        Assistant Mode
    </span>
@endsection

@section('content')
    <!-- Dashboard content space -->
@endsection
