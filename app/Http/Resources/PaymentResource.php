<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class PaymentResource extends JsonResource
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
            'seller' => $this->whenLoaded('seller', fn () => UserResource::make($this->seller)),
            'registerer' => $this->whenLoaded('registerer', fn () => UserResource::make($this->registerer)),
            'amount' => $this->amount,
            'notes' => $this->notes,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
