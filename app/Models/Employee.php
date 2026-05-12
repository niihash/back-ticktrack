<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'cpf',
        'employee_number',
        'position',
        'hired_at',
        'is_active',
        'work_schedule_id',
    ];

    public function workSchedule()
    {
        return $this->belongsTo(WorkSchedule::class);
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
