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
        <form>
            <div class="row">
                <div class="col-md-4">
                    <h2 class="fw-bold">休日追加</h2>
                </div>
                <div class="col-md-8">
                    <button type="button" onclick="createHolidayData()" class="btn btn-primary"
                            style="float: right; width: 150px;"
                            id="saveBtn">
                        追加
                    </button>
                    <a href="/admin/settings/holiday" class="btn btn-secondary"
                       style="float: right; margin-right: 10px;">休日一覧に戻る</a>
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
                    <label for="dateInput" class="form-label">名称</label>
                    <input type="text" class="form-control" id="nameInput" placeholder="名称を入力してください"
                    >
                </div>
                <div class="col-md-12 col-lg-6">
                    <label for="monthInput" class="form-label">年</label>
                    <input type="number" id="yearInput" class="form-control" placeholder="毎年" min="2000" max="9999">
                </div>
                <div class="col-md-12 col-lg-6">
                    <label for="monthInput" class="form-label">月</label>
                    <input type="number" id="monthInput" class="form-control" placeholder="毎月" min="1" max="12">
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="dayInput" class="form-label">日</label>
                    <input type="number" id="dayInput" class="form-control" placeholder="--" min="1" max="31">
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="status" class="form-label">種別</label>
                    <select class="form-select" aria-label="" id="status">
                        <option value="0" selected>有給</option>
                        <option value="1">無給</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <script>
        let startTime = '{{$data->created_at ?? ""}}'
        const startDate = new Date(startTime)
        const currentDate = new Date()
        let diff = new Date(currentDate.getTime() - startDate.getTime() + 54000000)
        let yearInput = document.getElementById("yearInput")
        let monthInput = document.getElementById("monthInput")

        function clearYear() {
            yearInput.value = ""
        }

        function clearMonth() {
            monthInput.value = ""
        }

        function createHolidayData() {
            const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

            let saveBtn = document.getElementById("saveBtn")
            let textArea = document.getElementById("textArea")

            let nameInput = document.getElementById("nameInput")
            let yearInput = document.getElementById("yearInput")
            let monthInput = document.getElementById("monthInput")
            let dayInput = document.getElementById("dayInput")
            let status = document.getElementById("status")
            let alert = document.getElementById("alert")

            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "追加しています"

            alert.innerHTML = ""

            axios
                .post("/admin/settings/holiday/new", {
                    name: nameInput.value,
                    year: yearInput.value,
                    month: monthInput.value,
                    day: dayInput.value,
                    mode: status.value,
                })
                .then(async (res) => {
                    const resultCode = res.data.code
                    console.log("Result: " + resultCode + " / " + res.data.message)
                    if (resultCode == 0) {
                        saveBtn.className = "btn btn-success"
                        saveBtn.innerText = "保存しました"
                        alert.innerHTML = '<div class="alert alert-success" role="alert">' +
                            '<strong>成功</strong> - 休日を追加しました。編集ページに移動しています...' +
                            '</div>'
                        await _sleep(1000)
                        location = "/admin/settings/holiday/edit/" + res.data.id
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
                        saveBtn.innerText = "追加失敗"
                        await _sleep(2000)
                    }
                    saveBtn.removeAttribute("disabled")
                    saveBtn.className = "btn btn-primary"
                    saveBtn.innerText = "追加"
                })
        }
    </script>
@endsection
