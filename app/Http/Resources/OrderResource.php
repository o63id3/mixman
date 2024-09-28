<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'seller' => $this->whenLoaded('seller', fn () => SellerResource::make($this->seller)),
            'action' => $this->whenLoaded('action', fn () => AdminResource::make($this->action)),
            'total_price_for_seller' => $this->whenHas('total_price_for_seller'),
            'total_price_for_consumer' => $this->whenHas('total_price_for_consumer'),
            'updated_at' => /*\Carbon\Carbon::parse($this->updated_at)->format('Y/m/d')*/
            $this->updated_at->diffForHumans(),
        ];
    }
}
