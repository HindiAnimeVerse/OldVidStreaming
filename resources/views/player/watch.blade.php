<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $file->filename }} - VidStreaming</title>
    <meta name="robots" content="noindex, nofollow">
    @php $favicon = \App\Models\Setting::get('site_favicon'); @endphp
    @if($favicon)
    <link rel="icon" href="{{ asset($favicon) }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/x7k9.css') }}">
    @if($playerSettings['netflix_skin'] ?? true)
    <link rel="stylesheet" href="{{ asset('player/jwplayer/skin/netflix.css') }}">
    @endif
</head>

<body>
    @if($isEncoding ?? false)
    <div class="state-screen">
        <div class="state-icon">
            <div class="state-ring"></div>
            <div class="state-ring"></div>
            <div class="state-ring"></div>
            <div class="state-center">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
            </div>
        </div>
        <div class="state-title">
            Video Encoding<span class="state-dots"><span></span><span></span><span></span></span>
        </div>
        <div class="state-subtitle">
            Your video is being processed and will be available shortly.
        </div>
        <div class="state-progress">
            <div class="state-progress-bar"></div>
        </div>
        <div class="file-ref">{{ $file->filename }}</div>
    </div>
    @else

    <div id="sb-warn" class="state-screen">
        <div class="sb-icon">
            <div class="sb-wave"></div>
            <div class="sb-wave"></div>
            <div class="sb-wave"></div>
            <svg class="sb-svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
            </svg>
        </div>
        <div class="sb-title">Playback Blocked</div>
        <div class="sb-text">Please disable your ad blocker and refresh to watch.</div>
        <a href="" id="sb-btn">Refresh Page</a>
    </div>

    <div id="player" style="display:none;"></div>
    <script src="{{ asset('assets/js/core.min.js') }}"></script>
    <script id="x" type="x">{{ $encodedScript }}</script>

    @endif
</body>

</html>