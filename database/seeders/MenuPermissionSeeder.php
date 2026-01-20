<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class MenuPermissionSeeder extends Seeder
{
    const DEFAULT_PERMISSION = [
        'view',
        'create',
        'read',
        'update',
        'delete',
        'restore',
        'force_delete',
    ];

    protected array $insertedMenuIds = [];

    public function run(): void
    {
        DB::transaction(function () {

            $menus = config('menu');

            $this->insertMenus($menus);

            $this->deleteUnusedMenus();
        });
    }

    private function insertMenus(array $items, ?Menu $parent = null): void
    {
        foreach ($items as $index => $item) {

            $menu = Menu::updateOrCreate(
                [
                    'url' => $item['url'] ?? null,
                ],
                [
                    'name'       => $item['name'],
                    'parent_id'  => $parent?->id,
                    'icon'       => $item['icon'] ?? null,
                    'sort_order' => $index + 1,
                    'is_active'  => true,
                ]
            );

            $this->insertedMenuIds[] = $menu->id;

            if (isset($item['permissions'])) {
                $permissions = $item['permissions'];
            } elseif (!isset($item['children'])) {
                $permissions = self::DEFAULT_PERMISSION;
            } else {
                $permissions = [];
            }

            if (isset($item['extra_permissions'])) {
                $permissions = array_unique([
                    ...$permissions,
                    ...$item['extra_permissions'],
                ]);
            }

            if (!empty($permissions) && isset($item['url'])) {

                $permissionIds = [];

                foreach ($permissions as $action) {
                    $permission = Permission::firstOrCreate([
                        'name' => "{$item['url']}.{$action}",
                        'guard_name' => 'web',
                    ]);

                    $permissionIds[] = $permission->id;
                }

                $menu->permissions()->sync($permissionIds);
            }

            if (isset($item['children'])) {
                $this->insertMenus($item['children'], $menu);
            }
        }
    }

    private function deleteUnusedMenus(): void
    {
        $menus = Menu::whereNotIn('id', $this->insertedMenuIds)
            ->with('permissions')
            ->get();

        foreach ($menus as $menu) {
            $menu->permissions()->detach();
            $menu->delete();
        }
    }
}
