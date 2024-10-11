<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\OrderFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class OrderFileDownloadsController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(OrderFile $file): BinaryFileResponse
    {
        Gate::authorize('download', $file);

        $path = storage_path("app/{$file->server_path}");

        if (! file_exists($path)) {
            abort(404, 'File Not Found!');
        }

        return Response::download($path, $file->original_file_name);
    }
}
