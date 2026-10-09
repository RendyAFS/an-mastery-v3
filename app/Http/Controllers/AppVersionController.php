<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppVersion\PublishAppVersionRequest;
use App\Services\AppVersionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppVersionController extends Controller
{
    public function __construct(
        protected AppVersionService $versionService
    ) {}

    /**
     * Display the version management page for Super Admin.
     */
    public function index(Request $request): View|JsonResponse
    {
        abort_unless(auth()->user()->can('app-version.view'), 403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');

        $currentVersion = $this->versionService->getCurrentVersion(fresh: true);
        $firebaseData = $this->versionService->getFirebaseVersion();

        $comparison = null;
        if ($firebaseData && !empty($firebaseData['version'])) {
            $comparison = $this->versionService->compare($currentVersion, $firebaseData['version']);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success'         => true,
                'current_version' => $currentVersion,
                'firebase_data'   => $firebaseData,
                'comparison'      => $comparison,
            ]);
        }

        return view('app-version.index', [
            'currentVersion' => $currentVersion,
            'firebaseData'   => $firebaseData,
            'comparison'     => $comparison,
            'firebaseConfig' => config('firebase'),
        ]);
    }

    /**
     * Publish a new version to Firebase RTDB and optionally update local files.
     */
    public function publish(PublishAppVersionRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $version = $this->versionService->normalizeVersion($validated['version']);

        // 1. Publish to Firebase
        $result = $this->versionService->publishToFirebase([
            'version'      => $version,
            'release_name' => $validated['release_name'] ?? ('Release v' . $version),
            'changelog'    => $validated['changelog'] ?? '',
            'update_guide' => $validated['update_guide'] ?? '',
        ]);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data'    => [
                'version' => $version,
            ],
        ]);
    }

    /**
     * Public / Authenticated JSON endpoint to check latest status.
     */
    public function status(): JsonResponse
    {
        $currentVersion = $this->versionService->getCurrentVersion();
        $firebaseData = $this->versionService->getFirebaseVersion();

        $updateAvailable = false;
        if ($firebaseData && !empty($firebaseData['version'])) {
            $updateAvailable = ($this->versionService->compare($currentVersion, $firebaseData['version']) === -1);
        }

        return response()->json([
            'current_version'  => $currentVersion,
            'latest_version'   => $firebaseData['version'] ?? $currentVersion,
            'update_available' => $updateAvailable,
            'release_info'     => $firebaseData,
            'is_super_admin'   => auth()->check() && auth()->user()->can('app-version.update'),
        ]);
    }

    /**
     * Mark system as manually updated and sync local database app_version with Firebase.
     */
    public function syncLocalVersion(Request $request): JsonResponse
    {
        abort_unless(auth()->user()->can('app-version.update'), 403, 'Akses ditolak.');

        $firebaseData = $this->versionService->getFirebaseVersion();
        $targetVersion = $request->input('version') ?: ($firebaseData['version'] ?? null);

        if (empty($targetVersion)) {
            return response()->json([
                'success' => false,
                'message' => 'Versi target tidak valid atau belum tersedia di Firebase.',
            ], 422);
        }

        $cleanVersion = $this->versionService->normalizeVersion($targetVersion);
        $this->versionService->setDatabaseVersion($cleanVersion);

        return response()->json([
            'success'     => true,
            'message'     => "Versi aplikasi lokal (database) berhasil disinkronkan ke v{$cleanVersion}!",
            'app_version' => $cleanVersion,
        ]);
    }
}
