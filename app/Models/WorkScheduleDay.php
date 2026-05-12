<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkScheduleDay extends Model
{
    protected $fillable = [
        'work_schedule_id',
        'weekday',
        'start_time',
        'end_time',
        'break_minutes',
    ];

    public function workSchedule()
    {
        return $this->belongsTo(WorkSchedule::class);
    }
}
