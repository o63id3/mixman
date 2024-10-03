<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Network;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

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
            'name' => $this->name,
            'username' => $this->whenHas('username'),
            'active' => $this->whenHas('active'),
            'role' => $this->whenHas('role'),
            'contact_info' => $this->whenHas('contact_info'),
            'network' => $this->whenLoaded('network', fn () => NetworkResource::make($this->network)),
            'percentage' => $this->whenHas('percentage', fn () => $this->percentage * 100),
            'share' => $this->whenPivotLoaded('network_partners', fn () => $this->pivot->share),
            'can' => $this->when($this->withPermissions, fn () => [
                'users' => [
                    'viewAny' => Gate::allows('viewAny', User::class),
                ],
                'networks' => [
                    'viewAny' => Gate::allows('viewAny', Network::class),
                ],
                'cards' => [
                    'viewAny' => $this->resource->isAhmed(),
                ],
                'orders' => [
                    'viewAny' => $this->resource->isAhmed(),
                ],
                'payments' => [
                    'viewAny' => $this->resource->isAhmed(),
                ],
                'expenses' => [
                    'viewAny' => $this->resource->isAhmed(),
                ],
            ],
            ),
        ];
    }
}
