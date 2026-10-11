<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Installation Wizard - VidStreaming</title>
    @php try { $favicon = \App\Models\Setting::get('site_favicon'); } catch (\Exception $e) { $favicon = null; } @endphp
    @if($favicon)
    <link rel="icon" href="{{ asset($favicon) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --black: #000;
            --dark: #0a0a0a;
            --dark-2: #111;
            --dark-3: #1a1a1a;
            --white: #fff;
            --gray: #888;
            --light-gray: #ccc;
            --error: #ef4444;
            --success: #22c55e;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--black);
            color: var(--white);
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes checkPop {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        /* ===== HEADER ===== */
        .header {
            padding: 1.25rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: var(--dark);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.15rem;
            font-weight: 800;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: var(--white);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon svg {
            width: 16px;
            height: 16px;
            fill: var(--black);
        }

        .wizard-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.875rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            font-size: 0.75rem;
            color: var(--gray);
        }

        /* ===== MAIN ===== */
        .main {
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        /* ===== STEPS INDICATOR ===== */
        .steps-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--dark-2);
            border: 2px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--gray);
            transition: all 0.3s ease;
        }

        .step-circle.active {
            background: var(--white);
            color: var(--black);
            border-color: var(--white);
        }

        .step-circle.completed {
            background: var(--success);
            border-color: var(--success);
        }

        .step-circle.completed svg {
            width: 14px;
            height: 14px;
            stroke: var(--white);
            stroke-width: 3;
            fill: none;
        }

        .step-line {
            width: 40px;
            height: 2px;
            background: rgba(255, 255, 255, 0.15);
            transition: background 0.3s ease;
        }

        .step-line.completed {
            background: var(--success);
        }

        /* ===== WIZARD CARD ===== */
        .wizard-card {
            background: var(--dark-2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            animation: fadeSlideUp 0.5s ease;
        }

        .wizard-header {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .wizard-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .wizard-icon svg {
            width: 24px;
            height: 24px;
            stroke: var(--white);
            stroke-width: 1.5;
            fill: none;
        }

        .wizard-title {
            font-size: 1.35rem;
            font-weight: 800;
            margin-bottom: 0.35rem;
        }

        .wizard-subtitle {
            color: var(--gray);
            font-size: 0.85rem;
        }

        /* ===== REQUIREMENTS GRID ===== */
        .requirements-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            background: var(--dark-3);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .requirement-info {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            min-width: 0;
        }

        .requirement-icon {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .requirement-icon svg {
            width: 16px;
            height: 16px;
            stroke: var(--white);
            stroke-width: 1.5;
            fill: none;
        }

        .requirement-name {
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .requirement-status {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .requirement-status.success {
            background: rgba(34, 197, 94, 0.15);
            color: var(--success);
        }

        .requirement-status.error {
            background: rgba(239, 68, 68, 0.15);
            color: var(--error);
        }

        .requirement-status.checking {
            background: rgba(255, 255, 255, 0.05);
            color: var(--gray);
        }

        .requirement-status svg {
            width: 12px;
            height: 12px;
            stroke: currentColor;
            stroke-width: 2.5;
            fill: none;
        }

        .mini-spinner {
            width: 12px;
            height: 12px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-top-color: var(--gray);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .requirements-summary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--success);
        }

        .requirements-summary.error {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.2);
            color: var(--error);
        }

        .requirements-summary svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        /* ===== FORM STYLES ===== */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--light-gray);
            margin-bottom: 0.4rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 0.875rem;
            background: var(--dark-3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: var(--white);
            font-size: 0.9rem;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: var(--gray);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--white);
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .form-hint {
            font-size: 0.7rem;
            color: var(--gray);
            margin-top: 0.25rem;
        }

        /* ===== TEST CONNECTION ===== */
        .test-connection {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.875rem 1rem;
            background: var(--dark-3);
            border-radius: 8px;
            margin-bottom: 1.25rem;
        }

        .test-label {
            font-size: 0.85rem;
            color: var(--light-gray);
        }

        .test-btn {
            padding: 0.4rem 0.875rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            color: var(--white);
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .test-btn:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .test-status {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
        }

        .test-status.success {
            color: var(--success);
        }

        .test-status.error {
            color: var(--error);
        }

        .test-status.loading {
            color: var(--gray);
        }

        .test-status svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .spinner {
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-top-color: var(--white);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        /* ===== BUTTONS ===== */
        .btn {
            padding: 0.75rem 1.25rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--white);
            color: var(--black);
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.15);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .wizard-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }

        .btn-secondary {
            flex: 1;
            background: var(--dark-3);
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* ===== STEP PANELS ===== */
        .step-panel {
            display: none;
        }

        .step-panel.active {
            display: block;
        }

        /* ===== SUCCESS PANEL ===== */
        .success-panel {
            text-align: center;
            padding: 1rem 0;
        }

        .success-icon {
            width: 72px;
            height: 72px;
            background: rgba(34, 197, 94, 0.1);
            border: 2px solid var(--success);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .success-icon svg {
            width: 32px;
            height: 32px;
            stroke: var(--success);
            stroke-width: 2.5;
            fill: none;
            animation: checkPop 0.5s ease;
        }

        .success-title {
            font-size: 1.35rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .success-text {
            color: var(--gray);
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            .main {
                padding: 1.5rem 1rem;
            }

            .wizard-card {
                padding: 1.5rem 1.25rem;
            }

            .wizard-title {
                font-size: 1.2rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .requirements-grid {
                grid-template-columns: 1fr;
            }

            .step-line {
                width: 24px;
            }

            .step-circle {
                width: 28px;
                height: 28px;
                font-size: 0.75rem;
            }

            .header {
                padding: 1rem;
            }

            .wizard-badge {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24">
                        <polygon points="5,3 19,12 5,21" />
                    </svg>
                </div>
                <span>VidStreaming</span>
            </div>
            <div class="wizard-badge">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                </svg>
                Installation Wizard
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="main">
        <!-- Steps Indicator -->
        <div class="steps-indicator">
            <div class="step-circle active" id="step1Circle">1</div>
            <div class="step-line" id="line1"></div>
            <div class="step-circle" id="step2Circle">2</div>
            <div class="step-line" id="line2"></div>
            <div class="step-circle" id="step3Circle">3</div>
            <div class="step-line" id="line3"></div>
            <div class="step-circle" id="step4Circle">4</div>
        </div>

        <!-- Wizard Card -->
        <div class="wizard-card">
            <!-- Step 1: Requirements Check -->
            <div class="step-panel active" id="step1">
                <div class="wizard-header">
                    <div class="wizard-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 11l3 3L22 4" />
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                        </svg>
                    </div>
                    <h2 class="wizard-title">Requirements Check</h2>
                    <p class="wizard-subtitle">Verifying server requirements</p>
                </div>

                <div class="requirements-grid" id="requirementsGrid">
                    <div class="requirement-item">
                        <div class="requirement-info">
                            <div class="requirement-icon"><svg viewBox="0 0 24 24">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z" />
                                    <path d="M2 17l10 5 10-5" />
                                    <path d="M2 12l10 5 10-5" />
                                </svg></div>
                            <span class="requirement-name">PHP 8.1+</span>
                        </div>
                        <div class="requirement-status checking" id="phpStatus">
                            <div class="mini-spinner"></div>
                        </div>
                    </div>
                    <div class="requirement-item">
                        <div class="requirement-info">
                            <div class="requirement-icon"><svg viewBox="0 0 24 24">
                                    <ellipse cx="12" cy="5" rx="9" ry="3" />
                                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3" />
                                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5" />
                                </svg></div>
                            <span class="requirement-name">MySQL</span>
                        </div>
                        <div class="requirement-status checking" id="mysqlStatus">
                            <div class="mini-spinner"></div>
                        </div>
                    </div>
                    <div class="requirement-item">
                        <div class="requirement-info">
                            <div class="requirement-icon"><svg viewBox="0 0 24 24">
                                    <path d="M4 7V4h16v3" />
                                    <path d="M9 20h6" />
                                    <path d="M12 4v16" />
                                </svg></div>
                            <span class="requirement-name">Mbstring</span>
                        </div>
                        <div class="requirement-status checking" id="mbstringStatus">
                            <div class="mini-spinner"></div>
                        </div>
                    </div>
                    <div class="requirement-item">
                        <div class="requirement-info">
                            <div class="requirement-icon"><svg viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg></div>
                            <span class="requirement-name">OpenSSL</span>
                        </div>
                        <div class="requirement-status checking" id="opensslStatus">
                            <div class="mini-spinner"></div>
                        </div>
                    </div>
                    <div class="requirement-item">
                        <div class="requirement-info">
                            <div class="requirement-icon"><svg viewBox="0 0 24 24">
                                    <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                                </svg></div>
                            <span class="requirement-name">cURL</span>
                        </div>
                        <div class="requirement-status checking" id="curlStatus">
                            <div class="mini-spinner"></div>
                        </div>
                    </div>
                    <div class="requirement-item">
                        <div class="requirement-info">
                            <div class="requirement-icon"><svg viewBox="0 0 24 24">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                </svg></div>
                            <span class="requirement-name">Storage</span>
                        </div>
                        <div class="requirement-status checking" id="storageStatus">
                            <div class="mini-spinner"></div>
                        </div>
                    </div>
                    <div class="requirement-item">
                        <div class="requirement-info">
                            <div class="requirement-icon"><svg viewBox="0 0 24 24">
                                    <polyline points="4 17 10 11 4 5" />
                                    <line x1="12" y1="19" x2="20" y2="19" />
                                </svg></div>
                            <span class="requirement-name">shell_exec</span>
                        </div>
                        <div class="requirement-status checking" id="shellExecStatus">
                            <div class="mini-spinner"></div>
                        </div>
                    </div>
                    <div class="requirement-item">
                        <div class="requirement-info">
                            <div class="requirement-icon"><svg viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" y1="8" x2="12" y2="12" />
                                    <line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg></div>
                            <span class="requirement-name" id="pythonLabel">Python 3.11+</span>
                        </div>
                        <div class="requirement-status checking" id="pythonStatus">
                            <div class="mini-spinner"></div>
                        </div>
                    </div>
                    <div class="requirement-item">
                        <div class="requirement-info">
                            <div class="requirement-icon"><svg viewBox="0 0 24 24">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                </svg></div>
                            <span class="requirement-name">rich</span>
                        </div>
                        <div class="requirement-status checking" id="richStatus">
                            <div class="mini-spinner"></div>
                        </div>
                    </div>
                    <div class="requirement-item">
                        <div class="requirement-info">
                            <div class="requirement-icon"><svg viewBox="0 0 24 24">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                </svg></div>
                            <span class="requirement-name">requests</span>
                        </div>
                        <div class="requirement-status checking" id="requestsStatus">
                            <div class="mini-spinner"></div>
                        </div>
                    </div>
                    <div class="requirement-item">
                        <div class="requirement-info">
                            <div class="requirement-icon"><svg viewBox="0 0 24 24">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                </svg></div>
                            <span class="requirement-name">bbpb</span>
                        </div>
                        <div class="requirement-status checking" id="bbpbStatus">
                            <div class="mini-spinner"></div>
                        </div>
                    </div>
                </div>

                <div class="requirements-summary" id="requirementsSummary" style="display: none;">
                    <svg viewBox="0 0 24 24">
                        <polyline points="20,6 9,17 4,12" />
                    </svg>
                    <span>All requirements met!</span>
                </div>

                <button type="button" class="btn btn-primary" id="step1Btn" disabled>Continue</button>
            </div>

            <!-- Step 2: Database Configuration -->
            <div class="step-panel" id="step2">
                <div class="wizard-header">
                    <div class="wizard-icon">
                        <svg viewBox="0 0 24 24">
                            <ellipse cx="12" cy="5" rx="9" ry="3" />
                            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3" />
                            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5" />
                        </svg>
                    </div>
                    <h2 class="wizard-title">Database Configuration</h2>
                    <p class="wizard-subtitle">Connect to your MySQL database</p>
                </div>

                <form id="dbForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Host</label>
                            <input type="text" class="form-input" id="dbHost" value="localhost" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Port</label>
                            <input type="text" class="form-input" id="dbPort" value="3306" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Database Name</label>
                        <input type="text" class="form-input" id="dbName" placeholder="vidstreaming" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-input" id="dbUser" placeholder="root" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-input" id="dbPass" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="test-connection">
                        <span class="test-label">Test connection</span>
                        <div id="testStatus"></div>
                        <button type="button" class="test-btn" id="testBtn">Test</button>
                    </div>

                    <div class="wizard-actions">
                        <button type="button" class="btn btn-secondary" id="backToStep1">Back</button>
                        <button type="submit" class="btn btn-primary" style="flex: 2;">Continue</button>
                    </div>
                </form>
            </div>

            <!-- Step 3: Admin Account -->
            <div class="step-panel" id="step3">
                <div class="wizard-header">
                    <div class="wizard-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                    <h2 class="wizard-title">Admin Account</h2>
                    <p class="wizard-subtitle">Create your administrator account</p>
                </div>

                <form id="adminForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-input" id="adminFirstName" placeholder="John" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-input" id="adminLastName" placeholder="Doe" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" id="adminEmail" placeholder="admin@example.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-input" id="adminPassword" placeholder="Min. 8 characters" required minlength="8">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-input" id="adminPasswordConfirm" placeholder="Repeat password" required>
                    </div>

                    <div class="wizard-actions">
                        <button type="button" class="btn btn-secondary" id="backToStep2">Back</button>
                        <button type="submit" class="btn btn-primary" style="flex: 2;">Create Account</button>
                    </div>
                </form>
            </div>

            <!-- Step 4: Complete -->
            <div class="step-panel" id="step4">
                <div class="success-panel">
                    <div class="success-icon">
                        <svg viewBox="0 0 24 24">
                            <polyline points="20,6 9,17 4,12" />
                        </svg>
                    </div>
                    <h2 class="success-title">Installation Complete!</h2>
                    <p class="success-text">VidStreaming is ready. Your admin account has been created.</p>
                    <a href="{{ url('/login') }}" class="btn btn-primary">Go to Login</a>
                </div>
            </div>
        </div>
    </main>

    <script>
        const circles = [
            document.getElementById('step1Circle'),
            document.getElementById('step2Circle'),
            document.getElementById('step3Circle'),
            document.getElementById('step4Circle')
        ];
        const lines = [
            document.getElementById('line1'),
            document.getElementById('line2'),
            document.getElementById('line3')
        ];
        const panels = [
            document.getElementById('step1'),
            document.getElementById('step2'),
            document.getElementById('step3'),
            document.getElementById('step4')
        ];

        let currentStep = 1;

        function updateStepUI(step) {
            circles.forEach((c, i) => {
                c.classList.remove('active', 'completed');
                if (i + 1 < step) {
                    c.classList.add('completed');
                    c.innerHTML = '<svg viewBox="0 0 24 24"><polyline points="20,6 9,17 4,12"/></svg>';
                } else if (i + 1 === step) {
                    c.classList.add('active');
                    c.textContent = i + 1;
                } else {
                    c.textContent = i + 1;
                }
            });
            lines.forEach((l, i) => l.classList.toggle('completed', i < step - 1));
            panels.forEach((p, i) => p.classList.toggle('active', i + 1 === step));
        }

        function goToStep(step) {
            currentStep = step;
            updateStepUI(step);
        }

        // Check requirements
        async function checkRequirements() {
            try {
                const response = await fetch('{{ url("/install/check-requirements") }}');
                const data = await response.json();
                let allPassed = true;

                // PHP requirements
                updateStatus('phpStatus', data.php_ok);
                updateStatus('mysqlStatus', data.mysql_ok);
                updateStatus('mbstringStatus', data.mbstring_ok);
                updateStatus('opensslStatus', data.openssl_ok);
                updateStatus('curlStatus', data.curl_ok);
                updateStatus('storageStatus', data.storage_ok);

                // Python requirements
                updateStatus('shellExecStatus', data.shell_exec_ok);
                updateStatus('pythonStatus', data.python_ok);
                updateStatus('richStatus', data.rich_ok);
                updateStatus('requestsStatus', data.requests_ok);
                updateStatus('bbpbStatus', data.bbpb_ok);

                // Update Python label with version
                if (data.python_version) {
                    document.getElementById('pythonLabel').textContent = 'Python ' + data.python_version;
                }

                // Check if all requirements passed
                const phpReqs = data.php_ok && data.mysql_ok && data.mbstring_ok && data.openssl_ok && data.curl_ok && data.storage_ok;
                const pythonReqs = data.shell_exec_ok && data.python_ok && data.rich_ok && data.requests_ok && data.bbpb_ok;
                allPassed = phpReqs && pythonReqs;

                const summary = document.getElementById('requirementsSummary');
                summary.style.display = 'flex';
                if (allPassed) {
                    document.getElementById('step1Btn').disabled = false;
                } else {
                    summary.classList.add('error');
                    summary.innerHTML = '<svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg><span>Some requirements not met</span>';
                }
            } catch (e) {
                console.error(e);
            }
        }

        function updateStatus(id, passed) {
            const el = document.getElementById(id);
            el.classList.remove('checking');
            el.classList.add(passed ? 'success' : 'error');
            el.innerHTML = passed ?
                '<svg viewBox="0 0 24 24"><polyline points="20,6 9,17 4,12"/></svg>' :
                '<svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
        }

        setTimeout(checkRequirements, 400);

        document.getElementById('step1Btn').addEventListener('click', () => goToStep(2));
        document.getElementById('backToStep1').addEventListener('click', () => goToStep(1));
        document.getElementById('backToStep2').addEventListener('click', () => goToStep(2));

        document.getElementById('testBtn').addEventListener('click', async () => {
            const testStatus = document.getElementById('testStatus');
            const testBtn = document.getElementById('testBtn');
            testStatus.innerHTML = '<div class="test-status loading"><div class="spinner"></div></div>';
            testBtn.disabled = true;

            try {
                const response = await fetch('{{ url("/install/test-db") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        host: document.getElementById('dbHost').value,
                        port: document.getElementById('dbPort').value,
                        database: document.getElementById('dbName').value,
                        username: document.getElementById('dbUser').value,
                        password: document.getElementById('dbPass').value,
                    })
                });
                const result = await response.json();
                testStatus.innerHTML = result.success ?
                    '<div class="test-status success"><svg viewBox="0 0 24 24"><polyline points="20,6 9,17 4,12"/></svg></div>' :
                    '<div class="test-status error"><svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></div>';
            } catch (e) {
                testStatus.innerHTML = '<div class="test-status error"><svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></div>';
            }
            testBtn.disabled = false;
        });

        document.getElementById('dbForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                const response = await fetch('{{ url("/install/save-db") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        host: document.getElementById('dbHost').value,
                        port: document.getElementById('dbPort').value,
                        database: document.getElementById('dbName').value,
                        username: document.getElementById('dbUser').value,
                        password: document.getElementById('dbPass').value,
                    })
                });
                const result = await response.json();
                if (result.success) goToStep(3);
                else alert(result.message || 'Database configuration failed');
            } catch (e) {
                alert('Error: ' + e.message);
            }
        });

        document.getElementById('adminForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const pw = document.getElementById('adminPassword').value;
            if (pw !== document.getElementById('adminPasswordConfirm').value) {
                alert('Passwords do not match');
                return;
            }
            try {
                const response = await fetch('{{ url("/install/create-admin") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        first_name: document.getElementById('adminFirstName').value,
                        last_name: document.getElementById('adminLastName').value,
                        email: document.getElementById('adminEmail').value,
                        password: pw,
                    })
                });
                const result = await response.json();
                if (result.success) goToStep(4);
                else alert(result.message || 'Account creation failed');
            } catch (e) {
                alert('Error: ' + e.message);
            }
        });
    </script>
</body>

</html>