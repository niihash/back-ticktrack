<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'cpf' => $this->cpf,
            'employee_number' => $this->employee_number,
            'position' => $this->position,
            'hired_at' => $this->hired_at,
            'is_active' => (bool) $this->is_active,
            //'user_id' => $this->user_id,
            'work_schedule_id' => $this->work_schedule_id,
            'user' => new UserResource($this->whenLoaded('user')),
            //'work_schedule'=> new WorkScheduleResource($this->whenLoaded('workSchedule')),
        ];
    }
}
