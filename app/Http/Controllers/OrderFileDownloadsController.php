<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\OrderFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class OrderFileDownloadsController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(OrderFile $file): StreamedResponse
    {
        Gate::authorize('download', $file);

        return Storage::download($file->server_path); // TODO: FIX download link
    }
}
