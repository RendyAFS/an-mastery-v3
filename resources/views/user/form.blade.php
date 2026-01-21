<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            Nama
        </label>

        <input type="text" name="name" value="{{ $user->name ?? '' }}" required
            class="mt-1 px-4 py-2 block w-full rounded-lg
                   bg-(--color-light-gray)
                   border border-(--color-gray)
                   text-(--color-dark)
                   focus:border-(--color-primary)
                   focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate)
                   dark:border-(--color-slate)
                   dark:text-(--color-light)
                   transition">
    </div>

    <div>
        <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            Email
        </label>

        <input type="email" name="email" value="{{ $user->email ?? '' }}" required
            class="mt-1 px-4 py-2 block w-full rounded-lg
                   bg-(--color-light-gray)
                   border border-(--color-gray)
                   text-(--color-dark)
                   focus:border-(--color-primary)
                   focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate)
                   dark:border-(--color-slate)
                   dark:text-(--color-light)
                   transition">
    </div>

    <div>
        <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            Password
        </label>

        <input type="password" name="password"
            class="mt-1 px-4 py-2 block w-full rounded-lg
                   bg-(--color-light-gray)
                   border border-(--color-gray)
                   text-(--color-dark)
                   focus:border-(--color-primary)
                   focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate)
                   dark:border-(--color-slate)
                   dark:text-(--color-light)
                   transition">

        @isset($user)
            <small class="text-xs text-(--color-dark-gray)">
                Kosongkan jika tidak ingin mengubah password
            </small>
        @endisset
    </div>
</div>
