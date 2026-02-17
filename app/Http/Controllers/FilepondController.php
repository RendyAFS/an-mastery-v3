<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FilepondController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:5120'],
        ]);

        $file = $request->file('file');

        $tmpPath = $file->store('tmp');

        return response()->json([
            'id' => $tmpPath,
        ]);
    }

    public function revert(Request $request)
    {
        $path = $request->getContent();

        if ($path && Storage::exists($path)) {
            Storage::delete($path);
        }

        return response()->noContent();
    }
}
