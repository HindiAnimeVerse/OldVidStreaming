<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - VidStreaming</title>
    <meta name="description" content="Reset your VidStreaming password">
    @php $favicon = \App\Models\Setting::get('site_favicon'); @endphp
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
        }

        html {
            height: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--black);
            color: var(--white);
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ===== ANIMATIONS ===== */
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

        @keyframes glowPulse {

            0%,
            100% {
                opacity: 0.1;
                transform: scale(1);
            }

            50% {
                opacity: 0.2;
                transform: scale(1.1);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes checkScale {
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

        /* ===== NAVBAR ===== */
        .navbar {
            padding: 1rem 1.5rem;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-container {
            width: 100%;
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

        .nav-link {
            font-size: 0.9rem;
            color: var(--gray);
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: var(--white);
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow: hidden;
        }

        .bg-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            filter: blur(120px);
            pointer-events: none;
        }

        .bg-glow-1 {
            top: -150px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--white);
            opacity: 0.08;
            animation: glowPulse 6s ease-in-out infinite;
        }

        /* ===== AUTH CARD ===== */
        .auth-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            background: var(--dark-2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2.5rem;
            animation: fadeSlideUp 0.6s ease forwards;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-icon {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            animation: float 3s ease-in-out infinite;
        }

        .auth-icon svg {
            width: 28px;
            height: 28px;
            stroke: var(--white);
            stroke-width: 1.5;
            fill: none;
        }

        .auth-title {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, var(--white) 0%, var(--gray) 50%, var(--white) 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s linear infinite;
        }

        .auth-subtitle {
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* ===== FORM STYLES ===== */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--light-gray);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            background: var(--dark-3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: var(--white);
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: var(--gray);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--white);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }

        .form-input:hover {
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* ===== BUTTONS ===== */
        .btn {
            width: 100%;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--white);
            color: var(--black);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* ===== BACK LINK ===== */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            color: var(--gray);
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--white);
        }

        .back-link svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        /* ===== SUCCESS STATE ===== */
        .success-state {
            display: none;
            text-align: center;
        }

        .success-state.active {
            display: block;
            animation: fadeSlideUp 0.4s ease;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: rgba(34, 197, 94, 0.1);
            border: 2px solid var(--success);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .success-icon svg {
            width: 36px;
            height: 36px;
            stroke: var(--success);
            stroke-width: 2.5;
            fill: none;
            animation: checkScale 0.5s ease 0.2s forwards;
            transform: scale(0);
        }

        .success-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .success-text {
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .success-email {
            color: var(--white);
            font-weight: 600;
        }

        .btn-secondary {
            background: var(--dark-3);
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            border-color: rgba(255, 255, 255, 0.3);
            background: var(--dark);
        }

        .form-state {
            display: block;
        }

        .form-state.hidden {
            display: none;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            .navbar {
                padding: 0.75rem 1rem;
            }

            .logo {
                font-size: 1.1rem;
            }

            .logo-icon {
                width: 28px;
                height: 28px;
            }

            .main {
                padding: 1.5rem 0.75rem;
            }

            .auth-card {
                padding: 1.75rem 1.25rem;
                border-radius: 16px;
            }

            .auth-icon {
                width: 56px;
                height: 56px;
            }

            .auth-icon svg {
                width: 24px;
                height: 24px;
            }

            .auth-title {
                font-size: 1.5rem;
            }

            .auth-subtitle {
                font-size: 0.85rem;
            }

            .form-input {
                padding: 0.75rem 0.875rem;
                font-size: 0.9rem;
            }

            .btn {
                padding: 0.75rem 1.25rem;
                font-size: 0.95rem;
            }

            .success-icon {
                width: 64px;
                height: 64px;
            }

            .success-icon svg {
                width: 28px;
                height: 28px;
            }
        }
    </style>
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
            <a href="{{ url('/login') }}" class="nav-link">Back to Login</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main">
        <div class="bg-glow bg-glow-1"></div>

        <div class="auth-card">
            <!-- Form State -->
            <div class="form-state" id="formState">
                <div class="auth-header">
                    <div class="auth-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </div>
                    <h1 class="auth-title">Forgot Password?</h1>
                    <p class="auth-subtitle">No worries! Enter your email and we'll send you instructions to reset your password.</p>
                </div>

                <form method="POST" action="{{ url('/forgot-password') }}" id="forgotForm">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-input"
                            placeholder="you@example.com"
                            value="{{ old('email') }}"
                            required
                            autofocus>
                    </div>

                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                </form>

                <a href="{{ url('/login') }}" class="back-link">
                    <svg viewBox="0 0 24 24">
                        <polyline points="15,18 9,12 15,6" />
                    </svg>
                    <span>Back to Sign In</span>
                </a>
            </div>

            <!-- Success State -->
            <div class="success-state" id="successState">
                <div class="success-icon">
                    <svg viewBox="0 0 24 24">
                        <polyline points="20,6 9,17 4,12" />
                    </svg>
                </div>
                <h2 class="success-title">Check Your Email</h2>
                <p class="success-text">
                    We've sent a password reset link to<br>
                    <span class="success-email" id="emailDisplay">your email</span>
                </p>
                <p class="success-text">
                    Didn't receive the email? Check your spam folder or try again.
                </p>
                <button type="button" class="btn btn-secondary" id="tryAgainBtn">Try Again</button>

                <a href="{{ url('/login') }}" class="back-link">
                    <svg viewBox="0 0 24 24">
                        <polyline points="15,18 9,12 15,6" />
                    </svg>
                    <span>Back to Sign In</span>
                </a>
            </div>
        </div>
    </main>

    <script>
        // Demo: Show success state on form submit
        const forgotForm = document.getElementById('forgotForm');
        const formState = document.getElementById('formState');
        const successState = document.getElementById('successState');
        const emailInput = document.getElementById('email');
        const emailDisplay = document.getElementById('emailDisplay');
        const tryAgainBtn = document.getElementById('tryAgainBtn');

        // For demo purposes - in production, this would be handled by server response
        forgotForm.addEventListener('submit', function(e) {
            // Uncomment below for demo mode (prevents actual form submission)
            // e.preventDefault();
            // emailDisplay.textContent = emailInput.value;
            // formState.classList.add('hidden');
            // successState.classList.add('active');
        });

        tryAgainBtn.addEventListener('click', function() {
            successState.classList.remove('active');
            formState.classList.remove('hidden');
            emailInput.value = '';
            emailInput.focus();
        });
    </script>
</body>

</html>