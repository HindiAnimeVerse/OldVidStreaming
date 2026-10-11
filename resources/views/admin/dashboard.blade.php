@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('styles')
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        animation: fadeIn 0.4s ease backwards;
        transition: border-color 0.2s;
    }

    .stat-card:hover {
        border-color: rgba(255, 255, 255, 0.12);
    }

    .stat-card:nth-child(1) {
        animation-delay: 0.05s;
    }

    .stat-card:nth-child(2) {
        animation-delay: 0.1s;
    }

    .stat-card:nth-child(3) {
        animation-delay: 0.15s;
    }

    .stat-card:nth-child(4) {
        animation-delay: 0.2s;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon svg {
        width: 22px;
        height: 22px;
        stroke: var(--white);
        stroke-width: 1.5;
        fill: none;
    }

    .stat-content {
        flex: 1;
        min-width: 0;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Card */
    .card {
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        animation: fadeIn 0.4s ease backwards;
        animation-delay: 0.25s;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .card-title {
        font-size: 0.9rem;
        font-weight: 600;
    }

    .card-action {
        font-size: 0.75rem;
        color: var(--light-gray);
        transition: opacity 0.2s;
    }

    .card-action:hover {
        opacity: 0.8;
    }

    .card-body {
        padding: 0;
    }

    /* Users Table */
    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th,
    .users-table td {
        padding: 0.75rem 1.25rem;
        text-align: left;
        font-size: 0.8rem;
    }

    .users-table th {
        color: var(--gray);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.65rem;
        letter-spacing: 0.05em;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .users-table tr:not(:last-child) td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--dark-3), var(--dark-4));
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .user-meta {
        min-width: 0;
    }

    .user-name {
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-email {
        font-size: 0.7rem;
        color: var(--gray);
    }

    .badge {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-admin {
        background: rgba(255, 255, 255, 0.1);
        color: var(--white);
    }

    .badge-user {
        background: rgba(255, 255, 255, 0.05);
        color: var(--gray);
    }

    .date-cell {
        color: var(--gray);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 1rem 1.25rem;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
        }

        .stat-icon svg {
            width: 20px;
            height: 20px;
        }

        .stat-value {
            font-size: 1.25rem;
        }

        .users-table th:nth-child(3),
        .users-table td:nth-child(3) {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $stats['total_users'] ?? 0 }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24">
                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                <polyline points="14,2 14,8 20,8" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Files</div>
            <div class="stat-value">{{ $stats['total_files'] ?? 0 }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Storage Used</div>
            <div class="stat-value">{{ $stats['storage_used'] ?? '0 GB' }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24">
                <polyline points="22,12 18,12 15,21 9,3 6,12 2,12" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Bandwidth</div>
            <div class="stat-value">{{ $stats['bandwidth_used'] ?? '0 GB' }}</div>
        </div>
    </div>
</div>

<!-- Recent Users -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Recent Users</h2>
        <a href="{{ route('admin.users') }}" class="card-action">View All</a>
    </div>
    <div class="card-body">
        <table class="users-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentUsers as $user)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                            <div class="user-meta">
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $user->is_admin ? 'badge-admin' : 'badge-user' }}">
                            {{ $user->is_admin ? 'Admin' : 'User' }}
                        </span>
                    </td>
                    <td class="date-cell">{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: var(--gray); padding: 2rem;">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection