<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

final class OrderFilesController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Order $order): RedirectResponse
    {
        Gate::authorize('createFiles', $order);

        $validated = $request->validate([
            'files' => ['array'],
            'files.*.server_path' => ['string'],
            'files.*.original_file_name' => ['string'],
            'files.*.extension' => ['string'],
            'files.*.size' => ['numeric'],
        ]);

        $order->files()->createMany($validated['files']);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderFile $file): RedirectResponse
    {
        Gate::authorize('createFiles', $file->order);

        $file->delete();

        return back();
    }
}
