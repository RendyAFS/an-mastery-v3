<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Navbar extends Component
{
    public User $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        return view('components.navbar');
    }
}
