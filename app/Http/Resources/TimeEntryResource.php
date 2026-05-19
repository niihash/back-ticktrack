<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeEntryResource extends JsonResource
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
            'employee_id' => (int) $this->employee_id,
            'type' => $this->type,
            'recorded_at' => $this->recorded_at,
            'source' => $this->source,
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,

            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'logs' => TimeEntryLogResource::collection($this->whenLoaded('logs')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
