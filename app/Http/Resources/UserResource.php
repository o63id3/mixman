<?php

declare(strict_types=1);

namespace App\Http\Resources;

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
            'name' => $this->name,
            'username' => $this->whenHas('username'),
            'active' => $this->whenHas('active'),
            'role' => $this->whenHas('role'),
            'contact_info' => $this->whenHas('contact_info'),
            'can' => $this->when($this->withPermissions, fn () => [
                'users' => [
                    'viewAny' => $this->resource->isAhmed(),
                ],
                'cards' => [
                    'viewAny' => $this->resource->isAhmed(),
                ],
                'networks' => [
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
