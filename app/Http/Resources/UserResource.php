<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
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
            'username' => $this->username,
            'active' => $this->active,
            'admin' => $this->admin,
            'balance' => $this->balance,
            'contact_info' => $this->contact_info,
            'notes' => $this->notes,
            'can' => [
                'sellers' => [
                    'viewAny' => $this->resource->can('viewAny', User::class),
                    'create' => $this->resource->can('create', User::class),
                    'update' => $this->resource->can('update', User::class),
                ],
            ],
        ];
    }
}
