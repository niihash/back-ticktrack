<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'type' => $this->type,
            'data' => $this->data,

            'read_at' => $this->whenPivotLoaded('notification_user', fn() => $this->pivot->read_at),
            'is_read' => (bool) $this->whenPivotLoaded('notification_user', fn() => $this->pivot->read_at !== null),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
