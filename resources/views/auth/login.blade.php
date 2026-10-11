<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - VidStreaming</title>
    <meta name="description" content="Login to your VidStreaming account">
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

        @keyframes borderGlow {

            0%,
            100% {
                border-color: rgba(255, 255, 255, 0.1);
            }

            50% {
                border-color: rgba(255, 255, 255, 0.3);
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
            top: -200px;
            right: -100px;
            background: var(--white);
            opacity: 0.08;
            animation: glowPulse 6s ease-in-out infinite;
        }

        .bg-glow-2 {
            bottom: -200px;
            left: -100px;
            background: var(--gray);
            opacity: 0.06;
            animation: glowPulse 8s ease-in-out infinite 2s;
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
            font-size: 0.95rem;
        }

        /* ===== FORM STYLES ===== */
        .form-group {
            margin-bottom: 1.25rem;
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

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray);
            cursor: pointer;
        }

        .checkbox-label input {
            width: 18px;
            height: 18px;
            accent-color: var(--white);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.875rem;
            color: var(--gray);
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: var(--white);
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

        /* ===== DIVIDER ===== */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .divider-text {
            font-size: 0.8rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ===== SOCIAL BUTTONS ===== */
        .social-btns {
            display: flex;
            gap: 0.75rem;
        }

        .btn-social {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem;
            background: var(--dark-3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: var(--light-gray);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-social:hover {
            border-color: rgba(255, 255, 255, 0.3);
            background: var(--dark);
        }

        .btn-social svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        /* ===== FOOTER LINK ===== */
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .auth-footer p {
            font-size: 0.9rem;
            color: var(--gray);
        }

        .auth-footer a {
            color: var(--white);
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .auth-footer a:hover {
            opacity: 0.8;
        }

        /* ===== ERROR MESSAGES ===== */
        .error-msg {
            color: var(--error);
            font-size: 0.8rem;
            margin-top: 0.35rem;
        }

        .form-input.error {
            border-color: var(--error);
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

            .auth-title {
                font-size: 1.5rem;
            }

            .auth-subtitle {
                font-size: 0.875rem;
            }

            .form-input {
                padding: 0.75rem 0.875rem;
                font-size: 0.9rem;
            }

            .form-row {
                flex-direction: column;
                gap: 0.75rem;
                align-items: flex-start;
            }

            .btn {
                padding: 0.75rem 1.25rem;
                font-size: 0.95rem;
            }

            .social-btns {
                flex-direction: column;
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
            <a href="{{ url('/register') }}" class="nav-link">Create Account</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main">
        <div class="bg-glow bg-glow-1"></div>
        <div class="bg-glow bg-glow-2"></div>

        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-subtitle">Sign in to your account to continue</p>
            </div>

            @if(session('error'))
            <div class="alert-error" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem;">
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert-success" style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #22c55e; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem;">
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
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

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="••••••••"
                        required>
                </div>

                <div class="form-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="{{ url('/forgot-password') }}" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>

            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">or continue with</span>
                <div class="divider-line"></div>
            </div>

            <div class="social-btns">
                <button type="button" class="btn-social">
                    <svg viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    Google
                </button>
                <button type="button" class="btn-social">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                    </svg>
                    GitHub
                </button>
            </div>

            <div class="auth-footer">
                <p>Don't have an account? <a href="{{ url('/register') }}">Sign up for free</a></p>
            </div>
        </div>
    </main>

    <script>
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.textContent;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Show loading
            submitBtn.disabled = true;
            submitBtn.textContent = 'Signing in...';

            // Clear previous errors
            document.querySelectorAll('.error-msg').forEach(el => el.remove());
            document.querySelectorAll('.form-input.error').forEach(el => el.classList.remove('error'));

            try {
                const response = await fetch('{{ url("/login") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value,
                        remember: document.querySelector('input[name="remember"]').checked
                    })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    showError(data.message || 'Invalid email or password');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    const errors = await error.response.json();
                    Object.keys(errors.errors || {}).forEach(field => {
                        const input = document.getElementById(field);
                        if (input) {
                            input.classList.add('error');
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'error-msg';
                            errorDiv.textContent = errors.errors[field][0];
                            input.parentNode.appendChild(errorDiv);
                        }
                    });
                } else {
                    showError('Invalid email or password');
                }
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }
        });

        function showError(message) {
            const authHeader = document.querySelector('.auth-header');
            let alertDiv = document.querySelector('.alert-error');
            if (!alertDiv) {
                alertDiv = document.createElement('div');
                alertDiv.className = 'alert-error';
                alertDiv.style.cssText = 'background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem;';
                authHeader.after(alertDiv);
            }
            alertDiv.textContent = message;
        }
    </script>
</body>

</html>