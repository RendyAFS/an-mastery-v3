<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\ImageFabric;

class LandingPageController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('media')->whereNull('deleted_at')->latest()->get();
        $imageFabrics = ImageFabric::with('media')->whereNull('deleted_at')->latest()->get();

        return view('welcome', compact('galleries', 'imageFabrics'));
    }
}
