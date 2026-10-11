@extends('layouts.dashboard')

@section('title', 'Player Settings')

@section('page_title', 'Player Settings')

@section('styles')
<style>
    .player-settings {
        width: 100%;
    }

    .settings-header {
        margin-bottom: 1.5rem;
    }

    .settings-header h1 {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .settings-header p {
        color: #666;
        font-size: 0.85rem;
    }

    .settings-card {
        background: #111;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .setting-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .setting-item:last-child {
        border-bottom: none;
    }

    .setting-info {
        flex: 1;
    }

    .setting-title {
        color: #fff;
        font-size: 0.95rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .setting-title .badge {
        background: #e50914;
        color: #fff;
        font-size: 0.6rem;
        padding: 0.15rem 0.4rem;
        border-radius: 3px;
        font-weight: 600;
    }

    .setting-title .badge-blue {
        background: #0066ff;
    }

    .setting-desc {
        color: #666;
        font-size: 0.8rem;
        margin-top: 0.2rem;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
        margin-left: 1rem;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #333;
        border-radius: 24px;
        transition: all 0.25s ease;
    }

    .toggle-slider::before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background: #666;
        border-radius: 50%;
        transition: all 0.25s ease;
    }

    .toggle-switch input:checked+.toggle-slider {
        background: #fff;
    }

    .toggle-switch input:checked+.toggle-slider::before {
        transform: translateX(20px);
        background: #000;
    }

    /* Toast */
    .toast {
        position: fixed;
        bottom: 1.5rem;
        left: 50%;
        transform: translateX(-50%) translateY(80px);
        background: #fff;
        color: #000;
        padding: 0.6rem 1.25rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        opacity: 0;
        transition: all 0.25s ease;
        z-index: 1000;
    }

    .toast.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    .section-title {
        color: #888;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1.25rem 0.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
</style>
@endsection

@section('content')
<div class="player-settings">

    <div class="settings-card">
        <div class="section-title">Appearance</div>
        <div class="setting-item">
            <div class="setting-info">
                <div class="setting-title">
                    Netflix Skin
                    <span class="badge">Popular</span>
                </div>
                <div class="setting-desc">Dark theme with red accent colors</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" id="netflixSkin" data-key="netflix_skin" {{ ($settings['netflix_skin'] ?? true) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="section-title">Features</div>
        <div class="setting-item">
            <div class="setting-info">
                <div class="setting-title">
                    Share Button
                    <span class="badge badge-blue">New</span>
                </div>
                <div class="setting-desc">Show share button in player controls</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" id="shareButton" data-key="share_button" {{ ($settings['share_button'] ?? true) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>
        <div class="setting-item">
            <div class="setting-info">
                <div class="setting-title">
                    Playback Speed
                </div>
                <div class="setting-desc">Show playback rate controls (0.5x - 2x)</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" id="playbackRate" data-key="playback_rate" {{ ($settings['playback_rate'] ?? true) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>
    </div>
</div>

<div class="toast" id="toast">Saved!</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggles = document.querySelectorAll('.toggle-switch input');
        const toast = document.getElementById('toast');

        function showToast(msg) {
            toast.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2000);
        }

        function saveSetting(key, value) {
            const data = {};
            data[key] = value;

            fetch('{{ route("player.settings.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) showToast('Saved!');
                })
                .catch(() => showToast('Error saving'));
        }

        toggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                saveSetting(this.dataset.key, this.checked);
            });
        });
    });
</script>
@endsection