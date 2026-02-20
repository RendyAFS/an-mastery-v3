@php
    $selectedPermissions = $rolePermissions ?? [];
@endphp

{{-- Role Name --}}
<div class="mb-5">
    <label class="block text-sm font-medium mb-1">Role Name</label>
    <input type="text" name="name" value="{{ $role->name ?? '' }}"
        class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)"
        required>
</div>

<div x-data="permissionManager({{ json_encode($selectedPermissions) }})">

    {{-- HEADER: GLOBAL SELECT ALL --}}
    <div class="flex items-center justify-between mb-6 px-1">
        <h4 class="text-lg font-semibold text-(--color-dark) dark:text-(--color-light)">Permissions</h4>
        <label
            class="inline-flex items-center gap-2 text-sm font-medium cursor-pointer select-none text-(--color-dark) dark:text-(--color-light)">
            <input type="checkbox"
                class="checkbox-custom"
                :checked="isAllChecked()" @change="toggleAll()">
            Select All
        </label>
    </div>

    {{-- PERMISSION GROUPS --}}
    <div class="space-y-4">
        @foreach ($menus as $menu)
            <div class="border border-(--color-gray) dark:border-(--color-slate) rounded-xl overflow-hidden">

                {{-- GROUP HEADER --}}
                <div
                    class="flex items-center justify-between px-4 py-3 bg-(--color-light-gray) dark:bg-(--color-dark-slate) border-b border-(--color-gray) dark:border-(--color-slate)">
                    <h5
                        class="font-semibold text-sm text-(--color-dark) dark:text-(--color-light) uppercase tracking-wide">
                        {{ $menu->name }}
                    </h5>
                    <label
                        class="inline-flex items-center gap-2 text-sm font-medium cursor-pointer select-none text-(--color-dark-gray) dark:text-(--color-gray)">
                        <input type="checkbox" class="checkbox-custom" :checked="isGroupChecked({{ $menu->id }})"
                            @change="toggleGroup({{ $menu->id }})">
                        Select All
                    </label>
                </div>

                <div class="p-4 bg-white dark:bg-(--color-dark)">

                    {{-- CASE 1: ADA SUBMENU --}}
                    @if ($menu->children->count() > 0)
                        <div class="space-y-4">
                            @foreach ($menu->children as $child)
                                <div
                                    class="rounded-lg border border-(--color-gray) dark:border-(--color-slate) bg-white dark:bg-(--color-dark)">

                                    {{-- SUBMENU HEADER --}}
                                    <div
                                        class="flex items-center justify-between px-3 py-2 border-b border-(--color-light-gray) dark:border-(--color-slate) bg-(--color-light-gray) dark:bg-(--color-dark-slate) rounded-t-lg">
                                        <span class="font-medium text-sm text-(--color-dark) dark:text-(--color-light)">
                                            {{ $child->name }}
                                        </span>
                                        <label
                                            class="inline-flex items-center gap-2 text-xs font-medium cursor-pointer select-none text-(--color-dark-gray) dark:text-(--color-gray)">
                                            <input type="checkbox" class="checkbox-custom"
                                                :checked="isSubmenuChecked({{ $child->id }})"
                                                @change="toggleSubmenu({{ $child->id }})">
                                            Select All
                                        </label>
                                    </div>

                                    {{-- SUBMENU PERMISSIONS --}}
                                    <div
                                        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-2 px-3 py-3">
                                        @foreach ($child->permissions as $permission)
                                            <label
                                                class="inline-flex items-center gap-2 text-sm cursor-pointer select-none text-(--color-dark-gray) dark:text-(--color-gray) hover:text-(--color-dark) dark:hover:text-(--color-light)">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $permission->name }}" class="checkbox-custom"
                                                    x-model="selected" data-menu="{{ $child->id }}"
                                                    data-parent="{{ $menu->id }}">
                                                <span
                                                    class="capitalize">{{ explode('.', $permission->name)[1] ?? $permission->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        {{-- CASE 2: TIDAK ADA SUBMENU --}}
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-2">
                            @foreach ($menu->permissions as $permission)
                                <label
                                    class="inline-flex items-center gap-2 text-sm cursor-pointer select-none text-(--color-dark-gray) dark:text-(--color-gray) hover:text-(--color-dark) dark:hover:text-(--color-light)">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        class="checkbox-custom" x-model="selected" data-menu="{{ $menu->id }}"
                                        data-parent="{{ $menu->id }}">
                                    <span
                                        class="capitalize">{{ explode('.', $permission->name)[1] ?? $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
