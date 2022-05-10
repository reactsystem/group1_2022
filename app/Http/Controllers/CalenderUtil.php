<?php

namespace App\Http\Controllers;

class CalenderUtil
{
    public static function renderCalendar($dt)
    {
        $dt->timezone = 'Asia/Tokyo'; //日本時刻で表示
    }
}
