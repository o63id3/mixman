<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ExpenseResource extends JsonResource
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
            'user' => $this->whenLoaded('user', fn () => UserResource::make($this->user)),
            'network' => $this->whenLoaded('network', fn () => NetworkResource::make($this->network)),
            'description' => $this->description,
            'amount' => $this->amount,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
