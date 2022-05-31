<?php

namespace App\Http\Middleware;

use App\Models\Notification;
use App\Models\PaidHoliday;
use Closure;
use DateTime;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaidHolidayMiddleware extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $user = Auth::user();
        $date = new \DateTime();
        if (Auth::user()->last_login != $date->format("Y-m-d")) {
            if (Storage::disk('local')->exists('config/paid_holidays.csv')) {
                $config = Storage::disk('local')->get('config/paid_holidays.csv');
                $config = str_replace(array("\r\n", "\r"), "\n", $config);
                $configArray = collect(explode("\n", $config));

                $holidays = 0;
                $getHolidays = 0;

                foreach ($configArray as $index => $dat) {
                    $item = preg_split("/,/", $dat);
                    if ($index == 0 || $dat == "") continue;

                    $joinDate = new DateTime($user->joined_date);
                    $year = intval($joinDate->format('Y'));
                    $month = intval($joinDate->format('m'));
                    $day = intval($joinDate->format('d'));

                    $lastLoginRaw = $user->last_login;
                    $lYear = 1990;
                    $lMonth = 1;
                    $lDay = 1;
                    if ($lastLoginRaw != null && $lastLoginRaw != "") {
                        $lastLogin = new DateTime($user->last_login ?? "1990-01-01 0:00:00");
                        $lYear = intval($lastLogin->format('Y'));
                        $lMonth = intval($lastLogin->format('m'));
                        $lDay = intval($lastLogin->format('d'));
                    }

                    $current = new \DateTime();
                    $cYear = intval($current->format('Y'));
                    $cMonth = intval($current->format('m'));
                    $cDay = intval($current->format('d'));

                    $dat0 = intval($lYear . sprintf("%02d", $lMonth) . sprintf("%02d", $lDay));
                    $dat1 = intval($year . sprintf("%02d", $month) . sprintf("%02d", $day));

                    $target_day = date("Y-m-d", strtotime($joinDate->format("Y-m-d")));
//1ヶ月後の日時を取得
                    $diffTime = strtotime($target_day . "+" . $item[0] . " month");
                    $dat2 = intval(date("Y", $diffTime) . date("m", $diffTime) . date("d", $diffTime));

                    $dat3 = intval($cYear . sprintf("%02d", $cMonth) . sprintf("%02d", $cDay));

                    //echo "DAT0: ".$dat0."<br>DAT1: ".$dat1."<br>DAT2: ".$dat2."<br>DAT3: ".$dat3."<br><br>";

                    if ($dat2 <= $dat0) {
                        //echo "<br><strong>SKIP</strong><br>";
                        continue;
                    }

                    if ($dat3 > $dat2) {

                        //echo "<br><strong>GET (".intval($item[1])." / ".($user->paid_holiday + intval($item[1])).")</strong><br>";
                        $dateDat = date("Y-m-d 00:00:00", $diffTime);
                        PaidHoliday::createHoliday(Auth::id(), intval($item[1]), $dateDat);
                        //$user->paid_holiday = $user->paid_holiday + intval($item[1]);
                        $holidays += intval($item[1]);
                    } else {
                        $getHolidays += intval($item[1]);
                    }
                }
                if ($holidays != 0) {
                    Notification::publish(['user_id' => Auth::id(), 'title' => '有休が付与されました', 'data' => '有給休暇が' . $holidays . '日付与されました。', 'url' => '/account', 'status' => 0]);
                }

                $user->last_login = new \DateTime();
                $user->save();
            }
        }
        return $next($request);
    }

}
