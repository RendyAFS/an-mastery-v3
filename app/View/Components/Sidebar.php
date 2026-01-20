<?php

namespace App\View\Components;

use App\Models\Menu;
use App\Models\User;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
    public $menus;

    public function __construct()
    {
        /** @var User $user */
        $user = Auth::user();

        $this->menus = Menu::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with([
                'children.permissions',
                'permissions'
            ])
            ->get()
            ->filter(function ($menu) use ($user) {
                if ($menu->permissions->isEmpty()) {
                    return true;
                }

                return $user->canAny(
                    $menu->permissions->pluck('name')->toArray()
                );
            });
    }

    public function render()
    {
        return view('components.sidebar');
    }
}
