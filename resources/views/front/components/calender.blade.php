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
<div style="display: flex">
    <?php
    $joinDate = new DateTime(Auth::user()->joined_date);
    $joinYear = intval($joinDate->format('Y'));
    $joinMonth = intval($joinDate->format('m'));
    $joinDay = intval($joinDate->format('d'));

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
        if ($confirmStatus != 0) {
            $style = "calender-body2 bg-gray2";
        } else if ($year == $cYear && $month == $cMonth && $i == $cDay) {
            $style = "calender-body bg-green";
        } else if ($daysCount % 7 == 1) {
            $style = "calender-body bg-red";
        } else if ($daysCount % 7 == 0) {
            $style = "calender-body bg-blue";
        } else {
            $style = "calender-body";
        }
        echo '<div class="' . $style . '" onclick="openDescModal(' . $i . ')"><div style="font-weight: bolder; font-size: 12pt">' . $i . $joinStr . '</div>';
    } else {
        echo '<div class="calender-body2 bg-gray" onclick="openDescModal(' . $i . ')"><div style="font-weight: bolder; font-size: 12pt">' . $i . '</div>';
    }
    $key = $year . '-' . sprintf("%02d", $month) . '-' . str_pad($i, 2, 0, STR_PAD_LEFT);
    $noWorkFlag = true;
    try {
    $data = $attendData[$key];
    if($data->left_at != null){
    $dateData = new DateTime($data->left_at);
    $dateData = $dateData->format("G:i");
    $noWorkFlag = false;
    ?>
    <div>
        <strong>
            {{$data->created_at->format("G:i")}}-{{$dateData}}
        </strong>
    </div>
    <div>
        <span style="color: #2288EE;">●</span>&nbsp;
        <strong>{{$data->time}}</strong>
    </div>
    <?php
    }else{
    $noWorkFlag = false;
    ?>
    <div>
        <span style="color: #F22;">●</span>&nbsp;
        <strong>{{$data->created_at->format("G:i")}}-</strong>
    </div>
    <?php
    }
    } catch (Exception $ex) {
        $noWorkFlag = true;
    }
    try {
        $reqHtml = $reqData[$key][1];
        $noWorkFlag = false;
        echo $reqHtml;
    } catch (Exception $ex) {
    }
    $diffDayOfWeek = $daysCount % 7 - 1;
    if ($noWorkFlag &&
        ($year >= $joinYear && $month >= $joinMonth && $i >= $joinDay) &&
        $diffDayOfWeek > 0 && $diffDayOfWeek < 6 &&
        ($year <= $cYear && ($month <= $cMonth && ($year != $cYear || $month != $cMonth || $i < $day)))) {
        echo '<div><span style="color: #888;">●</span> <strong>欠勤</strong></div>';
    }
    if (!$isJoinedBeforeDay) {
        echo '<div>---</div>';
    }
    ?>
</div>
<?php }
if ($daysCount % 7 != 0) {
    echo str_repeat("<div class=\"calender-disabled bg-gray\"><span style=\"font-weight: bold; font-size: 12pt\"></span></div>", 7 - ($daysCount % 7));
}
?>
