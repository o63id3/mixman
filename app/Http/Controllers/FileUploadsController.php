<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

final class FileUploadsController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $path = $request->file('file')->store('public/files');

        if (! $path) {
            return response()->json(['error' => 'The file could not be saved!'], 500);
        }

        return [
            'server_path' => $path,
            'original_file_name' => $request->file('file')->getClientOriginalName(),
            'extension' => $request->file('file')->getClientOriginalExtension(),
            'size' => $request->file('file')->getSize(),
        ];
    }
}
