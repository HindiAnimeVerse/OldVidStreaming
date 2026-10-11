@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('page_title', 'Dashboard')

@section('header_actions')
<button class="header-btn">
    <svg viewBox="0 0 24 24">
        <circle cx="11" cy="11" r="8" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
    </svg>
</button>
<button class="header-btn">
    <svg viewBox="0 0 24 24">
        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
    </svg>
    <span class="notification-dot"></span>
</button>
<button class="btn-upload">
    <svg viewBox="0 0 24 24">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
        <polyline points="17,8 12,3 7,8" />
        <line x1="12" y1="3" x2="12" y2="15" />
    </svg>
    <span>Upload</span>
</button>
@endsection

@section('styles')
<style>
    /* ===== ANIMATIONS ===== */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes countUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .page-content {
        animation: fadeIn 0.4s ease;
    }

    /* ===== HEADER BUTTONS ===== */
    .header-btn {
        width: 44px;
        height: 44px;
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
    }

    .header-btn:hover {
        background: var(--dark-3);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .header-btn svg {
        width: 22px;
        height: 22px;
        stroke: var(--light-gray);
        stroke-width: 1.5;
        fill: none;
    }

    .notification-dot {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 8px;
        height: 8px;
        background: var(--error);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    .btn-upload {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.75rem 1.5rem;
        background: var(--white);
        color: var(--black);
        border: none;
        border-radius: 12px;
        font-size: 0.925rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-upload:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
    }

    .btn-upload svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        stroke-width: 2;
        fill: none;
    }

    /* ===== STATS GRID ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: countUp 0.5s ease forwards;
        opacity: 0;
    }

    .stat-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .stat-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .stat-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .stat-card:nth-child(4) {
        animation-delay: 0.4s;
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
        width: 24px;
        height: 24px;
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
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ===== CONTENT GRID ===== */
    .content-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 1.5rem;
    }

    /* ===== CARD ===== */
    .card {
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        overflow: hidden;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 1.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
    }

    .card-action {
        font-size: 0.85rem;
        color: var(--gray);
        transition: color 0.2s;
    }

    .card-action:hover {
        color: var(--white);
    }

    .card-content {
        padding: 1.5rem 1.75rem;
    }

    /* ===== RECENT FILES ===== */
    .file-list {
        display: flex;
        flex-direction: column;
    }

    .file-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        transition: background 0.2s;
    }

    .file-item:last-child {
        border-bottom: none;
    }

    .file-item:hover {
        background: rgba(255, 255, 255, 0.02);
        margin: 0 -1.75rem;
        padding: 0.625rem 1.75rem;
    }

    .file-thumb {
        width: 40px;
        height: 40px;
        background: var(--dark-3);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .file-thumb svg {
        width: 18px;
        height: 18px;
        stroke: var(--gray);
        stroke-width: 1.5;
        fill: none;
    }

    .file-info {
        flex: 1;
        min-width: 0;
    }

    .file-name {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-meta {
        font-size: 0.85rem;
        color: var(--gray);
    }

    .file-actions {
        display: flex;
        gap: 0.6rem;
    }

    .file-action-btn {
        width: 36px;
        height: 36px;
        background: var(--dark-3);
        border: none;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .file-action-btn:hover {
        background: var(--dark-4);
    }

    .file-action-btn svg {
        width: 18px;
        height: 18px;
        stroke: var(--gray);
        stroke-width: 1.5;
        fill: none;
    }

    /* ===== STORAGE CARD ===== */
    .storage-info {
        margin-bottom: 1.75rem;
    }

    .storage-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.875rem;
    }

    .storage-label {
        font-size: 0.925rem;
        color: var(--light-gray);
    }

    .storage-value {
        font-size: 0.925rem;
        font-weight: 600;
    }

    .storage-bar {
        height: 10px;
        background: var(--dark-3);
        border-radius: 5px;
        overflow: hidden;
    }

    .storage-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--white), var(--gray));
        border-radius: 5px;
        transition: width 0.6s ease;
    }

    .storage-breakdown {
        display: flex;
        flex-direction: column;
        gap: 0.875rem;
        margin-top: 1.75rem;
    }

    .breakdown-item {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .breakdown-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .breakdown-dot.videos {
        background: var(--white);
    }

    .breakdown-dot.images {
        background: var(--info);
    }

    .breakdown-info {
        flex: 1;
    }

    .breakdown-label {
        font-size: 0.85rem;
        color: var(--light-gray);
    }

    .breakdown-value {
        font-size: 0.925rem;
        font-weight: 600;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 0.875rem 1rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
        }

        .stat-value {
            font-size: 1.25rem;
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
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">DMCA Notices</div>
            <div class="stat-value">{{ $stats['dmca_notices'] ?? 0 }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24">
                <polyline points="22,12 18,12 15,21 9,3 6,12 2,12" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Bandwidth Used</div>
            <div class="stat-value">{{ $stats['bandwidth_used'] ?? '0 GB' }}</div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="content-grid">
    <!-- Recent Files -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Recent Uploads</h2>
            @if(count($recentFiles ?? []) > 0)
            <a href="#" class="card-action">View All</a>
            @endif
        </div>
        <div class="card-content">
            <div class="file-list">
                @forelse($recentFiles ?? [] as $file)
                <div class="file-item">
                    <div class="file-thumb">
                        <svg viewBox="0 0 24 24">
                            <polygon points="23,7 16,12 23,17 23,7" />
                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2" />
                        </svg>
                    </div>
                    <div class="file-info">
                        <div class="file-name">{{ $file['name'] ?? 'Untitled' }}</div>
                        <div class="file-meta">{{ $file['size'] ?? '' }} • {{ $file['date'] ?? '' }}</div>
                    </div>
                    <div class="file-actions">
                        <button class="file-action-btn">
                            <svg viewBox="0 0 24 24">
                                <polygon points="5,3 19,12 5,21 5,3" />
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; text-align: center;">
                    <svg viewBox="0 0 24 24" style="width: 48px; height: 48px; stroke: var(--gray); stroke-width: 1; fill: none; margin-bottom: 1rem;">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17,8 12,3 7,8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 0.25rem;">No files uploaded yet</p>
                    <p style="color: var(--gray); font-size: 0.8rem; opacity: 0.7;">Upload your first file to get started</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Storage Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Storage</h2>
                <span class="card-action">Unlimited</span>
            </div>
            <div class="card-content">
                <div class="storage-info">
                    <div class="storage-header">
                        <span class="storage-label">Used</span>
                        <span class="storage-value">{{ $storage['total'] ?? '0 GB' }} / ∞</span>
                    </div>
                    <div class="storage-bar">
                        <div class="storage-fill" style="width: 0%;"></div>
                    </div>
                </div>

                <div class="storage-breakdown">
                    <div class="breakdown-item">
                        <div class="breakdown-dot videos"></div>
                        <div class="breakdown-info">
                            <div class="breakdown-label">Videos</div>
                        </div>
                        <div class="breakdown-value">{{ $storage['videos'] ?? '0 GB' }}</div>
                    </div>
                    <div class="breakdown-item">
                        <div class="breakdown-dot images"></div>
                        <div class="breakdown-info">
                            <div class="breakdown-label">Images</div>
                        </div>
                        <div class="breakdown-value">{{ $storage['images'] ?? '0 GB' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection