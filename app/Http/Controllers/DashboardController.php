<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, DashboardService $service): Response
    {
        $user = type($request->user())->as(User::class);

        // return Inertia::render('Dashboard', $service->getStatistics($user));
        return Inertia::render('Dashboard');
    }
}
