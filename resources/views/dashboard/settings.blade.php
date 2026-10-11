@extends('layouts.dashboard')

@section('title', 'Settings')

@section('page_title', 'Settings')

@section('styles')
<style>
    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* ===== SETTINGS CONTENT ===== */
    .settings-content {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* ===== SETTINGS SECTION ===== */
    .settings-section {
        background: var(--dark-2);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        animation: fadeInUp 0.4s ease backwards;
        transition: border-color 0.2s;
    }

    .settings-section:nth-child(1) {
        animation-delay: 0.05s;
    }

    .settings-section:nth-child(2) {
        animation-delay: 0.1s;
    }

    .settings-section:nth-child(3) {
        animation-delay: 0.15s;
    }

    .settings-section:hover {
        border-color: rgba(255, 255, 255, 0.12);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.875rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .section-icon {
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .section-icon svg {
        width: 18px;
        height: 18px;
        stroke: var(--white);
        stroke-width: 1.5;
        fill: none;
    }

    .section-icon.danger {
        background: rgba(239, 68, 68, 0.1);
    }

    .section-icon.danger svg {
        stroke: var(--error);
    }

    .section-info {
        flex: 1;
    }

    .section-title {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.125rem;
    }

    .section-title.danger {
        color: var(--error);
    }

    .section-subtitle {
        font-size: 0.8rem;
        color: var(--gray);
    }

    /* ===== FORM STYLES ===== */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.875rem;
    }

    .form-group {
        position: relative;
    }

    .form-group.full-width {
        grid-column: span 2;
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--gray);
        margin-bottom: 0.375rem;
        transition: color 0.2s;
    }

    .form-input {
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

    .form-input::placeholder {
        color: var(--gray);
    }

    .form-input:hover {
        border-color: rgba(255, 255, 255, 0.15);
    }

    .form-input:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.3);
        background: var(--dark-4);
    }

    .form-group:focus-within .form-label {
        color: var(--light-gray);
    }

    /* ===== PASSWORD WRAPPER ===== */
    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-input {
        padding-right: 2.5rem;
    }

    .password-toggle {
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--gray);
        cursor: pointer;
        padding: 0.25rem;
        transition: color 0.2s;
    }

    .password-toggle:hover {
        color: var(--white);
    }

    .password-toggle svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        stroke-width: 1.5;
        fill: none;
    }

    /* ===== BUTTONS ===== */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
        padding-top: 0.875rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .btn {
        padding: 0.5rem 1.25rem;
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary {
        background: var(--white);
        color: var(--black);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        transform: none;
        cursor: not-allowed;
    }

    .btn-danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--error);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .btn-danger:hover {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.3);
    }

    /* ===== ALERTS ===== */
    .alert {
        padding: 0.625rem 0.875rem;
        border-radius: 8px;
        font-size: 0.8rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: slideIn 0.2s ease;
    }

    .alert svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        stroke-width: 2;
        fill: none;
        flex-shrink: 0;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: var(--success);
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: var(--error);
    }

    .alert-hidden {
        display: none;
    }

    /* ===== DANGER ZONE ===== */
    .danger-zone {
        border-color: rgba(239, 68, 68, 0.15);
    }

    .danger-warning {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        background: rgba(239, 68, 68, 0.05);
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .danger-warning svg {
        width: 18px;
        height: 18px;
        stroke: var(--error);
        stroke-width: 1.5;
        fill: none;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .danger-warning p {
        color: var(--gray);
        font-size: 0.8rem;
        line-height: 1.5;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: span 1;
        }

        .settings-section {
            padding: 1rem 1.25rem;
        }

        .section-header {
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
        }

        .form-actions {
            margin-top: 0.875rem;
        }

        .btn {
            width: 100%;
            text-align: center;
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
</style>
@endsection

@section('content')
<div class="settings-content">
    <!-- Profile Section -->
    <section class="settings-section">
        <div class="section-header">
            <div class="section-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </div>
            <div class="section-info">
                <h2 class="section-title">Profile Information</h2>
                <p class="section-subtitle">Update your personal details</p>
            </div>
        </div>

        <div id="profileAlert" class="alert alert-hidden"></div>

        <form id="profileForm">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ $user->name ?? '' }}" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ $user->email ?? '' }}" placeholder="Enter your email" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="profileBtn">Save Changes</button>
            </div>
        </form>
    </section>

    <!-- Security Section -->
    <section class="settings-section">
        <div class="section-header">
            <div class="section-icon">
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
            </div>
            <div class="section-info">
                <h2 class="section-title">Security</h2>
                <p class="section-subtitle">Change your password</p>
            </div>
        </div>

        <div id="passwordAlert" class="alert alert-hidden"></div>

        <form id="passwordForm">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label" for="current_password">Current Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="current_password" name="current_password" class="form-input" placeholder="Enter current password" required>
                        <button type="button" class="password-toggle" data-target="current_password">
                            <svg viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">New Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-input" placeholder="Min 8 characters" required>
                        <button type="button" class="password-toggle" data-target="password">
                            <svg viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Confirm password" required>
                        <button type="button" class="password-toggle" data-target="password_confirmation">
                            <svg viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="passwordBtn">Update Password</button>
            </div>
        </form>
    </section>

    @if($user->can_use_gpmc || $user->is_admin)
    <!-- Cloud Storage Integration -->
    <section class="settings-section">
        <div class="section-header">
            <div class="section-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path>
                </svg>
            </div>
            <div class="section-info">
                <h2 class="section-title">Cloud Storage Import</h2>
                <p class="section-subtitle">Import media from your cloud storage account</p>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Authentication Token</label>
            <textarea class="form-input" id="cloudAuthData" rows="3" placeholder="Paste your authentication token here..." style="font-family: monospace; font-size: 0.8rem; word-break: break-all;">{{ $user->gpmc_auth_data ?? '' }}</textarea>
            <p class="form-hint" style="font-size: 0.75rem; color: var(--gray); margin-top: 0.25rem;">Paste the full authentication string to connect.</p>
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 1rem; flex-wrap: wrap; gap: 1rem;">
            <div id="cloudStatus" style="font-size: 0.85rem; font-weight: 500;">
                @if(!empty($user->gpmc_auth_data))
                <span style="color:#10b981;">✓ Connected</span>
                @else
                <span style="color:var(--gray);">Not connected</span>
                @endif
            </div>
            <div style="display:flex; gap:10px;">
                <button type="button" id="btnCloudConnect" class="btn btn-primary">
                    {{ !empty($user->gpmc_auth_data) ? 'Update Connection' : 'Connect' }}
                </button>
                <button type="button" id="btnCloudImport" class="btn" style="background:var(--success); color:var(--black); {{ !empty($user->gpmc_auth_data) ? '' : 'display:none;' }}">Import All Items</button>
            </div>
        </div>

        <!-- Progress Bar with CSS Animation -->
        <style>
            @keyframes indeterminate {
                0% {
                    transform: translateX(-100%);
                }

                100% {
                    transform: translateX(400%);
                }
            }

            .progress-bar-indeterminate {
                animation: indeterminate 1.5s ease-in-out infinite;
                width: 25% !important;
            }
        </style>
        <div id="importProgress" style="display:none; margin-top: 1.5rem;">
            <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; font-size:0.8rem; color:var(--gray);">
                <span>Syncing items...</span>
                <span id="progressCount">0 items processed</span>
            </div>
            <div style="height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow:hidden;">
                <div id="progressBar" style="height:100%; width:0%; background:var(--success);"></div>
            </div>
        </div>
    </section>
    @endif

    <!-- API Access Section -->
    <section class="settings-section">
        <div class="section-header">
            <div class="section-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4" />
                </svg>
            </div>
            <div class="section-info">
                <h2 class="section-title">API Access</h2>
                <p class="section-subtitle">Use your API key to access files programmatically</p>
            </div>
        </div>

        <div id="apiKeyAlert" class="alert alert-hidden"></div>

        <div class="form-group">
            <label class="form-label">Your API Key</label>
            <div style="display: flex; gap: 0.5rem;">
                <input type="text" id="apiKeyDisplay" class="form-input"
                    value="{{ $user->api_key ?? 'No API key generated yet' }}"
                    readonly
                    style="font-family: monospace; font-size: 0.8rem; {{ $user->api_key ? '' : 'color: var(--gray);' }}">
                @if($user->api_key)
                <button type="button" id="btnCopyApiKey" class="btn" style="background: var(--dark-4); border: 1px solid rgba(255,255,255,0.1); white-space: nowrap;">
                    <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;stroke-width:2;fill:none;vertical-align:middle;margin-right:4px;">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    Copy
                </button>
                @endif
            </div>
            <p class="form-hint" style="font-size: 0.75rem; color: var(--gray); margin-top: 0.5rem;">
                Use this key via <code style="background:var(--dark-4);padding:0.125rem 0.25rem;border-radius:3px;">X-API-Key</code> header or <code style="background:var(--dark-4);padding:0.125rem 0.25rem;border-radius:3px;">api_key</code> query param.
            </p>
        </div>

        <div class="form-actions">
            <button type="button" id="btnGenerateApiKey" class="btn btn-primary">
                {{ $user->api_key ? 'Regenerate Key' : 'Generate API Key' }}
            </button>
        </div>

        @if($user->api_key)
        <!-- API Documentation -->
        <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05);">
            <p class="form-label" style="margin-bottom: 0.75rem;">API Endpoints</p>
            <div style="background: var(--dark-3); border-radius: 8px; padding: 0.875rem; font-family: monospace; font-size: 0.75rem; line-height: 1.8;">
                <div style="margin-bottom: 0.5rem;">
                    <span style="color: #22c55e;">GET</span>
                    <span style="color: var(--light-gray);">/api/search?q=</span><span style="color: #fbbf24;">{query}</span>
                    <span style="color: var(--gray); margin-left: 0.5rem;">— Search your files</span>
                </div>
                <div>
                    <span style="color: #22c55e;">GET</span>
                    <span style="color: var(--light-gray);">/api/file/</span><span style="color: #fbbf24;">{slug}</span>
                    <span style="color: var(--gray); margin-left: 0.5rem;">— Get file + stream URLs</span>
                </div>
            </div>
            <p class="form-hint" style="font-size: 0.7rem; color: var(--gray); margin-top: 0.5rem;">
                Add <code style="background:var(--dark-4);padding:0.1rem 0.2rem;border-radius:3px;">?api_key=YOUR_KEY</code> to any endpoint or use header <code style="background:var(--dark-4);padding:0.1rem 0.2rem;border-radius:3px;">X-API-Key: YOUR_KEY</code>
            </p>
        </div>
        @endif
    </section>

    <!-- Danger Zone -->
    <section class="settings-section danger-zone">
        <div class="section-header">
            <div class="section-icon danger">
                <svg viewBox="0 0 24 24">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                    <line x1="12" y1="9" x2="12" y2="13" />
                    <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
            </div>
            <div class="section-info">
                <h2 class="section-title danger">Danger Zone</h2>
                <p class="section-subtitle">Irreversible actions</p>
            </div>
        </div>

        <div class="danger-warning">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            <p>Deleting your account will permanently remove all your files and data. This action cannot be undone.</p>
        </div>

        <button type="button" class="btn btn-danger">Delete Account</button>
    </section>
