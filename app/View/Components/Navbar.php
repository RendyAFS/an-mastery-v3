<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Collection;

class Navbar extends Component
{
    public ?User $user;
    public Collection $workshops;

    public function __construct()
    {
        $this->user = Auth::user()?->load('workshop');
        $this->workshops = Workshop::active()->orderBy('name')->get();
    }

    public function render()
    {
        return view('components.navbar');
    }
}
