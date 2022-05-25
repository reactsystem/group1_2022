@extends('layouts.main')

@section('styles')
    <style>
        @media only screen and (max-width: 767.999px) {
            .calender-main {
                transform: scale(0.7);
                width: 145%;
                margin-left: -23%;
                margin-top: -40px;
                border-radius: 15px;
            }

            .card-header {
                border-radius: 15px 15px 0 0 !important;
            }

            .cards {
                margin-top: 40px;
                border-radius: 20px;
            }
        }

        @media only screen and (min-width: 768px) {
            .calender-main {
                border-radius: 16px;
                margin-top: -30px;
            }

            .card-header {
                border-radius: 10px 10px 0 0 !important;
            }

            .cards {
                margin-top: 10px;
            }
        }

        @media only screen and (min-width: 992px) {
            .calender-main {
                transform: scale(0.75);
                width: 134%;
                margin-left: -17%;
                margin-top: -30px;
                border-radius: 13px;
            }

            .card-header {
                border-radius: 13px 13px 0 0 !important;
            }

            .cards {
                margin-top: 40px;
            }
        }

        @media only screen and (min-width: 1220px) {
            .calender-main {
                transform: scale(1.0);
                width: 100%;
                margin-left: 0;
                margin-top: 10px;
                border-radius: 10px;
            }

            .card-header {
                border-radius: 10px 10px 0 0 !important;
            }

            .cards {
                margin-top: 20px;
            }
        }
    </style>
