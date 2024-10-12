<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

final class CardResource extends JsonResource
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
            'name' => $this->name,
            'price_for_consumer' => $this->price_for_consumer,
            'active' => $this->active,
            'notes' => $this->notes,
            'pivot' => $this->whenPivotLoaded('order_cards', fn () => [
                'number_of_packages' => $this->pivot->number_of_packages,
                'number_of_cards_per_package' => $this->pivot->number_of_cards_per_package,
                'quantity' => $this->pivot->quantity,
                'total_price_for_consumer' => $this->pivot->total_price_for_consumer,
                'total_price_for_seller' => $this->pivot->total_price_for_seller,
                'can' => [
                    'delete' => Gate::allows('delete', $this->resource->pivot),
                ],
            ]),
        ];
    }
}
