@extends('layouts.admin')

@section('pageTitle', "勤怠情報管理")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">{{$title}}</h2>
            </div>
            <div class="col-md-6">
                <a class="btn btn-primary float-right width-100 ml-5px" href="/admin/attend-manage/new">追加</a>
                @if(count($parameters) != 0)
                    <a class="btn btn-success float-right width-100 ml-5px" href="/admin/attend-manage">検索終了</a>
                @endif
                <button class="btn btn-secondary float-right width-100 ml-5px"
                        data-bs-toggle="modal" data-bs-target="#searchModal">検索
                </button>
            </div>
            @if (session('error'))
                <div class="col-md-12 mt-3">
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
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
        </div>
        @if($searchStr != "")
            <span>{!! $searchStr !!}</span>
        @endif
        <hr>
        <table class="table">
            <tr>
                <th>日付</th>
                <th>社員名</th>
                <th>状態</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>勤務時間</th>
            </tr>
            @foreach($data as $dat)
                <tr class="attends-row" onclick="jump('/admin/attend-manage/view/{{$dat->id}}')">
                    <td>
                        <?php
                        /* @var $dat */
                        $date_now = new DateTime($dat->date);
                        echo $date_now->format('Y年m月d日');
                        ?>
                    </td>
                    <td>
                        {{$dat->name}}
                    </td>
                    <td>
                        {{$dat->mode == 1 ? "退勤済" : "出勤中"}}
                    </td>
                    <td>
                        {{$dat->created_at}}
                    </td>
                    <td>
                        {{$dat->left_at ?? "--:--"}}
                    </td>
                    <td>
                        <?php
                        $date_now = new DateTime();
                        $interval = $dat->created_at->diff($date_now);

                        $created = new DateTime($dat->created_at->format("H:i:50"));
                        $intervalTime = $created->diff($date_now);
                        if ($dat->mode == 1 && $dat->left_at != null) {
                            $tempLeftDat1 = preg_split("/ /", $dat->left_at);
                            $tempLeftDat2 = preg_split("/:/", $tempLeftDat1[1]);
                            $datx = $tempLeftDat2[0] . ":" . $tempLeftDat2[1] . ":50";
                            $left = new DateTime($tempLeftDat2[0] . ":" . $tempLeftDat2[1] . ":50");
                            $intervalTime = $created->diff($left);
                            //$intervalTime->set
                        }

                        $datArray = preg_split("/:/", $intervalTime->format('%h:%I'));
                        $restData = preg_split("/:/", $dat->rest ?? "00:00");
                        $wHours = intval($datArray[0]);
                        $wMinutes = intval($datArray[1]);
                        $rHours = intval($restData[0]);
                        $rMinutes = intval($restData[1]);
                        $xHours = max(0, $wHours - $rHours);
                        $xMinutes = $wMinutes - $rMinutes;
                        if ($xMinutes < 0 && $xHours != 0) {
                            $xMinutes = 60 - abs($xMinutes);
                            $xHours -= 1;
                        } else if ($xMinutes < 0) {
                            $xMinutes = 0;
                        }
                        echo $xHours . ":" . sprintf("%02d", $xMinutes);
                        ?>
                    </td>
                </tr>
            @endforeach
        </table>
        {{$data->appends($parameters)->links()}}
    </div>

    <!-- Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">勤怠情報を検索</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="dateInput" class="form-label">社員</label>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-9">
                            <select class="form-select" aria-label="" id="user">
                                <option value="0">指定なし</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{sprintf("%03d", $user->employee_id)}}
                                        / {{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-3">
                        </div>
                        <div class="col-sm-12">
                            <label for="dateInput" class="form-label">日付</label>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-9">
                            <input type="date" class="form-control" id="dateInput" placeholder="XXXX-XX-XX">
                        </div>
                        <div class="mb-3 col-sm-12 col-md-3">
                            <button class="btn btn-secondary" onclick="clearDate()">
                                クリア
                            </button>
                        </div>
                        <div class="col-sm-12">
                            <label for="status" class="form-label">状態</label>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-9">
                            <select class="form-select" aria-label="" id="status">
                                <option value="-1" selected>指定しない</option>
                                <option value="0">出勤中</option>
                                <option value="1">退勤済み</option>
                            </select>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-3">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-primary" id="submitBtn" onclick="searchAttendData()" disabled>
                        検索
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let submitBtn = document.getElementById("submitBtn")
        let user = document.getElementById("user")
        let dateInput = document.getElementById("dateInput")
        let status = document.getElementById("status")

        let userInputFilled = false
        let dateInputFilled = false
        let statusInputFilled = false

        user.onchange = function () {
            userInputFilled = user.value > 0;
            checkData();
        }
        dateInput.onchange = function () {
            dateInputFilled = dateInput.value != null && dateInput.value !== "";
            checkData();
        }
        status.onchange = function () {
            statusInputFilled = status.value >= 0;
            checkData();
        }

        function checkData() {
            if (userInputFilled || dateInputFilled || statusInputFilled) {
                submitBtn.removeAttribute("disabled")
                return true
            } else {
                submitBtn.setAttribute("disabled", "")
                return false
            }
        }

        function clearDate() {
            dateInput.value = ""
            dateInputFilled = false
            checkData();
        }

        function searchAttendData() {
            const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

            if (!checkData()) {
                console.log("Failed")
                return
            }

            let keywords = []
            if (user.value > 0) {
                keywords.push('user=' + user.value)
            }
            if (dateInput.value != null && dateInput.value !== "") {
                keywords.push('date=' + dateInput.value)
            }
            if (status.value >= 0) {
                keywords.push('status=' + status.value)
            }
            console.log("URL: " + "/admin/attend-manage/search?" + keywords.join("&"))

            jump("/admin/attend-manage/search?" + keywords.join("&"))
        }
    </script>
@endsection
