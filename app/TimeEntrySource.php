<?php

namespace App;

enum TimeEntrySource: String
{
    case Web = 'web';
    case Mobile = 'mobile';
    case Admin = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
