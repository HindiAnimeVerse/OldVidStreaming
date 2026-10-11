<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Setting;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function index()
    {
        // Get total storage from all files
        $totalSize = DB::table('files')->sum('file_size');
        $totalFiles = DB::table('files')->count();

        // Get stats
        $stats = [
            'total_users' => User::count(),
            'total_files' => $totalFiles,
            'storage_used' => \App\Helpers\FormatHelper::formatBytes($totalSize),
            'bandwidth_used' => '0 B', // Can be implemented with tracking
        ];

        // Recent users
        $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }

    /**
     * All Files Management
     */
    public function files(Request $request)
    {
        $query = DB::table('files')
            ->leftJoin('users', 'files.user_id', '=', 'users.id')
            ->select('files.*', 'users.name as user_name', 'users.email as user_email');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('files.filename', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('files.user_id', $request->user_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            if ($request->type === 'video') {
                $query->whereNotNull('files.resolution');
            } elseif ($request->type === 'image') {
                $query->whereNull('files.resolution');
            }
        }

        // Sort
        $sortField = $request->get('sort', 'timestamp');
        $sortDir = $request->get('dir', 'desc');
        $sortMap = [
            'filename' => 'files.filename',
            'file_size' => 'files.file_size',
            'timestamp' => 'files.timestamp',
            'user' => 'users.name'
        ];
        if (isset($sortMap[$sortField])) {
            $query->orderBy($sortMap[$sortField], $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $files = $query->paginate(24)->withQueryString();

        // Format files
        $files->getCollection()->transform(function ($file) {
            $file->formatted_size = \App\Helpers\FormatHelper::formatBytes($file->file_size);
            $file->formatted_date = \Carbon\Carbon::createFromTimestamp($file->timestamp)->diffForHumans();
            $file->is_video = !empty($file->resolution);
            return $file;
        });

        // Stats
        $totalFiles = DB::table('files')->count();
        $totalSize = DB::table('files')->sum('file_size');
        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('admin.files', compact('files', 'totalFiles', 'totalSize', 'users'));
    }

    /**
     * Users Management
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Toggle user admin status
     */
    public function toggleAdmin(User $user)
    {
        // Prevent self-demotion
        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Cannot modify your own admin status']);
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->is_admin ? 'User promoted to admin' : 'Admin privileges revoked',
            'is_admin' => $user->is_admin
        ]);
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete yourself'], 400);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    /**
     * Toggle GPMC Permission
     */
    public function toggleGpmcPermission(User $user)
    {
        $user->can_use_gpmc = !$user->can_use_gpmc;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'GPMC permission ' . ($user->can_use_gpmc ? 'granted' : 'revoked'),
            'status' => $user->can_use_gpmc
        ]);
    }

    /**
     * Site Settings
     */
    public function settings()
    {
        // Load all settings from database
        $settings = [
            'site_name' => Setting::get('site_name', 'VidStreaming'),
            'site_description' => Setting::get('site_description', 'Unlimited Video Hosting Platform'),
            'site_favicon' => Setting::get('site_favicon', ''),
            'allow_registration' => Setting::get('allow_registration', true),
            'email_verification' => Setting::get('email_verification', false),
            'dmca_enabled' => Setting::get('dmca_enabled', true),
            'maintenance_mode' => Setting::get('maintenance_mode', false),
            'gpmc_auth_data' => Setting::get('gpmc_auth_data', ''),
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Update Site Settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:1000',
        ]);

        // Save all settings
        Setting::set('site_name', $request->input('site_name', 'VidStreaming'));
        Setting::set('site_description', $request->input('site_description', ''));

        // Checkboxes are sent as '1' or '0' from JavaScript
        Setting::set('allow_registration', $request->input('allow_registration') === '1');
        Setting::set('email_verification', $request->input('email_verification') === '1');
        Setting::set('dmca_enabled', $request->input('dmca_enabled') === '1');
        Setting::set('maintenance_mode', $request->input('maintenance_mode') === '1');

        // Save GPMC Auth Data if present
        if ($request->has('gpmc_auth_data')) {
            Setting::set('gpmc_auth_data', $request->input('gpmc_auth_data'));
        }

        return response()->json(['success' => true, 'message' => 'Settings saved successfully']);
    }

    /**
     * Upload Favicon
     */
    public function uploadFavicon(Request $request)
    {
        $request->validate([
            'favicon' => 'required|image|mimes:ico,png,jpg,jpeg,gif,svg|max:1024',
        ]);

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');

            // Create branding directory if it doesn't exist
            $brandingPath = public_path('branding');
            if (!file_exists($brandingPath)) {
                mkdir($brandingPath, 0755, true);
            }

            // Delete old favicon if exists
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon && file_exists(public_path($oldFavicon))) {
                @unlink(public_path($oldFavicon));
            }

            // Generate filename and save
            $filename = 'favicon.' . $file->getClientOriginalExtension();
            $file->move($brandingPath, $filename);

            // Save path to settings
            $faviconPath = '/branding/' . $filename;
            Setting::set('site_favicon', $faviconPath);

            return response()->json([
                'success' => true,
                'message' => 'Favicon uploaded successfully',
                'path' => $faviconPath
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }

    /**
     * Advertisement Settings
     */
    public function ads()
    {
        $settings = [
            'ads_enabled' => Setting::get('ads_enabled', false),
            'ads_stream_clicks' => Setting::get('ads_stream_clicks', 2),
            'ads_download_countdown' => Setting::get('ads_download_countdown', 10),
            'ads_direct_links' => Setting::get('ads_direct_links', ''),
            'ads_vignette_code' => Setting::get('ads_vignette_code', ''),
            'ads_popunder_code' => Setting::get('ads_popunder_code', ''),
        ];

        return view('admin.ads', compact('settings'));
    }

    /**
     * Update Advertisement Settings
     */
    public function updateAds(Request $request)
    {
        // Save ad settings
        Setting::set('ads_enabled', $request->input('ads_enabled') === '1');
        Setting::set('ads_stream_clicks', (int) $request->input('ads_stream_clicks', 2));
        Setting::set('ads_download_countdown', (int) $request->input('ads_download_countdown', 10));
        Setting::set('ads_direct_links', $request->input('ads_direct_links', ''));
        Setting::set('ads_vignette_code', $request->input('ads_vignette_code', ''));
        Setting::set('ads_popunder_code', $request->input('ads_popunder_code', ''));

        return response()->json(['success' => true, 'message' => 'Ad settings saved successfully']);
    }
}
