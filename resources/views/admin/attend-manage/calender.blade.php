@extends('layouts.admin')

@section('styles')
    <style>
        .card-hover {
            box-shadow: 0 0 0;
            transition-duration: 0.1s;
        }

        .card-hover:hover {
            box-shadow: 0 0 10px #CCC;
            transition-duration: 0.2s;
        }

        .calender-doy {
            height: 30px;
            line-height: 30px;
            font-weight: bold;
            text-align: center;
            flex: 1;
            color: #222;
            border-width: 1px;
            border-style: solid;
            border-color: #666;
        }

        .calender-body {
            transition-duration: 0.2s;
            min-height: 130px;
            padding: 10px;
            flex: 1;
            color: #222;
            border-width: 1px;
            border-style: solid;
            border-color: #666;
            cursor: pointer;
            z-index: 1;
        }

        .calender-body2 {
            transition-duration: 0.2s;
            min-height: 130px;
            padding: 10px;
            flex: 1;
            color: #222;
            border-width: 1px;
            border-style: solid;
            border-color: #666;
            cursor: pointer;
        }

        .calender-disabled {
            transition-duration: 0.2s;
            min-height: 120px;
            padding: 10px;
            flex: 1;
            color: #222;
            border-width: 1px;
            border-style: solid;
            border-color: #666;
        }

        .calender-body:hover {
            transition-duration: 0.05s;
            box-shadow: 0 0 10px;
            min-height: 120px;
            padding: 10px;
            flex: 1;
            color: #222;
            border-width: 1px;
            border-style: solid;
            border-color: #666;
            z-index: 10;
        }

        .bg-gray {
            background-color: #888;
        }

        .bg-gray2 {
            background-color: #BBB;
        }

        .bg-green {
            background-color: #BFB;
        }

        .bg-red {
            background-color: #FBB;
        }

        .bg-blue {
            background-color: #BBF;
        }

        .sunday {
            height: 30px;
            line-height: 30px;
            font-weight: bold;
            text-align: center;
            flex: 1;
            background-color: #F33;
            color: #fff;
        }

        .saturday {
            height: 30px;
            line-height: 30px;
            font-weight: bold;
            text-align: center;
            flex: 1;
            background-color: #37F;
            color: #fff
        }

        .type-scroll::-webkit-scrollbar {
            display: block;
            height: 6px;
        }

        .type-scroll::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
        }

        .type-scroll::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.4);
            border-right: none;
            border-left: none;
        }

        .type-scroll::-webkit-scrollbar-track-piece:end {
            margin-bottom: 10px;
        }

        .type-scroll::-webkit-scrollbar-track-piece:start {
            margin-top: 10px;
        }

        .type-scroll {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            overflow-x: scroll;
            overflow: overlay;
            height: 50px;
        }

        .type-scroll {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            overflow: hidden;
            height: 50px;
            transition-duration: 0.3s;
        }

        .type-scroll:hover {
            overflow-x: scroll;
            overflow: overlay;
            transition-duration: 0.05s;
        }
    </style>
@endsection
@section('pageTitle', "勤怠情報管理")

