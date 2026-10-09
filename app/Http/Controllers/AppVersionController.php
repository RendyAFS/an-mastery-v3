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

        $currentVersion = $this->versionService->getCurrentVersion(fresh: true);
        $firebaseData = $this->versionService->getFirebaseVersion();

        $comparison = null;
        if ($firebaseData && !empty($firebaseData['version'])) {
            $comparison = $this->versionService->compare($currentVersion, $firebaseData['version']);
        }

        $deploymentScripts = $this->versionService->getDeploymentScripts();

        if ($request->expectsJson()) {
            return response()->json([
                'success'            => true,
                'current_version'    => $currentVersion,
                'firebase_data'      => $firebaseData,
                'comparison'         => $comparison,
                'deployment_scripts' => $deploymentScripts,
            ]);
        }

        return view('app-version.index', [
            'currentVersion'    => $currentVersion,
            'firebaseData'      => $firebaseData,
            'comparison'        => $comparison,
            'deploymentScripts' => $deploymentScripts,
            'firebaseConfig'    => config('firebase'),
        ]);
    }

    /**
     * Get list of deployment scripts as JSON.
     */
    public function getScripts(): JsonResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403, 'Akses ditolak.');

        return response()->json([
            'success' => true,
            'data'    => $this->versionService->getDeploymentScripts(),
        ]);
    }

    /**
     * Save deployment scripts configuration to database app_settings.
     */
    public function saveScripts(Request $request): JsonResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403, 'Akses ditolak.');

        $scripts = $request->input('scripts');

        if (is_string($scripts)) {
            $scripts = json_decode($scripts, true);
        }

        if (!is_array($scripts)) {
            return response()->json([
                'success' => false,
                'message' => 'Format data JSON script tidak valid.',
            ], 422);
        }

        $this->versionService->saveDeploymentScripts($scripts);

        return response()->json([
            'success' => true,
            'message' => 'Konfigurasi script terminal berhasil diperbarui di database!',
            'data'    => $this->versionService->getDeploymentScripts(),
        ]);
    }

    /**
     * Get detail of a single script including file content.
     */
    public function showScript(string $key): JsonResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403, 'Akses ditolak.');

        $detail = $this->versionService->getScriptDetail($key);

        if (!$detail) {
            return response()->json([
                'success' => false,
                'message' => "Script dengan key '{$key}' tidak ditemukan.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $detail,
        ]);
    }

    /**
     * Save / Update a single script item.
     */
    public function saveSingleScript(Request $request): JsonResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403, 'Akses ditolak.');

        $validated = $request->validate([
            'key'            => 'required|string|max:100',
            'name'           => 'required|string|max:200',
            'icon'           => 'nullable|string|max:50',
            'description'    => 'nullable|string|max:1000',
            'script_content' => 'required|string',
        ]);

        $saved = $this->versionService->saveSingleScript($validated);

        return response()->json([
            'success' => true,
            'message' => "Script '{$saved['name']}' berhasil disimpan ke database!",
            'data'    => $saved,
        ]);
    }

    /**
     * Delete a single script item.
     */
    public function deleteScript(string $key): JsonResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403, 'Akses ditolak.');

        $deleted = $this->versionService->deleteScript($key);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => "Script dengan key '{$key}' tidak ditemukan.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Script '{$key}' berhasil dihapus!",
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
            'is_super_admin'   => auth()->check() && auth()->user()->hasRole('Super Admin'),
        ]);
    }

    /**
     * Stream deployment update execution to the client terminal in real-time.
     * Reads bash script directly from app_settings database and streams output live.
     */
    public function streamUpdate(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403, 'Akses ditolak. Fitur eksekusi ini hanya untuk Super Admin.');

        $scriptKey = $request->query('script_key', 'update_project');
        $targetVersion = $request->query('target_version');

        return response()->stream(function () use ($scriptKey, $targetVersion) {
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

            $sendEvent('info', "====================================================\n");
            $sendEvent('info', "        AN MASTERY SYSTEM TERMINAL RUNNER\n");
            $sendEvent('info', "====================================================\n");
            $sendEvent('info', "🕒 Waktu Eksekusi: " . now()->translatedFormat('d F Y H:i:s') . "\n");
            $sendEvent('info', "👤 Operator: " . (auth()->user()?->name ?? 'Super Admin') . "\n\n");

            // 1. SAFETY PRE-FLIGHT CHECKS
            $sendEvent('info', "🔍 Menjalankan Pemeriksaan Keamanan Sistem (Pre-flight Checks)...\n");
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
                    'success'   => false,
                    'exit_code' => 1,
                    'reason'    => 'safety_preflight_failed',
                ]);
                return;
            }

            // Print Warnings if any
            if (!empty($preflight['warnings'])) {
                foreach ($preflight['warnings'] as $warn) {
                    $sendEvent('warning', " ⚠ Peringatan: {$warn}\n");
                }
            }

            // 2. RESOLVE SCRIPT DIRECTLY FROM DATABASE APP_SETTINGS
            $script = $this->versionService->resolveScriptByKey($scriptKey);

            if (!$script || empty($script['script_content'])) {
                $sendEvent('error', "\n❌ Script dengan key '{$scriptKey}' tidak ditemukan atau isi script kosong di database app_settings.\n", [
                    'success'   => false,
                    'exit_code' => 1,
                ]);
                return;
            }

            $scriptName = $script['name'] ?? $scriptKey;
            $scriptContent = $script['script_content'];

            $sendEvent('info', " • Perintah / Script: {$scriptName}\n");
            $sendEvent('info', " • Sumber Script: Database app_settings (JSON)\n\n");

            // Create a temporary executable shell script in storage
            $tempDir = storage_path('app/temp-scripts');
            if (!\Illuminate\Support\Facades\File::isDirectory($tempDir)) {
                \Illuminate\Support\Facades\File::makeDirectory($tempDir, 0755, true, true);
            }

            $tempScriptFile = $tempDir . '/exec_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $scriptKey) . '_' . time() . '.sh';
            \Illuminate\Support\Facades\File::put($tempScriptFile, str_replace("\r\n", "\n", $scriptContent));
            @chmod($tempScriptFile, 0755);

            $cmd = "bash " . escapeshellarg($tempScriptFile);
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

                // Clean up temporary script file immediately
                @unlink($tempScriptFile);

                if ($exitCode === 0) {
                    // If target version was requested, update database app_version
                    if (!empty($targetVersion) && ($scriptKey === 'update_project' || str_contains($scriptKey, 'update'))) {
                        $cleanTarget = $this->versionService->normalizeVersion($targetVersion);
                        $this->versionService->setDatabaseVersion($cleanTarget);
                        $sendEvent('info', "\n💾 Versi database lokal (app_settings) otomatis disinkronkan ke v{$cleanTarget}!\n");
                    }

                    $sendEvent('done', "\n====================================================\n✅ PROSES SELESAI DENGAN SUKSES!\n====================================================\n", [
                        'exit_code' => 0,
                        'success'   => true,
                        'new_version' => $targetVersion ?? null,
                    ]);
                } else {
                    $sendEvent('error', "\n====================================================\n❌ Proses selesai dengan kode keluar: {$exitCode}\n====================================================\n", [
                        'exit_code' => $exitCode,
                        'success'   => false,
                    ]);
                }
            } else {
                @unlink($tempScriptFile);
                $sendEvent('error', "❌ Gagal menginisialisasi proses perintah sistem (proc_open failed).\n", [
                    'success'   => false,
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
