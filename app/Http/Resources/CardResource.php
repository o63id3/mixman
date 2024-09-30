<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            // 'price_for_seller' => $this->price_for_seller,
            'active' => $this->active,
            'notes' => $this->notes,
        ];
    }
}
