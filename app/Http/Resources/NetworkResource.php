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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'manager' => $this->whenLoaded('manager', fn () => UserResource::make($this->manager)),
            'internet_price_per_week' => $this->internet_price_per_week,
            'active' => $this->active,
        ];
    }
}
