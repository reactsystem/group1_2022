@extends('layouts.admin')

@section('styles')
    <style>
        .attends-row {
            transition-duration: 0.2s;
            cursor: pointer;
        }

        .attends-row:hover {
            transition-duration: 0.05s;
            box-shadow: 0 0 10px #999;
            background-color: #0b5ed7;
            color: #fff;
        }
    </style>
@endsection
@section('pageTitle', "システム設定")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">各種情報編集</h2>
            </div>
            <div class="col-md-6">
                <button onclick="saveGeneralConfig()" id="saveBtn" class="btn btn-primary"
                        style="float: right; width: 150px">保存
                </button>
                <a href="/admin/settings/general" class="btn btn-secondary" style="float: right; margin-right: 10px">設定確認へ戻る</a>
            </div>
            <div class="col-md-12 mt-3" id="alert">
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
        <div class="row">
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="startInput" class="form-label">始業時刻</label>
                <input type="time" class="form-control" id="startInput" placeholder="未設定"
                       value="{{substr(($data->start ?? "09:30:00"), 0, 5)}}"
                >
            </div>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="endInput" class="form-label">終業時刻</label>
                <input type="time" class="form-control" id="endInput" placeholder="未設定"
                       value="{{substr(($data->end ?? "18:00:00"), 0, 5)}}"
                >
            </div>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="startInput" class="form-label">休憩時間初期値</label>
                <input type="time" class="form-control" id="restInput" placeholder="未設定"
                       value="{{substr(($data->rest ?? "00:45:00"), 0, 5)}}"
                >
            </div>
            {{--
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="endInput" class="form-label">休憩(残業)</label>
                <input type="time" class="form-control" id="restOverInput" placeholder="未設定"
                       value="{{substr(($data->rest_over ?? "00:15:00"), 0, 5)}}"
                >
            </div>  --}}
            <div class="mb-3">
                <label for="formFile" class="form-label">有給設定CSV</label>
                <input class="form-control" type="file" id="holidaysConfig">
            </div>
            <div class="mb-3">
                <a href="/admin/settings/general/download" class="btn btn-success">デフォルトの有給設定CSVをダウンロード</a>
            </div>
        </div>
    </div>

    <script>
        let startTime = '{{$data->created_at ?? ""}}'
        const startDate = new Date(startTime)
        const currentDate = new Date()
        let diff = new Date(currentDate.getTime() - startDate.getTime() + 54000000)

        let alert = document.getElementById("alert")
        const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

        function saveGeneralConfig() {

            let saveBtn = document.getElementById("saveBtn")

            let startInput = document.getElementById("startInput")
            let endInput = document.getElementById("endInput")
            let restInput = document.getElementById("restInput")
            //let restOverInput = document.getElementById("restOverInput")
            let holidaysConfig = document.getElementById("holidaysConfig")

            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "保存しています"

            alert.innerHTML = ""

            console.log(holidaysConfig.files[0])

            const params = new FormData();

            params.append("start", startInput.value)
            params.append("end", endInput.value)
            params.append("rest", restInput.value)
            //params.append("rest_over", restOverInput.value)
            params.append("paid_holiday", holidaysConfig.files[0])

            axios
                .post("/admin/settings/general/edit",
                    params,
                    {
                        headers: {
                            'content-type': 'multipart/form-data',
                        },
                    })
                .then(async (res) => {
                    const resultCode = res.data.code
                    console.log("Result: " + resultCode + " / " + res.data.message)
                    if (resultCode == 0) {
                        saveBtn.className = "btn btn-success"
                        saveBtn.innerText = "保存しました"
                        alert.innerHTML = '<div class="alert alert-success" role="alert">' +
                            '<strong>成功</strong> - ' + res.data.message +
                            '</div>'
                        await _sleep(1500)
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
                    saveBtn.innerText = "保存"
                })
        }
    </script>
@endsection
