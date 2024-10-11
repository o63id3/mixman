<?php

declare(strict_types=1);

use App\Models\Network;
use App\Models\User;
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
        Schema::create('networks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignIdFor(User::class, 'manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('internet_price_per_week')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('network_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained('users');
            $table->foreignIdFor(Network::class)->constrained('networks');
            $table->float('share');

            $table->unique(['user_id', 'network_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('networks');
        Schema::dropIfExists('network_partners');
    }
};
