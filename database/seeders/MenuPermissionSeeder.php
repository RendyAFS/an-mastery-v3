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
        'forceDelete',
    ];

    protected array $validMenuIds = [];
    protected array $validPermissionNames = [];

    public function run(): void
    {
        DB::transaction(function () {
            $this->syncMenus(config('menu'));
            $this->cleanupMenus();
            $this->cleanupPermissions();
            $this->cleanupRolePermissions();
        });
    }

    private function syncMenus(array $items, ?Menu $parent = null): void
    {
        foreach ($items as $index => $item) {

            $menu = Menu::updateOrCreate(
                ['url' => $item['url'] ?? null],
                [
                    'name'       => $item['name'],
                    'icon'       => $item['icon'] ?? null,
                    'parent_id'  => $parent?->id,
                    'sort_order' => $index + 1,
                    'is_active'  => true,
                ]
            );

            $this->validMenuIds[] = $menu->id;

            $permissions = [];

            if (isset($item['permissions'])) {
                $permissions = $item['permissions'];
            } elseif (!isset($item['children'])) {
                $permissions = self::DEFAULT_PERMISSION;
            }

            if (!empty($permissions) && isset($item['url']) && !isset($item['children'])) {

                $permissionNames = [];

                $prefix = Str::of($item['url'])
                    ->trim('/')
                    ->replace('/', '.');

                foreach ($permissions as $action) {
                    $name = "{$prefix}.{$action}";

                    $permission = Permission::updateOrCreate(
                        [
                            'name' => $name,
                            'guard_name' => 'web'
                        ],
                        [
                            'menu_id' => $menu->id
                        ]
                    );

                    $permissionNames[] = $permission->name;
                    $this->validPermissionNames[] = $permission->name;
                }
            } else {
                $menu->permissions()->detach();
            }

            if (isset($item['children'])) {
                $this->syncMenus($item['children'], $menu);
            }
        }
    }

    private function cleanupMenus(): void
    {
        Menu::whereNotIn('id', $this->validMenuIds)
            ->each(function ($menu) {
                $menu->permissions()->detach();
                $menu->delete();
            });
    }

    private function cleanupPermissions(): void
    {
        Permission::whereNotIn('name', $this->validPermissionNames)->delete();
    }

    private function cleanupRolePermissions(): void
    {
        DB::table('role_has_permissions')
            ->whereNotIn(
                'permission_id',
                Permission::pluck('id')
            )->delete();
    }
}
