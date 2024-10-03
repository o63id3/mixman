<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

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
            'user' => $this->whenLoaded('user', fn () => UserResource::single($this->user)),
            'network' => $this->whenLoaded('network', fn () => NetworkResource::single($this->network)),
            'description' => $this->description,
            'amount' => $this->amount,
            'created_at' => $this->created_at->diffForHumans(),
            'created_at_date' => $this->created_at,

            'can' => [
                'update' => Gate::allows('update', $this->resource),
            ],
        ];
    }
}
