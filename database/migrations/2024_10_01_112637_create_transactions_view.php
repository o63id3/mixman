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
                orders.id,
                'order' AS type,
                status,
                orderer_id AS user_id,
                manager_id,
                network_id,
                COALESCE(
                    SUM(
                        CASE
                            WHEN status = 'مكتمل' THEN - order_items.total_price_for_seller
                            WHEN status = 'مرجع' THEN 0
                            WHEN status = 'طلب جديد' THEN 0
                        END
                    ),
                    0
                ) AS amount,
                NULL AS description,
                orders.created_at
            FROM
                orders
                LEFT JOIN order_items ON orders.id = order_items.order_id
            GROUP BY
                orders.id,
                orders.orderer_id,
                orders.created_at
            UNION
            SELECT
                id,
                'payment' AS type,
                NULL AS status,
                user_id,
                recipient_id AS manager_id,
                network_id,
                amount,
                NULL AS description,
                created_at
            FROM
                payments
            UNION
            SELECT
                id,
                'expense' AS type,
                NULL AS status,
                user_id,
                NULL AS manager_id,
                network_id,
                amount,
                description,
                created_at
            FROM
                expenses
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