@section('content')
    <div class="container mb-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="fw-bold">{{$user->name}}の勤怠情報</h2>
            </div>
            <hr>
            <div class="col-md-4" style="display: flex">
                @if($month == 1)
                    <a href="/admin/attend-manage/calender/{{$user->id}}?year={{$year - 1}}&month=12&mode={{$mode}}"
                       class="btn btn-outline-secondary btn-sm"
                       style="height: 45px; flex: 1; margin-top: 0; font-size: 18pt">◀</a>
                @else
                    <a href="/admin/attend-manage/calender/{{$user->id}}?year={{$year}}&month={{$month - 1}}&mode={{$mode}}"
                       class="btn btn-outline-secondary btn-sm"
                       style="height: 45px; flex: 1; margin-top: 0; font-size: 18pt">◀</a>
                @endif
                <h2 class="fw-bold mt-1" style="flex: 9; text-align: center">{{$year}}年 {{$month}}月</h2>
                @if($month == 12)
                    <a href="/admin/attend-manage/calender/{{$user->id}}?year={{$year + 1}}&month=1&mode={{$mode}}"
                       class="btn btn-outline-secondary btn-sm"
                       style="height: 45px; flex: 1; margin-top: 0; font-size: 18pt">▶</a>
                @else
                    <a href="/admin/attend-manage/calender/{{$user->id}}?year={{$year}}&month={{$month + 1}}&mode={{$mode}}"
                       class="btn btn-outline-secondary btn-sm"
                       style="height: 45px; flex: 1; margin-top: 0; font-size: 18pt">▶</a>
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
            <div class="col-md-4" style="display: flex; flex-direction: row-reverse; gap: 3px;">
                @if($confirmStatus == -1)
                    <button class="btn btn-secondary" style="height: 40px" disabled>
                        月報確定
                    </button>
                @elseif($confirmStatus == 2)
                    <a href="/admin/attend-manage/cancel?id={{$user->id}}&year={{$year}}&month={{$month}}"
                       class="btn btn-danger" style="height: 40px">
                        承認取消
                    </a>
                @elseif($confirmStatus == 1)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmModal"
                            style="height: 40px">
                        月報承認
                    </button>
                @else
                    <button type="button" class="btn btn-warning"
                            style="height: 40px" disabled>
                        月報未確定
                    </button>
                @endif
                <a href="/admin/attends/view?id={{$user->id}}" class="btn btn-secondary"
                   style="height: 40px">
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
        <div style="display: flex; width: 100%; gap: 10px">
            <div style="flex: 1.5; padding: 0">
                <span class="text-muted" style="height: 40px; line-height: 40px">今月の労働時間</span>
            </div>
            <div style="flex: 1">
                <strong style="font-size: 20pt">{{$hours}}:{{sprintf("%02d", $minutes)}}</strong>
            </div>
            <div style="flex: 1">

            </div>
            <div style="flex: 1.5; padding: 0">
                <span class="text-muted" style="height: 40px; line-height: 40px">今月の残業時間</span>
            </div>
            <div style="flex: 1">
                <strong style="font-size: 20pt">{{$hoursReq}}:{{sprintf("%02d", $minutesReq)}}</strong>
            </div>
            <div style="flex: 4">

            </div>
        </div>
        <hr>
        @include('front.components.calender')
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

                    <form id='logout-form' action={{ route('logout')}} method="POST" style="display: none;">
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
            <?php foreach ($attendData as $data) {
                echo "'" . $data->date . "': { date: '" . $data->date . "',";
                echo "mode: " . $data->mode . ",";
                echo "status: " . $data->status . ",";
                echo "comment: '" . ($data->comment ?? "null") . "',";
                echo "time: '" . ($data->time ?? "null") . "',";
                echo "rest: '" . ($data->rest ?? '00:00:00') . "',";
                echo "start: '" . ($data->created_at ?? "null") . "',";
                echo "end: '" . ($data->left_at ?? "null") . "'},";
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
                $dayKey = preg_split("/-/", $key)[2];
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

        function attendManageOpenDescModal(day) {
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
                if (modalData.time === 'null') {
                    modalContext.innerHTML += `<div style="display: flex"> <div class="card" style="width: 20px; height: 80px;/* border: 0; */border-radius: 0;background: #F11;"></div><div class="card" style="width: 100%; height: 80px;border-radius: 0; display: flex; flex-direction: row; padding: 10px"><span style="flex: 1"><span>退勤情報未入力</span><h2 class="fw-bold">勤務中</h2></span></div></div>`
                } else {
                    modalContext.innerHTML += `<div style="display: flex"> <div class="card" style="width: 20px; height: 80px;/* border: 0; */border-radius: 0;background: #18F;"></div><div class="card" style="width: 100%; height: 80px;border-radius: 0; display: flex; flex-direction: row; padding: 10px"><span style="flex: 1"><span>勤務時間</span><h2 class="fw-bold">` + hours + `:` + ('00' + minutes).slice(-2) + `</h2></span><span style="flex: 1"><span>休憩時間</span><h2 class="fw-bold">` + restHours + `:` + restMinutes + `</h2></span></div></div>`
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
                if ($confirmStatus) {
                    echo '<div class="col-md-12 mt-3" id="alert">
                </div><h6 class="mt-3 fw-bold">勤務情報</h6><hr>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="restInput" class="form-label">休憩時間</label>
                <input type="time" class="form-control" id="restInput" placeholder="未設定"
                       value="`+modalData.rest+`" disabled
                >
            </div><h6 class="mt-3 fw-bold">勤務詳細</h6><textarea id="textArea" class="form-control mt-2" style="width: 100%; min-height: 200px" disabled>`+modalData.comment+`</textarea>';
                } else {
                    echo '<div class="col-md-12 mt-3" id="alert">
                </div><h6 class="mt-3 fw-bold">勤務情報</h6><hr>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="restInput" class="form-label">休憩時間</label>
                <input type="time" class="form-control" id="restInput" placeholder="未設定"
                       value="`+modalData.rest+`"
                >
            </div><h6 class="mt-3 fw-bold">勤務詳細</h6><textarea id="textArea" class="form-control mt-2" style="width: 100%; min-height: 200px">`+modalData.comment+`</textarea><button class="btn btn-primary" id="saveBtn" style="float: right; margin-top: 7px" onclick="saveComment(\'`+keys+`\')">勤務詳細を保存</button>';
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

        function attendManagePrimary() {
            if (mode === 1 && currentDay != 0) {
                location = "/request/create?date=" + year + "-" + ('00' + month).slice(-2) + "-" + ('00' + currentDay).slice(-2)
            }
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
            let textArea = document.getElementById("textArea")
            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "保存しています"
            alert.innerHTML = ""

            axios
                .post("/api/v1/attends/comment/set", {
                    text: textArea.value,
                    rest: restTime.value,
                    user: {{$user->id}},
                    date: date
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
