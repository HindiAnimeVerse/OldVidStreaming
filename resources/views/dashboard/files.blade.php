@extends('layouts.dashboard')

@section('title', 'My Files')

@section('page_title', 'My Files')

@section('header_actions')
<div class="header-stats">
    <span class="stat-pill">{{ number_format($totalFiles) }} files</span>
    <span class="stat-pill">{{ \App\Helpers\FormatHelper::formatBytes($totalSize) }}</span>
</div>
@endsection

@section('styles')
<style>
    .header-stats {
        display: flex;
        gap: 0.75rem;
    }

    .stat-pill {
        padding: 0.5rem 1rem;
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        font-size: 0.8rem;
        color: var(--light-gray);
    }

    .toolbar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 350px;
        min-width: 200px;
    }

    .search-box input {
        width: 100%;
        padding: 0.7rem 1rem 0.7rem 2.5rem;
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: var(--white);
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .search-box input:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.3);
    }

    .search-box input::placeholder {
        color: var(--gray);
    }

    .search-box svg {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        stroke: var(--gray);
        stroke-width: 2;
        fill: none;
    }

    .filter-btn {
        padding: 0.7rem 1rem;
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: var(--light-gray);
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
    }

    .filter-btn:hover {
        background: var(--dark-3);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .filter-btn.active {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.3);
        color: var(--white);
    }

    /* ===== FILES TABLE ===== */
    .files-table-wrapper {
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        overflow: hidden;
    }

    .files-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .files-table th,
    .files-table td {
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.85rem;
    }

    .files-table th {
        background: rgba(255, 255, 255, 0.03);
        color: var(--gray);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    /* Column widths - filename gets most space */
    .files-table th:nth-child(1),
    .files-table td:nth-child(1) {
        width: auto;
    }

    .files-table th:nth-child(2),
    .files-table td:nth-child(2) {
        width: 80px;
    }

    .files-table th:nth-child(3),
    .files-table td:nth-child(3) {
        width: 90px;
    }

    .files-table th:nth-child(4),
    .files-table td:nth-child(4) {
        width: 110px;
    }

    .files-table th:nth-child(5),
    .files-table td:nth-child(5) {
        width: 95px;
        padding-right: 1.5rem;
    }

    .files-table tr:not(:last-child) td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    }

    .files-table tr:hover td {
        background: rgba(255, 255, 255, 0.02);
    }

    .file-name {
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    .size-cell {
        color: var(--light-gray);
        white-space: nowrap;
    }

    .date-cell {
        color: var(--gray);
        white-space: nowrap;
    }

    .type-cell {
        color: var(--gray);
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .preview-btn {
        padding: 0.4rem 0.75rem;
        background: rgba(255, 255, 255, 0.08);
        border: none;
        border-radius: 6px;
        color: var(--light-gray);
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .preview-btn:hover {
        background: rgba(255, 255, 255, 0.15);
        color: var(--white);
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state h3 {
        font-size: 1.1rem;
        margin-bottom: 0.4rem;
    }

    .empty-state p {
        color: var(--gray);
        font-size: 0.85rem;
    }

    /* ===== PAGINATION ===== */
    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        font-size: 0.8rem;
        color: var(--gray);
    }

    .pagination-links {
        display: flex;
        gap: 0.35rem;
    }

    .pagination-links a,
    .pagination-links span {
        padding: 0.4rem 0.75rem;
        background: var(--dark-3);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 6px;
        color: var(--light-gray);
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination-links a:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--white);
    }

    .pagination-links .current {
        background: var(--white);
        color: var(--black);
        border-color: var(--white);
    }

    .pagination-links .disabled {
        opacity: 0.4;
        pointer-events: none;
    }

    /* ===== SYNC BUTTON ===== */
    .sync-btn {
        padding: 0.7rem 1rem;
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: var(--light-gray);
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-left: auto;
    }

    .sync-btn:hover:not(:disabled) {
        background: var(--dark-3);
        border-color: rgba(255, 255, 255, 0.2);
        color: var(--white);
    }

    .sync-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .sync-btn svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
    }

    .sync-btn.syncing {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.15);
        color: var(--white);
    }

    .sync-btn.syncing svg {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* New file row highlight - theme matching */
    .new-file-row {
        background: rgba(255, 255, 255, 0.06);
        animation: newRowFade 3s ease forwards;
        border-left: 3px solid rgba(255, 255, 255, 0.5);
    }

    @keyframes newRowFade {
        0% {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: rgba(255, 255, 255, 0.8);
        }

        100% {
            background: transparent;
            border-left-color: transparent;
        }
    }


    /* ===== SKELETON ROWS ===== */
    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    .skeleton-row td {
        padding: 0.85rem 1rem;
    }

    .skeleton-cell {
        height: 14px;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.04) 25%, rgba(255, 255, 255, 0.08) 50%, rgba(255, 255, 255, 0.04) 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 4px;
    }

    .skeleton-cell.name {
        width: 60%;
    }

    .skeleton-cell.type {
        width: 40px;
    }

    .skeleton-cell.size {
        width: 50px;
    }

    .skeleton-cell.date {
        width: 70px;
    }

    .skeleton-cell.btn {
        width: 55px;
        height: 24px;
    }

    .skeleton-row {
        opacity: 0;
        animation: fadeIn 0.3s forwards;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            max-width: none;
        }

        .header-stats {
            display: none;
        }

        .files-table th:nth-child(3),
        .files-table td:nth-child(3),
        .files-table th:nth-child(4),
        .files-table td:nth-child(4) {
            display: none;
        }

        .sync-btn {
            margin-left: 0;
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<!-- Toolbar -->
<div class="toolbar">
    <form action="{{ route('files') }}" method="GET" class="search-box">
        <svg viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
        <input type="text" name="search" placeholder="Search files..." value="{{ request('search') }}">
        @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
        @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
    </form>

    <a href="{{ route('files', array_merge(request()->except('type'), ['type' => request('type') === 'video' ? '' : 'video'])) }}"
        class="filter-btn {{ request('type') === 'video' ? 'active' : '' }}">Videos</a>

    <a href="{{ route('files', array_merge(request()->except('type'), ['type' => request('type') === 'image' ? '' : 'image'])) }}"
        class="filter-btn {{ request('type') === 'image' ? 'active' : '' }}">Images</a>

    <a href="{{ route('files', array_merge(request()->all(), ['sort' => 'timestamp', 'dir' => request('sort', 'timestamp') === 'timestamp' && request('dir') !== 'asc' ? 'asc' : 'desc'])) }}"
        class="filter-btn {{ request('sort', 'timestamp') === 'timestamp' ? 'active' : '' }}">
        Date {{ request('sort', 'timestamp') === 'timestamp' ? (request('dir') === 'asc' ? '↑' : '↓') : '' }}
    </a>

    <a href="{{ route('files', array_merge(request()->all(), ['sort' => 'file_size', 'dir' => request('sort') === 'file_size' && request('dir') !== 'asc' ? 'asc' : 'desc'])) }}"
        class="filter-btn {{ request('sort') === 'file_size' ? 'active' : '' }}">
        Size {{ request('sort') === 'file_size' ? (request('dir') === 'asc' ? '↑' : '↓') : '' }}
    </a>

    @if($user->can_use_gpmc && $user->gpmc_auth_data)
    <button class="sync-btn" id="syncBtn" onclick="triggerSync()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="23 4 23 10 17 10" />
            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
        </svg>
        <span>Sync Now</span>
    </button>
    @endif
</div>

<!-- Files Table -->
<div class="files-table-wrapper">
    @if($files->count() > 0)
    <table class="files-table">
        <thead>
            <tr>
                <th>File Name</th>
                <th>Type</th>
                <th>Size</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="filesBody">
            <!-- Skeleton rows inserted here during sync -->
            @foreach($files as $file)
            <tr>
                <td><span class="file-name" title="{{ $file->filename }}">{{ $file->filename }}</span></td>
                <td class="type-cell">{{ $file->resolution ?? 'Image' }}</td>
                <td class="size-cell">{{ $file->formatted_size }}</td>
                <td class="date-cell">{{ $file->formatted_date }}</td>
                <td><button class="preview-btn" data-id="{{ $file->id }}" data-url="{{ $file->media_url }}">Preview</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Custom Pagination -->
    <div class="pagination-wrapper">
        <span class="pagination-info">
            Showing {{ $files->firstItem() }} - {{ $files->lastItem() }} of {{ $files->total() }}
        </span>
        <div class="pagination-links">
            @if($files->onFirstPage())
            <span class="disabled">« Prev</span>
            @else
            <a href="{{ $files->previousPageUrl() }}">« Prev</a>
            @endif

            @if($files->currentPage() > 3)
            <a href="{{ $files->url(1) }}">1</a>
            @if($files->currentPage() > 4)<span>...</span>@endif
            @endif

            @for($i = max(1, $files->currentPage() - 2); $i <= min($files->lastPage(), $files->currentPage() + 2); $i++)
                @if($i == $files->currentPage())
                <span class="current">{{ $i }}</span>
                @else
                <a href="{{ $files->url($i) }}">{{ $i }}</a>
                @endif
                @endfor

                @if($files->currentPage() < $files->lastPage() - 2)
                    @if($files->currentPage() < $files->lastPage() - 3)<span>...</span>@endif
                        <a href="{{ $files->url($files->lastPage()) }}">{{ $files->lastPage() }}</a>
                        @endif

                        @if($files->hasMorePages())
                        <a href="{{ $files->nextPageUrl() }}">Next »</a>
                        @else
                        <span class="disabled">Next »</span>
                        @endif
        </div>
    </div>
    @else
    <div class="empty-state">
        <h3>No files found</h3>
        <p>{{ request('search') ? 'Try a different search term' : 'Import files from your cloud storage to get started' }}</p>
    </div>
    @endif
</div>

{{-- Preview Modal with Inline Styles --}}
<style>
    .preview-modal-overlay {
        display: none;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        background: rgba(0, 0, 0, 0.85) !important;
        z-index: 99999 !important;
        justify-content: center;
        align-items: center;
        padding: 0.75rem;
    }

    .preview-modal-overlay.active {
        display: flex !important;
    }

    .preview-modal {
        background: #111;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 10px;
        width: 100%;
        max-width: 600px;
        max-height: 85vh;
        overflow-y: auto;
    }

    .preview-modal .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0.75rem;
        background: #0a0a0a;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .preview-modal .modal-title {
        display: flex;
        flex-direction: column;
        gap: 0.1rem;
    }

    .preview-modal .modal-id {
        font-size: 0.65rem;
        color: #555;
    }

    .preview-modal .modal-filename {
        font-size: 0.75rem;
        color: #fff;
        font-weight: 500;
        max-width: 500px;
        word-wrap: break-word;
        white-space: normal;
        line-height: 1.3;
    }

    .preview-modal .modal-close {
        padding: 0.25rem 0.5rem;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 4px;
        color: #fff;
        cursor: pointer;
        font-size: 0.75rem;
    }

    .preview-modal .modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .preview-modal .modal-body {
        padding: 0.75rem;
        background: #111;
    }

    .preview-modal .player-container {
        background: #000;
        border-radius: 6px;
        overflow: hidden;
        aspect-ratio: 16/9;
        margin-bottom: 0.5rem;
        width: 100%;
    }

    .preview-modal #modalPlayer {
        width: 100%;
        height: 100%;
    }

    .preview-modal .links-section {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .preview-modal .link-row {
        display: flex;
        gap: 0.4rem;
    }

    .preview-modal .link-input {
        flex: 1;
        padding: 0.4rem 0.5rem;
        background: #0a0a0a;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        color: #888;
        font-size: 0.65rem;
        font-family: monospace;
        min-width: 0;
    }

    .preview-modal .iframe-input {
        width: 100%;
        padding: 0.4rem 0.5rem;
        background: #0a0a0a;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        color: #888;
        font-size: 0.6rem;
        font-family: monospace;
        resize: none;
        height: 40px;
    }

    .preview-modal .action-buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.35rem;
    }

    .preview-modal .action-btn {
        padding: 0.35rem 0.7rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        font-size: 0.7rem;
        cursor: pointer;
        background: transparent;
        color: #aaa;
    }

    .preview-modal .action-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .preview-modal .action-btn svg {
        width: 12px;
        height: 12px;
    }

    .preview-modal .close-btn {
        background: #fff;
        color: #000;
        border: none;
    }

    .preview-modal .close-btn:hover {
        background: #ddd;
    }

    @media (max-width: 550px) {
        .preview-modal {
            max-width: 95vw;
        }

        .preview-modal .link-row {
            flex-direction: column;
        }

        .preview-modal .action-buttons {
            gap: 0.25rem;
        }

        .preview-modal .action-btn {
            padding: 0.3rem 0.5rem;
            font-size: 0.65rem;
        }
    }
