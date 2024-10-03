<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

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
            'recipient' => $this->whenLoaded('recipient', fn () => UserResource::make($this->recipient)),
            'user' => $this->whenLoaded('user', fn () => UserResource::make($this->user)),
            'network' => $this->whenLoaded('network', fn () => NetworkResource::make($this->network)),
            'amount' => $this->amount,
            'notes' => $this->notes,
            'created_at' => $this->created_at->diffForHumans(),

            'can' => [
                'update' => Gate::allows('update', $this->resource),
            ],
        ];
    }
}
