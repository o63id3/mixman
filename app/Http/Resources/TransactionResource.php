<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class TransactionResource extends JsonResource
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
            'seller' => UserResource::make($this->seller),
            'amount' => $this->amount,
            'type' => $this->type,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
