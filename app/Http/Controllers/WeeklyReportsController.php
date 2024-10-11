<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\WeeklyReportResource;
use App\Models\User;
use App\Models\WeeklyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class WeeklyReportsController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', WeeklyReport::class);

        $user = type($request->user())->as(User::class);

        $reports = WeeklyReport::query()
            ->visibleTo($user)
            ->with(['network'])
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Reports/Index', [
            'reports' => WeeklyReportResource::collection($reports),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(WeeklyReport $report): Response
    {
        Gate::authorize('view', $report);

        $report->load('network');

        return Inertia::render('Reports/Show', [
            'report' => WeeklyReportResource::single($report),
        ]);
    }
}
