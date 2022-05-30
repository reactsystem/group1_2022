@extends('layouts.admin')

@section('pageTitle', "勤怠情報管理")

@section('content')
    <div class="container mb-5">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">{{$user->name}}の勤怠情報</h2>
            </div>
            <div class="col-md-6 mb-3">
                <div class="btn-group float-right" role="group">
                    <button id="btnGroupDrop1" type="button"
                            class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"
                            aria-expanded="false">
                        <span class="sr-only">CSVデータ出力</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        @if($attendData != null && count($attendData) != 0)
                            <li><a class="dropdown-item"
                                   href="/admin/attend-manage/download/{{$user->id}}/{{$year}}/{{$month}}">当月勤務データ出力</a>
                            </li>
                        @else
                            <li><a class="dropdown-item disabled">当月勤務データ出力</a>
                            </li>
                        @endif
                        <li><a class="dropdown-item"
                               href="/admin/attend-manage/download/{{$user->id}}/{{$year}}/-1">当年勤務データ出力</a>
                        </li>
                        @if($reqData != null && count($reqData) != 0)
                            <li><a class="dropdown-item"
                                   href="/admin/attend-manage/download-requests/{{$user->id}}/{{$year}}/{{$month}}">当月申請データ出力</a>
                            </li>
                        @else
                            <li><a class="dropdown-item disabled">当月申請データ出力</a>
                            </li>
                        @endif
                        <li><a class="dropdown-item"
                               href="/admin/attend-manage/download-requests/{{$user->id}}/{{$year}}/-1">当年申請データ出力</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="col-md-5 col-lg-6 col-xl-4 flex-view">
                @if($month == 1)
                    <a href="/admin/attend-manage/calender/{{$user->id}}?year={{$year - 1}}&month=12&mode={{$mode}}"
                       class="btn btn-outline-secondary btn-sm btn-calender-month">◀</a>
                @else
                    <a href="/admin/attend-manage/calender/{{$user->id}}?year={{$year}}&month={{$month - 1}}&mode={{$mode}}"
                       class="btn btn-outline-secondary btn-sm btn-calender-month">◀</a>
                @endif
                <h2 class="fw-bold mt-1 calender-year-month">{{$year}}年 {{$month}}月</h2>
                @if($month == 12)
                    <a href="/admin/attend-manage/calender/{{$user->id}}?year={{$year + 1}}&month=1&mode={{$mode}}"
                       class="btn btn-outline-secondary btn-sm btn-calender-month">▶</a>
                @else
                    <a href="/admin/attend-manage/calender/{{$user->id}}?year={{$year}}&month={{$month + 1}}&mode={{$mode}}"
                       class="btn btn-outline-secondary btn-sm btn-calender-month">▶</a>
                @endif
            </div>
            <div class="col-md-7 col-lg-6 col-xl-4 type-scroll">
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
            <div class="col-md-12 col-lg-12 col-xl-4 header-flex-area">
                @if($confirmStatus == -1)
                    <button class="btn btn-secondary height-40" disabled>
                        月報確定
                    </button>
                @elseif($confirmStatus == 2)
                    <a href="/admin/attend-manage/cancel?id={{$user->id}}&year={{$year}}&month={{$month}}"
                       class="btn btn-danger height-40">
                        承認取消
                    </a>
                @elseif($confirmStatus == 1)
                    <button type="button" class="btn btn-primary height-40" data-bs-toggle="modal"
                            data-bs-target="#confirmModal">
                        月報承認
                    </button>
                @else
                    <button type="button" class="btn btn-warning height-40" disabled>
                        月報未確定
                    </button>
                @endif
                <a href="/admin/attends/view?id={{$user->id}}" class="btn btn-secondary height-40">
                    社員情報に戻る
                </a>
            </div>
        </div>
        @if (session('error'))
            <div class="col-md-12 mt-3">
                <div class="alert alert-danger" role="alert">
                    <strong>エラー</strong> {{ session('error') }}
                </div>
            </div>
        @endif
        @if (session('result'))
            <div class="col-md-12 mt-3">
                <div class="alert alert-success" role="alert">
                    {{ session('result') }}
                </div>
            </div>
        @endif
        <div class="row w-100 mt-3 ml-0">
            <div class="col-md-2 col-sm-6 padding-0">
                <span class="text-muted vert-center-40">今月の労働時間</span>
            </div>
            <div class="col-md-3 col-sm-6">
                <strong class="font-20">{{$hours}}:{{sprintf("%02d", $minutes)}}</strong>
            </div>
            <div class="col-md-1 d-md-inline-flex d-sm-none">

            </div>
            <div class="col-md-2 col-sm-6 padding-0">
                <span class="text-muted vert-center-40">今月の残業時間</span>
            </div>
            <div class="col-md-3 col-sm-6">
                <strong class="font-20">{{$hoursReq}}:{{sprintf("%02d", $minutesReq)}}</strong>
            </div>
            <div class="col-md-1 d-md-inline-flex d-sm-none">

            </div>
        </div>
        <hr>
        <div class="calender-main">
            @include('front.components.calender')
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">月報承認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    月報を承認すると社員はその月の月報編集が出来なくなります。<br>
                    <span class="text-muted">月報の承認を取り消すこともできます。</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <a href="/admin/attend-manage/confirm?id={{$user->id}}&year={{$year}}&month={{$month}}"
                       class="btn btn-primary">
                        月報を承認
                    </a>
                    @csrf
                </div>
            </div>
        </div>
    </div>

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
                    <div style="margin: 0 auto;">
                        <button type="button" class="btn btn-primary" id="primaryButton"
                                onclick="attendManagePrimary()">PRIMARY
                        </button>
                        <button type="button" class="btn btn-secondary" id="secondaryButton"
                                onclick="attendManageSecondary()">
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
            /* @var $reqData */
            foreach ($reqData as $key => $data) {
                echo "'" . $key . "': [";
                foreach ($data[0] as $dat) {
                    echo "{date: '" . $dat->date . "',";
                    echo "id: '" . $dat->id . "',";
                    echo "typeName: '" . $dat->name . "',";
                    echo "typeColor: '" . $dat->color . "',";
                    echo "status: " . $dat->status . ",";
                    echo "time: '" . ($dat->time ?? "null") . "'},";
                }
                $dayKey = preg_split("/-/", $key)[2];
                /* @var $holidays */
                if (array_key_exists($dayKey, $holidays)) {
                    $found[$dayKey] = true;
                    foreach ($holidays[$dayKey] as $holiday) {
                        echo "{date: '" . $key . "',";
                        echo "typeName: '" . $holiday->name . " (会社設定休日)',";
                        echo "typeColor: '#ee5822',";
                        echo "status: 0,";
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
                        echo "time: ''},";
                    }
                    echo "],";
                }
            }
            ?>
        }

        function attendManageOpenDescModal(day) {
            currentDay = day
            modalContext.innerHTML = ""
            // DISABLED - console.log("選択: " + day)
            modalHeader.innerText = "{{$month}}月" + day + "日の勤務情報"
            const keys = '{{$year}}-' + ('00' + {{$month}}).slice(-2) + '-' + ('00' + day).slice(-2)
            // DISABLED - console.log("KEYS: " + keys)
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
                // DISABLED - console.log(requestsData)
                requestsData.forEach(data => {
                    const timeData = data.time.split(":")
                    const tempHours = parseInt(timeData[0])
                    const tempMinutes = parseInt(timeData[1])
                    hours += tempHours
                    minutes += tempMinutes
                    let links = ''
                    let linksClass = ''
                    if (data.id != null) {
                        links = ' onclick="href(\'/admin/request/detail?id=' + (data.id) + '\')"'
                        linksClass = ' card-hover pointer-cursor'
                    }
                    modalContext.innerHTML += `<div style="display: flex" class="mt-1` + linksClass + `"` + links + `> <div class="card" style="width: 20px; height: 80px;/* border: 0; */border-radius: 0;background: ` + data.typeColor + `;"></div><div class="card" style="width: 100%; height: 80px;border-radius: 0; display: flex; flex-direction: row; padding: 10px"><span style="flex: 1"><span>` + data.typeName + `</span><h2 class="fw-bold">` + ((data.time === 'null' ? '--:--' : data.time) ?? '--:--') + `</h2></span></div></div>`
                    // DISABLED - console.log(data.typeName)
                })
            }

            if (modalData !== undefined) {
                modalContext.innerHTML += `<?php
                /* @var $confirmStatus */
                if ($confirmStatus) {
                    echo '<div class="col-md-12 mt-3" id="alert">
                </div><h6 class="mt-3 fw-bold">勤務情報</h6><hr><div class="row">';
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
            // DISABLED - console.log("TotalHours: " + hours + ":" + minutes)

            // language=HTML
            primaryButton.style.display = "none"
            secondaryButton.innerText = "閉じる"
            mode = 1
            jQuery('#basicModal').modal("show");
        }

        function attendManagePrimary() {
        }

        function attendManageSecondary() {
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
            let endValue = null
            try {
                let endInput = document.getElementById("endInput")
                endValue = endInput.value
            } catch (error) {
            }

            let textArea = document.getElementById("textArea")
            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "保存しています"

            alert.innerHTML = ""

            axios
                .post("/api/v1/attends/comment/set", {
                    text: textArea.value,
                    rest: restTime.value,
                    start: startInput.value,
                    end: endValue,
                    user: {{$user->id}},
                    date: date
                })
                .then(async (res) => {
                    const resultCode = res.data.code
                    // DISABLED - console.log("Result: " + resultCode + " (" + res.data.message + ")")
                    if (resultCode == 0) {
                        saveBtn.className = "btn btn-success"
                        saveBtn.innerText = "保存しました"
                        alert.innerHTML = '<div class="alert alert-success" role="alert">' +
                            '<strong>成功</strong> - ' + res.data.message +
                            '</div>'
                        await _sleep(1000)
                        location = "/admin/attend-manage/calender/{{$user->id}}?year={{$year}}&month={{$month}}&mode=0"
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
