<?php

declare(strict_types=1);

use App\Models\Network;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Network::class)->constrained('networks');
            $table->float('total_payments_amount');
            $table->float('total_expenses_amount');
            $table->float('network_income');
            $table->json('partners_shares');
            $table->float('income_overflow');
            $table->timestamp('start_from');
            $table->timestamp('end_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_reports');
    }
};
