<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
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
            'username' => $this->whenHas('username'),
            'active' => $this->whenHas('active'),
            'role' => $this->whenHas('role'),
            'contact_info' => $this->whenHas('contact_info'),
            'network' => $this->whenLoaded('network', fn () => NetworkResource::single($this->network)),
            'percentage' => $this->whenHas('percentage', fn () => $this->percentage * 100),
            'share' => $this->whenPivotLoaded('network_partners', fn () => $this->pivot->share),
        ];
    }
}
