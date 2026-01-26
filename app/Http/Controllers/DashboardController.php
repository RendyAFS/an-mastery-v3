<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $this->authorize('dashboard.view');

        return view('dashboard.index');
    }
}
