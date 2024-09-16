<?php

declare(strict_types=1);

use App\Http\Resources\RegionResource;
use App\Models\Region;

test('make', function () {
    $region = Region::factory()->create();

    $resource = RegionResource::make($region)->resolve();

    expect($resource)->toBe([
        'id' => $region->id,
        'name' => $region->name,
    ]);
});
