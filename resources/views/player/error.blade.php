<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Not Found - VidStreaming</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            background: #0a0a0a;
            overflow: hidden;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .error-screen {
            position: fixed;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .error-icon {
            width: 70px;
            height: 70px;
            margin-bottom: 1.25rem;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-icon svg {
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .error-code {
            font-size: 3rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.25rem;
            letter-spacing: -0.02em;
        }

        .error-title {
            font-size: 1.1rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 0.5rem;
        }

        .error-subtitle {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.4);
            text-align: center;
            max-width: 300px;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="error-screen">
        <div class="error-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.6)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <path d="M16 16s-1.5-2-4-2-4 2-4 2" />
                <line x1="9" y1="9" x2="9.01" y2="9" />
                <line x1="15" y1="9" x2="15.01" y2="9" />
            </svg>
        </div>
        <div class="error-code">404</div>
        <div class="error-title">Video Not Found</div>
        <div class="error-subtitle">The video you're looking for doesn't exist or has been removed.</div>
    </div>
</body>

</html>