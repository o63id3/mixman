<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\WeeklyReportResource;
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
    public function index(): Response
    {
        Gate::authorize('viewAny', WeeklyReport::class);

        $reports = WeeklyReport::query()
            ->with(['network'])
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Reports/Index', [
            'reports' => WeeklyReportResource::collection($reports),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WeeklyReport $weeklyReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WeeklyReport $weeklyReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WeeklyReport $weeklyReport)
    {
        //
    }
}
