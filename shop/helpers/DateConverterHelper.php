<?php

namespace shop\helpers;

class DateConverterHelper
{
    public static function convertDateToTimestamp(string $date) : int
    {
        return strtotime($date);
    }

    public static function convertTimestampToDate($format, int $timestamp) : string
    {
        return date($format, $timestamp);
    }
}