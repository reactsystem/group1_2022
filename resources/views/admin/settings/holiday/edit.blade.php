@extends('layouts.admin')

@section('pageTitle', "システム設定")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h2 class="fw-bold">休日編集</h2>
            </div>
            <div class="col-md-8">
                <button type="button" onclick="saveHolidayData()" class="btn btn-primary float-right width-150"
                        id="saveBtn">
                    保存
                </button>
                <button class="btn btn-danger float-right mr-10px" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    削除
                </button>
                <a href="/admin/settings/holiday" class="btn btn-secondary float-right mr-10px">休日一覧に戻る</a>
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
                       value="{{$data->name}}"
                >
            </div>
            <div class="col-md-12 col-lg-6">
                <label for="monthInput" class="form-label">年</label>
                <input type="number" id="yearInput" class="form-control" placeholder="毎年"
                       value="{{$data->year}}" min="2000" max="9999">
            </div>
            <div class="col-md-12 col-lg-6">
                <label for="monthInput" class="form-label">月</label>
                <input type="number" id="monthInput" class="form-control" placeholder="毎月"
                       value="{{$data->month}}" min="1" max="12">
            </div>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="dayInput" class="form-label">日</label>
                <input type="number" id="dayInput" class="form-control" placeholder="--"
                       value="{{$data->day}}" min="1" max="31">
            </div>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="status" class="form-label">種別</label>
                <select class="form-select" aria-label="" id="status">
                    <option value="0" <?php
                    /* @var $data */
                    if($data->mode == 0){?>selected<?php }?>>有給
                    </option>
                    <option value="1" <?php if($data->mode == 1){?>selected<?php }?>>無給</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">削除確認</h5>
                </div>
                <div class="modal-body">
                    休日を削除してもよろしいですか?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <a href="/admin/settings/holiday/delete/{{$id}}" type="button" class="btn btn-danger">削除</a>
                </div>
            </div>
        </div>
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

        function saveHolidayData() {
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
            saveBtn.innerText = "保存しています"

            alert.innerHTML = ""

            axios
                .post("/admin/settings/holiday/edit/{{$id}}", {
                    name: nameInput.value,
                    year: yearInput.value,
                    month: monthInput.value,
                    day: dayInput.value,
                    mode: status.value,
                })
                .then(async (res) => {
                    const resultCode = res.data.code
                    // DISABLED - console.log("Result: " + resultCode + " / " + res.data.message)
                    if (resultCode == 0) {
                        saveBtn.className = "btn btn-success float-right width-150"
                        saveBtn.innerText = "保存しました"
                        alert.innerHTML = '<div class="alert alert-success" role="alert">' +
                            '<strong>成功</strong> - 保存が完了しました。' +
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
                        saveBtn.className = "btn btn-danger float-right width-150"
                        saveBtn.innerText = "保存失敗"
                        await _sleep(2000)
                    }
                    saveBtn.removeAttribute("disabled")
                    saveBtn.className = "btn btn-primary float-right width-150"
                    saveBtn.innerText = "保存"
                })
        }
    </script>
@endsection
