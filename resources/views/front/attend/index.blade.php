@extends('layouts.main')

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
    </style>
@endsection
@section('pageTitle', "出勤・退勤入力")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @if($data != null && $data->mode == 0)
                    <div class="row">
                        <div class="col-auto" style="line-height: 40px; height: 40px; padding-right: 0">
                            勤務時間
                        </div>
                        <div class="col-auto"
                             style="line-height: 40px; height: 40px; font-weight: bold; font-size: 32pt">
                            {{$interval->format("%h:%I")}}
                        </div>
                        <div class="col-md-1">

                        </div>
                        <div class="col-auto" style="line-height: 40px; height: 40px; padding-right: 0">
                            出勤時刻
                        </div>
                        <div class="col-auto"
                             style="line-height: 40px; height: 40px; font-weight: bold; font-size: 32pt">
                            {{$data->created_at->format("H:i")}}
                        </div>
                    </div>
                @elseif($data != null && $data->mode == 1)
                    <div class="row">
                        <div class="col-auto" style="line-height: 40px; height: 40px; padding-right: 0">
                            勤務時間
                        </div>
                        <div class="col-auto"
                             style="line-height: 40px; height: 40px; font-weight: bold; font-size: 32pt">
                            {{$interval->format("%h:%I")}}
                        </div>
                        <div class="col-auto" style="line-height: 40px; height: 40px; padding-right: 0">
                            出勤時刻
                        </div>
                        <div class="col-auto"
                             style="line-height: 40px; height: 40px; font-weight: bold; font-size: 32pt">
                            {{$data->created_at->format("H:i")}}
                        </div>
                        <div class="col-auto" style="line-height: 40px; height: 40px; padding-right: 0">
                            退勤時刻
                        </div>
                        <div class="col-auto"
                             style="line-height: 40px; height: 40px; font-weight: bold; font-size: 32pt">
                            {{$data->updated_at->format("H:i")}}
                        </div>
                    </div>
                @else
                    <h5 class="" style="line-height: 40px">まだ出勤していません</h5>
                @endif
            </div>
            <div class="col-md-4">
                @if($data != null && $data->mode == 0)
                    <a class="btn btn-danger" onclick="leave()"
                       style="float: right; margin-left: 5px; width: 100px">退勤</a>
                @elseif($data != null && $data->mode == 1)
                    <a class="btn btn-secondary" href="/attends/cancel"
                       style="float: right; margin-left: 5px; width: 100px">退勤取消</a>
                @else
                    <a class="btn btn-primary" href="/attends/start"
                       style="float: right; margin-left: 5px; width: 100px">出勤</a>
                @endif
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
        </div>
        <hr>
        @if($data != null && $data->mode == 0)
            <div class="row">
                <div class="col-md-8">
                    <h5 style="line-height: 40px; height: 40px;">勤務内容を入力</h5>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary" id="saveBtn" onclick="saveComment()"
                            style="float: right; margin-left: 5px;">勤務詳細を保存
                    </button>
                </div>
            </div>
            <textarea id="textArea" class="form-control" style="min-height: 70vh; width: 100%; height: 100%"
                      placeholder="ここに勤務詳細を入力">{{$data->comment}}</textarea>
        @elseif($data != null && $data->mode == 1
)
            <div class="row">
                <div class="col-md-8">
                    <h5 style="line-height: 40px; height: 40px;">勤務内容を入力</h5>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary" id="saveBtn" onclick="saveComment()"
                            style="float: right; margin-left: 5px;">勤務詳細を保存
                    </button>
                </div>
            </div>
            <textarea id="textArea" class="form-control" style="min-height: 70vh; width: 100%; height: 100%"
                      placeholder="勤務内容が入力されていません">{{$data->comment}}</textarea>
        @endif
    </div>
    <!-- Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="modalHeader" aria-hidden="true">
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
        let startTime = '{{$data->created_at}}'
        const startDate = new Date(startTime)
        const currentDate = new Date()
        let diff = new Date(currentDate.getTime() - startDate.getTime() + 54000000)

        console.log(diff.toString())

        let modalHeader = document.getElementById("modalHeader")
        let modalContext = document.getElementById("modalContext")
        let primaryButton = document.getElementById("primaryButton")
        let secondaryButton = document.getElementById("secondaryButton")

        let mode = 0
        let reason = ''

        function primary() {
            if (mode === 3) {
                let dateText = currentDate.getFullYear() + '-' + (currentDate.getMonth() + 1) + '-' + currentDate.getDate()
                let diff2 = new Date(diff.getTime() - 1800000)
                const requestTime = diff2.getHours() + ':' + ('00' + diff2.getMinutes()).slice(-2)
                primaryButton.setAttribute("disabled", "")
                primaryButton.innerText = "申請しています"
                axios
                    .post("/request/create", {
                        dates: dateText,
                        type: 1,
                        reason: reason,
                        time: requestTime
                    })
                    .then(async (res) => {
                        location = "/attends/end"
                    });
                return
            }
            if (mode === 2) {
                mode = 3
                reason = document.getElementById('reasonTextArea').value
                let diff2 = new Date(diff.getTime() - 1800000)
                modalHeader.innerText = "申請確認"
                modalContext.innerHTML = '<div class="alert alert-primary" role="alert">' +
                    '以下の内容で残業申請を行います' +
                    '</div>' +
                    '<div class="mb-3 fw-bold">' +
                    '種別: 残業 / 申請時間: ' + diff2.getHours() + ':' + ('00' + diff2.getMinutes()).slice(-2) + '' +
                    '</div>' +
                    '<div class="mb-3">' +
                    '<label for="exampleFormControlTextarea1" class="form-label">理由</label>' +
                    '<textarea class="form-control" id="reasonTextArea" rows="3" placeholder="理由を記入してください" disabled>' + reason + '</textarea>' +
                    '</div>'
                primaryButton.innerText = "申請して退勤"
                secondaryButton.innerText = "戻る"
                return
            }
            if (mode === 1) {
                let diff2 = new Date(diff.getTime() - 1800000)
                modalHeader.innerText = "残業申請"
                modalContext.innerHTML = '<div class="mb-3">' +
                    '種別: 残業 / 申請時間: ' + diff2.getHours() + ':' + ('00' + diff2.getMinutes()).slice(-2) + ')' +
                    '</div>' +
                    '<div class="mb-3">' +
                    '<label for="exampleFormControlTextarea1" class="form-label">理由</label>' +
                    '<textarea class="form-control" id="reasonTextArea" rows="3" placeholder="理由を記入してください"></textarea>' +
                    '</div>'
                document.getElementById('reasonTextArea').value = reason
                primaryButton.innerText = "申請内容確認"
                secondaryButton.innerText = "戻る"
                mode = 2
            }
        }

        function secondary() {
            if (mode === 1) {
                location = "/attends/end"
            }
            if (mode === 2) {
                leaveModal()
            }
            if (mode === 3) {
                mode = 1
                primary()
            }
        }

        function leave() {
            if (leaveModal()) {
                jQuery('#cancelModal').modal("show");
            }
        }

        function leaveModal() {
            console.log("CURRENT: " + (diff.getTime() / 1000) + " / " + diff.getMinutes())
            if ((diff.getTime() / 1000) > 1000) { // 27000
                let diff2 = new Date(diff.getTime() - 1800000)
                console.log('残業あり')
                modalHeader.innerText = "退勤確認"
                modalContext.innerHTML = '<div class="text-center mb-3">' +
                    '時間外労働(' + diff2.getHours() + ':' + ('00' + diff2.getMinutes()).slice(-2) + ')が発生しています。<br>残業申請を行いますか?' +
                    '</div>'
                primaryButton.innerText = "残業申請"
                secondaryButton.innerText = "このまま退勤"
                mode = 1
                return true
            } else {
                console.log('残業無し')
                href = "/attends/end"
            }
            return false
        }

        function saveComment() {
            const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

            let saveBtn = document.getElementById("saveBtn")
            let textArea = document.getElementById("textArea")
            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "保存しています"

            axios
                .post("/api/v1/attends/comment/set", {
                    text: textArea.value
                })
                .then(async (res) => {
                    const resultCode = res.data.code
                    console.log("Result: " + resultCode)
                    if (resultCode == 0) {
                        saveBtn.className = "btn btn-success"
                        saveBtn.innerText = "保存しました"
                        await _sleep(1500)
                    } else {
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
