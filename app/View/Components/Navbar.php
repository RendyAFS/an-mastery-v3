<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\User;

class Navbar extends Component
{
    public User $user;
    public bool $hasNewVersion;

    public function __construct()
    {
        $this->user          = Auth::user();
        $this->hasNewVersion = Setting::hasNewVersion();
    }

    public function render()
    {
        return view('components.navbar');
    }
}
