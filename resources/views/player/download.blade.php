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
                    // Android: Use intent to open in Chrome
                    var intent = 'intent://' + url.replace(/^https?:\/\//, '') + '#Intent;scheme=https;package=com.android.chrome;end';
                    window.location.href = intent;
                } else if (isIOS) {
                    // iOS: Try googlechrome:// scheme
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
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 7rem 2rem 2rem;
            position: relative;
            z-index: 1;
        }

        /* Card */
        .download-card {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            overflow: hidden;
            animation: fadeUp 0.6s ease-out;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header - centered filename */
        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            text-align: center;
        }

        .file-name {
            font-size: clamp(0.8rem, 1.8vw, 0.95rem);
            font-weight: 600;
            color: #fff;
            line-height: 1.5;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        /* Meta info */
        .card-meta {
            padding: 0.75rem 1.5rem;
            display: grid;
            gap: 0;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.4rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .meta-row:last-child {
            border-bottom: none;
        }

        .meta-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 500;
        }

        .meta-value {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }

        /* Download section */
        .card-download {
            padding: 1rem 1.5rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .download-note {
            text-align: center;
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 0.75rem;
        }

        .download-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            width: 100%;
            padding: 0.75rem 1.25rem;
            background: #fff;
            color: #000;
            font-size: 0.85rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s ease;
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
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.05), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            100% {
                left: 100%;
            }
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 255, 255, 0.15);
        }

        .download-btn:active {
            transform: translateY(0);
        }

        .download-btn svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            animation: bounce 2s ease infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-3px);
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
            text-decoration: none;
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

            .card-header {
                padding: 1.25rem;
            }

            .card-meta,
            .card-download {
                padding-left: 1.25rem;
                padding-right: 1.25rem;
            }

            .meta-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
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
        <div class="download-card">
            <div class="card-header">
                <div class="file-name">{{ $file->filename }}</div>
            </div>

            @php
            $prints = [];
            if ($file->rip_type) $prints[] = strtoupper($file->rip_type);
            if ($file->resolution) $prints[] = $file->resolution;
            if ($file->codec) $prints[] = strtoupper($file->codec);

            $printsStr = implode(' | ', array_unique($prints));
            @endphp

            <div class="card-meta">
                @if($printsStr)
                <div class="meta-row">
                    <span class="meta-label">Prints</span>
                    <span class="meta-value">{{ $printsStr }}</span>
                </div>
                @endif
                <div class="meta-row">
                    <span class="meta-label">Size</span>
                    <span class="meta-value">{{ \App\Helpers\FormatHelper::formatBytes($file->file_size) }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Added</span>
                    <span class="meta-value">{{ $file->created_at->format('d M Y, g:i A') }}</span>
                </div>
                @if($file->user)
                <div class="meta-row">
                    <span class="meta-label">Shared By</span>
                    <span class="meta-value">{{ $file->user->name }}</span>
                </div>
                @endif
            </div>

            <div class="card-download">
                <div class="download-note">
                    Click the button below to proceed to download
                </div>

                <!-- Script blocked message - shown by default -->
                <div id="dl-verify" style="text-align:center;padding:1.5rem;">
                    <div style="width:50px;height:50px;margin:0 auto 1rem;background:linear-gradient(135deg,#ff6b6b,#ee5a24);border-radius:50%;display:flex;align-items:center;justify-content:center;animation:pulse 2s infinite;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                        </svg>
                    </div>
                    <div style="font-size:1rem;font-weight:600;color:#fff;margin-bottom:.375rem;">Scripts Blocked</div>
                    <div style="font-size:.8rem;color:rgba(255,255,255,.6);line-height:1.4;">
                        Disable ad blocker to download
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

                <!-- Actual button - hidden by default -->
                <div id="dl-form" style="display:none;">
                    <a href="{{ URL::temporarySignedRoute('player.download.server', now()->addMinutes(5), ['slug' => $file->slug, 'server' => '10gbps']) }}" class="download-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                            <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                            <line x1="6" y1="6" x2="6.01" y2="6"></line>
                            <line x1="6" y1="18" x2="6.01" y2="18"></line>
                        </svg>
                        Download Server 10Gbps
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        Powered by <a href="{{ url('/') }}">VidStreaming</a>
    </div>
</body>

</html>