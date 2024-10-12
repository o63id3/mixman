<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class NetworkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->partners);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'manager' => $this->whenLoaded('manager', fn () => UserResource::single($this->manager)),
            'partners' => $this->whenLoaded('partners', fn () => UserResource::collection($this->partners)),
            'balance' => $this->whenHas('balance'),
            'internet_price_per_week' => $this->whenHas('internet_price_per_week'),
            'active' => $this->whenHas('active'),
        ];
    }
}
