<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - VidStreaming</title>
    <meta name="robots" content="noindex, nofollow">
    <script>
        // Telegram browser redirect
        (function() {
            var ua = navigator.userAgent || '';
            var isTelegram = /Telegram/i.test(ua) || window.TelegramWebview || window.TelegramWebviewProxy;
            if (isTelegram) {
                var url = window.location.href;
                var isAndroid = /Android/i.test(ua);
                var isIOS = /iPhone|iPad|iPod/i.test(ua);
                if (isAndroid) {
                    var intent = 'intent://' + url.replace(/^https?:\/\//, '') + '#Intent;scheme=https;package=com.android.chrome;end';
                    window.location.href = intent;
                } else if (isIOS) {
                    var chromeUrl = url.replace(/^https?:\/\//, 'googlechrome://');
                    window.location.href = chromeUrl;
                }
            }
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: #000;
            color: #fff;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 1rem 2rem;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: 800;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon svg {
            width: 16px;
            height: 16px;
            fill: #000;
        }

        .nav-btns {
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            padding: 0.6rem 1.25rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-ghost {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-ghost:hover {
            border-color: #fff;
        }

        .btn-white {
            background: #fff;
            color: #000;
        }

        .btn-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
        }

        /* Animated background */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.04) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.04) 0%, transparent 40%);
            pointer-events: none;
            z-index: 0;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 6rem 2rem 2rem;
            position: relative;
            z-index: 1;
        }

        /* Server Card */
        .server-card {
            width: 100%;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            overflow: hidden;
            animation: fadeUp 0.6s ease-out;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Server Header */
        .server-header {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .server-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.05));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.2);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 20px 5px rgba(255, 255, 255, 0.1);
            }
        }

        .server-icon svg {
            width: 32px;
            height: 32px;
            stroke: #fff;
        }

        .server-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .server-speed {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
        }

        .speed-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        /* File Info */
        .file-info {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .file-name {
            font-size: 0.9rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.5;
            word-break: break-word;
            overflow-wrap: anywhere;
            text-align: center;
            margin-bottom: 1rem;
        }

        .file-meta {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .meta-item {
            text-align: center;
        }

        .meta-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #fff;
        }

        .meta-label {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Warnings */
        .warnings {
            padding: 1.5rem 2rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .warning-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 10px;
        }

        .warning-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            stroke: rgba(255, 255, 255, 0.5);
        }

        .warning-text {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.4;
        }

        .warning-text strong {
            color: rgba(255, 255, 255, 0.9);
        }

        /* Download Button */
        .download-section {
            padding: 1.5rem 2rem 2rem;
        }

        .download-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 1rem 1.5rem;
            background: #fff;
            color: #000;
            font-size: 1rem;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .download-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.08), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            100% {
                left: 100%;
            }
        }

        .download-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 255, 255, 0.2);
        }

        .download-btn:active {
            transform: translateY(-1px);
        }

        .download-btn svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            animation: bounce 2s ease infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-4px);
            }
        }

        .download-btn.disabled {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.4);
            cursor: not-allowed;
            pointer-events: none;
        }

        .download-btn.disabled::before {
            display: none;
        }

        .download-btn.disabled svg {
            animation: none;
        }

        /* Encoding state */
        .encoding-message {
            text-align: center;
            padding: 1rem;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
        }

        .encoding-message .spinner {
            width: 24px;
            height: 24px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 0.75rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Back link */
        .back-link {
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.4);
            text-align: center;
        }

        .back-link a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: underline;
            transition: color 0.2s;
        }

        .back-link a:hover {
            color: #fff;
        }

        /* Footer */
        .footer {
            padding: 1.5rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.2);
            font-size: 0.7rem;
            position: relative;
            z-index: 1;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.4);
        }

        .footer a:hover {
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .navbar {
                padding: 0.75rem 1rem;
            }

            .logo {
                font-size: 1rem;
            }

            .logo-icon {
                width: 28px;
                height: 28px;
            }

            .nav-btns .btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
            }

            .main-content {
                padding: 5rem 1rem 1rem;
            }

            .server-card {
                border-radius: 16px;
            }

            .server-header,
            .file-info,
            .warnings,
            .download-section {
                padding-left: 1.25rem;
                padding-right: 1.25rem;
            }

            .server-icon {
                width: 52px;
                height: 52px;
            }

            .server-icon svg {
                width: 26px;
                height: 26px;
            }

            .server-title {
                font-size: 1.25rem;
            }

            .file-meta {
                gap: 1rem;
            }
        }
    </style>

    @if($popunderCode ?? false)
    {!! $popunderCode !!}
    @endif

    <script src="{{ asset('js/aab.min.js') }}" defer></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24">
                        <polygon points="5,3 19,12 5,21" />
                    </svg>
                </div>
                <span>VidStreaming</span>
            </a>
            <div class="nav-btns">
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-white">Dashboard</a>
                @else
                <a href="{{ url('/login') }}" class="btn btn-ghost">Login</a>
                <a href="{{ url('/register') }}" class="btn btn-white">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="bg-pattern"></div>

    <div class="main-content">
        <div class="server-card">
            <div class="server-header">
                <div class="server-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                        <line x1="6" y1="6" x2="6.01" y2="6"></line>
                        <line x1="6" y1="18" x2="6.01" y2="18"></line>
                    </svg>
                </div>
                <h1 class="server-title">Download Server</h1>
                <span class="server-speed">
                    <span class="speed-dot"></span>
                    {{ $serverSpeed }} Server
                </span>
            </div>

            <div class="file-info">
                <div class="file-name">{{ $file->filename }}</div>
                <div class="file-meta">
                    <div class="meta-item">
                        <div class="meta-value">{{ \App\Helpers\FormatHelper::formatBytes($file->file_size) }}</div>
                        <div class="meta-label">Size</div>
                    </div>
                    @if($file->resolution)
                    <div class="meta-item">
                        <div class="meta-value">{{ $file->resolution }}</div>
                        <div class="meta-label">Quality</div>
                    </div>
                    @endif
                    @if($file->rip_type)
                    <div class="meta-item">
                        <div class="meta-value">{{ strtoupper($file->rip_type) }}</div>
                        <div class="meta-label">Source</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="warnings">
                <div class="warning-item">
                    <svg class="warning-icon" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span class="warning-text"><strong>Resume not supported.</strong> If interrupted, download must restart from beginning.</span>
                </div>
                <div class="warning-item">
                    <svg class="warning-icon" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
                        <path d="M1.42 9a16 16 0 0 1 21.16 0"></path>
                        <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
                        <line x1="12" y1="20" x2="12.01" y2="20"></line>
                    </svg>
                    <span class="warning-text"><strong>Stable internet required.</strong> Ensure uninterrupted connection for large files.</span>
                </div>
            </div>

            <div class="download-section">
                <!-- Script blocked message - shown by default, hidden by aab.min.js -->
                <div id="dl-verify" style="text-align:center;padding:1.5rem;">
                    <div style="width:60px;height:60px;margin:0 auto 1.25rem;background:linear-gradient(135deg,#ff6b6b,#ee5a24);border-radius:50%;display:flex;align-items:center;justify-content:center;animation:pulse 2s infinite;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                        </svg>
                    </div>
                    <div style="font-size:1.1rem;font-weight:600;color:#fff;margin-bottom:.5rem;">Download Blocked</div>
                    <div style="font-size:.875rem;color:rgba(255,255,255,.6);line-height:1.5;max-width:280px;margin:0 auto;">
                        Please turn off your ad blocker and refresh the page.
                    </div>
                </div>
                <style>
                    @keyframes pulse {

                        0%,
                        100% {
                            transform: scale(1);
                            opacity: 1
                        }

                        50% {
                            transform: scale(1.05);
                            opacity: .9
                        }
                    }
                </style>

                <!-- Actual download form - hidden by default -->
                <div id="dl-form" style="display:none;">
                    @if($downloadAvailable)
                    <form id="f" method="POST" action="{{ route('player.download.start', ['slug' => $file->slug, 'server' => $server]) }}" target="_blank" style="margin: 0;">
                        @csrf
                        <input type="hidden" name="_t" value="{{ $downloadToken }}">
                        <button type="submit" class="download-btn" id="startDownloadBtn">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                <polyline points="7,10 12,15 17,10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                            <span>Start Download</span>
                        </button>
                    </form>
                    @else
                    <div class="encoding-message">
                        <div class="spinner"></div>
                        Download not available. Video may still be processing.
                    </div>
                    <button class="download-btn disabled">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                            <polyline points="7,10 12,15 17,10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                        Download Unavailable
                    </button>
                    @endif
                </div><!-- /dl-form -->

                <div class="back-link" style="margin-top:0.5rem;">
                    <a href="{{ route('player.download', $file->slug) }}">← Back to file info</a>
                </div>
            </div>
        </div>

        <div class="footer">
            Powered by <a href="{{ url('/') }}">VidStreaming</a>
        </div>

        <script>
            (function() {
                var f = document.getElementById('f');
                if (!f) return;

                // Ad interception
                var directLinks = @json($directLinks ?? []);
                var adClicked = false;
                var linkIndex = 0;

                f.onsubmit = function(e) {
                    if (directLinks.length > 0 && !adClicked) {
                        e.preventDefault();
                        window.open(directLinks[linkIndex % directLinks.length], '_blank');
                        linkIndex++;
                        adClicked = true;

                        var btnText = f.querySelector('.download-btn span');
                        if (btnText) btnText.textContent = 'Click Again to Download';

                        return false;
                    }
                    // Second click proceeds to download (action is already set)
                };

                f.oncontextmenu = function(e) {
                    e.preventDefault();
                };
            })();
        </script>
</body>

</html>