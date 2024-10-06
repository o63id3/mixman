<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

final class OrderFileResource extends JsonResource
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
            'original_file_name' => $this->original_file_name,
            'extension' => $this->extension,
            'size' => $this->size,
            'can' => [
                'download' => Gate::allows('download', $this->resource),
                'delete' => Gate::allows('delete', $this->resource),
            ],
        ];
    }
}
