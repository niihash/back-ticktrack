<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeEntryLog extends Model
{
    protected $fillable = [
        'time_entry_id',
        'action',
        'old_value',
        'new_value',
        'changed_by',
    ];


    protected function casts(): array
    {
        return [
            'old_value' => 'array',
            'new_value' => 'array',
        ];
    }

    public function timeEntry()
    {
        return $this->belongsTo(TimeEntry::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class);
    }
}
