<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\RoleEnum;
use App\Models\Network;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

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
            ->where('active', true)
            ->get();

        foreach ($networks as $network) {
            // calculate net income
            $totalIncome = $network->total_payments_amount - $network->total_expenses_amount;
            $this->info("Network {$network->name} net income: {$totalIncome}!");

            // calculate partners shares
            foreach ($network->partners as $partner) {
                $share = $partner->pivot?->share;
                $money = $share * $totalIncome;

                if ($totalIncome > 0) {
                    $this->info("Partner {$partner->name}: {$share} * {$totalIncome} = {$money}!");
                } else {
                    $this->info("Partner {$partner->name}: 0!");
                }
            }

            // Store data
            $network->reports()->forceCreate([
                'total_payments_amount' => $network->total_payments_amount ?? 0,
                'total_expenses_amount' => $network->total_expenses_amount ?? 0,
                'network_income' => $totalIncome,
                'start_from' => $startOfLastWeek,
                'end_at' => $endOfLastWeek,
            ]);

            // Add price for internet
            if ($network->internet_price_per_week > 0) {
                $network->expenses()->forceCreate([
                    'user_id' => 1,
                    'amount' => $network->internet_price_per_week,
                    'description' => 'حساب الانترنت الأسبوعي',
                ]);
            }

            // Generate the pdf file
            // Send telegram notifications
        }
    }
}
