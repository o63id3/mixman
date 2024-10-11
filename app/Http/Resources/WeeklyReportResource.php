<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class WeeklyReportResource extends JsonResource
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
            'network' => $this->whenLoaded('network', fn () => NetworkResource::single($this->network)),
            'total_payments_amount' => $this->total_payments_amount,
            'total_expenses_amount' => $this->total_expenses_amount,
            'network_income' => $this->network_income,
            'start_from_date' => $this->start_from,
            'end_at_date' => $this->end_at,
            'created_at_date' => $this->created_at,
        ];
    }
}
