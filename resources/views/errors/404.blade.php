<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    @php $favicon = \App\Models\Setting::get('site_favicon'); @endphp
    @if($favicon)
    <link rel="icon" href="{{ asset($favicon) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
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
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--black);
            color: var(--white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        /* Animated background grid */
        .grid-bg {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(50px, 50px);
            }
        }

        /* Glowing orb */
        .orb {
            position: fixed;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.5;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 0.8;
            }
        }

        /* 404 Number */
        .error-code {
            font-size: clamp(8rem, 25vw, 14rem);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -0.05em;
            background: linear-gradient(180deg, var(--white) 0%, var(--gray) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            animation: glitchText 3s ease-in-out infinite;
        }

        @keyframes glitchText {

            0%,
            90%,
            100% {
                transform: translate(0);
            }

            92% {
                transform: translate(-2px, 2px);
            }

            94% {
                transform: translate(2px, -2px);
            }

            96% {
                transform: translate(-1px, -1px);
            }

            98% {
                transform: translate(1px, 1px);
            }
        }

        .error-code::before,
        .error-code::after {
            content: '404';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, var(--white) 0%, var(--gray) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .error-code::before {
            animation: glitchBefore 3s ease-in-out infinite;
            clip-path: polygon(0 0, 100% 0, 100% 35%, 0 35%);
        }

        .error-code::after {
            animation: glitchAfter 3s ease-in-out infinite;
            clip-path: polygon(0 65%, 100% 65%, 100% 100%, 0 100%);
        }

        @keyframes glitchBefore {

            0%,
            90%,
            100% {
                transform: translate(0);
            }

            92% {
                transform: translate(3px, 0);
            }

            94% {
                transform: translate(-3px, 0);
            }

            96% {
                transform: translate(2px, 0);
            }
        }

        @keyframes glitchAfter {

            0%,
            90%,
            100% {
                transform: translate(0);
            }

            92% {
                transform: translate(-3px, 0);
            }

            94% {
                transform: translate(3px, 0);
            }

            96% {
                transform: translate(-2px, 0);
            }
        }

        .title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 1rem;
            animation: fadeInUp 0.8s ease 0.2s backwards;
        }

        .message {
            color: var(--gray);
            font-size: 1rem;
            max-width: 400px;
            margin: 1rem auto 2rem;
            line-height: 1.6;
            animation: fadeInUp 0.8s ease 0.4s backwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            background: var(--white);
            color: var(--black);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            animation: fadeInUp 0.8s ease 0.6s backwards;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(255, 255, 255, 0.2);
        }

        .btn svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            transition: transform 0.3s ease;
        }

        .btn:hover svg {
            transform: translateX(-3px);
        }

        /* Floating particles */
        .particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 15s linear infinite;
        }

        .particle:nth-child(1) {
            left: 10%;
            animation-delay: 0s;
            animation-duration: 12s;
        }

        .particle:nth-child(2) {
            left: 20%;
            animation-delay: 2s;
            animation-duration: 14s;
        }

        .particle:nth-child(3) {
            left: 30%;
            animation-delay: 4s;
            animation-duration: 11s;
        }

        .particle:nth-child(4) {
            left: 40%;
            animation-delay: 1s;
            animation-duration: 16s;
        }

        .particle:nth-child(5) {
            left: 50%;
            animation-delay: 3s;
            animation-duration: 13s;
        }

        .particle:nth-child(6) {
            left: 60%;
            animation-delay: 5s;
            animation-duration: 15s;
        }

        .particle:nth-child(7) {
            left: 70%;
            animation-delay: 2s;
            animation-duration: 12s;
        }

        .particle:nth-child(8) {
            left: 80%;
            animation-delay: 4s;
            animation-duration: 14s;
        }

        .particle:nth-child(9) {
            left: 90%;
            animation-delay: 1s;
            animation-duration: 11s;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100vh) scale(1);
                opacity: 0;
            }
        }

        /* Scan line effect */
        .scanline {
            position: fixed;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: scan 6s linear infinite;
            pointer-events: none;
        }

        @keyframes scan {
            0% {
                top: -10px;
            }

            100% {
                top: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="grid-bg"></div>
    <div class="orb"></div>
    <div class="scanline"></div>

    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="container">
        <div class="error-code">404</div>
        <h1 class="title">Page Not Found</h1>
        <p class="message">
            The page you're looking for doesn't exist or has been moved to another location.
        </p>
        <a href="{{ url('/') }}" class="btn">
            <svg viewBox="0 0 24 24">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Back to Home
        </a>
    </div>
</body>

</html>