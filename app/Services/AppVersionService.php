<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AppVersionService
{
    /**
     * Get the current local app version from Database or fallback to config.
     */
    public function getCurrentVersion(bool $fresh = false): string
    {
        return (string) (AppSetting::get('app_version', config('app.version', '1.0.0'), $fresh) ?: '1.0.0');
    }

    /**
     * Set the application version in the database.
     */
    public function setDatabaseVersion(string $version): void
    {
        $cleanVersion = $this->normalizeVersion($version);
        AppSetting::set('app_version', $cleanVersion, 'Current running application version');
    }

    /**
     * Validate semantic version string (e.g. 1.2.0 or 1.2.0-beta.1).
     */
    public function isValidSemVer(string $version): bool
    {
        $clean = ltrim($version, 'vV');
        return (bool) preg_match('/^\d+\.\d+\.\d+(-[0-9A-Za-z.-]+)?$/', $clean);
    }

    /**
     * Normalize version string by removing 'v' prefix.
     */
    public function normalizeVersion(string $version): string
    {
        return ltrim(trim($version), 'vV');
    }

    /**
     * Compare two versions.
     * Returns:
     *  -1 if $current < $latest (update available)
     *   0 if $current == $latest
     *   1 if $current > $latest (ahead of latest/dev)
     */
    public function compare(string $current, string $latest): int
    {
        $v1 = $this->normalizeVersion($current);
        $v2 = $this->normalizeVersion($latest);

        return version_compare($v1, $v2);
    }

    /**
     * Fetch latest version data from Firebase Realtime Database.
     */
    public function getFirebaseVersion(): ?array
    {
        $dbUrl = config('firebase.database_url');
        if (empty($dbUrl)) {
            return null;
        }

        $url = rtrim($dbUrl, '/') . '/app_version.json';

        try {
            $response = Http::timeout(4)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                if (is_array($data) && !empty($data['version'])) {
                    return $data;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('AppVersionService: Failed to fetch version from Firebase: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Publish new version to Firebase Realtime Database.
     */
    public function publishToFirebase(array $payload): array
    {
        $dbUrl = config('firebase.database_url');
        if (empty($dbUrl)) {
            return [
                'success' => false,
                'message' => 'FIREBASE_DATABASE_URL is not configured.',
            ];
        }

        $cleanVersion = $this->normalizeVersion($payload['version'] ?? '');
        if (!$this->isValidSemVer($cleanVersion)) {
            return [
                'success' => false,
                'message' => 'Invalid semantic version format. Example: 1.2.0',
            ];
        }

        $url = rtrim($dbUrl, '/') . '/app_version.json';
        $secret = config('firebase.database_secret');
        if (!empty($secret)) {
            $url .= '?auth=' . urlencode($secret);
        }

        $body = [
            'version'       => $cleanVersion,
            'release_name'  => $payload['release_name'] ?? ('v' . $cleanVersion),
            'changelog'     => $payload['changelog'] ?? '',
            'update_guide'  => $payload['update_guide'] ?? "1. Buka terminal di folder project\n2. Jalankan: git pull origin develop\n3. Jalankan: composer install\n4. Jalankan: npm run build\n5. Jalankan: php artisan optimize:clear",
            'published_at'  => now()->toISOString(),
            'published_by'  => auth()->user()?->name ?? 'Developer',
        ];

        try {
            $response = Http::timeout(6)->put($url, $body);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Versi v' . $cleanVersion . ' berhasil dipublikasikan ke Firebase!',
                    'data'    => $response->json(),
                ];
            }

            $status = $response->status();
            $errorMsg = $response->body();

            if ($status === 401 || $status === 403) {
                return [
                    'success' => false,
                    'message' => "Firebase Rules menolak penulisan (Status {$status}: Permission Denied). Silakan sesuaikan Rules atau atur FIREBASE_DATABASE_SECRET di .env.",
                ];
            }

            return [
                'success' => false,
                'message' => "Gagal mempublikasikan ke Firebase (HTTP {$status}): {$errorMsg}",
            ];
        } catch (\Throwable $e) {
            Log::error('AppVersionService: Publish error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Koneksi ke Firebase gagal: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Update APP_VERSION in .env file.
     */
    public function updateEnvVersion(string $version): bool
    {
        $cleanVersion = $this->normalizeVersion($version);
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return false;
        }

        $content = File::get($envPath);

        if (preg_match('/^APP_VERSION=.*$/m', $content)) {
            $content = preg_replace('/^APP_VERSION=.*$/m', 'APP_VERSION=' . $cleanVersion, $content);
        } else {
            // Append after APP_NAME or at top
            if (preg_match('/^APP_NAME=.*$/m', $content)) {
                $content = preg_replace('/^(APP_NAME=.*)$/m', "$1\nAPP_VERSION=" . $cleanVersion, $content, 1);
            } else {
                $content = "APP_VERSION=" . $cleanVersion . "\n" . $content;
            }
        }

        File::put($envPath, $content);
        return true;
    }

    /**
     * Update fallback version in config/app.php file.
     */
    public function updateConfigFileVersion(string $version): bool
    {
        $cleanVersion = $this->normalizeVersion($version);
        $configPath = config_path('app.php');

        if (!File::exists($configPath)) {
            return false;
        }

        $content = File::get($configPath);

        // Replace 'version' => env('APP_VERSION', 'x.x.x')
        $content = preg_replace(
            "/('version'\s*=>\s*env\('APP_VERSION',\s*['\"])[^'\"]+(['\"]\),?)/i",
            '${1}' . $cleanVersion . '${2}',
            $content
        );

        File::put($configPath, $content);
        return true;
    }

    /**
     * Sync version to README.md in all designated places:
     * 1. Version Badge (e.g. App_Version-v1.2.2-6d9886)
     * 2. Env sample block (APP_VERSION=1.2.2)
     * 3. Dynamic PWA Service Worker description (CACHE_NAME = "an-mastery-v1.2.2")
     */
    public function syncReadme(string $version): bool
    {
        $cleanVersion = $this->normalizeVersion($version);
        $readmePath = base_path('README.md');

        if (!File::exists($readmePath)) {
            return false;
        }

        $content = File::get($readmePath);

        // 1. Badge version: App_Version-v1.2.2-6d9886
        $content = preg_replace(
            '/(\[!\[Version\]\(https:\/\/img\.shields\.io\/badge\/App_Version-v)[0-9A-Za-z\.\-]+(-6d9886\?style=for-the-badge\))/i',
            '${1}' . $cleanVersion . '${2}',
            $content
        );

        // 2. Env config block: APP_VERSION=1.2.2
        $content = preg_replace(
            '/(```env\s*[\r\n]+APP_VERSION=)[0-9A-Za-z\.\-]+/i',
            '${1}' . $cleanVersion,
            $content
        );

        // 3. PWA Service Worker description: CACHE_NAME = "an-mastery-v1.2.2"
        $content = preg_replace(
            '/(CACHE_NAME\s*=\s*["\']an-mastery-v)[0-9A-Za-z\.\-]+(["\'])/i',
            '${1}' . $cleanVersion . '${2}',
            $content
        );

        File::put($readmePath, $content);
        return true;
    }
}
