<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\RoleEnum;
use App\Http\Resources\WeeklyReportResource;
use App\Models\Network;
use App\Models\User;
use App\Models\WeeklyReport;
use App\Notifications\NetworksWeeklyReportNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

final class GenerateWeeklyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate last week report';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();

        $networks = Network::query()
            ->with(['partners'])
            ->withSum(['payments as total_payments_amount' => function ($query) use ($startOfLastWeek, $endOfLastWeek) {
                $query
                    ->whereBetween('payments.created_at', [$startOfLastWeek, $endOfLastWeek])
                    ->whereHas('recipient', function (Builder $query) {
                        $query->where('role', RoleEnum::Ahmed);
                    });
            }], 'amount')
            ->withSum(['expenses as total_expenses_amount' => function ($query) use ($startOfLastWeek, $endOfLastWeek) {
                $query
                    ->whereBetween('expenses.created_at', [$startOfLastWeek, $endOfLastWeek]);
            }], 'amount')
            ->whereActive(true)
            ->get();

        foreach ($networks as $network) {
            $report = $this->persistStateInDatabase($network);

            $this->addInternetPrice($network);

            $this->generatePdf($report);
        }

        $this->notifyUsers();
    }

    private function persistStateInDatabase(Network $network): WeeklyReport
    {
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();

        $totalIncome = $network->total_payments_amount - $network->total_expenses_amount;
        $partnersShares = $this->calculatePartnersShares($network->partners, $totalIncome);

        return $network->reports()->forceCreate([
            'total_payments_amount' => $network->total_payments_amount ?? 0,
            'total_expenses_amount' => $network->total_expenses_amount ?? 0,
            'network_income' => $totalIncome,
            'partners_shares' => $partnersShares,
            'income_overflow' => $totalIncome - $partnersShares->sum('benefit'),
            'start_from' => $startOfLastWeek,
            'end_at' => $endOfLastWeek,
        ]);
    }

    private function calculatePartnersShares(Collection $partners, float $totalIncome): Collection
    {
        $partnersShares = collect();
        foreach ($partners as $partner) {
            $partnersShares[$partner->name] = [
                'share' => $partner->pivot->share,
                'benefit' => $totalIncome > 0 ? $totalIncome * $partner->pivot->share : 0,
            ];
        }

        return $partnersShares;
    }

    private function addInternetPrice(Network $network): void
    {
        if ($network->internet_price_per_week <= 0) {
            return;
        }

        $network->expenses()->forceCreate([
            'user_id' => 1,
            'amount' => $network->internet_price_per_week,
            'description' => 'حساب الانترنت الأسبوعي',
        ]);
    }

    private function generatePdf(WeeklyReport $report): string
    {
        $pdf = Pdf::loadView('reports.network-weekly-report', [
            'title' => "{$report->network->name} تقرير رقم {$report->id}",
            'report' => WeeklyReportResource::single($report),
        ]);

        $filePath = "reports/{$report->id}.pdf";
        Storage::disk('public')->put($filePath, $pdf->output());

        return $filePath;
    }

    private function notifyUsers(): void
    {
        $admins = User::whereRole(RoleEnum::Ahmed)->whereNotNull('telegram')->get();
        Notification::sendNow($admins, new NetworksWeeklyReportNotification());
    }
}
