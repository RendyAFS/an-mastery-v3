<?php

namespace App\Console\Commands;

use App\Services\AppVersionService;
use Illuminate\Console\Command;

class SyncAppVersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:version
                            {version? : The new semantic version to set (e.g. 1.3.0)}
                            {--publish : Publish the version to Firebase Realtime Database}
                            {--release-name= : Custom release name / label}
                            {--description= : Short summary / description for Firebase}
                            {--changelog= : Changelog / release notes for Firebase}
                            {--sync-readme : Sync existing config version to README.md without changing .env}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage app version, sync to .env, README.md, and optionally publish to Firebase';

    /**
     * Execute the console command.
     */
    public function handle(AppVersionService $versionService): int
    {
        $currentVersion = $versionService->getCurrentVersion();

        // Mode 1: Sync README from existing version
        if ($this->option('sync-readme')) {
            $this->info("Syncing README.md to current version: v{$currentVersion}...");
            $versionService->syncReadme($currentVersion);
            $this->info('✓ README.md synchronized successfully!');
            return Command::SUCCESS;
        }

        $newVersion = $this->argument('version');

        // Mode 2: Show version status
        if (!$newVersion) {
            $this->components->info("Current Local App Version: v{$currentVersion}");

            $this->output->write('Fetching version from Firebase Realtime Database... ');
            $fbData = $versionService->getFirebaseVersion();

            if ($fbData && !empty($fbData['version'])) {
                $fbVersion = $fbData['version'];
                $this->output->writeln("<info>v{$fbVersion}</info>");

                $cmp = $versionService->compare($currentVersion, $fbVersion);
                if ($cmp === -1) {
                    $this->warn("⚠ Update Available! Local: v{$currentVersion} | Firebase: v{$fbVersion}");
                } elseif ($cmp === 0) {
                    $this->info("✓ Local version is UP TO DATE with Firebase (v{$currentVersion}).");
                } else {
                    $this->line("ℹ Local version (v{$currentVersion}) is ahead of Firebase (v{$fbVersion}).");
                }

                if (!empty($fbData['published_at'])) {
                    $this->line("  Published At: {$fbData['published_at']}");
                }
                if (!empty($fbData['changelog'])) {
                    $this->line("  Changelog: {$fbData['changelog']}");
                }
            } else {
                $this->output->writeln('<comment>[Firebase unreachable or no version published]</comment>');
            }

            $this->newLine();
            $this->line('To set a new version, run: <info>php artisan app:version <new-version> [--publish]</info>');
            return Command::SUCCESS;
        }

        // Mode 3: Set new version
        $cleanVersion = $versionService->normalizeVersion($newVersion);
        if (!$versionService->isValidSemVer($cleanVersion)) {
            $this->error("Invalid semantic version format: '{$newVersion}'. Expected format: X.Y.Z (e.g. 1.2.0)");
            return Command::FAILURE;
        }

        $this->info("Setting app version to: v{$cleanVersion}");

        // 1. Update Database app_settings
        $this->output->write('1. Updating Database (app_settings)... ');
        $versionService->setDatabaseVersion($cleanVersion);
        $this->output->writeln('<info>DONE</info>');

        // 2. Update .env & config/app.php
        $this->output->write('2. Updating .env & config/app.php (APP_VERSION)... ');
        $versionService->updateEnvVersion($cleanVersion);
        $versionService->updateConfigFileVersion($cleanVersion);
        $this->output->writeln('<info>DONE</info>');

        // 3. Sync README.md
        $this->output->write('3. Updating README.md (Badge, Env sample, PWA SW)... ');
        $versionService->syncReadme($cleanVersion);
        $this->output->writeln('<info>DONE</info>');

        // 4. Clear config cache
        $this->output->write('4. Clearing config cache... ');
        $this->callSilently('config:clear');
        $this->output->writeln('<info>DONE</info>');

        // 4. Optionally publish to Firebase
        if ($this->option('publish')) {
            $this->output->write('4. Publishing to Firebase Realtime Database... ');
            $result = $versionService->publishToFirebase([
                'version'      => $cleanVersion,
                'release_name' => $this->option('release-name') ?? ('Release v' . $cleanVersion),
                'description'  => $this->option('description') ?? '',
                'changelog'    => $this->option('changelog') ?? '',
            ]);

            if ($result['success']) {
                $this->output->writeln('<info>PUBLISHED</info>');
            } else {
                $this->output->writeln("<error>FAILED ({$result['message']})</error>");
            }
        }

        $this->newLine();
        $this->components->info("✓ App version successfully updated to v{$cleanVersion}!");
        return Command::SUCCESS;
    }
}
