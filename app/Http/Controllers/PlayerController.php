<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\GooglePhotosService;
use App\Helpers\PlayerEncoder;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Video watch page - displays JWPlayer with encoded/protected config
     */
    public function watch($slug)
    {
        $file = File::where('slug', $slug)->with('user')->first();

        // Show custom error page for player context (works in iframe)
        if (!$file) {
            return response()->view('player.error', [], 404);
        }

        $sources = [];
        $isEncoding = false;

        // Get streaming sources if file has an owner with gpmc connected
        if ($file->user && $file->user->gpmc_auth_data && $file->media_url) {
            $gpService = new GooglePhotosService($file->user);
            $result = $gpService->getAllQualityUrlsWithStatus($file->media_url);
            $sources = $result['sources'];
            $isEncoding = $result['isEncoding'];
        }

        // Fallback: if no sources available and not encoding, use media_url directly
        if (empty($sources) && !$isEncoding && $file->media_url) {
            $sources = [
                ['file' => $file->media_url, 'label' => 'Default', 'default' => true]
            ];
        }

        // Get player settings from file owner
        $playerSettings = $file->user?->player_settings ?? ['netflix_skin' => true];

        // Encode the ENTIRE player JavaScript for protection
        $encodedScript = null;
        if (!empty($sources) && !$isEncoding) {
            $playerConfig = [
                'sources' => $sources,
                'aboutlink' => url('/'),
                'sharelink' => url()->current(),
            ];

            // Get ad settings
            $adsEnabled = \App\Models\Setting::get('ads_enabled', false);
            $adsStreamClicks = (int) \App\Models\Setting::get('ads_stream_clicks', 2);
            $adsDirectLinksRaw = \App\Models\Setting::get('ads_direct_links', '');
            $adsDirectLinks = array_values(array_filter(array_map('trim', explode("\n", $adsDirectLinksRaw))));

            // Build ad config
            $adConfig = [
                'enabled' => $adsEnabled && count($adsDirectLinks) > 0,
                'clicks' => $adsStreamClicks,
                'links' => $adsDirectLinks,
                'slug' => $file->slug,
            ];

            $encodedScript = PlayerEncoder::encodeFullPlayer($playerConfig, $playerSettings, $adConfig);
        }

        return view('player.watch', [
            'file' => $file,
            'title' => $file->filename,
            'sources' => $sources,
            'playerSettings' => $playerSettings,
            'encodedScript' => $encodedScript,
            'isEncoding' => $isEncoding,
        ]);
    }

    /**
     * Generate the ad interception script
     */
    private function generateAdScript(string $slug, int $required, array $links): string
    {
        $linksJson = json_encode($links);

        return <<<JS
(function(){
var c=parseInt(sessionStorage.getItem('vs_$slug')||'0');
var r=$required;
var l=$linksJson;
if(c>=r){sessionStorage.removeItem('vs_$slug');}else{
var i=setInterval(function(){
if(typeof jwplayer!=='undefined'&&jwplayer('player')){
clearInterval(i);
var p=jwplayer('player');
p.on('play',function(){
if(c<r){
p.pause();
var u=l[c%l.length];
c++;
sessionStorage.setItem('vs_$slug',c);
window.open(u,'_blank');
if(c>=r){sessionStorage.removeItem('vs_$slug');setTimeout(function(){p.play();},300);}
}
});
if(c>=r){setTimeout(function(){p.play();},500);}
}
},100);
}
})();
JS;
    }

    /**
     * API endpoint to get streaming URLs (for AJAX requests)
     */
    public function getStreamUrls($slug)
    {
        $file = File::where('slug', $slug)->with('user')->firstOrFail();

        $sources = [];

        if ($file->user && $file->user->gpmc_auth_data && $file->media_url) {
            $gpService = new GooglePhotosService($file->user);
            $sources = $gpService->getAllQualityUrls($file->media_url);
        }

        return response()->json([
            'success' => !empty($sources),
            'sources' => $sources,
            'file' => [
                'id' => $file->id,
                'filename' => $file->filename,
                'slug' => $file->slug,
            ]
        ]);
    }

    /**
     * Download page - shows file info and link to download server
     */
    public function download($slug)
    {
        $file = File::where('slug', $slug)->with('user')->first();

        if (!$file) {
            return response()->view('player.error', [], 404);
        }

        // Check if ads are enabled
        $adsEnabled = \App\Models\Setting::get('ads_enabled', false);
        $popunderCode = $adsEnabled ? \App\Models\Setting::get('ads_popunder_code', '') : '';

        return view('player.download', [
            'file' => $file,
            'title' => 'Download - ' . $file->filename,
            'adsEnabled' => $adsEnabled,
            'popunderCode' => $popunderCode,
        ]);
    }

    /**
     * Download countdown page - shows timer before redirecting to download server
     */
    public function downloadCountdown(Request $request, $slug, $server)
    {
        $file = File::where('slug', $slug)->with('user')->first();

        if (!$file) {
            return response()->view('player.error', [], 404);
        }

        // Get ad settings
        $countdown = (int) \App\Models\Setting::get('ads_download_countdown', 10);
        $popunderCode = \App\Models\Setting::get('ads_popunder_code', '');
        $vignetteCode = \App\Models\Setting::get('ads_vignette_code', '');

        // Get direct links for click redirects
        $directLinksRaw = \App\Models\Setting::get('ads_direct_links', '');
        $directLinks = array_filter(array_map('trim', explode("\n", $directLinksRaw)));

        // Generate signed URL for the download
        $downloadUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'player.download.server',
            now()->addMinutes(5),
            ['slug' => $slug, 'server' => $server]
        );

        return view('player.download-countdown', [
            'file' => $file,
            'title' => 'Download - ' . $file->filename,
            'countdown' => $countdown,
            'downloadUrl' => $downloadUrl,
            'server' => $server,
            'popunderCode' => $popunderCode,
            'vignetteCode' => $vignetteCode,
            'directLinks' => $directLinks,
        ]);
    }

    /**
     * Download server page - token with IP/UA binding for security
     */
    public function downloadServer(Request $request, $slug, $server)
    {
        $file = File::where('slug', $slug)->with('user')->firstOrFail();

        $serverName = '10 Gbps Server';
        $serverSpeed = '10 Gbps';
        $downloadAvailable = false;

        // Handle different server types
        switch ($server) {
            case '10gbps':
            default:
                $downloadAvailable = $file->user && $file->user->gpmc_auth_data && $file->media_url;
                $serverName = '10 Gbps Server';
                $serverSpeed = '10 Gbps';
                break;
        }

        // Generate token with strong binding (IP, UA, session, time)
        $downloadToken = null;
        if ($downloadAvailable) {
            $tokenData = [
                'slug' => $slug,
                'server' => $server,
                'expires' => time() + 300, // 5 minutes
                'ip' => $request->ip(),
                'ua_hash' => hash('sha256', $request->userAgent()),
                'session_id' => session()->getId(),
                'nonce' => bin2hex(random_bytes(8)),
            ];
            $downloadToken = encrypt(json_encode($tokenData));
        }

        // Get ad settings
        $adsEnabled = \App\Models\Setting::get('ads_enabled', false);
        $popunderCode = $adsEnabled ? \App\Models\Setting::get('ads_popunder_code', '') : '';

        // Get direct links for click interception
        $directLinks = [];
        if ($adsEnabled) {
            $directLinksRaw = \App\Models\Setting::get('ads_direct_links', '');
            $directLinks = array_values(array_filter(array_map('trim', explode("\n", $directLinksRaw))));
        }

        return view('player.download-server', [
            'file' => $file,
            'title' => 'Download - ' . $file->filename,
            'downloadAvailable' => $downloadAvailable,
            'serverName' => $serverName,
            'serverSpeed' => $serverSpeed,
            'server' => $server,
            'downloadToken' => $downloadToken,
            'popunderCode' => $popunderCode,
            'directLinks' => $directLinks,
        ]);
    }

    /**
     * Initiate download - validates token with IP/UA/session binding
     */
    public function initiateDownload(Request $request, $slug, $server)
    {
        $token = $request->input('_t');

        if (!$token) {
            return redirect()->route('player.download', $slug)
                ->with('error', 'Invalid download request');
        }

        // Decrypt and validate token data
        try {
            $tokenData = json_decode(decrypt($token), true);

            // Verify token belongs to this request
            if ($tokenData['slug'] !== $slug || $tokenData['server'] !== $server) {
                return redirect()->route('player.download', $slug)
                    ->with('error', 'Invalid download token');
            }

            // Check expiration
            if (time() > $tokenData['expires']) {
                return redirect()->route('player.download', $slug)
                    ->with('error', 'Download link expired. Please try again.');
            }

            // Verify IP matches (primary security)
            if ($tokenData['ip'] !== $request->ip()) {
                return redirect()->route('player.download', $slug)
                    ->with('error', 'Security check failed: IP mismatch');
            }

            // Verify user agent hash matches
            if ($tokenData['ua_hash'] !== hash('sha256', $request->userAgent())) {
                return redirect()->route('player.download', $slug)
                    ->with('error', 'Security check failed: Browser mismatch');
            }

            // Verify session matches
            if ($tokenData['session_id'] !== session()->getId()) {
                return redirect()->route('player.download', $slug)
                    ->with('error', 'Security check failed: Session mismatch');
            }
        } catch (\Exception $e) {
            return redirect()->route('player.download', $slug)
                ->with('error', 'Invalid download token');
        }

        $file = File::where('slug', $slug)->with('user')->firstOrFail();
        $downloadUrl = null;

        switch ($server) {
            case '10gbps':
            default:
                if ($file->user && $file->user->gpmc_auth_data && $file->media_url) {
                    $gpService = new GooglePhotosService($file->user);
                    $downloadUrl = $gpService->getStreamUrl($file->media_url, 'dv');
                }
                break;
        }

        if (!$downloadUrl) {
            return redirect()->route('player.download', $file->slug)
                ->with('error', 'Download not available');
        }

        // Redirect to actual download URL
        return redirect()->away($downloadUrl);
    }
}
