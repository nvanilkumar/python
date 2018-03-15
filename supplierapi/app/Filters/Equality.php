<?php

namespace App\Filters;

final class Equality
{
    const EQUAL                 = 'eq';
    const NOT_EQUAL             = 'neq';
    const GREATER_THAN          = 'gt';
    const GREATER_THAN_EQUAL    = 'gte';
    const LESS_THAN             = 'lt';
    const LESS_THAN_EQUAL       = 'lte';
    const LIKE                  = 'like';
    const NOT_LIKE              = 'nlike';

    public static function validate(string $string)
    {
        $valid = true;

        switch ($string) {
            case self::EQUAL:
                break;
            case self::NOT_EQUAL:
                break;
            case self::GREATER_THAN:
                break;
            case self::GREATER_THAN_EQUAL:
                break;
            case self::LESS_THAN:
                break;
            case self::LESS_THAN_EQUAL:
                break;
            case self::LIKE:
                break;
            case self::NOT_LIKE:
                break;
            default:
                $valid = false;
                break;
        }
        return $valid;
    }
}
