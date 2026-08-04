<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(string $locale, Request $request): RedirectResponse
    {
        if (! in_array($locale, ['en', 'id'], true)) {
            abort(404);
        }

        session(['locale' => $locale]);

        return redirect()->back();
    }
}
