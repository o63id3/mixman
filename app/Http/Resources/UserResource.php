<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Admin;
use App\Models\Card;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Region;
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
            'region' => $this->whenLoaded('region', fn () => RegionResource::make($this->region)),
            'name' => $this->name,
            'username' => $this->username,
            'active' => $this->active,
            'admin' => $this->admin,
            'contact_info' => $this->contact_info,
            'notes' => $this->notes,
            'can' => [
                'sellers' => [
                    'viewAny' => $this->resource->can('viewAny', User::class),
                    'create' => $this->resource->can('create', User::class),
                    'update' => $this->resource->can('update', User::class),
                ],
                'admins' => [
                    'viewAny' => $this->resource->can('viewAny', Admin::class),
                ],
                'regions' => [
                    'viewAny' => $this->resource->can('viewAny', Region::class),
                    'create' => $this->resource->can('create', Region::class),
                    'update' => $this->resource->can('update', Region::class),
                ],
                'cards' => [
                    'viewAny' => $this->resource->can('viewAny', Card::class),
                    'create' => $this->resource->can('create', Card::class),
                    'update' => $this->resource->can('update', Card::class),
                ],
                'orders' => [
                    'viewAny' => $this->resource->can('viewAny', Order::class),
                    'create' => $this->resource->can('create', Order::class),
                ],
                'payments' => [
                    'viewAny' => $this->resource->can('viewAny', Payment::class),
                    'create' => $this->resource->can('create', Payment::class),
                ],
                'transactions' => [
                    'viewAny' => $this->resource->can('viewAny', Payment::class),
                ],
            ],
        ];
    }
}
