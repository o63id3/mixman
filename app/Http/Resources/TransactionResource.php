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
            'user' => UserResource::single($this->user),
            'manager' => UserResource::single($this->manager),
            'network' => UserResource::single($this->network),
            'amount' => $this->amount,
            'description' => $this->description,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
