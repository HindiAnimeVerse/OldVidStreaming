@extends('layouts.admin')

@section('title', 'Settings')
@section('page_title', 'Site Settings')

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

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .settings-card {
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        animation: fadeIn 0.4s ease backwards;
    }

    .settings-card:nth-child(1) {
        animation-delay: 0.05s;
    }

    .settings-card:nth-child(2) {
        animation-delay: 0.1s;
    }

    .settings-card:nth-child(3) {
        animation-delay: 0.15s;
    }

    .settings-card:nth-child(4) {
        animation-delay: 0.2s;
    }

    .settings-card.full-width {
        grid-column: span 2;
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
    .form-select,
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
        min-height: 80px;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: var(--gray);
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.3);
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23888' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        padding-right: 2rem;
    }

    .form-hint {
        font-size: 0.7rem;
        color: var(--gray);
        margin-top: 0.25rem;
    }

    .toggle-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    }

    .toggle-group:last-child {
        border-bottom: none;
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

    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }

        .settings-card.full-width {
            grid-column: span 1;
        }

        .save-bar {
            margin: 1.5rem -1rem -1rem;
            padding: 1rem;
        }
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
        backdrop-filter: blur(4px);
    }

    .modal-overlay.visible {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 2rem;
        width: 100%;
        max-width: 400px;
        text-align: center;
        transform: translateY(20px);
        transition: transform 0.3s;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.4);
    }

    .modal-overlay.visible .modal-content {
        transform: translateY(0);
    }

    .modal-icon {
        width: 48px;
        height: 48px;
        background: rgba(34, 197, 94, 0.1);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .modal-icon svg {
        width: 24px;
        height: 24px;
        stroke: var(--success);
        stroke-width: 2;
        fill: none;
    }

    .modal-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--white);
    }

    .modal-desc {
        color: var(--gray);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }

    .modal-actions {
        display: flex;
        gap: 0.75rem;
    }

    .modal-actions button {
        flex: 1;
        padding: 0.75rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-modal-cancel {
        background: var(--dark-4);
        color: var(--white);
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    .btn-modal-cancel:hover {
        background: var(--dark-3);
    }

    .btn-modal-confirm {
        background: var(--success);
        color: var(--black);
    }

    .btn-modal-confirm:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
    }

    /* Favicon Upload */
    .favicon-upload {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        background: var(--dark-3);
        border: 2px dashed rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .favicon-upload:hover {
        border-color: rgba(255, 255, 255, 0.3);
        background: var(--dark-4);
    }

    .favicon-preview {
        width: 48px;
        height: 48px;
        background: var(--dark-4);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    .favicon-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .favicon-placeholder svg {
        width: 24px;
        height: 24px;
        stroke: var(--gray);
        stroke-width: 1.5;
        fill: none;
    }

    .favicon-info {
        flex: 1;
    }

    .favicon-hint {
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .favicon-size {
        font-size: 0.7rem;
        color: var(--gray);
    }

    .favicon-upload.uploading {
        pointer-events: none;
        opacity: 0.7;
    }
</style>
@endsection

@section('content')
<form id="settingsForm">
    <div class="settings-grid">
        <!-- General Settings -->
        <div class="settings-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M12 1v6m0 6v10M4.22 4.22l4.24 4.24m7.08 7.08l4.24 4.24M1 12h6m6 0h10M4.22 19.78l4.24-4.24m7.08-7.08l4.24-4.24" />
                    </svg>
                </div>
                <h3 class="card-title">General</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Site Name</label>
                    <input type="text" class="form-input" name="site_name" value="{{ $settings['site_name'] ?? 'VidStreaming' }}" placeholder="Your site name">
                </div>
                <div class="form-group">
                    <label class="form-label">Site Description</label>
                    <textarea class="form-textarea" name="site_description" placeholder="Brief description of your site">{{ $settings['site_description'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Registration Settings -->
        <div class="settings-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="8.5" cy="7" r="4" />
                        <line x1="20" y1="8" x2="20" y2="14" />
                        <line x1="23" y1="11" x2="17" y2="11" />
                    </svg>
                </div>
                <h3 class="card-title">Registration</h3>
            </div>
            <div class="card-body">
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">Allow Registration</div>
                        <div class="toggle-desc">Enable new user sign-ups</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="allow_registration" {{ $settings['allow_registration'] ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">Email Verification</div>
                        <div class="toggle-desc">Require email verification</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="email_verification" {{ $settings['email_verification'] ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="settings-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                </div>
                <h3 class="card-title">Security</h3>
            </div>
            <div class="card-body">
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">DMCA Protection</div>
                        <div class="toggle-desc">Enable DMCA takedown system</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="dmca_enabled" {{ $settings['dmca_enabled'] ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="toggle-group">
                    <div class="toggle-info">
                        <div class="toggle-label">Maintenance Mode</div>
                        <div class="toggle-desc">Disable public access temporarily</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="maintenance_mode" {{ $settings['maintenance_mode'] ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Branding -->
        <div class="settings-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21,15 16,10 5,21" />
                    </svg>
                </div>
                <h3 class="card-title">Branding</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Favicon</label>
                    <div class="favicon-upload" id="faviconUpload">
                        <div class="favicon-preview">
                            @if($settings['site_favicon'])
                            <img src="{{ asset($settings['site_favicon']) }}" alt="Favicon" id="faviconPreview">
                            @else
                            <div class="favicon-placeholder" id="faviconPlaceholder">
                                <svg viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21,15 16,10 5,21" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="favicon-info">
                            <div class="favicon-hint">Click to upload favicon (ICO, PNG, JPG)</div>
                            <div class="favicon-size">Max 1MB, recommended 32x32 or 64x64</div>
                        </div>
                        <input type="file" id="faviconInput" accept=".ico,.png,.jpg,.jpeg,.gif,.svg" hidden>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Save Bar -->
    <div class="save-bar">
        <button type="submit" class="btn-save">Save Changes</button>
    </div>
</form>

<!-- Success Modal -->
<div class="modal-overlay" id="successModal">
    <div class="modal-content">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24">
                <polyline points="20,6 9,17 4,12" />
            </svg>
        </div>
        <h3 class="modal-title">Settings Saved</h3>
        <p class="modal-desc">Your settings have been updated successfully.</p>
        <div class="modal-actions">
            <button class="btn-modal-confirm" onclick="closeModal()">OK</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('settingsForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = {};

        // Handle checkboxes properly
        const checkboxes = ['allow_registration', 'email_verification', 'dmca_enabled', 'maintenance_mode'];
        checkboxes.forEach(name => {
            data[name] = formData.get(name) === 'on' ? '1' : '0';
        });

        // Handle text fields
        data['site_name'] = formData.get('site_name') || '';
        data['site_description'] = formData.get('site_description') || '';

        try {
            const response = await fetch('{{ route("admin.settings.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                showToast('Settings saved successfully!', 'success');
            } else {
                showToast(result.message || 'Failed to save settings', 'error');
            }
        } catch (error) {
            showToast('Error saving settings. Please try again.', 'error');
        }
    });

    function closeModal() {
        document.getElementById('successModal').classList.remove('visible');
    }

    // Favicon Upload
    const faviconUpload = document.getElementById('faviconUpload');
    const faviconInput = document.getElementById('faviconInput');

    faviconUpload.addEventListener('click', () => faviconInput.click());

    faviconInput.addEventListener('change', async function() {
        const file = this.files[0];
        if (!file) return;

        // Validate file size (max 1MB)
        if (file.size > 1024 * 1024) {
            showToast('File too large. Maximum size is 1MB.', 'error');
            return;
        }

        // Show uploading state
        faviconUpload.classList.add('uploading');

        const formData = new FormData();
        formData.append('favicon', file);

        try {
            const response = await fetch('{{ route("admin.settings.favicon") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showToast('Favicon uploaded successfully!', 'success');

                // Update preview
                const preview = faviconUpload.querySelector('.favicon-preview');
                const placeholder = preview.querySelector('.favicon-placeholder');
                let img = preview.querySelector('img');

                if (placeholder) placeholder.remove();

                if (!img) {
                    img = document.createElement('img');
                    img.id = 'faviconPreview';
                    preview.appendChild(img);
                }
                img.src = result.path + '?t=' + Date.now();

                // Update browser favicon
                let link = document.querySelector("link[rel*='icon']");
                if (link) link.href = result.path + '?t=' + Date.now();
            } else {
                showToast(result.message || 'Upload failed', 'error');
            }
        } catch (error) {
            showToast('Error uploading favicon', 'error');
        } finally {
            faviconUpload.classList.remove('uploading');
            faviconInput.value = '';
        }
    });
</script>
@endsection