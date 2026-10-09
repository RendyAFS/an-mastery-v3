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
        abort_unless(auth()->user()->hasRole('Super Admin'), 403, 'Akses ditolak. Fitur ini hanya untuk Super Admin.');

        $currentVersion = $this->versionService->getCurrentVersion();
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

        // 2. Always store version into database app_settings
        $this->versionService->setDatabaseVersion($version);

        // 3. Optionally sync local environment, config/app.php & README
        if ($request->boolean('sync_local')) {
            $this->versionService->updateEnvVersion($version);
            $this->versionService->updateConfigFileVersion($version);
            $this->versionService->syncReadme($version);
            \Illuminate\Support\Facades\Artisan::call('config:clear');
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data'    => [
                'version' => $version,
                'synced_local' => $request->boolean('sync_local'),
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
            'is_super_admin'   => auth()->check() && auth()->user()->hasRole('Super Admin'),
        ]);
    }

    /**
     * Stream deployment update execution to the client terminal in real-time.
     * Incorporates strict pre-flight safety checks for folder structure and script validation.
     */
    public function streamUpdate(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403, 'Akses ditolak. Fitur eksekusi ini hanya untuk Super Admin.');

        return response()->stream(function () {
            while (ob_get_level()) {
                ob_end_clean();
            }

            $sendEvent = function ($type, $text, $extra = []) {
                $payload = json_encode(array_merge([
                    'type' => $type,
                    'text' => $text,
                    'time' => now()->format('H:i:s'),
                ], $extra));
                echo "data: {$payload}\n\n";
                if (ob_get_level()) ob_flush();
                flush();
            };

            $sendEvent('info', "=========================================\n");
            $sendEvent('info', "       AN MASTERY SYSTEM UPDATE\n");
            $sendEvent('info', "=========================================\n");
            $sendEvent('info', "🕒 Waktu Eksekusi: " . now()->translatedFormat('d F Y H:i:s') . "\n");
            $sendEvent('info', "👤 Operator: " . (auth()->user()?->name ?? 'Super Admin') . "\n\n");

            // 1. SAFETY PRE-FLIGHT CHECKS
            $sendEvent('info', "🔍 Menjalankan Pemeriksaan Keamanan (Safety Pre-flight Checks)...\n");
            $preflight = $this->versionService->validateEnvironmentForUpdate();

            $sendEvent('info', " • Direktori Root : {$preflight['base_path']}\n");
            $sendEvent('info', " • Sistem Operasi : {$preflight['os']}\n");

            // Check if there are blocking safety errors
            if (!$preflight['is_safe']) {
                $sendEvent('error', "\n❌ [SAFETY ABORT] Pengecekan Keamanan Gagal:\n");
                foreach ($preflight['errors'] as $err) {
                    $sendEvent('error', "   - {$err}\n");
                }
                $sendEvent('error', "\nProses dihentikan secara otomatis demi mencegah kerusakan sistem.\n", [
                    'success' => false,
                    'exit_code' => 1,
                    'reason' => 'safety_preflight_failed',
                ]);
                return;
            }

            // Print Warnings if any
            if (!empty($preflight['warnings'])) {
                foreach ($preflight['warnings'] as $warn) {
                    $sendEvent('warning', " ⚠ Peringatan: {$warn}\n");
                }
            }

            // 2. RESOLVE UPDATE COMMAND
            $script = $preflight['script_path'];
            if ($script && file_exists($script)) {
                $sendEvent('info', " • Script Update   : {$script} (Terverifikasi)\n\n");
                $cmd = "bash " . escapeshellarg($script);
            } else {
                $sendEvent('info', " • Mode Update    : Rangkaian Perintah Bawaan (Standard Fallback)\n\n");
                if (PHP_OS_FAMILY === 'Windows') {
                    $cmd = 'git pull origin develop && composer install --no-interaction && npm run build && php artisan optimize:clear';
                } else {
                    $cmd = 'git pull origin develop && composer install --no-interaction && npm run build && php artisan migrate --force && php artisan optimize:clear';
                }
            }

            $sendEvent('cmd', "$ {$cmd}\n\n");

            // 3. EXECUTE PROCESS WITH REAL-TIME STREAMING
            $descriptors = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $startTime = microtime(true);
            $maxExecutionTimeout = 360; // 6 minutes max
            $process = @proc_open($cmd, $descriptors, $pipes, base_path());

            if (is_resource($process)) {
                fclose($pipes[0]);
                stream_set_blocking($pipes[1], false);
                stream_set_blocking($pipes[2], false);

                while (true) {
                    // Check timeout
                    if ((microtime(true) - $startTime) > $maxExecutionTimeout) {
                        $sendEvent('error', "\n❌ [TIMEOUT] Proses pembaruan melebihi batas waktu maksimal ({$maxExecutionTimeout} detik). Menghentikan proses.\n");
                        proc_terminate($process, 9);
                        break;
                    }

                    $read = [$pipes[1], $pipes[2]];
                    $write = null;
                    $except = null;

                    if (stream_select($read, $write, $except, 0, 150000) > 0) {
                        foreach ($read as $pipe) {
                            $chunk = fread($pipe, 2048);
                            if ($chunk !== false && strlen($chunk) > 0) {
                                $sendEvent('output', $chunk);
                            }
                        }
                    }

                    $status = proc_get_status($process);
                    if (!$status['running']) {
                        while ($chunk = fread($pipes[1], 2048)) {
                            if (strlen($chunk) > 0) $sendEvent('output', $chunk);
                        }
                        while ($chunk = fread($pipes[2], 2048)) {
                            if (strlen($chunk) > 0) $sendEvent('output', $chunk);
                        }
                        break;
                    }
                }

                fclose($pipes[1]);
                fclose($pipes[2]);
                $exitCode = proc_close($process);

                if ($exitCode === 0) {
                    $sendEvent('done', "\n=========================================\n✅ UPDATE BERHASIL DISELESAIKAN!\n=========================================\n", [
                        'exit_code' => 0,
                        'success' => true,
                    ]);
                } else {
                    $sendEvent('error', "\n=========================================\n❌ Update gagal / terhenti dengan kode keluar (exit code): {$exitCode}\n=========================================\n", [
                        'exit_code' => $exitCode,
                        'success' => false,
                    ]);
                }
            } else {
                $sendEvent('error', "❌ Gagal menginisialisasi proses perintah sistem (proc_open failed).\n", [
                    'success' => false,
                    'exit_code' => 1,
                ]);
            }
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection'        => 'keep-alive',
        ]);
    }
}
