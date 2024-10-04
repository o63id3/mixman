<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\Network;
use App\Models\Order;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'orderer_id')->constrained('users');
            $table->foreignIdFor(User::class, 'manager_id')->constrained('users');
            $table->foreignIdFor(Network::class)->constrained('networks');
            $table->string('status');
            $table->longText('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained('orders')->cascadeOnDelete();
            $table->foreignIdFor(Card::class)->nullable()->constrained('cards')->nullOnDelete();
            $table->integer('number_of_packages');
            $table->integer('number_of_cards_per_package');
            $table->integer('quantity');
            $table->integer('total_price_for_consumer');
            $table->integer('total_price_for_seller');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
    }
};
