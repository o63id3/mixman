<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Order;

final class StoreOrderCardsAction
{
    public function handle(Order $order, array $cards)
    {
        $this->transform($cards);

        $order
            ->cards()
            ->attach($cards);
    }

    private function transform(array $cards)
    {
        foreach ($cards as $key => $value) {
            $card = $value['card_id'];

            unset($value['card_id']);
            $cards[$card] = $value;
            unset($cards[$key]);
        }
    }
}
