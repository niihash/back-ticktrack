<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $fillable = [
        'name',
        'expected_daily_hours',
    ];

    public function workScheduleDays()
    {
        return $this->hasMany(WorkScheduleDay::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
