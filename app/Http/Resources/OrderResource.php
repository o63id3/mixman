<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class OrderResource extends JsonResource
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
            'status' => $this->status,
            'orderer' => $this->whenLoaded('orderer', fn () => UserResource::make($this->orderer)),
            'manager' => $this->whenLoaded('manager', fn () => UserResource::make($this->manager)),
            'network' => $this->whenLoaded('network', fn () => NetworkResource::make($this->network)),
            'total_price_for_seller' => $this->whenHas('total_price_for_seller'),
            'total_price_for_consumer' => $this->whenHas('total_price_for_consumer'),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
