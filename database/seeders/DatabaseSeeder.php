<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'region_id' => null,
            'username' => 'hos',
            'password' => '1',
            'admin' => true,
        ]);

        $regions = Region::factory(5)->create();

        User::factory(30)->recycle($regions)->create();

        Card::factory(5)->create();
    }
}
