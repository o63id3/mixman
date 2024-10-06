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
            'orderer' => $this->whenLoaded('orderer', fn () => UserResource::single($this->orderer)),
            'manager' => $this->whenLoaded('manager', fn () => UserResource::single($this->manager)),
            'network' => $this->whenLoaded('network', fn () => NetworkResource::single($this->network)),
            'items' => $this->whenLoaded('items', fn () => OrderItemResource::collection($this->items)),
            'files' => $this->whenLoaded('files', fn () => OrderFileResource::collection($this->files)),
            'total_price_for_seller' => $this->whenHas('total_price_for_seller'),
            'total_price_for_consumer' => $this->whenHas('total_price_for_consumer'),
            'created_at_date' => $this->created_at,
            'updated_at' => $this->updated_at->diffForHumans(),
            'updated_at_date' => $this->updated_at,

            'can' => [
                'update' => Gate::allows('update', $this->resource),
            ],
        ];
    }
}