</div>

<!-- Custom Confirmation Modal -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-content">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" y1="15" x2="12" y2="3" />
            </svg>
        </div>
        <h3 class="modal-title">Ready to Import?</h3>
        <p class="modal-desc">This will fetch your entire Google Photos library and import it into your account. The process may take a few minutes depending on your library size.</p>
        <div class="modal-actions">
            <button type="button" class="btn-modal-cancel" id="btnModalCancel">Cancel</button>
            <button type="button" class="btn-modal-confirm" id="btnModalConfirm">Start Import</button>
        </div>
    </div>
</div>

<!-- API Key Confirmation Modal -->
<div class="modal-overlay" id="apiKeyModal">
    <div class="modal-content">
        <div class="modal-icon" style="background: rgba(251, 191, 36, 0.1);">
            <svg viewBox="0 0 24 24" style="stroke: #fbbf24;">
                <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4" />
            </svg>
        </div>
        <h3 class="modal-title" id="apiKeyModalTitle">Generate API Key?</h3>
        <p class="modal-desc">This will create a new API key for your account. Any existing integrations using the old key will <strong>stop working immediately</strong>.</p>
        <div class="modal-actions">
            <button type="button" class="btn-modal-cancel" id="btnApiKeyCancel">Cancel</button>
            <button type="button" class="btn-modal-confirm" id="btnApiKeyConfirm" style="background: #fbbf24; color: #000;">Generate Key</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Password visibility toggle
    document.querySelectorAll('.password-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });

    function showAlert(el, type, msg) {
        el.className = `alert alert-${type}`;
        el.innerHTML = `<svg viewBox="0 0 24 24">${type === 'success' ? '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>' : '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>'}</svg>${msg}`;
    }

    // Profile Form
    document.getElementById('profileForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('profileBtn');
        const alert = document.getElementById('profileAlert');
        btn.disabled = true;
        btn.textContent = 'Saving...';

        try {
            const res = await fetch('{{ route("settings.profile") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value
                })
            });
            const data = await res.json();
            showAlert(alert, data.success ? 'success' : 'error', data.message || 'Something went wrong');
        } catch {
            showAlert(alert, 'error', 'An error occurred');
        }

        btn.disabled = false;
        btn.textContent = 'Save Changes';
    });

    // Password Form
    document.getElementById('passwordForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('passwordBtn');
        const alert = document.getElementById('passwordAlert');
        const pwd = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;

        if (pwd !== confirm) {
            showAlert(alert, 'error', 'Passwords do not match');
            return;
        }
        if (pwd.length < 8) {
            showAlert(alert, 'error', 'Password must be at least 8 characters');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Updating...';

        try {
            const res = await fetch('{{ route("settings.password") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    current_password: document.getElementById('current_password').value,
                    password: pwd,
                    password_confirmation: confirm
                })
            });
            const data = await res.json();
            showAlert(alert, data.success ? 'success' : 'error', data.message || 'Something went wrong');
            if (data.success) document.getElementById('passwordForm').reset();
        } catch {
            showAlert(alert, 'error', 'An error occurred');
        }

        btn.disabled = false;
        btn.textContent = 'Update Password';
    });

    // Cloud Storage Integration Logic
    const btnConnect = document.getElementById('btnCloudConnect');

    if (btnConnect) {
        const btnImport = document.getElementById('btnCloudImport');
        const statusDiv = document.getElementById('cloudStatus');
        const authInput = document.getElementById('cloudAuthData');
        const importProgress = document.getElementById('importProgress');
        const progressBar = document.getElementById('progressBar');
        const progressCount = document.getElementById('progressCount');
        const confirmModal = document.getElementById('confirmModal');
        const btnModalCancel = document.getElementById('btnModalCancel');
        const btnModalConfirm = document.getElementById('btnModalConfirm');

        // CSS animation controls
        function startProgressAnimation() {
            progressBar.classList.add('progress-bar-indeterminate');
        }

        function stopProgressAnimation() {
            progressBar.classList.remove('progress-bar-indeterminate');
        }

        // Track polling interval for page reload resume
        let pageLoadPollInterval = null;

        // Poll for progress updates
        async function checkProgress() {
            try {
                const res = await fetch('{{ route("settings.cloud.progress") }}');
                const data = await res.json();

                if (data.status === 'running') {
                    importProgress.style.display = 'block';
                    btnImport.disabled = true;
                    btnConnect.disabled = true;
                    btnImport.textContent = 'Syncing...';
                    progressCount.textContent = (data.processed || 0) + ' items processed';
                    statusDiv.innerHTML = '<span style="color:var(--gray);">Syncing in progress...</span>';

                    // Resume animation and polling if returning to page
                    if (!pageLoadPollInterval) {
                        startProgressAnimation();
                        pageLoadPollInterval = setInterval(checkProgress, 2000);
                    }

                } else if (data.status === 'completed') {
                    if (pageLoadPollInterval) {
                        clearInterval(pageLoadPollInterval);
                        pageLoadPollInterval = null;
                    }
                    stopProgressAnimation();
                    importProgress.style.display = 'block';
                    progressBar.style.width = '100%';
                    progressCount.textContent = (data.processed || 0) + ' items synced';
                    statusDiv.innerHTML = '<span style="color:#10b981;">✓ ' + data.message + '</span>';
                    btnImport.textContent = 'Sync Complete';
                    btnImport.disabled = false;
                    btnConnect.disabled = false;

                    setTimeout(() => {
                        importProgress.style.display = 'none';
                        btnImport.textContent = 'Import All Items';
                        progressBar.style.width = '0%';
                    }, 5000);

                } else if (data.status === 'failed') {
                    if (pageLoadPollInterval) {
                        clearInterval(pageLoadPollInterval);
                        pageLoadPollInterval = null;
                    }
                    stopProgressAnimation();
                    statusDiv.innerHTML = '<span style="color:var(--error);">' + data.message + '</span>';
                    btnImport.textContent = 'Retry';
                    btnImport.disabled = false;
                    btnConnect.disabled = false;
                    importProgress.style.display = 'none';
                }
            } catch (e) {
                console.error('Progress check error', e);
            }
        }

        // Check on page load
        checkProgress();

        // Connect Button
        btnConnect.addEventListener('click', async () => {
            const authData = authInput.value;
            if (!authData) {
                statusDiv.innerHTML = '<span style="color:var(--error);">Please enter token first.</span>';
                return;
            }

            btnConnect.disabled = true;
            btnConnect.textContent = 'Connecting...';
            statusDiv.innerHTML = '<span style="color:var(--gray);">Verifying...</span>';
            btnImport.style.display = 'none';

            try {
                const formData = new FormData();
                formData.append('auth_data', authData);

                const response = await fetch('{{ route("settings.cloud.verify") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    statusDiv.innerHTML = '<span style="color:#10b981;">✓ Connected</span>';
                    btnImport.style.display = 'inline-block';
                    btnConnect.textContent = 'Update Connection';
                } else {
                    statusDiv.innerHTML = '<span style="color:var(--error);">Failed: ' + data.message + '</span>';
                    btnConnect.textContent = 'Connect';
                }
            } catch (e) {
                statusDiv.innerHTML = '<span style="color:var(--error);">Error: ' + e.message + '</span>';
                btnConnect.textContent = 'Connect';
            }
            btnConnect.disabled = false;
        });

        // Modal Actions
        btnImport.addEventListener('click', () => confirmModal.classList.add('visible'));
        btnModalCancel.addEventListener('click', () => confirmModal.classList.remove('visible'));

        // Confirm Import Action
        btnModalConfirm.addEventListener('click', async () => {
            confirmModal.classList.remove('visible');

            btnImport.disabled = true;
            btnConnect.disabled = true;
            btnImport.textContent = 'Syncing...';
            importProgress.style.display = 'block';
            progressBar.style.width = '0%';
            progressCount.textContent = 'Starting...';
            statusDiv.innerHTML = '<span style="color:var(--gray);">Syncing in progress...</span>';

            // Start CSS animation and polling
            startProgressAnimation();
            const pollInterval = setInterval(checkProgress, 2000);

            try {
                const response = await fetch('{{ route("settings.cloud.import") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: new FormData()
                });
                const data = await response.json();

                clearInterval(pollInterval);
                stopProgressAnimation();

                if (data.success) {
                    progressBar.style.width = '100%';
                    progressCount.textContent = data.count + ' items synced';
                    statusDiv.innerHTML = '<span style="color:#10b981;">✓ ' + data.message + '</span>';
                    btnImport.textContent = 'Sync Complete';

                    setTimeout(() => {
                        importProgress.style.display = 'none';
                        btnImport.textContent = 'Import All Items';
                        progressBar.style.width = '0%';
                    }, 5000);
                } else {
                    statusDiv.innerHTML = '<span style="color:var(--error);">' + data.message + '</span>';
                    btnImport.textContent = 'Retry';
                    importProgress.style.display = 'none';
                }
                btnImport.disabled = false;
                btnConnect.disabled = false;
            } catch (e) {
                clearInterval(pollInterval);
                stopProgressAnimation();
                statusDiv.innerHTML = '<span style="color:var(--error);">Error: ' + e.message + '</span>';
                btnImport.textContent = 'Retry';
                btnImport.disabled = false;
                btnConnect.disabled = false;
                importProgress.style.display = 'none';
            }
        });
    }

    // API Key Generation with Custom Modal
    const btnGenerateApiKey = document.getElementById('btnGenerateApiKey');
    const btnCopyApiKey = document.getElementById('btnCopyApiKey');
    const apiKeyDisplay = document.getElementById('apiKeyDisplay');
    const apiKeyAlert = document.getElementById('apiKeyAlert');
    const apiKeyModal = document.getElementById('apiKeyModal');
    const btnApiKeyCancel = document.getElementById('btnApiKeyCancel');
    const btnApiKeyConfirm = document.getElementById('btnApiKeyConfirm');
    const apiKeyModalTitle = document.getElementById('apiKeyModalTitle');

    if (btnGenerateApiKey && apiKeyModal) {
        // Show modal on button click
        btnGenerateApiKey.addEventListener('click', () => {
            const isRegenerate = apiKeyDisplay.value.length === 64;
            apiKeyModalTitle.textContent = isRegenerate ? 'Regenerate API Key?' : 'Generate API Key?';
            btnApiKeyConfirm.textContent = isRegenerate ? 'Regenerate Key' : 'Generate Key';
            apiKeyModal.classList.add('visible');
        });

        // Cancel button closes modal
        btnApiKeyCancel.addEventListener('click', () => {
            apiKeyModal.classList.remove('visible');
        });

        // Click outside modal to close
        apiKeyModal.addEventListener('click', (e) => {
            if (e.target === apiKeyModal) {
                apiKeyModal.classList.remove('visible');
            }
        });

        // Confirm button generates key
        btnApiKeyConfirm.addEventListener('click', async () => {
            apiKeyModal.classList.remove('visible');
            btnGenerateApiKey.disabled = true;
            btnGenerateApiKey.textContent = 'Generating...';

            try {
                const res = await fetch('{{ route("settings.apikey.generate") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();

                if (data.success) {
                    apiKeyDisplay.value = data.api_key;
                    apiKeyDisplay.style.color = '';
                    showAlert(apiKeyAlert, 'success', 'API key generated successfully! Make sure to copy it now.');
                    btnGenerateApiKey.textContent = 'Regenerate Key';

                    // Reload to show copy button if it doesn't exist
                    if (!document.getElementById('btnCopyApiKey')) {
                        location.reload();
                    }
                } else {
                    showAlert(apiKeyAlert, 'error', data.message || 'Failed to generate API key');
                }
            } catch (e) {
                showAlert(apiKeyAlert, 'error', 'An error occurred');
            }

            btnGenerateApiKey.disabled = false;
        });
    }

    if (btnCopyApiKey) {
        btnCopyApiKey.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(apiKeyDisplay.value);
                const originalText = btnCopyApiKey.innerHTML;
                btnCopyApiKey.innerHTML = '<svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;stroke-width:2;fill:none;vertical-align:middle;margin-right:4px;"><polyline points="20 6 9 17 4 12"></polyline></svg>Copied!';
                btnCopyApiKey.style.color = 'var(--success)';
                setTimeout(() => {
                    btnCopyApiKey.innerHTML = originalText;
                    btnCopyApiKey.style.color = '';
                }, 2000);
            } catch (e) {
                // Fallback for older browsers
                apiKeyDisplay.select();
                document.execCommand('copy');
            }
        });
    }
</script>
@endsection