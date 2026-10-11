<?php

namespace App\Services;

use App\Models\User;

/**
 * Google Photos Service
 * Handles bearer token extraction and media URL resolution for video streaming
 */
class GooglePhotosService
{
    /**
     * Quality mappings for video streams
     * Format: suffix => label
     */
    public const QUALITY_MAP = [
        'm37' => '1080p',
        'm22' => '720p',
        'm18' => '360p',
    ];

    private User $user;
    private string $userAgent = 'com.google.android.apps.photos/49029607 (Linux; U; Android 9; en_US; Pixel XL; Build/PQ2A.190205.001; Cronet/127.0.6510.5) (gzip)';

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get bearer token from user (cached in database for 50 minutes)
     */
    public function getBearerToken(): ?string
    {
        // Check if cached token is still valid (50 minutes = 3000 seconds)
        if ($this->user->gpmc_bearer_token && $this->user->gpmc_bearer_expiry > time()) {
            return $this->user->gpmc_bearer_token;
        }

        // Fetch new token
        $token = $this->fetchBearerToken();

        if ($token) {
            // Save to user record (expires in 50 minutes)
            $this->user->update([
                'gpmc_bearer_token' => $token,
                'gpmc_bearer_expiry' => time() + 3000,
            ]);
        }

        return $token;
    }

    /**
     * Fetch fresh bearer token from Google
     */
    private function fetchBearerToken(): ?string
    {
        $authData = $this->user->gpmc_auth_data;
        if (empty($authData)) {
            return null;
        }

        parse_str($authData, $authDataDict);

        $authRequestData = [
            'androidId' => $authDataDict['androidId'] ?? '',
            'app' => 'com.google.android.apps.photos',
            'client_sig' => $authDataDict['client_sig'] ?? '',
            'callerPkg' => 'com.google.android.apps.photos',
            'callerSig' => $authDataDict['callerSig'] ?? '',
            'device_country' => $authDataDict['device_country'] ?? '',
            'Email' => $authDataDict['Email'] ?? '',
            'google_play_services_version' => $authDataDict['google_play_services_version'] ?? '',
            'lang' => $authDataDict['lang'] ?? '',
            'oauth2_foreground' => $authDataDict['oauth2_foreground'] ?? '',
            'sdk_version' => $authDataDict['sdk_version'] ?? '',
            'service' => $authDataDict['service'] ?? '',
            'Token' => $authDataDict['Token'] ?? '',
        ];

        $headers = [
            'Accept-Encoding: gzip',
            'app: com.google.android.apps.photos',
            'Connection: Keep-Alive',
            'Content-Type: application/x-www-form-urlencoded',
            'device: ' . $authRequestData['androidId'],
            'User-Agent: GoogleAuth/1.4 (Pixel XL PQ2A.190205.001); gzip',
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://android.googleapis.com/auth',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($authRequestData),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => 'gzip',
            CURLOPT_TIMEOUT => 15,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        foreach (explode("\n", $response) as $line) {
            if (strpos($line, 'Auth=') === 0) {
                return substr($line, 5);
            }
        }

        return null;
    }

    /**
     * Get the redirect URL for a media URL with quality suffix
     *
     * @param string $baseMediaUrl The base media URL (without quality suffix)
     * @param string $quality Quality suffix (m37, m22, m18)
     * @return string|null The redirect URL or null on failure
     */
    public function getStreamUrl(string $baseMediaUrl, string $quality = 'm37'): ?string
    {
        $bearerToken = $this->getBearerToken();
        if (!$bearerToken) {
            return null;
        }

        // Remove any existing suffix and add the quality suffix
        $mediaUrl = preg_replace('/=[a-zA-Z0-9]+$/', '', $baseMediaUrl);
        $mediaUrl .= '=' . $quality;

        $headers = [
            'Authorization: Bearer ' . $bearerToken,
            'User-Agent: ' . $this->userAgent,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $mediaUrl,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_TIMEOUT => 30,
        ]);

        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        curl_close($ch);

        if ($httpCode === 302 && !empty($redirectUrl)) {
            return $redirectUrl;
        }

        return null;
    }

    /**
     * Get all quality stream URLs for a media URL using parallel requests
     *
     * @param string $baseMediaUrl The base media URL
     * @return array Array of ['file' => 'stream_url', 'label' => '1080p', 'default' => true/false]
     */
    public function getAllQualityUrls(string $baseMediaUrl): array
    {
        $result = $this->getAllQualityUrlsWithStatus($baseMediaUrl);
        return $result['sources'];
    }

    /**
     * Get all quality stream URLs with encoding status
     * 
     * @param string $baseMediaUrl The base media URL
     * @return array ['sources' => [...], 'isEncoding' => bool, 'httpCodes' => [...]]
     */
    public function getAllQualityUrlsWithStatus(string $baseMediaUrl): array
    {
        $bearerToken = $this->getBearerToken();
        if (!$bearerToken) {
            return ['sources' => [], 'isEncoding' => false, 'httpCodes' => []];
        }

        // Remove any existing suffix from base URL
        $cleanUrl = preg_replace('/=[a-zA-Z0-9]+$/', '', $baseMediaUrl);

        $headers = [
            'Authorization: Bearer ' . $bearerToken,
            'User-Agent: ' . $this->userAgent,
        ];

        // Setup parallel curl handles
        $multiHandle = curl_multi_init();
        $handles = [];

        foreach (self::QUALITY_MAP as $suffix => $label) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $cleanUrl . '=' . $suffix,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_TIMEOUT => 6,
                CURLOPT_CONNECTTIMEOUT => 3,
            ]);
            curl_multi_add_handle($multiHandle, $ch);
            $handles[$suffix] = ['handle' => $ch, 'label' => $label];
        }

        // Execute all requests in parallel
        $running = null;
        do {
            curl_multi_exec($multiHandle, $running);
            curl_multi_select($multiHandle);
        } while ($running > 0);

        // Collect results
        $sources = [];
        $httpCodes = [];
        $all404 = true;

        foreach ($handles as $suffix => $data) {
            $ch = $data['handle'];
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

            $httpCodes[$suffix] = $httpCode;

            if ($httpCode !== 404) {
                $all404 = false;
            }

            if ($httpCode === 302 && !empty($redirectUrl)) {
                $sources[] = [
                    'file' => $redirectUrl,
                    'type' => 'mp4',
                    'label' => $data['label'],
                    'default' => ($suffix === 'm37'),
                ];
            }

            curl_multi_remove_handle($multiHandle, $ch);
            curl_close($ch);
        }

        curl_multi_close($multiHandle);

        // If all qualities return 404, the video is still encoding
        $isEncoding = $all404 && count($httpCodes) === count(self::QUALITY_MAP);

        return [
            'sources' => $sources,
            'isEncoding' => $isEncoding,
            'httpCodes' => $httpCodes,
        ];
    }
}
