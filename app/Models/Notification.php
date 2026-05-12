<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'data',
    ];


    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('read_at')->withTimestamps();
    }
}