</style>

<div class="preview-modal-overlay" id="previewModal">
    <div class="preview-modal">
        <div class="modal-header">
            <div class="modal-title">
                <span class="modal-id">Preview ID: <span id="modalFileId"></span></span>
                <span class="modal-filename" id="modalFilename"></span>
            </div>
            <button class="modal-close" onclick="closePreviewModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="player-container">
                <iframe id="modalPlayer" src="" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="links-section">
                <div class="link-row">
                    <input type="text" id="videoLink" readonly class="link-input">
                    <input type="text" id="downloadLink" readonly class="link-input">
                </div>
                <div class="iframe-row"><textarea id="iframeCode" readonly class="iframe-input"></textarea></div>
                <div class="action-buttons">
                    <button class="action-btn copy-btn" onclick="copyToClipboard('videoLink', this)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" />
                            <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1" />
                        </svg> Copy Link</button>
                    <button class="action-btn copy-btn" onclick="copyToClipboard('iframeCode', this)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="16 18 22 12 16 6" />
                            <polyline points="8 6 2 12 8 18" />
                        </svg> Copy Iframe</button>
                    <button class="action-btn copy-btn" onclick="copyToClipboard('downloadLink', this)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg> Copy DL</button>
                    <button class="action-btn close-btn" onclick="closePreviewModal()">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.preview-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const fileId = this.dataset.id;
            try {
                const response = await fetch(`{{ url('/file') }}/${fileId}/preview`);
                const file = await response.json();
                if (file.error) {
                    alert(file.error);
                    return;
                }
                document.getElementById('modalFileId').textContent = file.id;
                document.getElementById('modalFilename').textContent = file.filename;
                document.getElementById('videoLink').value = file.video_url;
                document.getElementById('downloadLink').value = file.download_url;
                document.getElementById('iframeCode').value = `<iframe src="${file.video_url}" scrolling="no" frameborder="0" width="100%" height="100%" allowfullscreen="true"></iframe>`;
                document.getElementById('previewModal').classList.add('active');
                document.body.style.overflow = 'hidden';

                // Load the player in iframe
                document.getElementById('modalPlayer').src = file.video_url;
            } catch (error) {
                console.error('Preview error:', error);
                alert('Failed to load preview');
            }
        });
    });

    function closePreviewModal() {
        document.getElementById('previewModal').classList.remove('active');
        document.body.style.overflow = '';
        // Clear iframe to stop video
        document.getElementById('modalPlayer').src = '';
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closePreviewModal();
    });
    document.getElementById('previewModal').addEventListener('click', e => {
        if (e.target.id === 'previewModal') closePreviewModal();
    });

    function copyToClipboard(elementId, btn) {
        const element = document.getElementById(elementId);
        element.select();
        document.execCommand('copy');
        const originalText = btn.innerHTML;
        btn.classList.add('copied');
        btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Copied!';
        setTimeout(() => {
            btn.classList.remove('copied');
            btn.innerHTML = originalText;
        }, 1500);
    }

    // ===== SYNC FUNCTIONALITY =====
    let isSyncing = false;
    let pollInterval = null;

    async function triggerSync() {
        if (isSyncing) return;
        isSyncing = true;
        updateSyncUI(true);
        insertSkeletonRows(3);

        try {
            const response = await fetch('{{ route("sync.manual") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            const result = await response.json();

            if (result.status === 'queued') {
                // Already syncing, start polling
                startPolling();
            } else {
                // Sync complete - insert real rows
                removeSkeletonRows();
                if (result.files && result.files.length > 0) {
                    insertRealRows(result.files);
                    updateFileCount(result.inserted);
                }
                isSyncing = false;
                updateSyncUI(false);
            }
        } catch (e) {
            console.error('Sync error:', e);
            removeSkeletonRows();
            isSyncing = false;
            updateSyncUI(false);
        }
    }

    function updateSyncUI(syncing) {
        const btn = document.getElementById('syncBtn');
        if (syncing) {
            btn.disabled = true;
            btn.classList.add('syncing');
            btn.querySelector('span').textContent = 'Syncing...';
        } else {
            btn.disabled = false;
            btn.classList.remove('syncing');
            btn.querySelector('span').textContent = 'Sync Now';
        }
    }

    function insertSkeletonRows(count) {
        const tbody = document.getElementById('filesBody');
        if (!tbody) return;
        for (let i = 0; i < count; i++) {
            const tr = document.createElement('tr');
            tr.className = 'skeleton-row';
            tr.style.animationDelay = (i * 0.1) + 's';
            tr.innerHTML = `
                <td><div class="skeleton-cell name"></div></td>
                <td><div class="skeleton-cell type"></div></td>
                <td><div class="skeleton-cell size"></div></td>
                <td><div class="skeleton-cell date"></div></td>
                <td><div class="skeleton-cell btn"></div></td>
            `;
            tbody.insertBefore(tr, tbody.firstChild);
        }
    }

    function removeSkeletonRows() {
        document.querySelectorAll('.skeleton-row').forEach(r => r.remove());
    }

    function insertRealRows(files) {
        const tbody = document.getElementById('filesBody');
        if (!tbody) return;

        // Insert files in reverse order so newest appears at top
        files.reverse().forEach((file, i) => {
            const tr = document.createElement('tr');
            tr.className = 'new-file-row';
            tr.style.opacity = '0';
            tr.style.animation = `fadeIn 0.4s ease ${i * 0.1}s forwards`;
            tr.innerHTML = `
                <td><span class="file-name" title="${escapeHtml(file.filename)}">${escapeHtml(file.filename)}</span></td>
                <td class="type-cell">${file.resolution || 'Image'}</td>
                <td class="size-cell">${file.formatted_size}</td>
                <td class="date-cell">${file.formatted_date}</td>
                <td><button class="preview-btn" data-id="${file.id}" data-url="${file.media_url}">Preview</button></td>
            `;
            tbody.insertBefore(tr, tbody.firstChild);

            // Attach preview event
            tr.querySelector('.preview-btn').addEventListener('click', handlePreviewClick);
        });
    }

    function updateFileCount(added) {
        const statPills = document.querySelectorAll('.stat-pill');
        if (statPills.length > 0) {
            const current = parseInt(statPills[0].textContent.replace(/,/g, '')) || 0;
            statPills[0].textContent = (current + added).toLocaleString() + ' files';
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function handlePreviewClick() {
        const fileId = this.dataset.id;
        fetch(`{{ url('/file') }}/${fileId}/preview`)
            .then(r => r.json())
            .then(file => {
                if (file.error) return alert(file.error);
                document.getElementById('modalFileId').textContent = file.id;
                document.getElementById('modalFilename').textContent = file.filename;
                document.getElementById('videoLink').value = file.video_url;
                document.getElementById('downloadLink').value = file.download_url;
                document.getElementById('iframeCode').value = `<iframe src="${file.video_url}" scrolling="no" frameborder="0" width="100%" height="100%" allowfullscreen="true"></iframe>`;
                document.getElementById('previewModal').classList.add('active');
                document.body.style.overflow = 'hidden';
                document.getElementById('modalPlayer').src = file.video_url;
            })
            .catch(e => alert('Failed to load preview'));
    }


    function startPolling() {
        if (pollInterval) return;
        pollInterval = setInterval(async () => {
            try {
                const res = await fetch('{{ route("sync.status") }}');
                const data = await res.json();
                if (!data.syncing) {
                    clearInterval(pollInterval);
                    pollInterval = null;
                    // Sync finished - fetch new files via manual sync (it will return immediately with files)
                    removeSkeletonRows();
                    isSyncing = false;
                    updateSyncUI(false);
                    // Trigger a quick sync to get the files that were just synced
                    fetchLatestFiles();
                }
            } catch (e) {
                clearInterval(pollInterval);
                pollInterval = null;
                removeSkeletonRows();
                isSyncing = false;
                updateSyncUI(false);
            }
        }, 2000);
    }

    // Fetch latest files without page reload
    async function fetchLatestFiles() {
        try {
            const res = await fetch('{{ route("sync.manual") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            const result = await res.json();
            if (result.files && result.files.length > 0) {
                insertRealRows(result.files);
                updateFileCount(result.inserted);
            }
        } catch (e) {
            console.log('Could not fetch latest files:', e);
        }
    }

    // Sync is now manual only - no auto-check on page load
</script>
@endsection