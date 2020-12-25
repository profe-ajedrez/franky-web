<?php declare(strict_types = 1);

namespace jotaa\franky_web\helpers\date;

final class DateHelper
{
    public static function stringDateTime(string $strDate) : string
    {
        return (new \Safe\DateTime($strDate))->format('Y-m-d H:i:s');
    }
}
