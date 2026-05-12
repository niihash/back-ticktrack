<?php

namespace App;

enum TimeEntryType: String
{
    case ClockIn = 'clock_in';
    case ClockOut = 'clock_out';
    case BreakStart = 'break_start';
    case BreakEnd = 'break_end';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
