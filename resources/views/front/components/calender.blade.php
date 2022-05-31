<div style="display: flex">
    <div class="calender-doy sunday">
        日
    </div>
    <div class="calender-doy">
        月
    </div>
    <div class="calender-doy">
        火
    </div>
    <div class="calender-doy">
        水
    </div>
    <div class="calender-doy">
        木
    </div>
    <div class="calender-doy">
        金
    </div>
    <div class="calender-doy saturday">
        土
    </div>
</div>
<div class="flex-view">
    <?php
    /* @var $user */
    $joinDate = new DateTime($user->joined_date);
    $joinYear = intval($joinDate->format('Y'));
    $joinMonth = intval($joinDate->format('m'));
    $joinDay = intval($joinDate->format('d'));

    /* @var $dt */
    /* @var $year */
    /* @var $month */
    $daysInMonth = $dt->daysInMonth;
    $dayOfWeek = date('w', strtotime(date($year . '-' . $month . '-01')));
    $daysCount = $dayOfWeek;
    if ($dayOfWeek > 0) {
        echo str_repeat("<div class=\"calender-disabled bg-gray\"><span style=\"font-weight: bold; font-size: 12pt\"></span></div>", $dayOfWeek);
    }
    for ($i = 1; $i <= $daysInMonth; $i++) {
    if ($i > 1 && $daysCount % 7 == 0) {
        echo '</div><div style="display: flex">';
    }
    $daysCount++;
    $afterInt = intval($year . sprintf("%02d", $month) . sprintf("%02d", $i));
    $beforeInt = intval($joinYear . sprintf("%02d", $joinMonth) . sprintf("%02d", $joinDay));
    $isJoinedBeforeDay = $afterInt >= $beforeInt;
    if ($isJoinedBeforeDay) {
        $joinStr = "";
        if ($year == $joinYear && $month == $joinMonth && $i == $joinDay) {
            $joinStr = ' / 入社日';
        }
        $style = "";
        /* @var $confirmStatus */
        /* @var $cYear */
        /* @var $cMonth */
        /* @var $cDay */
        if ($confirmStatus != 0) {
            $style = "calender-body2 bg-gray2";
        } else if ($year == $cYear && $month == $cMonth && $i == $cDay) {
            $style = "calender-body bg-green";
        } else if ($daysCount % 7 == 1) {
            $style = "calender-body bg-red";
        } else if ($daysCount % 7 == 0) {
            $style = "calender-body bg-blue";
        } else {
            $style = "calender-body bg-white";
        }
        echo '<div class="' . $style . '" onclick="attendManageOpenDescModal(' . $i . ')"><div style="font-weight: bolder; font-size: 12pt">' . $i . $joinStr . '</div>';
    } else {
        echo '<div class="calender-body2 bg-gray" onclick="attendManageOpenDescModal(' . $i . ')"><div style="font-weight: bolder; font-size: 12pt">' . $i . '</div>';
    }
    $key = $year . '-' . sprintf("%02d", $month) . '-' . str_pad($i, 2, 0, STR_PAD_LEFT);
    $noWorkFlag = true;
    try {
    /* @var $holidays */
    if(array_key_exists($i, $holidays)){
    $noWorkFlag = false;
    foreach ($holidays[$i] as $holiday) {
    ?>
    <div>
        <span style="color: #ee5822;">●</span>&nbsp;
        <strong>{{$holiday->name}}</strong>
    </div>
    <?php
    }
    }
    /* @var $attendData */
    $data = $attendData[$key];
    if($data->left_at != null){
    $dateData = new DateTime($data->left_at);
    $dateData = $dateData->format("G:i");
    $noWorkFlag = false;
    $workData = preg_split("/:/", $data->time);
    $restData = preg_split("/:/", $data->rest);
    $wHours = intval($workData[0]);
    $wMinutes = intval($workData[1]);
    $rHours = intval($restData[0]);
    $rMinutes = intval($restData[1]);
    $hours = max(0, $wHours - $rHours);
    $minutes = $wMinutes - $rMinutes;
    if ($minutes < 0 && $hours != 0) {
        $minutes = 60 - abs($minutes);
        $hours -= 1;
    } else if ($minutes < 0) {
        $minutes = 0;
    }
    ?>
    <div>
        <strong>
            {{$data->created_at->format("G:i")}}-{{$dateData}}
        </strong>
    </div>
    <div>
        <span style="color: #2288EE;">●</span>&nbsp;
        <strong>{{$hours}}:{{sprintf("%02d", $minutes)}}</strong>
    </div>
    <?php
    }else{
    $noWorkFlag = false;
    ?>
    <div>
        <span style="color: #A11;">●</span>&nbsp;
        <strong>{{$data->created_at->format("G:i")}}-</strong>
    </div>
    <?php
    }
    } catch (Exception $ex) {

    }
    try {
        /* @var $reqData */
        $reqHtml = $reqData[$key][1];
        $noWorkFlag = false;
        echo $reqHtml;
    } catch (Exception $ex) {
    }
        $diffDayOfWeek = $daysCount % 7 - 1;

        if (!$isJoinedBeforeDay) {
            echo '<div>---</div>';
        } else {
            /* @var $day */
            if ($noWorkFlag &&
                $diffDayOfWeek > 0 && $diffDayOfWeek < 6 &&
                ((($year <= $cYear && $month <= $cMonth) || ($year < $cYear)) && (($year != $cYear || $month != $cMonth || $i < $day)))) {
                echo '<div><span style="color: #888;">●</span> <strong>欠勤</strong></div> ';
            }
        }
        ?>
</div>
<?php }
if ($daysCount % 7 != 0) {
    echo str_repeat("<div class=\"calender-disabled bg-gray\"><span style=\"font-weight: bold; font-size: 12pt\"></span></div>", 7 - ($daysCount % 7));
}
?>
