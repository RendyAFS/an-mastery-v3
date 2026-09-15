<?php

namespace App\Helpers;

use App\Models\Menu;
use App\Models\User;

class MenuHelper
{
    /**
     * Get the first accessible page URL for the given user based on their permissions.
     */
    public static function getFirstAccessibleUrl(User $user): string
    {
        // 1. Super Admin or user with dashboard.view permission -> /dashboard
        if ($user->hasRole('Super Admin') || $user->can('dashboard.view')) {
            return config('fortify.home', '/dashboard');
        }

        // 2. Query active top-level menus and their active children & permissions
        $menus = Menu::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with([
                'children' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')->with('permissions'),
                'permissions'
            ])
            ->get();

        foreach ($menus as $menu) {
            // Case A: Top-level menu has explicit permissions
            if ($menu->permissions->isNotEmpty()) {
                if ($user->canAny($menu->permissions->pluck('name')->toArray())) {
                    if (self::isValidUrl($menu->url)) {
                        return $menu->url;
                    }
                }
            }

            // Case B: Top-level menu has submenus (children)
            if ($menu->children->isNotEmpty()) {
                foreach ($menu->children as $child) {
                    if ($child->permissions->isEmpty() || $user->canAny($child->permissions->pluck('name')->toArray())) {
                        if (self::isValidUrl($child->url)) {
                            return $child->url;
                        }
                    }
                }
            }

            // Case C: Top-level menu with no permissions & no children
            if ($menu->permissions->isEmpty() && $menu->children->isEmpty()) {
                if (self::isValidUrl($menu->url)) {
                    return $menu->url;
                }
            }
        }

        // 3. Fallback: Profile page
        return route('profile.index', [], false) ?: '/profile';
    }

    private static function isValidUrl(?string $url): bool
    {
        return !empty($url) && !str_starts_with($url, '#');
    }
}
