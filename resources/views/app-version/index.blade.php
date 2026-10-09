@extends('layouts.main', ['title' => __('app_version.title')])

@push('scripts')
    @vite('resources/js/pages/app-version/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-(--color-dark) dark:text-(--color-light)">
                    {{ __('app_version.title') }}
                </h1>
                <p class="text-sm text-(--color-dark-gray) dark:text-(--color-gray) mt-1">
                    {{ __('app_version.subtitle') }}
                </p>
            </div>

            <button type="button" id="btn-refresh-status" data-loading-text="{{ __('app_version.btn_checking') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-medium
                       bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/30
                       hover:border-(--color-primary) hover:text-(--color-primary)
                       text-(--color-dark) dark:text-(--color-light) shadow-2xs transition-all cursor-pointer">
                <span class="size-4 border-2 border-(--color-primary) border-t-transparent rounded-full animate-spin hidden"
                    data-spinner></span>
                <i data-lucide="refresh-cw" class="size-4" data-icon></i>
                <span data-text>{{ __('app_version.btn_refresh') }}</span>
            </button>
        </div>

        <!-- Status Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- 1. Local Version -->
            <div
                class="p-5 rounded-2xl bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/20 shadow-sm relative overflow-hidden group">
                <div
                    class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity text-(--color-primary)">
                    <i data-lucide="hard-drive" class="size-24"></i>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-xs font-semibold uppercase tracking-wider text-(--color-dark-gray) dark:text-(--color-gray)">
                        {{ __('app_version.current_local_version') }}
                    </span>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-(--color-primary)/10 text-(--color-primary)">
                        <span class="size-1.5 rounded-full bg-(--color-primary) animate-pulse"></span>
                        {{ __('app_version.database_sot') }}
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-(--color-dark) dark:text-(--color-light)"
                        id="display-local-version">
                        v{{ $currentVersion }}
                    </span>
                </div>
                <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-2">
                    {{ __('app_version.sot_info', ['table' => 'app_settings']) }}
                </p>
            </div>

            <!-- 2. Firebase RTDB Version -->
            <div
                class="p-5 rounded-2xl bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/20 shadow-sm relative overflow-hidden group">
                <div
                    class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity text-(--color-blue)">
                    <i data-lucide="cloud" class="size-24"></i>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-xs font-semibold uppercase tracking-wider text-(--color-dark-gray) dark:text-(--color-gray)">
                        {{ __('app_version.firebase_latest_version') }}
                    </span>
                    <span
                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-(--color-blue)/10 text-(--color-blue)">
                        <i data-lucide="database" class="size-3"></i>
                        {{ __('app_version.firebase_rtdb') }}
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-(--color-dark) dark:text-(--color-light)"
                        id="display-firebase-version">
                        {{ isset($firebaseData['version']) ? 'v' . $firebaseData['version'] : '-' }}
                    </span>
                </div>
                <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-2" id="display-firebase-meta">
                    @if (isset($firebaseData['published_at']))
                        {{ __('app_version.published_diff', ['time' => \Carbon\Carbon::parse($firebaseData['published_at'])->diffForHumans()]) }}
                        @if (!empty($firebaseData['published_by']))
                            {{ __('app_version.published_by_user', ['user' => $firebaseData['published_by']]) }}
                        @endif
                    @else
                        {{ __('app_version.status_unreachable') }}
                    @endif
                </p>
            </div>

            <!-- 3. Sync Status Badge -->
            <div
                class="p-5 rounded-2xl bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/20 shadow-sm relative overflow-hidden group flex flex-col justify-between">
                <div>
                    <span
                        class="text-xs font-semibold uppercase tracking-wider text-(--color-dark-gray) dark:text-(--color-gray)">
                        {{ __('app_version.sync_status') }}
                    </span>
                    <div class="mt-3" id="display-sync-status">
                        @if (is_null($comparison))
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-(--color-yellow)/10 text-(--color-yellow) text-sm font-semibold">
                                <i data-lucide="alert-circle" class="size-4"></i>
                                <span>{{ __('app_version.status_not_connected') }}</span>
                            </div>
                            <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-2">
                                {{ __('app_version.status_not_connected_desc') }}
                            </p>
                        @elseif($comparison === 0)
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-(--color-green)/10 text-(--color-green) text-sm font-semibold">
                                <i data-lucide="check-circle-2" class="size-4"></i>
                                <span>{{ __('app_version.status_up_to_date') }}</span>
                            </div>
                            <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-2">
                                {{ __('app_version.status_up_to_date_desc') }}
                            </p>
                        @elseif($comparison === -1)
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-(--color-primary)/15 text-(--color-primary) text-sm font-semibold">
                                <i data-lucide="arrow-up-circle" class="size-4"></i>
                                <span>{{ __('app_version.status_update_available') }}</span>
                            </div>
                            <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-2">
                                {{ __('app_version.status_update_available_desc', ['firebase_version' => $firebaseData['version'] ?? '', 'local_version' => $currentVersion]) }}
                            </p>
                        @else
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-(--color-blue)/10 text-(--color-blue) text-sm font-semibold">
                                <i data-lucide="code" class="size-4"></i>
                                <span>{{ __('app_version.status_ahead') }}</span>
                            </div>
                            <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-2">
                                {{ __('app_version.status_ahead_desc') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form & Info Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Publish Form (2 cols) -->
            <div
                class="lg:col-span-2 p-6 rounded-2xl bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/20 shadow-sm space-y-5">
                <div class="border-b border-(--color-gray)/15 pb-4">
                    <h2 class="text-lg font-bold text-(--color-dark) dark:text-(--color-light) flex items-center gap-2">
                        <i data-lucide="rocket" class="size-5 text-(--color-primary)"></i>
                        {{ __('app_version.publish_new_version') }}
                    </h2>
                    <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-1">
                        {{ __('app_version.publish_desc') }}
                    </p>
                </div>

                <form id="form-publish-version" class="space-y-4">
                    <!-- Version Number with Helper Buttons -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="input-version"
                                class="text-xs font-semibold uppercase tracking-wider text-(--color-dark) dark:text-(--color-light)">
                                {{ __('app_version.version_number') }} <span class="text-(--color-red)">*</span>
                            </label>
                            <!-- Quick semver increment buttons -->
                            <div class="flex items-center gap-1.5 text-xs">
                                <span
                                    class="text-(--color-dark-gray) text-[11px] hidden sm:inline">{{ __('app_version.increment_label') }}</span>
                                <button type="button"
                                    class="btn-increment px-2 py-0.5 rounded bg-(--color-light-gray) dark:bg-(--color-dark-slate) hover:bg-(--color-primary)/10 text-(--color-primary) text-[11px] font-medium transition-colors cursor-pointer"
                                    data-inc="patch">
                                    +Patch
                                </button>
                                <button type="button"
                                    class="btn-increment px-2 py-0.5 rounded bg-(--color-light-gray) dark:bg-(--color-dark-slate) hover:bg-(--color-primary)/10 text-(--color-primary) text-[11px] font-medium transition-colors cursor-pointer"
                                    data-inc="minor">
                                    +Minor
                                </button>
                                <button type="button"
                                    class="btn-increment px-2 py-0.5 rounded bg-(--color-light-gray) dark:bg-(--color-dark-slate) hover:bg-(--color-primary)/10 text-(--color-primary) text-[11px] font-medium transition-colors cursor-pointer"
                                    data-inc="major">
                                    +Major
                                </button>
                                <button type="button"
                                    class="btn-increment inline-flex items-center gap-1 px-2 py-0.5 rounded bg-(--color-light-gray) dark:bg-(--color-dark-slate) hover:bg-red-500/10 hover:text-red-500 text-(--color-dark-gray) dark:text-(--color-gray) text-[11px] font-medium transition-colors cursor-pointer"
                                    data-inc="reset" title="{{ __('app_version.btn_reset_version') }}">
                                    <i data-lucide="rotate-ccw" class="size-3"></i>
                                    <span>Reset</span>
                                </button>
                            </div>
                        </div>
                        <div class="relative">
                            <input type="text" id="input-version" name="version" value="{{ $currentVersion }}"
                                placeholder="{{ __('app_version.version_placeholder') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-(--color-gray)/30 bg-transparent
                                       text-(--color-dark) dark:text-(--color-light) font-mono text-sm
                                       focus:border-(--color-primary) focus:ring-1 focus:ring-(--color-primary) outline-none transition-all">
                        </div>
                        <span class="text-[11px] text-(--color-dark-gray) dark:text-(--color-gray) mt-1 block">
                            {{ __('app_version.semver_help', ['format' => 'X.Y.Z']) }}
                        </span>
                    </div>

                    <!-- Release Name -->
                    <div>
                        <label for="input-release-name"
                            class="block text-xs font-semibold uppercase tracking-wider text-(--color-dark) dark:text-(--color-light) mb-1.5">
                            {{ __('app_version.release_name') }}
                        </label>
                        <input type="text" id="input-release-name" name="release_name"
                            placeholder="{{ __('app_version.release_name_placeholder') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-(--color-gray)/30 bg-transparent
                                   text-(--color-dark) dark:text-(--color-light) text-sm
                                   focus:border-(--color-primary) focus:ring-1 focus:ring-(--color-primary) outline-none transition-all">
                    </div>

                    <!-- Changelog -->
                    <div>
                        <label for="input-changelog"
                            class="block text-xs font-semibold uppercase tracking-wider text-(--color-dark) dark:text-(--color-light) mb-1.5">
                            {{ __('app_version.changelog') }}
                        </label>
                        <textarea id="input-changelog" name="changelog" rows="4"
                            placeholder="{{ __('app_version.changelog_placeholder') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-(--color-gray)/30 bg-transparent
                                   text-(--color-dark) dark:text-(--color-light) text-sm font-sans
                                   focus:border-(--color-primary) focus:ring-1 focus:ring-(--color-primary) outline-none transition-all custom-scrollbar"></textarea>
                    </div>

                    <!-- Update Guide -->
                    <div>
                        <label for="input-update-guide"
                            class="block text-xs font-semibold uppercase tracking-wider text-(--color-dark) dark:text-(--color-light) mb-1.5">
                            {{ __('app_version.update_guide') }}
                        </label>
                        <textarea id="input-update-guide" name="update_guide" rows="6"
                            class="w-full px-4 py-2.5 rounded-xl border border-(--color-gray)/30 bg-transparent
                                   text-(--color-dark) dark:text-(--color-light) text-xs font-mono
                                   focus:border-(--color-primary) focus:ring-1 focus:ring-(--color-primary) outline-none transition-all custom-scrollbar">
1. Buka terminal di folder project
2. Jalankan: git pull origin main
3. Jalankan: composer install
4. Jalankan: npm run build
5. Jalankan: php artisan optimize:clear
                                </textarea>
                    </div>

                    <!-- Sync Local Checkbox -->
                    <div
                        class="flex items-start gap-3 p-3.5 rounded-xl bg-(--color-light-gray)/60 dark:bg-(--color-dark-slate)/60 border border-(--color-gray)/20">
                        <input type="checkbox" id="check-sync-local" name="sync_local" value="1" checked
                            class="mt-0.5 size-4 rounded border-(--color-gray)/40 text-(--color-primary) focus:ring-(--color-primary) cursor-pointer">
                        <label for="check-sync-local"
                            class="text-xs text-(--color-dark) dark:text-(--color-light) cursor-pointer select-none">
                            <span class="font-semibold block">{{ __('app_version.sync_readme_label') }}</span>
                            <span class="text-[11px] text-(--color-dark-gray) dark:text-(--color-gray)">
                                {{ __('app_version.sync_readme_desc', ['table' => 'app_settings', 'file' => 'README.md']) }}
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2 flex justify-end">
                        <button type="submit" id="btn-submit-publish"
                            data-loading-text="{{ __('app_version.btn_publishing') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold
                                   bg-(--color-primary) text-(--color-light) shadow-md shadow-(--color-primary)/20
                                   hover:bg-(--color-primary)/90 active:scale-95 transition-all cursor-pointer">
                            <span
                                class="size-4 border-2 border-white border-t-transparent rounded-full animate-spin hidden"
                                data-spinner></span>
                            <i data-lucide="cloud-upload" class="size-4" data-icon></i>
                            <span data-text>{{ __('app_version.btn_publish') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: Technical Details & CLI Guide (1 col) -->
            <div class="space-y-5">
                <!-- CLI Automation Card -->
                <div
                    class="p-5 rounded-2xl bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/20 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-(--color-dark) dark:text-(--color-light) flex items-center gap-2">
                        <i data-lucide="terminal" class="size-4 text-(--color-primary)"></i>
                        {{ __('app_version.cli_shortcut_title') }}
                    </h3>
                    <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray)">
                        {{ __('app_version.cli_shortcut_desc') }}
                    </p>
                    <div
                        class="p-3 rounded-xl bg-(--color-dark) text-emerald-400 font-mono text-[11px] space-y-2 overflow-x-auto">
                        <div>
                            <span class="text-gray-400">{{ __('app_version.cli_check_comment') }}</span><br>
                            php artisan app:version
                        </div>
                        <div>
                            <span class="text-gray-400">{{ __('app_version.cli_set_comment') }}</span><br>
                            php artisan app:version x.x.x
                        </div>
                        <div>
                            <span class="text-gray-400">{{ __('app_version.cli_publish_comment') }}</span><br>
                            php artisan app:version x.x.x --publish
                        </div>
                    </div>
                </div>

                <!-- Firebase RTDB Info -->
                <div
                    class="p-5 rounded-2xl bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/20 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-(--color-dark) dark:text-(--color-light) flex items-center gap-2">
                        <i data-lucide="shield-alert" class="size-4 text-(--color-blue)"></i>
                        {{ __('app_version.security_rules_title') }}
                    </h3>
                    <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) leading-relaxed">
                        {{ __('app_version.security_rules_desc', ['path' => 'app_version']) }}
                    </p>
                    <div class="p-3 rounded-xl bg-(--color-dark) text-yellow-300 font-mono text-[11px] overflow-x-auto">
                        <pre class="m-0">{
  "rules": {
    "app_version": {
      ".read": true,
      ".write": true
    }
  }
}</pre>
                    </div>
                    <p class="text-[11px] text-(--color-dark-gray) dark:text-(--color-gray)">
                        {{ __('app_version.security_rules_note') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Script Management & Terminal Execution Section (Full Width) -->
        <div class="p-6 rounded-2xl bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/20 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-(--color-gray)/15 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-(--color-dark) dark:text-(--color-light) flex items-center gap-2">
                        <i data-lucide="terminal-square" class="size-5 text-(--color-primary)"></i>
                        {{ __('app_version.scripts_management_title') }}
                    </h2>
                    <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-1">
                        {{ __('app_version.scripts_management_subtitle') }}
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button type="button"
                        id="btn-add-new-script"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold
                               bg-(--color-primary) hover:bg-(--color-primary)/90 text-white shadow-sm transition-all cursor-pointer">
                        <i data-lucide="plus" class="size-4"></i>
                        <span>{{ __('app_version.btn_add_script') }}</span>
                    </button>

                    <button type="button"
                        data-hs-overlay="#modal-edit-scripts-json"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold
                               bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/30
                               hover:border-(--color-primary) hover:text-(--color-primary)
                               text-(--color-dark) dark:text-(--color-light) shadow-2xs transition-all cursor-pointer">
                        <i data-lucide="file-json" class="size-4 text-emerald-500"></i>
                        <span>{{ __('app_version.btn_edit_scripts_json') }}</span>
                    </button>
                </div>
            </div>

            <!-- Grid of configured scripts -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5" id="scripts-container">
                @foreach($deploymentScripts as $script)
                    <div class="p-5 rounded-2xl bg-(--color-light-gray)/40 dark:bg-(--color-dark-slate)/40 border border-(--color-gray)/20 flex flex-col justify-between space-y-4 hover:border-(--color-primary)/50 shadow-xs transition-all">
                        <div class="space-y-3">
                            <!-- Header -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-xl bg-(--color-primary)/10 text-(--color-primary) flex items-center justify-center shrink-0">
                                        <i data-lucide="{{ $script['icon'] ?? 'terminal' }}" class="size-5"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-(--color-dark) dark:text-(--color-light) leading-snug">
                                            {{ $script['name'] }}
                                        </h4>
                                        <span class="text-[10px] font-mono text-(--color-dark-gray) dark:text-(--color-gray)">
                                            key: <span class="font-semibold text-(--color-primary)">{{ $script['key'] }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) leading-relaxed">
                                {{ $script['description'] ?? '' }}
                            </p>

                            <!-- Target Script Preview Box -->
                            <div class="p-3 rounded-xl bg-(--color-dark) text-neutral-300 font-mono text-[11px] space-y-1.5 overflow-x-auto">
                                <div class="text-[10px] text-neutral-400 font-semibold flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="terminal" class="size-3.5 text-emerald-400"></i>
                                        <span>Script Shell (Database):</span>
                                    </div>
                                    <span class="text-[10px] text-emerald-400 font-sans">Database SOT</span>
                                </div>
                                <pre class="text-emerald-400 text-[10.5px] font-mono whitespace-pre-wrap max-h-20 overflow-y-auto custom-scrollbar m-0 select-text leading-relaxed opacity-90">{{ \Illuminate\Support\Str::limit($script['script_content'] ?? '#!/usr/bin/env bash', 250) }}</pre>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="pt-3 border-t border-(--color-gray)/15 flex items-center justify-between gap-2">
                            <button type="button"
                                data-script-key="{{ $script['key'] }}"
                                class="btn-edit-single-script inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold bg-(--color-light) dark:bg-(--color-dark) hover:bg-(--color-primary)/10 text-(--color-dark) dark:text-(--color-light) hover:text-(--color-primary) border border-(--color-gray)/30 transition-all cursor-pointer">
                                <i data-lucide="pencil" class="size-3.5 text-blue-500"></i>
                                <span>{{ __('app_version.btn_edit_script') }}</span>
                            </button>

                            <button type="button"
                                data-script-key="{{ $script['key'] }}"
                                class="btn-run-script inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-(--color-primary) hover:bg-(--color-primary)/90 text-white shadow-2xs transition-all cursor-pointer">
                                <i data-lucide="play" class="size-3.5"></i>
                                <span>{{ __('app_version.btn_run_script') }}</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Edit / Add Single Script -->
    <div id="modal-edit-single-script"
        class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
        role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-300 mt-0 opacity-0 ease-out transition-all sm:max-w-2xl sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/20 shadow-2xl rounded-2xl pointer-events-auto overflow-hidden">
                <div class="p-5 border-b border-(--color-gray)/15 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base text-(--color-dark) dark:text-(--color-light)" id="single-script-modal-title">
                            {{ __('app_version.modal_edit_single_script_title') }}
                        </h3>
                        <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-0.5">
                            {{ __('app_version.modal_edit_single_script_desc') }}
                        </p>
                    </div>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-lg text-(--color-dark-gray) hover:bg-(--color-gray)/20 cursor-pointer" data-hs-overlay="#modal-edit-single-script">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>

                <form id="form-edit-single-script" class="p-5 space-y-4 max-h-[78vh] overflow-y-auto custom-scrollbar">
                    <input type="hidden" id="single-script-mode" value="edit">
                    <input type="hidden" id="input-script-original-key" name="original_key" value="">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Key -->
                        <div>
                            <label for="input-script-key" class="block text-xs font-semibold uppercase tracking-wider text-(--color-dark) dark:text-(--color-light) mb-1.5">
                                {{ __('app_version.field_script_key') }} <span class="text-(--color-red)">*</span>
                            </label>
                            <input type="text" id="input-script-key" name="key" required
                                placeholder="contoh: update_project"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-(--color-gray)/30 bg-transparent text-(--color-dark) dark:text-(--color-light) font-mono text-xs focus:border-(--color-primary) focus:ring-1 focus:ring-(--color-primary) outline-none transition-all">
                        </div>

                        <!-- Icon -->
                        <div>
                            <label for="input-script-icon" class="block text-xs font-semibold uppercase tracking-wider text-(--color-dark) dark:text-(--color-light) mb-1.5">
                                {{ __('app_version.field_script_icon') }}
                            </label>
                            <input type="text" id="input-script-icon" name="icon"
                                placeholder="zap, database, archive, terminal, refresh-cw"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-(--color-gray)/30 bg-transparent text-(--color-dark) dark:text-(--color-light) text-xs focus:border-(--color-primary) focus:ring-1 focus:ring-(--color-primary) outline-none transition-all">
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="input-script-name" class="block text-xs font-semibold uppercase tracking-wider text-(--color-dark) dark:text-(--color-light) mb-1.5">
                            {{ __('app_version.field_script_name') }} <span class="text-(--color-red)">*</span>
                        </label>
                        <input type="text" id="input-script-name" name="name" required
                            placeholder="contoh: Update Project (Git, NPM, DB, Build)"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-(--color-gray)/30 bg-transparent text-(--color-dark) dark:text-(--color-light) text-xs font-medium focus:border-(--color-primary) focus:ring-1 focus:ring-(--color-primary) outline-none transition-all">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="input-script-description" class="block text-xs font-semibold uppercase tracking-wider text-(--color-dark) dark:text-(--color-light) mb-1.5">
                            {{ __('app_version.field_script_description') }}
                        </label>
                        <textarea id="input-script-description" name="description" rows="2"
                            placeholder="Penjelasan fungsi dan tujuan script..."
                            class="w-full p-3 rounded-xl border border-(--color-gray)/30 bg-transparent text-(--color-dark) dark:text-(--color-light) text-xs focus:border-(--color-primary) focus:ring-1 focus:ring-(--color-primary) outline-none custom-scrollbar"></textarea>
                    </div>

                    <!-- Shell Script Content Editor (Direct to Database) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="textarea-script-content" class="text-xs font-semibold uppercase tracking-wider text-(--color-dark) dark:text-(--color-light) flex items-center gap-1.5">
                                <i data-lucide="code" class="size-3.5 text-emerald-500"></i>
                                {{ __('app_version.field_script_content') }} <span class="text-(--color-red)">*</span>
                            </label>
                            <span class="text-[10px] text-emerald-500 font-medium">
                                Disimpan langsung di database app_settings
                            </span>
                        </div>
                        <textarea id="textarea-script-content" name="script_content" rows="12" required
                            placeholder="#!/usr/bin/env bash&#10;&#10;echo 'Running script...'&#10;php artisan optimize:clear"
                            class="w-full p-3.5 rounded-xl border border-(--color-gray)/30 bg-neutral-950 text-emerald-400 font-mono text-xs focus:ring-1 focus:ring-(--color-primary) outline-none custom-scrollbar leading-relaxed whitespace-pre"></textarea>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-(--color-gray)/15">
                        <button type="button" id="btn-delete-single-script"
                            class="py-2 px-3.5 inline-flex items-center gap-1.5 text-xs font-semibold rounded-xl text-red-500 hover:bg-red-500/10 transition-colors cursor-pointer hidden">
                            <i data-lucide="trash-2" class="size-3.5"></i>
                            <span>Hapus</span>
                        </button>

                        <div class="flex items-center gap-2 ms-auto">
                            <button type="button"
                                class="py-2 px-3.5 inline-flex items-center text-xs font-medium rounded-xl border border-(--color-gray)/30 hover:bg-(--color-gray)/20 cursor-pointer"
                                data-hs-overlay="#modal-edit-single-script">
                                {{ __('app_version.modal_btn_close') }}
                            </button>
                            <button type="submit" id="btn-submit-single-script"
                                data-loading-text="{{ __('app_version.modal_btn_saving') }}"
                                class="py-2 px-4 inline-flex items-center gap-2 text-xs font-semibold rounded-xl bg-(--color-primary) text-white hover:bg-(--color-primary)/90 shadow-sm cursor-pointer">
                                <span class="size-3.5 border-2 border-white border-t-transparent rounded-full animate-spin hidden" data-spinner></span>
                                <i data-lucide="save" class="size-3.5" data-icon></i>
                                <span data-text>{{ __('app_version.modal_btn_save_single_script') }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Scripts JSON -->
    <div id="modal-edit-scripts-json"
        class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
        role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-300 mt-0 opacity-0 ease-out transition-all sm:max-w-2xl sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/20 shadow-2xl rounded-2xl pointer-events-auto overflow-hidden">
                <div class="p-5 border-b border-(--color-gray)/15 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base text-(--color-dark) dark:text-(--color-light)">
                            {{ __('app_version.modal_edit_scripts_title') }}
                        </h3>
                        <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-0.5">
                            {{ __('app_version.modal_edit_scripts_desc') }}
                        </p>
                    </div>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-lg text-(--color-dark-gray) hover:bg-(--color-gray)/20 cursor-pointer" data-hs-overlay="#modal-edit-scripts-json">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>

                <form id="form-save-scripts-json" class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-(--color-dark) dark:text-(--color-light) mb-1.5">
                            Konfigurasi Array JSON Scripts
                        </label>
                        <textarea id="textarea-scripts-json" name="scripts_json" rows="12"
                            class="w-full p-3.5 rounded-xl border border-(--color-gray)/30 bg-neutral-950 text-emerald-400 font-mono text-xs focus:ring-1 focus:ring-(--color-primary) outline-none custom-scrollbar leading-relaxed">{{ json_encode($deploymentScripts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-(--color-gray)/15">
                        <button type="button"
                            class="py-2 px-3.5 inline-flex items-center text-xs font-medium rounded-xl border border-(--color-gray)/30 hover:bg-(--color-gray)/20 cursor-pointer"
                            data-hs-overlay="#modal-edit-scripts-json">
                            {{ __('app_version.modal_btn_close') }}
                        </button>
                        <button type="submit" id="btn-save-scripts-json"
                            data-loading-text="{{ __('app_version.modal_btn_saving') }}"
                            class="py-2 px-4 inline-flex items-center gap-2 text-xs font-semibold rounded-xl bg-(--color-primary) text-white hover:bg-(--color-primary)/90 shadow-sm cursor-pointer">
                            <span class="size-3.5 border-2 border-white border-t-transparent rounded-full animate-spin hidden" data-spinner></span>
                            <i data-lucide="save" class="size-3.5" data-icon></i>
                            <span data-text>{{ __('app_version.modal_btn_save_scripts') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Dedicated Script Terminal Runner -->
    <div id="modal-script-terminal"
        class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
        role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-300 mt-0 opacity-0 ease-out transition-all sm:max-w-2xl sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/20 shadow-2xl rounded-2xl pointer-events-auto overflow-hidden">
                <div class="p-4 bg-neutral-900 border-b border-neutral-800 flex items-center justify-between text-neutral-200">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1.5">
                            <span class="size-2.5 rounded-full bg-red-500/80 inline-block"></span>
                            <span class="size-2.5 rounded-full bg-yellow-500/80 inline-block"></span>
                            <span class="size-2.5 rounded-full bg-emerald-500/80 inline-block"></span>
                        </div>
                        <span class="text-xs font-mono ms-2" id="runner-terminal-title">deploy@an-mastery:~$ script</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="runner-status-badge" class="text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 animate-pulse">
                            Running...
                        </span>
                        <button type="button" class="text-neutral-400 hover:text-white p-1 rounded hover:bg-neutral-800 cursor-pointer" data-hs-overlay="#modal-script-terminal">
                            <i data-lucide="x" class="size-4"></i>
                        </button>
                    </div>
                </div>

                <div class="p-4 bg-neutral-950">
                    <pre id="runner-terminal-output"
                        class="p-3.5 bg-neutral-950 text-emerald-400 font-mono text-xs leading-relaxed overflow-x-auto overflow-y-auto max-h-96 rounded-xl border border-neutral-800 whitespace-pre-wrap select-text m-0"></pre>
                </div>

                <div class="p-3 px-5 bg-(--color-light-gray)/40 dark:bg-(--color-dark-slate)/40 border-t border-(--color-gray)/15 flex items-center justify-between">
                    <span class="text-[11px] text-(--color-dark-gray) dark:text-(--color-gray)">
                        Output terminal diperbarui secara live melalui SSE stream.
                    </span>
                    <button type="button"
                        class="py-1.5 px-3.5 text-xs font-medium rounded-xl border border-(--color-gray)/30 hover:bg-(--color-gray)/20 cursor-pointer"
                        data-hs-overlay="#modal-script-terminal">
                        {{ __('app_version.modal_btn_close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
