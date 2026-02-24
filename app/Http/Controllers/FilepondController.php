<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FilepondController extends Controller
{
    public function load(Request $request)
    {
        $filePath = $request->query('file');
        if (!$filePath || !Storage::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $file = Storage::get($filePath);
        $mime = Storage::mimeType($filePath);
        $name = basename($filePath);

        return response($file)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', "inline; filename=\"$name\"");
    }

    public function process(Request $request)
    {
        $maxSize = $request->input('max_size', 5120);
        $allowedTypes = $request->input('allowed_types', []);
        $folder = $request->input('folder', 'tmp');

        $rules = [
            'file' => ['required', 'file', "max:$maxSize"],
        ];

        if (!empty($allowedTypes)) {
            $rules['file'][] = 'mimetypes:' . implode(',', $allowedTypes);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('file');

        $path = $file->store($folder);

        return response()->json([
            'id' => $path,
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
