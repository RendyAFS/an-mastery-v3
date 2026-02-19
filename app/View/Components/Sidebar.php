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

        $query = Menu::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with([
                'children.permissions:id,name,menu_id',
                'permissions:id,name,menu_id'
            ]);

        if ($user->hasRole('Super Admin')) {
            $this->menus = $query->get();
            return;
        }

        $this->menus = $query->get()
            ->filter(function ($menu) use ($user) {

                if ($menu->permissions->isEmpty() && $menu->children->isEmpty()) {
                    return true;
                }

                if (
                    $menu->permissions->isNotEmpty() &&
                    $user->canAny($menu->permissions->pluck('name')->toArray())
                ) {
                    return true;
                }

                if ($menu->children->isNotEmpty()) {
                    $accessibleChildren = $menu->children->filter(function ($child) use ($user) {

                        if ($child->permissions->isEmpty()) {
                            return true;
                        }

                        return $user->canAny(
                            $child->permissions->pluck('name')->toArray()
                        );
                    });

                    if ($accessibleChildren->isNotEmpty()) {
                        $menu->setRelation('children', $accessibleChildren);
                        return true;
                    }
                }

                return false;
            });
    }

    public function render()
    {
        return view('components.sidebar');
    }
}
