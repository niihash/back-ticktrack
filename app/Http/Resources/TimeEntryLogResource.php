<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeEntryLogResource extends JsonResource
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
            'time_entry_id' => (int) $this->time_entry_id,
            'action' => $this->action,
            'old_value' => $this->old_value,
            'new_value' => $this->new_value,
            'changed_by' => (int) $this->changed_by,
            'changed_by_user' => new UserResource($this->whenLoaded('changedBy')),
            'created_at' => $this->changed_at,
        ];
    }
}
