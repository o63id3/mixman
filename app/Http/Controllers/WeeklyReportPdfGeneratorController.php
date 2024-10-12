<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\WeeklyReportResource;
use App\Models\WeeklyReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

final class WeeklyReportPdfGeneratorController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(WeeklyReport $report): Response
    {
        Gate::authorize('view', $report);

        $report->load('network:id,name');

        return Pdf::loadView('reports.network-weekly-report', [
            'title' => "{$report->network->name} تقرير رقم {$report->id}",
            'report' => WeeklyReportResource::single($report),
        ])->stream(); // FIX: fix charset
    }
}
