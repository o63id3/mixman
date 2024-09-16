<?php

declare(strict_types=1);

use App\Models\Region;
use App\Models\User;

test('sellers', function () {
    $region = Region::factory()->create();
    User::factory()->recycle($region)->create();
    User::factory()->create();

    expect($region->sellers->pluck('region_id'))->each->toBe($region->id);
});
