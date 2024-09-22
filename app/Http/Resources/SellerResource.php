<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class SellerResource extends JsonResource
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
            'region' => $this->whenLoaded('region', fn () => RegionResource::make($this->region)),
            'name' => $this->name,
            'username' => $this->username,
            'active' => $this->active,
            'balance' => $this->whenHas('balance'),
            'contact_info' => $this->contact_info,
            'notes' => $this->notes,
        ];
    }
}
