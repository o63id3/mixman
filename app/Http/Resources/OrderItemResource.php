<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

final class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order' => $this->whenLoaded('order', fn () => OrderResource::make($this->order)),
            'card' => $this->whenLoaded('card', fn () => CardResource::make($this->card)),
            'number_of_packages' => $this->number_of_packages,
            'number_of_cards_per_package' => $this->number_of_cards_per_package,
            'quantity' => $this->quantity,
            'total_price_for_consumer' => $this->total_price_for_consumer,
            'total_price_for_seller' => $this->total_price_for_seller,
            'can' => [
                'delete' => Gate::allows('delete', $this->resource),
            ],
        ];
    }
}
