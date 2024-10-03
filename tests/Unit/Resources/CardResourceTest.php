<?php

declare(strict_types=1);

use App\Http\Resources\CardResource;
use App\Models\Card;

test('make', function () {
    $card = Card::factory()->create();

    $resource = CardResource::single($card)->resolve();

    expect($resource)->toBe([
        'id' => $card->id,
        'name' => $card->name,
        'price_for_consumer' => $card->price_for_consumer,
        'price_for_seller' => $card->price_for_seller,
        'active' => $card->active,
        'notes' => $card->notes,
    ]);
});
