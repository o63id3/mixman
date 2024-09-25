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

        DB::statement('
            CREATE VIEW admins AS
            SELECT
                *
            FROM
                users
            WHERE
                admin = true
        ');

        DB::statement('
            CREATE TRIGGER insert_admin
            INSTEAD OF INSERT ON admins
            FOR EACH ROW
            BEGIN
                INSERT INTO users (region_id, name, username, password, admin, contact_info, notes, remember_token, created_at, updated_at)
                VALUES (null, NEW.name, NEW.username, NEW.password, TRUE, NEW.contact_info, NEW.notes, NEW.remember_token, NEW.created_at, NEW.updated_at);
            END
        ');

        DB::statement('
            CREATE TRIGGER update_admin
            INSTEAD OF UPDATE ON admins
            FOR EACH ROW
            BEGIN
                UPDATE users
                SET
                    region_id = COALESCE(NEW.region_id, OLD.region_id),
                    name = COALESCE(NEW.name, OLD.name),
                    username = COALESCE(NEW.username, OLD.username),
                    password = COALESCE(NEW.password, OLD.password),
                    active = COALESCE(NEW.active, OLD.active),
                    contact_info = COALESCE(NEW.contact_info, OLD.contact_info),
                    notes = COALESCE(NEW.notes, OLD.notes),
                    remember_token = COALESCE(NEW.remember_token, OLD.remember_token),
                    updated_at = COALESCE(NEW.updated_at, OLD.updated_at)
                WHERE id = OLD.id;
            END
        ');

        DB::statement('
            CREATE TRIGGER delete_admin
            INSTEAD OF DELETE ON admins
            FOR EACH ROW
            BEGIN
                DELETE FROM users
                WHERE id = OLD.id;
            END
        ');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS admins');
        DB::statement('DROP TRIGGER IF EXISTS insert_admin');
        DB::statement('DROP TRIGGER IF EXISTS update_admin');
        DB::statement('DROP TRIGGER IF EXISTS delete_admin');
    }
};
