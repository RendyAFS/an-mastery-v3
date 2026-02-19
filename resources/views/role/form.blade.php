@php
    $selectedPermissions = $rolePermissions ?? [];
@endphp

<div class="space-y-6" x-data="permissionManager({{ json_encode($selectedPermissions) }})">

    {{-- Role Name --}}
    <div>
        <label class="block text-sm font-medium mb-1">Role Name</label>
        <input type="text" name="name" value="{{ $role->name ?? '' }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)"
            required>
    </div>

    {{-- Permissions --}}
    <div>
        <h4 class="text-lg font-semibold mb-4">Permissions</h4>

        <div class="space-y-6">
            @foreach ($permissions as $group => $groupPermissions)
                <div
                    class="border rounded-xl p-4  bg-(--color-light-gray) border-(--color-gray)
                   text-(--color-dark) dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">

                    {{-- Group Header --}}
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-semibold capitalize">
                            {{ $group }}
                        </h5>

                        {{-- Select All per group --}}
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="checkbox" :checked="isGroupChecked('{{ $group }}')"
                                @change="toggleGroup('{{ $group }}')"
                                class="shrink-0 mt-0.5
                                border-(--color-gray) rounded-sm
                                text-(--color-primary)
                                focus:ring-(--color-primary) checked:border-(--color-primary)
                                disabled:opacity-50 cursor-pointer
                                dark:bg-(--color-dark-slate)
                                dark:border-(--color-slate)
                                dark:checked:bg-(--color-primary)
                                dark:checked:border-(--color-primary)
                                dark:focus:ring-offset-(--color-dark-slate)">
                            Select All
                        </label>
                    </div>

                    {{-- Permission List --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach ($groupPermissions as $permission)
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    x-model="selected"
                                    class="shrink-0 mt-0.5
                                        border-(--color-gray) rounded-sm
                                        text-(--color-primary)
                                        focus:ring-(--color-primary) checked:border-(--color-primary)
                                        disabled:opacity-50 cursor-pointer
                                        dark:bg-(--color-dark-slate)
                                        dark:border-(--color-slate)
                                        dark:checked:bg-(--color-primary)
                                        dark:checked:border-(--color-primary)
                                        dark:focus:ring-offset-(--color-dark-slate)">

                                {{ explode('.', $permission->name)[1] ?? $permission->name }}
                            </label>
                        @endforeach
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>
