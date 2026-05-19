<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'expected_daily_hours' => (int) $this->expected_daily_hours,
            'days' => WorkScheduleDayResource::collection($this->whenLoaded('workScheduleDays')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
