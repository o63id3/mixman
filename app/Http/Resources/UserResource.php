<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Admin;
use App\Models\Card;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Region;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = null;

    protected bool $withPermissions = false;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function withPermissions()
    {
        $this->withPermissions = true;

        return $this;
    }

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
            'can' => $this->when($this->withPermissions, fn () => [
                'sellers' => [
                    'viewAny' => $this->resource->can('viewAny', User::class),
                ],
                'admins' => [
                    'viewAny' => $this->resource->can('viewAny', Admin::class),
                ],
                'regions' => [
                    'viewAny' => $this->resource->can('viewAny', Region::class),
                ],
                'cards' => [
                    'viewAny' => $this->resource->can('viewAny', Card::class),
                ],
                'orders' => [
                    'viewAny' => $this->resource->can('viewAny', Order::class),
                ],
                'payments' => [
                    'viewAny' => $this->resource->can('viewAny', Payment::class),
                ],
                'transactions' => [
                    'viewAny' => $this->resource->can('viewAny', Transaction::class),
                ],
            ],
            ),
        ];
    }
}
