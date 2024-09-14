<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\Order;
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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained('orders')->cascadeOnDelete();
            $table->foreignIdFor(Card::class)->constrained('cards')->nullOnDelete();
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
        Schema::dropIfExists('order_items');
    }
};
