<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW transactions AS
            SELECT
                id,
                amount AS amount,
                seller_id,
                'payment' AS type,
                NULL AS status,
                created_at
            FROM
                payments
            UNION
            SELECT
                orders.id,
                COALESCE(
                    SUM(
                        CASE
                            WHEN status = 'مرجع' THEN order_items.total_price_for_seller
                            WHEN status = 'مكتمل' THEN -order_items.total_price_for_seller
                            WHEN status = 'طلب جديد' THEN 0
                        END
                    ),
                    0
                ) AS amount,
                orders.seller_id,
                'order' AS type,
                orders.status,
                orders.created_at
            FROM
                orders
                LEFT JOIN order_items ON orders.id = order_items.order_id
            GROUP BY
                orders.id,
                orders.seller_id,
                orders.created_at
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS transactions');
    }
};
