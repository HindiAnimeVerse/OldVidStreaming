@extends('layouts.admin')

@section('title', 'Advertisements')
@section('page_title', 'Advertisement Settings')

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

    .mz-container {
        max-width: 900px;
    }

    .mz-header {
        margin-bottom: 1.5rem;
        animation: fadeIn 0.4s ease backwards;
    }

    .mz-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .mz-desc {
        font-size: 0.85rem;
        color: var(--gray);
    }

    .mz-card {
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        margin-bottom: 1.25rem;
        animation: fadeIn 0.4s ease backwards;
    }

    .mz-card:nth-child(1) {
        animation-delay: 0.05s;
    }

    .mz-card:nth-child(2) {
        animation-delay: 0.1s;
    }

    .mz-card:nth-child(3) {
        animation-delay: 0.15s;
    }

    .mz-card:nth-child(4) {
        animation-delay: 0.2s;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .card-icon {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-icon svg {
        width: 16px;
        height: 16px;
        stroke: var(--white);
        stroke-width: 1.5;
        fill: none;
    }

    .card-title {
        font-size: 0.9rem;
        font-weight: 600;
    }

    .card-body {
        padding: 1.25rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--gray);
        margin-bottom: 0.375rem;
    }

    .form-input,
    .form-textarea {
        width: 100%;
        padding: 0.625rem 0.875rem;
        background: var(--dark-3);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        color: var(--white);
        font-size: 0.85rem;
        font-family: inherit;
        transition: all 0.2s;
    }

    .form-textarea {
        resize: vertical;
        min-height: 120px;
        font-family: 'Consolas', 'Monaco', monospace;
        font-size: 0.75rem;
        line-height: 1.5;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.3);
    }

    .form-hint {
        font-size: 0.7rem;
        color: var(--gray);
        margin-top: 0.375rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    @media (max-width: 640px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .toggle-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
    }

    .toggle-info {
        flex: 1;
    }

    .toggle-label {
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 0.125rem;
    }

    .toggle-desc {
        font-size: 0.7rem;
        color: var(--gray);
    }

    .toggle-switch {
        position: relative;
        width: 42px;
        height: 24px;
    }

    .toggle-switch input {
        display: none;
    }

    .toggle-slider {
        position: absolute;
        inset: 0;
        background: var(--dark-4);
        border-radius: 12px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .toggle-slider::before {
        content: '';
        position: absolute;
        left: 3px;
        top: 3px;
        width: 18px;
        height: 18px;
        background: var(--white);
        border-radius: 50%;
        transition: transform 0.2s;
    }

    .toggle-switch input:checked+.toggle-slider {
        background: var(--white);
    }

    .toggle-switch input:checked+.toggle-slider::before {
        transform: translateX(18px);
        background: var(--black);
    }

    .save-bar {
        position: sticky;
        bottom: 0;
        background: var(--dark);
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        padding: 1rem 2rem;
        margin: 1.5rem -2rem -1.5rem;
        display: flex;
        justify-content: flex-end;
    }

    .btn-save {
        padding: 0.625rem 1.5rem;
        background: var(--white);
        border: none;
        border-radius: 8px;
        color: var(--black);
        font-size: 0.85rem;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-save:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
    }

    .info-box {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.75rem;
        animation: fadeIn 0.4s ease backwards;
    }

    .info-box svg {
        width: 18px;
        height: 18px;
        stroke: #3b82f6;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .info-box-text {
        font-size: 0.8rem;
        color: var(--light-gray);
        line-height: 1.5;
    }
</style>
@endsection

@section('content')
<div class="mz-container">
    <div class="mz-header">
        <h2 class="mz-title">Monetag Ad Integration</h2>
        <p class="mz-desc">Configure your Monetag advertisement codes for streaming and download pages.</p>
    </div>

    <div class="info-box">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="16" x2="12" y2="12" />
            <line x1="12" y1="8" x2="12.01" y2="8" />
        </svg>
        <div class="info-box-text">
            Paste your Monetag ad codes directly from the Monetag dashboard. The codes already include Anti-AdBlock functionality built-in.
        </div>
    </div>

    <form id="adsForm">
        <!-- Master Toggle -->
        <div class="mz-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M18.36 6.64a9 9 0 1 1-12.73 0" />
                        <line x1="12" y1="2" x2="12" y2="12" />
                    </svg>
                </div>
                <h3 class="card-title">Ad Status</h3>
            </div>
            <div class="card-body">
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">Enable Advertisements</div>
                        <div class="toggle-desc">Show ads on streaming and download pages</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="ads_enabled" {{ ($settings['ads_enabled'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Ad Behavior -->
        <div class="mz-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12,6 12,12 16,14" />
                    </svg>
                </div>
                <h3 class="card-title">Ad Behavior</h3>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Stream Clicks Required</label>
                        <input type="number" class="form-input" name="ads_stream_clicks" value="{{ $settings['ads_stream_clicks'] ?? 2 }}" min="1" max="5">
                        <div class="form-hint">User must click this many times before video plays</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Download Countdown (seconds)</label>
                        <input type="number" class="form-input" name="ads_download_countdown" value="{{ $settings['ads_download_countdown'] ?? 10 }}" min="5" max="60">
                        <div class="form-hint">Wait time on download countdown page</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Direct Link URLs -->
        <div class="mz-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6" />
                        <polyline points="15,3 21,3 21,9" />
                        <line x1="10" y1="14" x2="21" y2="3" />
                    </svg>
                </div>
                <h3 class="card-title">Direct Link URLs (Highest Paying)</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Monetag Direct Link URLs (one per line)</label>
                    <textarea class="form-textarea" name="ads_direct_links" placeholder="https://otfrfrk.com/4/XXXXXXX&#10;https://otfrfrk.com/4/YYYYYYY" style="min-height: 80px;">{{ $settings['ads_direct_links'] ?? '' }}</textarea>
                    <div class="form-hint">Add one Direct Link URL per line. Each click will use a different URL to bypass frequency caps and maximize earnings. Create multiple zones in Monetag for different URLs.</div>
                </div>
            </div>
        </div>

        <!-- Vignette Code -->
        <div class="mz-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                        <line x1="8" y1="21" x2="16" y2="21" />
                        <line x1="12" y1="17" x2="12" y2="21" />
                    </svg>
                </div>
                <h3 class="card-title">Vignette Banner Code</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <textarea class="form-textarea" name="ads_vignette_code" placeholder="Paste your Monetag Vignette Banner script here...">{{ $settings['ads_vignette_code'] ?? '' }}</textarea>
                    <div class="form-hint">This ad displays on the download countdown page while user waits</div>
                </div>
            </div>
        </div>

        <!-- Popunder Code -->
        <div class="mz-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                        <line x1="9" y1="3" x2="9" y2="21" />
                    </svg>
                </div>
                <h3 class="card-title">Popunder Code (Download)</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <textarea class="form-textarea" name="ads_popunder_code" placeholder="Paste your Monetag Popunder/Onclick script here...">{{ $settings['ads_popunder_code'] ?? '' }}</textarea>
                    <div class="form-hint">This ad triggers on the download page clicks. Runs throughout the entire download process.</div>
                </div>
            </div>
        </div>

        <!-- Save Bar -->
        <div class="save-bar">
            <button type="submit" class="btn-save">Save Changes</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('adsForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = {};

        // Handle checkbox
        data['ads_enabled'] = formData.get('ads_enabled') === 'on' ? '1' : '0';

        // Handle other fields
        data['ads_stream_clicks'] = formData.get('ads_stream_clicks') || '2';
        data['ads_download_countdown'] = formData.get('ads_download_countdown') || '10';
        data['ads_direct_links'] = formData.get('ads_direct_links') || '';
        data['ads_vignette_code'] = formData.get('ads_vignette_code') || '';
        data['ads_popunder_code'] = formData.get('ads_popunder_code') || '';

        try {
            const response = await fetch('{{ route("admin.ads.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                showToast('Ad settings saved successfully!', 'success');
            } else {
                showToast(result.message || 'Failed to save settings', 'error');
            }
        } catch (error) {
            showToast('Error saving settings. Please try again.', 'error');
        }
    });
</script>
@endsection