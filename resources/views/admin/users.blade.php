@extends('layouts.admin')

@section('title', 'Users')
@section('page_title', 'Users Management')

@section('styles')
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .users-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        flex: 1;
        max-width: 320px;
    }

    .search-box svg {
        width: 16px;
        height: 16px;
        stroke: var(--gray);
        stroke-width: 1.5;
        fill: none;
        flex-shrink: 0;
    }

    .search-box input {
        background: none;
        border: none;
        color: var(--white);
        font-size: 0.8rem;
        font-family: inherit;
        width: 100%;
    }

    .search-box input::placeholder {
        color: var(--gray);
    }

    .search-box input:focus {
        outline: none;
    }

    .users-count {
        font-size: 0.8rem;
        color: var(--gray);
    }

    .users-count strong {
        color: var(--white);
    }

    /* Card */
    .card {
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        animation: fadeIn 0.3s ease;
    }

    /* Table */
    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th,
    .users-table td {
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.8rem;
    }

    .users-table th {
        color: var(--gray);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.65rem;
        letter-spacing: 0.05em;
        background: var(--dark-3);
    }

    .users-table tr:not(:last-child) td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    }

    .users-table tr:hover td {
        background: rgba(255, 255, 255, 0.02);
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
        flex-shrink: 0;
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

    .actions-cell {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        padding: 0.35rem 0.6rem;
        background: var(--dark-3);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 500;
        color: var(--light-gray);
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }

    .action-btn:hover {
        background: var(--dark-4);
    }

    .action-btn.danger {
        color: var(--error);
        border-color: rgba(239, 68, 68, 0.2);
    }

    .action-btn.danger:hover {
        background: rgba(239, 68, 68, 0.1);
    }

    .action-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        width: 36px;
        height: 20px;
        display: inline-block;
        vertical-align: middle;
    }

    .toggle-switch input {
        display: none;
    }

    .toggle-slider {
        position: absolute;
        inset: 0;
        background: var(--dark-4);
        border-radius: 20px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .toggle-slider::before {
        content: '';
        position: absolute;
        left: 2px;
        top: 2px;
        width: 16px;
        height: 16px;
        background: var(--gray);
        border-radius: 50%;
        transition: transform 0.2s, background 0.2s;
    }

    .toggle-switch input:checked+.toggle-slider {
        background: rgba(34, 197, 94, 0.2);
    }

    .toggle-switch input:checked+.toggle-slider::before {
        transform: translateX(16px);
        background: var(--success);
    }

    .toggle-switch input:disabled+.toggle-slider {
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 0.25rem;
    }

    .pagination a,
    .pagination span {
        padding: 0.4rem 0.75rem;
        background: var(--dark-3);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 6px;
        font-size: 0.75rem;
        color: var(--gray);
        transition: all 0.2s;
    }

    .pagination a:hover {
        background: var(--dark-4);
        color: var(--white);
    }

    .pagination .active span {
        background: var(--white);
        border-color: var(--white);
        color: var(--black);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--gray);
    }

    @media (max-width: 768px) {

        .users-table th:nth-child(3),
        .users-table td:nth-child(3),
        .users-table th:nth-child(4),
        .users-table td:nth-child(4) {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="users-header">
    <form action="{{ route('admin.users') }}" method="GET" class="search-box">
        <svg viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
        <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}">
    </form>
    <div class="users-count">Total: <strong>{{ $users->total() }}</strong> users</div>
</div>

<div class="card">
    <table class="users-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Photos Access</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr id="user-{{ $user->id }}">
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
                    <span class="badge {{ $user->is_admin ? 'badge-admin' : 'badge-user' }}" id="badge-{{ $user->id }}">
                        {{ $user->is_admin ? 'Admin' : 'User' }}
                    </span>
                </td>
                <td>
                    @if($user->is_admin)
                    <label class="toggle-switch" title="Admins always have access">
                        <input type="checkbox" checked disabled>
                        <span class="toggle-slider"></span>
                    </label>
                    <span style="font-size: 0.75rem; color: var(--gray); margin-left: 0.5rem;">Forced</span>
                    @else
                    <label class="toggle-switch">
                        <input type="checkbox" onchange="togglePhotosAccess({{ $user->id }}, this)" {{ $user->can_use_gpmc ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                    @endif
                </td>
                <td class="date-cell">{{ $user->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="actions-cell">
                        <button class="action-btn" onclick="toggleAdmin({{ $user->id }})" id="toggle-{{ $user->id }}" {{ $user->id === Auth::id() ? 'disabled' : '' }}>
                            {{ $user->is_admin ? 'Revoke Admin' : 'Make Admin' }}
                        </button>
                        <button class="action-btn danger" onclick="deleteUser({{ $user->id }})" {{ $user->id === Auth::id() ? 'disabled' : '' }}>Delete</button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="empty-state">No users found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const baseUrl = '{{ url("/") }}';

    async function toggleAdmin(userId) {
        const btn = document.getElementById(`toggle-${userId}`);
        btn.disabled = true;

        try {
            const res = await fetch(`${baseUrl}/admin/users/${userId}/toggle-admin`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();

            if (data.success) {
                const badge = document.getElementById(`badge-${userId}`);
                badge.className = `badge ${data.is_admin ? 'badge-admin' : 'badge-user'}`;
                badge.textContent = data.is_admin ? 'Admin' : 'User';
                btn.textContent = data.is_admin ? 'Revoke Admin' : 'Make Admin';
                showToast(data.message || 'Role updated successfully', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message || 'Failed to update role', 'error');
            }
        } catch {
            showToast('An error occurred. Please try again.', 'error');
        }

        btn.disabled = false;
    }

    async function togglePhotosAccess(userId, checkbox) {
        // Optimistic UI, but revert on failure
        const originalState = !checkbox.checked;
        checkbox.disabled = true;

        try {
            const res = await fetch(`${baseUrl}/admin/users/${userId}/toggle-cloud`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            });

            if (!res.ok) {
                throw new Error(`Server error: ${res.status}`);
            }

            const data = await res.json();

            if (data.success) {
                showToast(data.message || 'Photos access updated', 'success');
            } else {
                checkbox.checked = originalState;
                showToast(data.message || 'Failed to update access', 'error');
            }
        } catch (err) {
            checkbox.checked = originalState;
            showToast(err.message || 'An error occurred. Please try again.', 'error');
            console.error('Toggle error:', err);
        }

        checkbox.disabled = false;
    }

    async function deleteUser(userId) {
        if (!confirm('Are you sure you want to delete this user?')) return;

        try {
            const res = await fetch(`${baseUrl}/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();

            if (data.success) {
                document.getElementById(`user-${userId}`).remove();
                showToast('User deleted successfully', 'success');
            } else {
                showToast(data.message || 'Failed to delete user', 'error');
            }
        } catch {
            showToast('An error occurred. Please try again.', 'error');
        }
    }
</script>
@endsection