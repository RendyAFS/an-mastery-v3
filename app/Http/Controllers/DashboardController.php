<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $this->authorize('dashboard.view');

        return view('dashboard.index');
    }
}
