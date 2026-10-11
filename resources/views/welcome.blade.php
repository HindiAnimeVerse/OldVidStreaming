<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VidStreaming - Premium Video Hosting</title>
    <meta name="description" content="VidStreaming - Unlimited storage, lifetime retention, 1080p streaming, 10Gbps downloads.">
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
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--black);
            color: var(--white);
            line-height: 1.5;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ===== NAVBAR ===== */
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
            padding: 0 2rem;
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

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            font-size: 0.9rem;
            color: var(--gray);
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--white);
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
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-ghost:hover {
            border-color: var(--white);
        }

        .btn-white {
            background: var(--white);
            color: var(--black);
        }

        .btn-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
        }

        .mobile-menu {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
        }

        .mobile-menu span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--white);
            margin: 5px 0;
        }

        /* ===== HERO ===== */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6rem 1.5rem 3rem;
            position: relative;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.12;
            animation: glow-pulse 4s ease-in-out infinite;
        }

        .hero-glow-1 {
            top: -200px;
            right: -100px;
            background: var(--white);
        }

        .hero-glow-2 {
            bottom: -200px;
            left: -100px;
            background: var(--gray);
            animation-delay: 2s;
        }

        @keyframes glow-pulse {

            0%,
            100% {
                opacity: 0.12;
                transform: scale(1);
            }

            50% {
                opacity: 0.2;
                transform: scale(1.1);
            }
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            width: 100%;
            padding: 0 2rem;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            font-size: 0.8rem;
            color: var(--light-gray);
            margin-bottom: 1.5rem;
            animation: fade-slide-up 0.6s ease forwards;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            background: #22c55e;
            border-radius: 50%;
            animation: pulse-dot 1.5s infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.2);
            }
        }

        .hero-title {
            font-size: clamp(2.5rem, 7vw, 4.5rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1rem;
            animation: fade-slide-up 0.6s ease 0.1s forwards;
            opacity: 0;
        }

        .hero-title span {
            background: linear-gradient(90deg, var(--white) 0%, var(--gray) 50%, var(--white) 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: text-shimmer 3s linear infinite;
        }

        @keyframes text-shimmer {
            0% {
                background-position: 0% center;
            }

            100% {
                background-position: 200% center;
            }
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.15rem);
            color: var(--gray);
            max-width: 800px;
            margin: 0 auto 2rem;
            animation: fade-slide-up 0.6s ease 0.2s forwards;
            opacity: 0;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 3rem;
            animation: fade-slide-up 0.6s ease 0.3s forwards;
            opacity: 0;
        }

        .btn-lg {
            padding: 0.85rem 2rem;
            font-size: 1rem;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            flex-wrap: wrap;
            animation: fade-slide-up 0.6s ease 0.4s forwards;
            opacity: 0;
        }

        .stat {
            text-align: center;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            display: block;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        @keyframes fade-slide-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== FEATURES ===== */
        .features {
            padding: 4rem 1.5rem;
            background: var(--dark);
        }

        .container {
            width: 100%;
            padding: 0 3rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-tag {
            display: inline-block;
            padding: 0.35rem 0.85rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gray);
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            font-weight: 800;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
        }

        .feature-card {
            background: var(--dark-2);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            animation: card-appear 0.5s ease forwards;
            opacity: 0;
        }

        .feature-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .feature-card:nth-child(2) {
            animation-delay: 0.15s;
        }

        .feature-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .feature-card:nth-child(4) {
            animation-delay: 0.25s;
        }

        .feature-card:nth-child(5) {
            animation-delay: 0.3s;
        }

        .feature-card:nth-child(6) {
            animation-delay: 0.35s;
        }

        @keyframes card-appear {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .feature-icon svg {
            width: 24px;
            height: 24px;
            stroke: var(--white);
            stroke-width: 1.5;
            fill: none;
        }

        .feature-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .feature-desc {
            font-size: 0.9rem;
            color: var(--gray);
            line-height: 1.6;
        }

        /* ===== STREAMING SECTION ===== */
        .streaming {
            padding: 4rem 1.5rem;
            background: var(--black);
        }

        .streaming-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 3rem;
            align-items: center;
        }

        .streaming-content h2 {
            font-size: clamp(1.5rem, 3.5vw, 2.25rem);
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .streaming-content p {
            color: var(--gray);
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            line-height: 1.7;
        }

        .check-list {
            list-style: none;
        }

        .check-list li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            color: var(--light-gray);
        }

        .check-icon {
            width: 20px;
            height: 20px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .check-icon svg {
            width: 12px;
            height: 12px;
            stroke: var(--black);
            stroke-width: 3;
        }

        /* Quality Player */
        .quality-player {
            background: var(--dark-2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            overflow: hidden;
        }

        .player-screen {
            position: relative;
            aspect-ratio: 16/9;
            background: linear-gradient(135deg, #1a1a1a 0%, #0a0a0a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .player-content {
            text-align: center;
            transition: all 0.4s ease;
        }

        .player-quality-display {
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 0.25rem;
            transition: all 0.3s ease;
        }

        .player-quality-label {
            font-size: 1rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .player-bars {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 4px;
        }

        .player-bar {
            width: 4px;
            background: var(--white);
            border-radius: 2px;
            animation: bar-dance 0.8s ease-in-out infinite;
        }

        .player-bar:nth-child(1) {
            height: 15px;
            animation-delay: 0s;
        }

        .player-bar:nth-child(2) {
            height: 25px;
            animation-delay: 0.1s;
        }

        .player-bar:nth-child(3) {
            height: 20px;
            animation-delay: 0.2s;
        }

        .player-bar:nth-child(4) {
            height: 30px;
            animation-delay: 0.3s;
        }

        .player-bar:nth-child(5) {
            height: 18px;
            animation-delay: 0.4s;
        }

        @keyframes bar-dance {

            0%,
            100% {
                transform: scaleY(1);
            }

            50% {
                transform: scaleY(0.5);
            }
        }

        .quality-selector {
            display: flex;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .quality-btn {
            flex: 1;
            padding: 1rem;
            background: transparent;
            border: none;
            color: var(--gray);
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .quality-btn:last-child {
            border-right: none;
        }

        .quality-btn:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--white);
        }

        .quality-btn.active {
            background: var(--white);
            color: var(--black);
        }

        /* ===== SPEED SECTION ===== */
        .speed {
            padding: 4rem 1.5rem;
            background: var(--dark);
        }

        .speed-container {
            text-align: center;
        }

        .speed-visual {
            margin: 2.5rem auto;
            max-width: 400px;
        }

        .speed-meter {
            position: relative;
            width: 220px;
            height: 220px;
            margin: 0 auto;
        }

        .speed-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.1);
        }

        .speed-ring-progress {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: var(--white);
            border-right-color: var(--white);
            border-bottom-color: var(--white);
            animation: speed-spin 2s linear infinite;
        }

        @keyframes speed-spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .speed-inner {
            position: absolute;
            inset: 15px;
            background: var(--dark);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .speed-value {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1;
            animation: speed-pulse 1.5s ease-in-out infinite;
        }

        @keyframes speed-pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .speed-unit {
            font-size: 1.25rem;
            color: var(--gray);
            font-weight: 600;
        }

        .speed-features {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .speed-feature {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--light-gray);
            font-size: 0.9rem;
        }

        .speed-feature svg {
            width: 18px;
            height: 18px;
            stroke: var(--white);
            stroke-width: 2;
            fill: none;
        }

        /* ===== CTA ===== */
        .cta {
            padding: 4rem 1.5rem;
            background: var(--black);
            text-align: center;
        }

        .cta h2 {
            font-size: clamp(1.75rem, 4vw, 2.75rem);
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .cta p {
            color: var(--gray);
            max-width: 500px;
            margin: 0 auto 2rem;
            font-size: 1rem;
        }

        .cta-btns {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* ===== FOOTER ===== */
        .footer {
            padding: 3rem 1.5rem 1.5rem;
            background: var(--dark);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr repeat(3, 1fr);
            gap: 2rem;
            padding: 0 3rem;
            margin-bottom: 2rem;
        }

        .footer-brand p {
            color: var(--gray);
            font-size: 0.875rem;
            margin-top: 1rem;
            line-height: 1.6;
            max-width: 280px;
        }

        .footer-links h4 {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1rem;
            color: var(--light-gray);
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: var(--gray);
            font-size: 0.875rem;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--white);
        }

        .footer-bottom {
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            color: var(--gray);
            font-size: 0.8rem;
            padding-left: 3rem;
            padding-right: 3rem;
        }

        /* ===== RESPONSIVE ===== */

        /* Large Tablet / Small Desktop */
        @media (max-width: 1024px) {
            .nav-container {
                padding: 0 1.5rem;
            }

            .container {
                padding: 0 2rem;
            }

            .hero-content {
                padding: 0 1.5rem;
            }

            .streaming-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .streaming-content {
                order: 1;
                text-align: center;
            }

            .quality-player {
                order: 2;
                max-width: 600px;
                margin: 0 auto;
            }

            .check-list {
                display: inline-block;
                text-align: left;
            }

            .footer-grid {
                padding: 0 2rem;
            }

            .footer-bottom {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        /* Tablet */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.75rem 1rem;
            }

            .nav-container {
                padding: 0 0.5rem;
            }

            .nav-links,
            .nav-btns {
                display: none;
            }

            .mobile-menu {
                display: block;
            }

            .hero {
                padding: 5rem 1rem 2rem;
                min-height: auto;
            }

            .hero-content {
                padding: 0 0.5rem;
            }

            .hero-title {
                font-size: 2.25rem;
                line-height: 1.15;
            }

            .hero-subtitle {
                font-size: 0.95rem;
                max-width: 100%;
                padding: 0 0.5rem;
            }

            .hero-badge {
                font-size: 0.7rem;
                padding: 0.35rem 0.75rem;
            }

            .hero-cta {
                flex-direction: column;
                align-items: center;
                gap: 0.75rem;
            }

            .hero-cta .btn {
                width: 100%;
                max-width: 280px;
                justify-content: center;
            }

            .hero-stats {
                gap: 1.5rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .stat {
                min-width: 80px;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .stat-label {
                font-size: 0.65rem;
            }

            .features {
                padding: 3rem 1rem;
            }

            .container {
                padding: 0 1rem;
            }

            .section-header {
                margin-bottom: 2rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .section-tag {
                font-size: 0.65rem;
                padding: 0.3rem 0.7rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .feature-card {
                padding: 1.25rem;
            }

            .feature-icon {
                width: 40px;
                height: 40px;
            }

            .feature-icon svg {
                width: 20px;
                height: 20px;
            }

            .feature-title {
                font-size: 1rem;
            }

            .feature-desc {
                font-size: 0.85rem;
            }

            .streaming {
                padding: 3rem 1rem;
            }

            .streaming-content h2 {
                font-size: 1.5rem;
            }

            .streaming-content p {
                font-size: 0.9rem;
            }

            .check-list li {
                font-size: 0.85rem;
                margin-bottom: 0.5rem;
            }

            .check-icon {
                width: 18px;
                height: 18px;
            }

            .check-icon svg {
                width: 10px;
                height: 10px;
            }

            .quality-player {
                max-width: 100%;
                width: 100%;
            }

            .player-quality-display {
                font-size: 3rem;
            }

            .player-quality-label {
                font-size: 0.85rem;
            }

            .quality-btn {
                padding: 0.85rem 0.75rem;
                font-size: 0.85rem;
            }

            .speed {
                padding: 3rem 1rem;
            }

            .speed-meter {
                width: 180px;
                height: 180px;
            }

            .speed-value {
                font-size: 2.75rem;
            }

            .speed-unit {
                font-size: 1rem;
            }

            .speed-features {
                flex-direction: column;
                align-items: center;
                gap: 0.75rem;
            }

            .speed-feature {
                font-size: 0.85rem;
            }

            .cta {
                padding: 3rem 1rem;
            }

            .cta h2 {
                font-size: 1.5rem;
            }

            .cta p {
                font-size: 0.9rem;
                max-width: 100%;
            }

            .cta-btns {
                flex-direction: column;
                align-items: center;
            }

            .cta-btns .btn {
                width: 100%;
                max-width: 280px;
                justify-content: center;
            }

            .footer {
                padding: 2rem 1rem 1rem;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem;
                padding: 0 0.5rem;
            }

            .footer-brand {
                grid-column: 1 / -1;
                text-align: center;
            }

            .footer-brand p {
                max-width: 100%;
                margin: 1rem auto 0;
            }

            .footer-links {
                text-align: center;
            }

            .footer-links h4 {
                font-size: 0.75rem;
                margin-bottom: 0.75rem;
            }

            .footer-links a {
                font-size: 0.8rem;
            }

            .footer-bottom {
                padding: 1rem 0.5rem 0;
                font-size: 0.75rem;
            }
        }

        /* Mobile */
        @media (max-width: 480px) {
            .navbar {
                padding: 0.6rem 0.75rem;
            }

            .logo {
                font-size: 1rem;
                gap: 0.4rem;
            }

            .logo-icon {
                width: 28px;
                height: 28px;
                border-radius: 6px;
            }

            .logo-icon svg {
                width: 14px;
                height: 14px;
            }

            .mobile-menu span {
                width: 20px;
                margin: 4px 0;
            }

            .hero {
                padding: 4.5rem 0.75rem 1.5rem;
            }

            .hero-content {
                padding: 0;
            }

            .hero-badge {
                font-size: 0.65rem;
                padding: 0.3rem 0.6rem;
                margin-bottom: 1rem;
            }

            .badge-dot {
                width: 5px;
                height: 5px;
            }

            .hero-title {
                font-size: 1.75rem;
                margin-bottom: 0.75rem;
            }

            .hero-subtitle {
                font-size: 0.85rem;
                margin-bottom: 1.5rem;
                line-height: 1.5;
            }

            .hero-cta {
                margin-bottom: 2rem;
            }

            .btn {
                padding: 0.55rem 1rem;
                font-size: 0.8rem;
            }

            .btn-lg {
                padding: 0.7rem 1.5rem;
                font-size: 0.9rem;
            }

            .hero-stats {
                gap: 1rem;
            }

            .stat-value {
                font-size: 1.25rem;
            }

            .stat-label {
                font-size: 0.6rem;
            }

            .features {
                padding: 2.5rem 0.75rem;
            }

            .container {
                padding: 0 0.75rem;
            }

            .section-header {
                margin-bottom: 1.5rem;
            }

            .section-title {
                font-size: 1.35rem;
            }

            .feature-card {
                padding: 1rem;
            }

            .feature-icon {
                width: 36px;
                height: 36px;
                margin-bottom: 0.75rem;
            }

            .feature-icon svg {
                width: 18px;
                height: 18px;
            }

            .feature-title {
                font-size: 0.95rem;
                margin-bottom: 0.35rem;
            }

            .feature-desc {
                font-size: 0.8rem;
                line-height: 1.5;
            }

            .streaming {
                padding: 2.5rem 0.75rem;
            }

            .streaming-content {
                text-align: left;
            }

            .streaming-content h2 {
                font-size: 1.35rem;
            }

            .streaming-content p {
                font-size: 0.85rem;
                margin-bottom: 1rem;
            }

            .check-list li {
                font-size: 0.8rem;
                gap: 0.5rem;
            }

            .player-screen {
                aspect-ratio: 16/10;
            }

            .player-quality-display {
                font-size: 2.5rem;
            }

            .player-quality-label {
                font-size: 0.75rem;
            }

            .player-bars {
                bottom: 0.75rem;
            }

            .player-bar {
                width: 3px;
            }

            .quality-btn {
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
            }

            .speed {
                padding: 2.5rem 0.75rem;
            }

            .speed-visual {
                margin: 2rem auto;
            }

            .speed-meter {
                width: 150px;
                height: 150px;
            }

            .speed-inner {
                inset: 12px;
            }

            .speed-value {
                font-size: 2.25rem;
            }

            .speed-unit {
                font-size: 0.85rem;
            }

            .speed-feature {
                font-size: 0.8rem;
            }

            .speed-feature svg {
                width: 16px;
                height: 16px;
            }

            .cta {
                padding: 2.5rem 0.75rem;
            }

            .cta h2 {
                font-size: 1.35rem;
            }

            .cta p {
                font-size: 0.85rem;
                margin-bottom: 1.5rem;
            }

            .footer {
                padding: 1.5rem 0.75rem 1rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
                padding: 0;
                text-align: center;
            }

            .footer-brand .logo {
                justify-content: center;
            }

            .footer-links h4 {
                margin-bottom: 0.5rem;
            }

            .footer-links li {
                margin-bottom: 0.35rem;
            }

            .footer-bottom {
                padding: 0.75rem 0 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="#" class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24">
                        <polygon points="5,3 19,12 5,21" />
                    </svg>
                </div>
                <span>VidStreaming</span>
            </a>
            <ul class="nav-links">
                <li><a href="#features">Features</a></li>
                <li><a href="#streaming">Streaming</a></li>
                <li><a href="#speed">Speed</a></li>
            </ul>
            <div class="nav-btns">
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-white">Dashboard</a>
                @else
                <a href="{{ url('/login') }}" class="btn btn-ghost">Login</a>
                <a href="{{ url('/register') }}" class="btn btn-white">Get Started</a>
                @endauth
            </div>
            <button class="mobile-menu" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-bg">
            <div class="hero-glow hero-glow-1"></div>
            <div class="hero-glow hero-glow-2"></div>
        </div>
        <div class="hero-content">
            <div class="hero-badge">
                <span class="badge-dot"></span>
                <span>10Gbps Download Speed</span>
            </div>
            <h1 class="hero-title">
                <span>Premium Video Hosting & Streaming</span>
            </h1>
            <p class="hero-subtitle">
                Unlimited storage, lifetime retention, crystal-clear streaming up to 1080p, and blazing-fast downloads.
            </p>
            <div class="hero-cta">
                <a href="{{ url('/register') }}" class="btn btn-white btn-lg">Start Uploading</a>
                <a href="#features" class="btn btn-ghost btn-lg">Learn More</a>
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <span class="stat-value">∞</span>
                    <span class="stat-label">Storage</span>
                </div>
                <div class="stat">
                    <span class="stat-value">10 Gbps</span>
                    <span class="stat-label">Speed</span>
                </div>
                <div class="stat">
                    <span class="stat-value">1080p</span>
                    <span class="stat-label">Quality</span>
                </div>
                <div class="stat">
                    <span class="stat-value">Forever</span>
                    <span class="stat-label">Retention</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Features</span>
                <h2 class="section-title">Everything You Need</h2>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Unlimited Storage</h3>
                    <p class="feature-desc">Upload without limits. No restrictions on file size or total storage used.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12,6 12,12 16,14" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Lifetime Retention</h3>
                    <p class="feature-desc">Your files never expire. Upload once and access forever.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24">
                            <polygon points="5,3 19,12 5,21" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Multi-Quality Streaming</h3>
                    <p class="feature-desc">Stream in 1080p, 720p, or 360p. Adaptive quality for all connections.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                        </svg>
                    </div>
                    <h3 class="feature-title">10Gbps Downloads</h3>
                    <p class="feature-desc">Lightning-fast speeds from our global server network.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="2" y="3" width="20" height="14" rx="2" />
                            <line x1="8" y1="21" x2="16" y2="21" />
                            <line x1="12" y1="17" x2="12" y2="21" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Multiple Servers</h3>
                    <p class="feature-desc">Redundant download links ensure your content is always available.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22,4 12,14.01 9,11.01" />
                        </svg>
                    </div>
                    <h3 class="feature-title">No Limits</h3>
                    <p class="feature-desc">No bandwidth caps, no daily limits. Complete freedom.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Streaming -->
    <section class="streaming" id="streaming">
        <div class="container">
            <div class="streaming-grid">
                <div class="streaming-content">
                    <span class="section-tag">Streaming</span>
                    <h2>Adaptive Quality Streaming</h2>
                    <p>Our intelligent player automatically adjusts quality based on connection speed. Switch between resolutions instantly with zero buffering.</p>
                    <ul class="check-list">
                        <li>
                            <span class="check-icon"><svg viewBox="0 0 24 24">
                                    <polyline points="20,6 9,17 4,12" />
                                </svg></span>
                            Instant quality switching
                        </li>
                        <li>
                            <span class="check-icon"><svg viewBox="0 0 24 24">
                                    <polyline points="20,6 9,17 4,12" />
                                </svg></span>
                            Zero buffering experience
                        </li>
                        <li>
                            <span class="check-icon"><svg viewBox="0 0 24 24">
                                    <polyline points="20,6 9,17 4,12" />
                                </svg></span>
                            Works on all devices
                        </li>
                        <li>
                            <span class="check-icon"><svg viewBox="0 0 24 24">
                                    <polyline points="20,6 9,17 4,12" />
                                </svg></span>
                            Global CDN distribution
                        </li>
                    </ul>
                </div>
                <div class="quality-player">
                    <div class="player-screen">
                        <div class="player-content">
                            <div class="player-quality-display" id="qualityDisplay">1080p</div>
                            <div class="player-quality-label">Full HD</div>
                        </div>
                        <div class="player-bars">
                            <div class="player-bar"></div>
                            <div class="player-bar"></div>
                            <div class="player-bar"></div>
                            <div class="player-bar"></div>
                            <div class="player-bar"></div>
                        </div>
                    </div>
                    <div class="quality-selector">
                        <button class="quality-btn active" data-quality="1080p" data-label="Full HD">1080p</button>
                        <button class="quality-btn" data-quality="720p" data-label="HD">720p</button>
                        <button class="quality-btn" data-quality="360p" data-label="SD">360p</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Speed -->
    <section class="speed" id="speed">
        <div class="container speed-container">
            <span class="section-tag">Performance</span>
            <h2 class="section-title">Blazing Fast Downloads</h2>
            <div class="speed-visual">
                <div class="speed-meter">
                    <div class="speed-ring"></div>
                    <div class="speed-ring-progress"></div>
                    <div class="speed-inner">
                        <span class="speed-value">10</span>
                        <span class="speed-unit">Gbps</span>
                    </div>
                </div>
            </div>
            <div class="speed-features">
                <div class="speed-feature">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                    Instant Access
                </div>
                <div class="speed-feature">
                    <svg viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                    </svg>
                    Multiple Servers
                </div>
                <div class="speed-feature">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="2" y1="12" x2="22" y2="12" />
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                    </svg>
                    Global CDN
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <h2>Ready to Get Started?</h2>
        <p>Join thousands of creators using VidStreaming for their video hosting needs.</p>
        <div class="cta-btns">
            <a href="{{ url('/register') }}" class="btn btn-white btn-lg">Create Free Account</a>
            <a href="#" class="btn btn-ghost btn-lg">Contact Sales</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="#" class="logo">
                    <div class="logo-icon">
                        <svg viewBox="0 0 24 24">
                            <polygon points="5,3 19,12 5,21" />
                        </svg>
                    </div>
                    <span>VidStreaming</span>
                </a>
                <p>Premium video hosting with unlimited storage, forever retention, and blazing-fast streaming.</p>
            </div>
            <div class="footer-links">
                <h4>Product</h4>
                <ul>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Pricing</a></li>
                    <li><a href="#">API</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Company</h4>
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Legal</h4>
                <ul>
                    <li><a href="#">Terms</a></li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">DMCA</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} VidStreaming. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Quality Selector - Actually changes the display
        const qualityBtns = document.querySelectorAll('.quality-btn');
        const qualityDisplay = document.getElementById('qualityDisplay');
        const qualityLabel = document.querySelector('.player-quality-label');
        const playerBars = document.querySelectorAll('.player-bar');

        const barHeights = {
            '1080p': [15, 25, 20, 30, 18],
            '720p': [12, 20, 15, 22, 14],
            '360p': [8, 14, 10, 16, 9]
        };

        qualityBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active state
                qualityBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // Get quality data
                const quality = btn.dataset.quality;
                const label = btn.dataset.label;

                // Animate the display change
                qualityDisplay.style.transform = 'scale(0.8)';
                qualityDisplay.style.opacity = '0';

                setTimeout(() => {
                    qualityDisplay.textContent = quality;
                    qualityLabel.textContent = label;
                    qualityDisplay.style.transform = 'scale(1)';
                    qualityDisplay.style.opacity = '1';

                    // Update bar heights
                    const heights = barHeights[quality];
                    playerBars.forEach((bar, i) => {
                        bar.style.height = heights[i] + 'px';
                    });
                }, 150);
            });
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', e => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>