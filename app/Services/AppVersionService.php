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

    /**
     * Get list of deployment and maintenance scripts stored as JSON in app_settings.
     */
    public function getDeploymentScripts(): array
    {
        // Default built-in scripts with full bash script content stored directly in database
        $defaultScripts = [
            [
                'key'            => 'update_project',
                'name'           => 'Update Project (Git, Composer, NPM, DB, Build, Optimize)',
                'icon'           => 'zap',
                'description'    => 'Menjalankan pembaruan menyeluruh: Git Pull, Composer, NPM, Database Migration & Seeding, Build Assets, dan Optimize.',
                'script_content' => <<<'BASH'
#!/usr/bin/env bash

# Resolve project directory dynamically
if [ -f "artisan" ]; then
    PROJECT_DIR="$(pwd)"
elif [ -d "$HOME/project-apps/an-mastery-v3" ]; then
    PROJECT_DIR="$HOME/project-apps/an-mastery-v3"
elif [ -d "$HOME/an-mastery-v3" ]; then
    PROJECT_DIR="$HOME/an-mastery-v3"
else
    PROJECT_DIR="$(pwd)"
fi

echo "========================================="
echo "      AN MASTERY - UPDATE PROJECT"
echo "========================================="
echo

cd "$PROJECT_DIR" || {
    echo "❌ Project tidak ditemukan: $PROJECT_DIR"
    exit 1
}

echo "📁 $(pwd)"
echo

# Prevent dubious ownership error in Git (Linux web server permissions)
git config --global --add safe.directory "$PROJECT_DIR" 2>/dev/null || true
git config --global --add safe.directory "*" 2>/dev/null || true

# Setup SSH parameters so web server (e.g. www-data) can authenticate using system SSH keys without host prompt
export GIT_TERMINAL_PROMPT=0

SSH_KEY_ARG=""
for CANDIDATE_DIR in "$HOME/.ssh" "/home/mint/.ssh" "/root/.ssh"; do
    if [ -d "$CANDIDATE_DIR" ]; then
        for CANDIDATE_KEY in "$CANDIDATE_DIR/id_ed25519" "$CANDIDATE_DIR/id_rsa" "$CANDIDATE_DIR/id_ecdsa"; do
            if [ -f "$CANDIDATE_KEY" ]; then
                SSH_KEY_ARG="-i $CANDIDATE_KEY"
                break 2
            fi
        done
    fi
done

export GIT_SSH_COMMAND="ssh $SSH_KEY_ARG -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null"

# Determine active git branch automatically
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "develop")
echo "⬇️  Git Pull (branch: $CURRENT_BRANCH)..."
git pull origin "$CURRENT_BRANCH" 2>&1 || git pull origin develop 2>&1 || git pull origin main 2>&1 || git pull 2>&1 || exit 1

echo
echo "📦 Composer Install..."
composer install --no-interaction --prefer-dist --optimize-autoloader || exit 1

echo
echo "📦 NPM Install..."
npm install || exit 1

echo
echo "🗄️  Database Migration..."
php artisan migrate --force || exit 1

echo
echo "🌱 Seed Menu Permissions..."
php artisan db:seed --class=MenuPermissionSeeder --force || exit 1

echo
echo "🧹 Clear Cache..."
php artisan optimize:clear || exit 1

echo
echo "🏗️  Build Assets..."
npm run build || exit 1

echo
echo "⚡ Optimize..."
php artisan optimize || exit 1

IP=$(hostname -I 2>/dev/null | awk '{print $1}')
if [ -z "$IP" ]; then
    IP="127.0.0.1"
fi

echo
echo "========================================="
echo "✅ UPDATE BERHASIL"
echo "========================================="
echo
echo "🌐 HTTPS : https://$IP"
echo "🌐 HTTP  : http://$IP"
echo
BASH
            ],
            [
                'key'            => 'backup_db',
                'name'           => 'Backup Database MySQL',
                'icon'           => 'database',
                'description'    => 'Melakukan ekspor dump database MySQL ke folder backup.',
                'script_content' => <<<'BASH'
#!/bin/bash

# =========================================
# AN MASTERY DATABASE BACKUP
# =========================================

BACKUP_DIR="${BACKUP_DIR:-$HOME/backup-data}"

DB_NAME="an_mastery"
DB_USER="andri-sablon"
DB_PASSWORD="@andri-sablon"

TIMESTAMP=$(date +"%Y-%m-%d_%H-%M-%S")
BACKUP_FILE="${BACKUP_DIR}/an-mastery-db-${TIMESTAMP}.sql"

echo "========================================="
echo "     AN MASTERY DATABASE BACKUP"
echo "========================================="
echo

mkdir -p "$BACKUP_DIR"

echo "🗄️  Database : $DB_NAME"
echo "📄 Backup   : $BACKUP_FILE"
echo

echo "🗄️  Export database..."

mysqldump \
    -u"$DB_USER" \
    -p"$DB_PASSWORD" \
    --single-transaction \
    --no-tablespaces \
    --routines \
    --triggers \
    --events \
    "$DB_NAME" > "$BACKUP_FILE"

if [ $? -ne 0 ]; then
    echo
    echo "❌ Database backup GAGAL!"
    echo
    rm -f "$BACKUP_FILE"
    exit 1
fi

if [ ! -s "$BACKUP_FILE" ]; then
    echo
    echo "❌ File SQL kosong!"
    echo "Backup dibatalkan."
    rm -f "$BACKUP_FILE"
    exit 1
fi

echo
echo "========================================="
echo "✅ DATABASE BACKUP BERHASIL"
echo "========================================="
echo
echo "📄 File:"
echo "$BACKUP_FILE"
echo
echo "📊 Ukuran:"
du -h "$BACKUP_FILE" | awk '{print $1}'
echo
echo "📁 Folder:"
echo "$BACKUP_DIR"
echo
echo "========================================="
BASH
            ],
            [
                'key'            => 'backup_all',
                'name'           => 'Backup Full (Database + Storage)',
                'icon'           => 'archive',
                'description'    => 'Melakukan backup menyeluruh untuk database MySQL dan seluruh file storage.',
                'script_content' => <<<'BASH'
#!/bin/bash

# =========================================
# AN MASTERY FULL BACKUP (DB + STORAGE)
# =========================================

# Resolve project directory dynamically
if [ -f "artisan" ]; then
    PROJECT_DIR="$(pwd)"
elif [ -d "$HOME/project-apps/an-mastery-v3" ]; then
    PROJECT_DIR="$HOME/project-apps/an-mastery-v3"
elif [ -d "$HOME/an-mastery-v3" ]; then
    PROJECT_DIR="$HOME/an-mastery-v3"
else
    PROJECT_DIR="$(pwd)"
fi

BACKUP_DIR="${BACKUP_DIR:-$HOME/backup-data}"

DB_NAME="an_mastery"
DB_USER="andri-sablon"
DB_PASSWORD="@andri-sablon"

TIMESTAMP=$(date +"%Y-%m-%d_%H-%M-%S")
BACKUP_NAME="an-mastery-full-${TIMESTAMP}"
TEMP_DIR="/tmp/${BACKUP_NAME}"
ZIP_FILE="${BACKUP_DIR}/${BACKUP_NAME}.zip"
STORAGE_DIR="$PROJECT_DIR/public/storage"

echo "========================================="
echo "       AN MASTERY FULL BACKUP"
echo "========================================="
echo

if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ Project tidak ditemukan: $PROJECT_DIR"
    exit 1
fi

mkdir -p "$BACKUP_DIR"
rm -rf "$TEMP_DIR"
mkdir -p "$TEMP_DIR"

echo "📁 Project : $PROJECT_DIR"
echo "🗄️  Database: $DB_NAME"
echo "📂 Storage : $STORAGE_DIR"
echo "📦 Backup  : $ZIP_FILE"
echo

echo "Export database..."

mysqldump \
    -u"$DB_USER" \
    -p"$DB_PASSWORD" \
    --single-transaction \
    --no-tablespaces \
    --routines \
    --triggers \
    --events \
    "$DB_NAME" > "$TEMP_DIR/database.sql"

if [ $? -ne 0 ]; then
    echo
    echo "❌ Database backup GAGAL!"
    rm -rf "$TEMP_DIR"
    exit 1
fi

if [ ! -s "$TEMP_DIR/database.sql" ]; then
    echo
    echo "❌ File database.sql kosong!"
    rm -rf "$TEMP_DIR"
    exit 1
fi

echo "✅ Database berhasil di-export"

if [ -d "$STORAGE_DIR" ]; then
    echo "Backup storage..."
    cp -a "$STORAGE_DIR" "$TEMP_DIR/storage"
    echo "✅ Storage berhasil di-backup"
fi

echo "Membuat ZIP..."
cd /tmp || exit 1
zip -r "$ZIP_FILE" "$BACKUP_NAME" > /dev/null

if [ $? -ne 0 ]; then
    echo "❌ Gagal membuat ZIP!"
    rm -rf "$TEMP_DIR"
    exit 1
fi

rm -rf "$TEMP_DIR"

echo
echo "========================================="
echo "✅ FULL BACKUP BERHASIL"
echo "========================================="
echo
echo "📦 File:"
echo "$ZIP_FILE"
echo
echo "📊 Ukuran:"
du -h "$ZIP_FILE" | awk '{print $1}'
echo
echo "========================================="
BASH
            ],
        ];

        $json = AppSetting::get('deployment_scripts');

        if (!empty($json)) {
            $decoded = json_decode($json, true);
            if (is_array($decoded) && !empty($decoded)) {
                $needsResave = false;
                // Check if existing records lack script_content or need safe.directory upgrade
                foreach ($decoded as &$item) {
                    if (empty($item['script_content'])) {
                        foreach ($defaultScripts as $def) {
                            if (($def['key'] ?? '') === ($item['key'] ?? '')) {
                                $item['script_content'] = $def['script_content'];
                                $needsResave = true;
                                break;
                            }
                        }
                    } elseif (($item['key'] ?? '') === 'update_project' && !str_contains($item['script_content'], 'GIT_SSH_COMMAND')) {
                        foreach ($defaultScripts as $def) {
                            if (($def['key'] ?? '') === 'update_project') {
                                $item['script_content'] = $def['script_content'];
                                $needsResave = true;
                                break;
                            }
                        }
                    }
                }

                if ($needsResave) {
                    $this->saveDeploymentScripts($decoded);
                }

                return $decoded;
            }
        }

        // Seed default into app_settings
        $this->saveDeploymentScripts($defaultScripts);

        return $defaultScripts;
    }

    /**
     * Save deployment scripts configuration JSON to app_settings.
     */
    public function saveDeploymentScripts(array $scripts): void
    {
        AppSetting::set('deployment_scripts', json_encode($scripts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), 'JSON configuration of deployment and terminal scripts');
    }

    /**
     * Resolve a deployment script by key from app_settings.
     */
    public function resolveScriptByKey(?string $key = null): ?array
    {
        $scripts = $this->getDeploymentScripts();
        $targetKey = $key ?: 'update_project';

        foreach ($scripts as $s) {
            if (($s['key'] ?? '') === $targetKey) {
                return $s;
            }
        }

        return $scripts[0] ?? null;
    }

    /**
     * Get details of a single script from database.
     */
    public function getScriptDetail(string $key): ?array
    {
        $script = $this->resolveScriptByKey($key);
        if (!$script) {
            return null;
        }

        return [
            'script'         => $script,
            'script_content' => $script['script_content'] ?? '',
        ];
    }

    /**
     * Save or update a single script item by key in database (supports key renaming).
     */
    public function saveSingleScript(array $data): array
    {
        $scripts = $this->getDeploymentScripts();
        $key = trim($data['key'] ?? '');
        $originalKey = trim($data['original_key'] ?? ($data['key'] ?? ''));

        if (empty($key)) {
            $key = \Illuminate\Support\Str::slug($data['name'] ?? 'custom_script', '_');
        }

        $scriptItem = [
            'key'            => $key,
            'name'           => trim($data['name'] ?? $key),
            'icon'           => trim($data['icon'] ?? 'terminal'),
            'description'    => trim($data['description'] ?? ''),
            'script_content' => trim($data['script_content'] ?? ($data['file_content'] ?? '')),
        ];

        $updated = false;
        foreach ($scripts as $idx => $s) {
            if (($s['key'] ?? '') === $originalKey || ($s['key'] ?? '') === $key) {
                $scripts[$idx] = $scriptItem;
                $updated = true;
                break;
            }
        }

        if (!$updated) {
            $scripts[] = $scriptItem;
        }

        $this->saveDeploymentScripts($scripts);

        return $scriptItem;
    }

    /**
     * Delete a script by key from database.
     */
    public function deleteScript(string $key): bool
    {
        $scripts = $this->getDeploymentScripts();
        $filtered = array_values(array_filter($scripts, fn($s) => ($s['key'] ?? '') !== $key));

        if (count($filtered) !== count($scripts)) {
            $this->saveDeploymentScripts($filtered);
            return true;
        }

        return false;
    }

    /**
     * Perform pre-flight safety checks before executing automatic deployment update.
     */
    public function validateEnvironmentForUpdate(): array
    {
        $errors = [];
        $warnings = [];

        // 1. Check proc_open function availability
        if (!function_exists('proc_open')) {
            $errors[] = 'Fungsi PHP proc_open() dinonaktifkan di php.ini (disable_functions).';
        }

        // 2. Check base project files
        if (!File::exists(base_path('artisan'))) {
            $errors[] = 'Struktur folder tidak cocok: file artisan tidak ditemukan di direktori root project (' . base_path() . ').';
        }
        if (!File::exists(base_path('composer.json'))) {
            $errors[] = 'Struktur folder tidak cocok: composer.json tidak ditemukan.';
        }

        // 3. Check git repository
        $isGit = File::exists(base_path('.git')) || File::isDirectory(base_path('.git'));
        if (!$isGit) {
            $warnings[] = 'Folder .git tidak ditemukan di root project. Pastikan folder ini merupakan clone repository Git.';
        }

        return [
            'is_safe'     => empty($errors),
            'is_git'      => $isGit,
            'os'          => PHP_OS_FAMILY,
            'base_path'   => base_path(),
            'errors'      => $errors,
            'warnings'    => $warnings,
        ];
    }
}
