<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

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
            'seller' => $this->whenLoaded('seller', fn () => UserResource::make($this->seller)),
            'action' => $this->whenLoaded('action', fn () => UserResource::make($this->action)),

            'can' => [
                'view' => Gate::allows('view', $this->resource),
            ],
        ];
    }
}