@endsection
@section('pageTitle', "勤怠情報確認")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 flex-view">
                @if($month == 1)
                    <a href="/attend-manage?year={{$year - 1}}&month=12&mode={{$mode}}"
                       class="btn btn-outline-secondary btn-sm btn-calender-month">◀</a>
                @else
                    <a href="/attend-manage?year={{$year}}&month={{$month - 1}}&mode={{$mode}}"
                       class="btn btn-outline-secondary btn-sm btn-calender-month">◀</a>
                @endif
                <h2 class="fw-bold mt-1 calender-year-month">{{$year}}年 {{$month}}月</h2>
                @if($month == 12)
                    <a href="/attend-manage?year={{$year + 1}}&month=1&mode={{$mode}}"
                       class="btn btn-outline-secondary btn-sm btn-calender-month">▶</a>
                @else
                    <a href="/attend-manage?year={{$year}}&month={{$month + 1}}&mode={{$mode}}"
                       class="btn btn-outline-secondary btn-sm btn-calender-month">▶</a>
                @endif
            </div>
            <div class="col-md-4 type-scroll">
                @foreach($cats as $cat)
                    <div>
                        <span style="color: {{$cat->color}}">
                            ●
                        </span>{{$cat->name}}&nbsp;
                    </div>
                @endforeach
                <div>
                        <span style="color: #ee5822">
                            ●
                        </span>会社設定休日&nbsp;
                </div>
                <div>
                        <span style="color: #888">
                            ●
                        </span>欠勤&nbsp;
                </div>
            </div>
            <div class="col-md-4 header-flex-area">
                @if($confirmStatus == -1)
                    <button class="btn btn-secondary height-40" disabled>
                        月報確定
                    </button>
                @elseif($confirmStatus == 2)
                    <button class="btn btn-success height-40" disabled>
                        承認済み
                    </button>
                @elseif($confirmStatus == 1)
                    <a href="/attend-manage/unconfirm?year={{$year}}&month={{$month}}" class="btn btn-danger height-40">
                        確定解除
                    </a>
                @else
                    <a href="/attend-manage/confirm?year={{$year}}&month={{$month}}" class="btn btn-primary height-40">
                        月報確定
                    </a>
                @endif
                <a href="/attend-manage?year={{$year}}&month={{$month}}&mode=0" class="btn btn-secondary height-40">
                    カレンダー表示
                </a>
            </div>
        </div>
        <hr>
        <div class="cards">
            @if(count($attendData) == 0)
                <div class="text-muted mt-5 calender-list-empty">
                    この月の勤務情報がありません
                </div>
            @endif
            @foreach($attendData as $data)
                    <div class="card work-card calender-main card-hover pointer-cursor mb-3"
                         onclick="openDescModal({{$data->created_at->format('d')}})">
                    <div class="card-header calender-card-header">
                        {{$data->created_at->format('Y年m月d日')}}の勤務情報&nbsp;<span><?php
                            try {
                            /* @var $data */
                            if($data->left_at != null){
                            $dateData = new DateTime($data->left_at);
                            $dateData = $dateData->format("G:i");
                            ?><span style="color: #2288EE;">●</span><?php
                            }else{?><span style="color: #A11;">●</span><?php
                            }
                            } catch (Exception $ex) {
                            }
                            try {
                                /* @var $reqData */
                                $reqHtml = $reqData[$data->date][1];
                                echo $reqHtml;
                            } catch (Exception $ex) {
                            }
                            $leftDate = "--:--";
                            if ($data->left_at != null) {
                                $tempDate = new DateTime($data->left_at);
                                $leftDate = $tempDate->format('G:i');
                            }
                            $diff = strtotime($data->left_at ?? $data->created_at) - strtotime($data->created_at);
                            $diffTime = new DateTime();
                            $diffTime->setTimestamp($diff);
                            $diffTime->setTimeZone(new DateTimeZone('UTC'));
                            $diffDat = preg_split("/:/", $diffTime->format("H:i"));
                            $hours = intval($diffDat[0]);
                            $minutes = intval($diffDat[1]);

                            $restData = preg_split("/:/", $data->rest ?? "00:00");
                            $wHours = intval($diffDat[0]);
                            $wMinutes = intval($diffDat[1]);
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

                            $restTime = $rHours . ":" . sprintf("%02d", $rMinutes);
                            if (array_key_exists($data->date, $reqData)) {
                                foreach ($reqData[$data->date][0] as $rDat) {
                                    if ($rDat->time == null) continue;
                                    $tempDiffDat = preg_split("/:/", $rDat->time);
                                    $tempHours = intval($tempDiffDat[0]);
                                    $tempMinutes = intval($tempDiffDat[1]);
                                    $hours += $tempHours;
                                    $minutes += $tempMinutes;
                                    if ($minutes > 60) {
                                        $hours++;
                                        $minutes -= 60;
                                    }
                                }
                            }
                            ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="calender-card-body">
                            <div class="text-muted calender-card-labels">
                                出勤
                            </div>
                            <div class="fw-bold calender-card-text">{{$data->created_at->format('G:i')}}</div>
                            <div class="text-muted calender-card-symbols">
                                ▶
                            </div>
                            <div class="text-muted calender-card-labels">
                                退勤
                            </div>
                            <div class="fw-bold calender-card-text">{{$leftDate}}</div>
                            <div class="text-muted calender-card-splitter">
                                |
                            </div>
                            <div class="text-muted calender-card-labels">
                                勤務
                            </div>
                            <div class="fw-bold calender-card-text">{{$hours.":".sprintf("%02d", $minutes)}}</div>
                            <div class="text-muted calender-card-symbols">
                                ▶
                            </div>
                            <div class="text-muted calender-card-labels">
                                休憩
                            </div>
                            <div class="fw-bold calender-card-text">{{$restTime}}</div>
                        </div>
                    </div>

                    <?php
                    try {
                        if ($data->left_at != null) {
                            $dateData = new DateTime($data->left_at);
                            $dateData = $dateData->format("G:i");
                        }
                    } catch (Exception $ex) {
                    }
                    ?>
                </div>
            @endforeach
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="modalHeader" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="modalHeader">MODAL_HEADER</h5>
                </div>
                <div class="modal-body" id="modalContext">
                    <div class="text-center mb-3">
                        MODAL_CONTEXT
                    </div>
                    <div id="checkForm"></div>
                </div>
                <div class="modal-footer">
                    <div class="margin-0-auto">
                        <button type="button" class="btn btn-primary" id="primaryButton" onclick="primary()">PRIMARY
                        </button>
                        <button type="button" class="btn btn-secondary" id="secondaryButton" onclick="secondary()">
                            CANCEL
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let modalHeader = document.getElementById("modalHeader")
        let modalContext = document.getElementById("modalContext")
        let primaryButton = document.getElementById("primaryButton")
        let secondaryButton = document.getElementById("secondaryButton")
        let mode = 0
        let currentDay = 0
        let year = {{$year}}
            let
        month = {{$month}}

            let
        works = {
            <?php
            /* @var $attendData */
            foreach ($attendData as $data) {
                $leftDate = $data->left_at;
                $hours = 0;
                $minutes = 0;
                if ($leftDate != null) {
                    $datx = new DateTime($data->left_at);
                    $dateData = new DateTime($leftDate);
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
                    $leftDate = $datx->format('H:i');
                } else {
                    $dateDatax = new DateTime();
                    $dateData = $dateDatax->format("G:i");
                    $noWorkFlag = false;

                    $current = strtotime($dateDatax->format("Y-m-d H:i:00"));
                    $before = strtotime($data->created_at->format("Y-m-d {$data->created_at->format('H:i')}:00"));
                    $diff = $current - $before;
                    $hours = intval($diff / 60 / 60);
                    $minutes = intval($diff / 60) % 60;
                    $workTime = sprintf("%02d", $hours) . ":" . sprintf("%02d", $minutes);

                    $workData = preg_split("/:/", $workTime);
                    $restData = preg_split("/:/", $data->rest ?? "00:00");
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
                }
                echo "'" . $data->date . "': { date: '" . $data->date . "',";
                echo "mode: " . $data->mode . ",";
                echo "status: " . $data->status . ",";
                echo "comment: '" . ($data->comment ?? "null") . "',";
                echo "time: '" . ($data->time ?? "null") . "',";
                echo "rtime: '" . $hours . ":" . sprintf("%02d", $minutes) . "',";
                echo "rest: '" . ($data->rest ?? '00:00:00') . "',";
                echo "start: '" . ($data->created_at->format("H:i") ?? "null") . "',";
                echo "end: '" . ($leftDate ?? "00:00") . "'},";
            }?>
        }

        let requests = {<?php
            $found = [];
            foreach ($reqData as $key => $data) {
                echo "'" . $key . "': [";
                foreach ($data[0] as $dat) {
                    echo "{date: '" . $dat->date . "',";
                    echo "typeName: '" . $dat->name . "',";
                    echo "typeColor: '" . $dat->color . "',";
                    echo "status: " . $dat->status . ",";
                    echo "reason: '" . ($dat->reason ?? "null") . "',";
                    echo "time: '" . ($dat->time ?? "null") . "'},";
                }
                $dayKey = intval(preg_split("/-/", $key)[2]);
                /* @var $holidays */
                if (array_key_exists($dayKey, $holidays)) {
                    $found[$dayKey] = true;
                    foreach ($holidays[$dayKey] as $holiday) {
                        echo "{date: '" . $key . "',";
                        echo "typeName: '" . $holiday->name . " (会社設定休日)',";
                        echo "typeColor: '#ee5822',";
                        echo "status: 0,";
                        echo "reason: '',";
                        echo "time: ''},";
                    }
                }

                echo "],";
            }
            for ($i = 1; $i <= 31; $i++) {
                /* @var $year */
                /* @var $month */
                if (!array_key_exists($i, $found) && array_key_exists($i, $holidays)) {
                    $dxKey = $year . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $i);
                    echo "'" . $dxKey . "': [";
                    $found[$i] = true;
                    foreach ($holidays[$i] as $holiday) {
                        echo "{date: '" . $dxKey . "',";
                        echo "typeName: '" . $holiday->name . " (会社設定休日)',";
                        echo "typeColor: '#ee5822',";
                        echo "status: 0,";
                        echo "reason: '',";
                        echo "time: ''},";
                    }
                    echo "],";
                }
            }
            ?>
        }

        function openDescModal(day) {
            let alert = document.getElementById("alert")
            currentDay = day
            modalContext.innerHTML = ""
            console.log("選択: " + day)
            modalHeader.innerText = "{{$month}}月" + day + "日の勤務情報"
            const keys = '{{$year}}-' + ('00' + {{$month}}).slice(-2) + '-' + ('00' + day).slice(-2)
            console.log("KEYS: " + keys)
            const modalData = works[keys]
            const requestsData = requests[keys]
            let hours = 0
            let minutes = 0
            let restTimeMode = 0
            if (modalData === undefined && requestsData === undefined) {
                modalContext.innerHTML = "勤務情報なし"
            }
            if (modalData !== undefined) {
                const timeData = modalData.time.split(":")
                hours = parseInt(timeData[0])
                minutes = parseInt(timeData[1])
                const restData = modalData.rest.split(":")
                let restHours = restData[0]
                let restMinutes = restData[1]
                hours = Math.max(0, hours - restHours)
                minutes = minutes - restMinutes
                if (minutes < 0 && hours !== 0) {
                    minutes = 60 - Math.abs(minutes)
                    hours -= 1
                } else if (minutes < 0) {
                    minutes = 0
                }
                if (modalData.time === 'null' || modalData.end === 'null' || modalData.mode !== 1) {
                    modalContext.innerHTML += `<div style="display: flex"> <div class="card" style="width: 20px; height: 80px;/* border: 0; */border-radius: 0;background: #F22;"></div><div class="card" style="width: 100%; height: 80px;border-radius: 0; display: flex; flex-direction: row; padding: 10px"><span style="flex: 1"><span>勤務時間 (未退勤)</span><h2 class="fw-bold">` + modalData.rtime + `</h2></span><span style="flex: 1"><span>休憩時間</span><h2 class="fw-bold">` + restHours + `:` + restMinutes + `</h2></span></div></div>`
                } else {
                    modalContext.innerHTML += `<div style="display: flex"> <div class="card" style="width: 20px; height: 80px;/* border: 0; */border-radius: 0;background: #18F;"></div><div class="card" style="width: 100%; height: 80px;border-radius: 0; display: flex; flex-direction: row; padding: 10px"><span style="flex: 1"><span>勤務時間</span><h2 class="fw-bold">` + modalData.rtime + `</h2></span><span style="flex: 1"><span>休憩時間</span><h2 class="fw-bold">` + restHours + `:` + restMinutes + `</h2></span></div></div>`
                }
            }
            if (requestsData !== undefined) {
                console.log(requestsData)
                requestsData.forEach(data => {
                    const timeData = data.time.split(":")
                    const tempHours = parseInt(timeData[0])
                    const tempMinutes = parseInt(timeData[1])
                    hours += tempHours
                    minutes += tempMinutes
                    /*

                    let restHours = '0'
                    let restMinutes = '00'
                    console.log("CurrentHours: " + hours + ":" + minutes)
                    if (restTimeMode === 0) {
                        if (hours >= 8) {
                            restTimeMode = 2
                            restHours = '1'
                            restMinutes = '00'
                        } else if (hours >= 6) {
                            restTimeMode = 1
                            restHours = '0'
                            restMinutes = '45'
                        }
                    } else if (restTimeMode === 1) {
                        restHours = '0'
                        restMinutes = '15'
                        restTimeMode = 2
                    }
                    let restTimeStr = restHours + `:` + restMinutes
                    if (isNaN(tempHours) || isNaN(tempMinutes)) {
                        restTimeStr = "--:--"
                    }*/
                    modalContext.innerHTML += `<div style="display: flex" class="mt-1"> <div class="card" style="width: 20px; height: 80px;/* border: 0; */border-radius: 0;background: ` + data.typeColor + `;"></div><div class="card" style="width: 100%; height: 80px;border-radius: 0; display: flex; flex-direction: row; padding: 10px"><span style="flex: 1"><span>` + data.typeName + `</span><h2 class="fw-bold">` + ((data.time === 'null' ? '--:--' : data.time) ?? '--:--') + `</h2></span></div></div>`
                    console.log(data.typeName)
                })
            }

            if (modalData !== undefined) {
                modalContext.innerHTML += `<?php
                /* @var $confirmStatus */
                if ($confirmStatus) {
                    echo '<div class="col-md-12 mt-3" id="alert">
                </div><h6 class="mt-3 fw-bold">勤務情報</h6><hr><div class="row">';
                    if (env("ENABLE_EDIT_ATTENDANCE", false)) {
                        echo '<div class="mb-3 col-md-12 col-lg-6">
                <label for="startInput" class="form-label">出勤時刻</label>
                <input type="time" class="form-control" id="startInput" placeholder="未設定"
                       value="`+modalData.start+`" disabled
                >
            </div>';
                        echo '` + (modalData.mode == 1 ? `<div class="mb-3 col-md-12 col-lg-6">
                <label for="endInput" class="form-label">退勤時刻</label>
                <input type="time" class="form-control" id="endInput" placeholder="未設定"
                       value="`+modalData.end+`" disabled
                >
            </div> ` : ``) + `';
                    }
                    echo '
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="restInput" class="form-label">休憩時間</label>
                <input type="time" class="form-control" id="restInput" placeholder="未設定"
                       value="`+modalData.rest+`" disabled
                >
            </div></div><h6 class="mt-3 fw-bold">勤務詳細</h6><textarea id="textArea" class="form-control mt-2" style="width: 100%; min-height: 200px" disabled>`+modalData.comment+`</textarea>';
                } else {
                    echo '<div class="col-md-12 mt-3" id="alert">
                </div><h6 class="mt-3 fw-bold">勤務情報</h6><hr><div class="row">';
                    if (env("ENABLE_EDIT_ATTENDANCE", false)) {
                        echo '<div class="mb-3 col-md-12 col-lg-6">
                <label for="startInput" class="form-label">出勤時刻</label>
                <input type="time" class="form-control" id="startInput" placeholder="未設定"
                       value="`+modalData.start+`"
                >
            </div>';
                        echo '` + (modalData.mode == 1 ? `<div class="mb-3 col-md-12 col-lg-6">
                <label for="endInput" class="form-label">退勤時刻</label>
                <input type="time" class="form-control" id="endInput" placeholder="未設定"
                       value="`+modalData.end+`"
                >
            </div> ` : ``) + `';
                    }
                    echo '
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="restInput" class="form-label">休憩時間</label>
                <input type="time" class="form-control" id="restInput" placeholder="未設定"
                       value="`+modalData.rest+`"
                >
            </div></div><h6 class="mt-3 fw-bold">勤務詳細</h6><textarea id="textArea" class="form-control mt-2" style="width: 100%; min-height: 200px">`+modalData.comment+`</textarea><button class="btn btn-primary" id="saveBtn" style="float: right; margin-top: 7px" onclick="saveComment(\'`+keys+`\')">勤務詳細を保存</button>';
                }
                ?>`
            }
            console.log("TotalHours: " + hours + ":" + minutes)

            // language=HTML
            primaryButton.innerText = "新規申請"
            secondaryButton.innerText = "閉じる"
            mode = 1
            jQuery('#basicModal').modal("show");
        }

        function primary() {
            if (mode === 1 && currentDay != 0) {
                location = "/request/create?date=" + year + "-" + ('00' + month).slice(-2) + "-" + ('00' + currentDay).slice(-2)
            }
        }

        function secondary() {
            if (mode === 1) {
                mode = 0
                jQuery('#basicModal').modal("hide");
            }
        }

        function saveComment(date) {
            let alert = document.getElementById("alert")
            const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

            let saveBtn = document.getElementById("saveBtn")
            let restTime = document.getElementById("restInput")

            @if(env("ENABLE_EDIT_ATTENDANCE", false))
            let startInput = document.getElementById("startInput")
            let endValue = null
            try {
                let endInput = document.getElementById("endInput")
                endValue = endInput.value
            } catch (error) {
            }
            @endif

            let textArea = document.getElementById("textArea")
            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "保存しています"

            alert.innerHTML = ""

            axios
                .post("/api/v1/attends/comment/set", {
                    @if(env("ENABLE_EDIT_ATTENDANCE", false))
                    text: textArea.value,
                    rest: restTime.value,
                    start: startInput.value,
                    end: endValue,
                    date: date
                    @else
                    text: textArea.value,
                    rest: restTime.value,
                    date: date
                    @endif
                })
                .then(async (res) => {
                    const resultCode = res.data.code
                    console.log("Result: " + resultCode + " (" + res.data.message + ")")
                    if (resultCode == 0) {
                        saveBtn.className = "btn btn-success"
                        saveBtn.innerText = "保存しました"
                        alert.innerHTML = '<div class="alert alert-success" role="alert">' +
                            '<strong>成功</strong> - ' + res.data.message +
                            '</div>'
                        await _sleep(1000)
                        location = "/attend-manage?year={{$year}}&month={{$month}}&mode=0"
                        return
                    } else {
                        let alertStr = '<div class="alert alert-danger" role="alert">' +
                            '<strong>エラー</strong> - ' +
                            res.data.message + '<br>'
                        if (resultCode === 1) {
                            Object.keys(res.data.errors).forEach(key => {
                                alertStr += res.data.errors[key] + '<br>'
                            });
                        }
                        alertStr += '</div>';
                        alert.innerHTML = alertStr
                        saveBtn.className = "btn btn-danger"
                        saveBtn.innerText = "保存失敗"
                        await _sleep(2000)
                    }
                    saveBtn.removeAttribute("disabled")
                    saveBtn.className = "btn btn-primary"
                    saveBtn.innerText = "勤務詳細を保存"
                })
        }

    </script>
@endsection
