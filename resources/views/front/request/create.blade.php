@extends('layouts.main')
@section('pageTitle', "各種申請")
@section('styles')
    <link href="{{ asset('css/air-datepicker.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">新規申請</h2>
            </div>
            <div class="col-md-6">
                <a href="/request" class="btn btn-secondary float-right">キャンセル</a>
            </div>
        </div>
        <hr>
        <form action="/request/create" method="post" id="requestForm">
            @csrf
            <div class="row">
                <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                    <label class="form-label">申請を行う日付を選択</label>
                    <input type="text"
                           name="dates"
                           id="requestDate"
                           class="form-control"
                           data-language='en'
                           data-multiple-dates-separator=", "
                           placeholder="クリックして日付を選択"
                           value="{{old('dates')}}"
                           data-position='top left' readonly/>
                    <div class="form-text">最大10日までまとめて選択できます</div>
                </div>
                <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                    <label for="exampleInputEmail1" class="form-label">申請種別</label>
                    <select name="type" class="form-select" aria-label="Default select example" id="requestType">
                        <option selected>選択してください</option>
                        @foreach($types as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 col-lg-4 col-md-6 col-sm-12" style="display: none" id="workTime">
                    <label class="form-label">労働時間</label>
                    <input name="time" id="requestTime" type="time" class="form-control" placeholder="0:00"
                           value="{{old('time')}}"/>
                    <div class="form-text">分単位で入力できます</div>
                </div>
                <div class="mb-3 col-lg-4 col-md-6 col-sm-12" style="display: none" id="workTime2">
                    <label class="form-label">退勤時刻</label>
                    <input name="time" id="requestTime2" type="time" class="form-control" placeholder="0:00"
                           value="{{old('time')}}"/>
                </div>
                <div class="mb-3 col-sm-12">
                    <label class="form-label">申請理由</label>
                    <textarea name="reason" id="requestReason"
                              class="form-control height-50vh">{{old('reason')}}</textarea>
                </div>
            </div>
        </form>
        <div class="float-right">
            <button id="proceedButton" class="btn btn-primary" onclick="check()" disabled>次へ</button>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="cancelModalLabel">申請確認</h5>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        以下の内容で申請を行います。
                    </div>
                    <div id="checkForm"></div>
                </div>
                <div class="modal-footer">
                    <div class="margin-0-auto">
                        <a type="button" class="btn btn-primary" id="submitButton" onclick="submit()">申請</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('js/air-datepicker.js')}}">
    </script>
    <script defer>
        let typeSelected = false
        let timeAvailable = <?php echo(isset($reqDate) ? "true" : "false");?>;
        let workTimeAvailable = false
        let reasonAvailable = false
        let sendData = false

        const isNumber = function (value) {
            return ((typeof value === 'number') && (isFinite(value)));
        };

        const types = [
                @foreach($types as $type)
            ['{{$type->name}}', {{$type->type}}],
            @endforeach
        ]
        let proceedButton = document.getElementById('proceedButton');
        let requestDate = document.getElementById('requestDate');
        let requestTime = document.getElementById('requestTime');
        let requestTime2 = document.getElementById('requestTime2');
        let requestReason = document.getElementById('requestReason');
        let requestForm = document.getElementById('requestForm');
        let checkPassed = false

        function submit() {
            if (!checkPassed || sendData) return
            checkPassed = false
            sendData = true
            let submitButton = document.getElementById('submitButton');
            submitButton.setAttribute("disabled", "")
            submitButton.className = "btn btn-primary disabled"
            submitButton.innerText = "申請しています..."
            requestForm.submit()
        }

        function check() {
            let requestType = document.getElementById('requestType');
            checkPassed = false
            if (!isFinite(requestType.value) || requestType.value <= 0) {
                return
            }
            let checkForm = document.getElementById('checkForm');
            let submitButton = document.getElementById('submitButton');

            submitButton.className = "btn btn-primary"
            submitButton.innerText = "申請"

            let v1 = 0
            const reqDates = requestDate.value.split(',')
            let req2 = []

            reqDates.forEach(element => {
                    console.log("DATE: " + element)
                    const dateData = new Date(element)
                    req2[req2.length] = dateData.getFullYear() + '年' + (dateData.getMonth() + 1) + '月' + (dateData.getDate()) + '日'
                    console.log('WRITE: ' + req2[req2.length - 1])
                }
            );

            let message = '<b>申請する日付:</b> ' + req2.join(', ') + '<br>' +
                '<b>種別:</b> ' + types[requestType.value - 1][0] + '<br>'
            if (types[requestType.value - 1][1] === 1) {
                if (requestTime.value !== "") {
                    message += '<b>時間:</b> ' + requestTime.value + '<br>'
                } else {
                    submitButton.className = "btn btn-danger disabled"
                    submitButton.innerText = "申請できません"
                    v1++
                    message += '<b>時間:</b> <span style="color: #FFF; background-color: #900">時間が指定されていません</span><br>'
                }
            }
            if (types[requestType.value - 1][1] === -1) {
                if (requestTime2.value !== "") {
                    message += '<b>退勤時刻:</b> ' + requestTime2.value + '<br>'
                } else {
                    submitButton.className = "btn btn-danger disabled"
                    submitButton.innerText = "申請できません"
                    v1++
                    message += '<b>退勤時刻:</b> <span style="color: #FFF; background-color: #900">退勤時刻が指定されていません</span><br>'
                }
            }
            if (types[requestType.value - 1][1] === 2) {
                if (reqDates.length > {{\App\Models\PaidHoliday::getHolidays(Auth::id())}}) {
                    v1++
                    submitButton.className = "btn btn-danger disabled"
                    submitButton.innerText = "申請できません"
                    message += '<b>有給消費:</b> <span style="color: #FFF; background-color: #B33"> ' + reqDates.length + '日(残り{{\App\Models\PaidHoliday::getHolidays(Auth::id())}}日) </span> <span style="color: #C00">&nbsp;有給消費が残日数を超えています</span><br>'
                } else {
                    message += '<b>有給消費:</b> ' + reqDates.length + '日(残り{{\App\Models\PaidHoliday::getHolidays(Auth::id())}}日)<br>'
                }
            }
            if (types[requestType.value - 1][1] !== 2 && types[requestType.value - 1][1] !== 3 && types[requestType.value - 1][1] !== -1 && requestReason.value === "") {
                submitButton.className = "btn btn-danger disabled"
                submitButton.innerText = "申請できません"
                v1++
                message += '<b>理由:</b> <span style="color: #FFF; background-color: #900">理由を入力してください</span><br>'
            } else if (requestReason.value !== "") {
                message += '<b>理由:</b> ' + requestReason.value + '<br>'
            }
            checkPassed = v1 === 0

            checkForm.innerHTML = message
            jQuery('#cancelModal').modal("show");
        }

        requestTime.onchange = function () {
            workTimeAvailable = (requestTime.value !== "" && requestTime.value !== "00:00");
            console.log('WORKTIME: ' + requestTime.value + ' / ' + workTimeAvailable)
            checkData()
        }

        requestTime2.onchange = function () {
            workTimeAvailable = (requestTime2.value !== "");
            console.log('WORKTIME: ' + requestTime2.value + ' / ' + workTimeAvailable)
            checkData()
        }
        requestReason.onchange = function () {
            reasonAvailable = requestReason.value !== "";
            console.log('REASON: ' + requestReason.value)
            checkData()
        }

        let requestType = document.getElementById('requestType');
        let workTime = document.getElementById('workTime');
        let workTime2 = document.getElementById('workTime2');
        requestType.onchange = function () {
            console.log('DATA: ' + requestType.value + ' / ' + isFinite(requestType.value))
            if (requestType.value !== 0 && isFinite(requestType.value)) {
                typeSelected = true
                checkData()
            } else {
                typeSelected = false
                checkData()
                workTime.style.display = "none"
                return
            }
            if (types[requestType.value - 1][1] === 1) {
                workTime.style.display = "inline"
                requestTime2.value = ""
                workTime2.style.display = "none"
            } else if (types[requestType.value - 1][1] === -1) {
                workTime.style.display = "none"
                requestTime.value = ""
                workTime2.style.display = "inline"
            } else {
                workTime.style.display = "none"
                workTime2.style.display = "none"
                requestTime.value = ""
            }
        };

        function checkData() {
            console.log('Type: ' + typeSelected + ' / Time: ' + timeAvailable + ' / Work: ' + workTimeAvailable + ' / Reason: ' + reasonAvailable)
            if (typeSelected && timeAvailable && ((types[requestType.value - 1][1] !== 1 && types[requestType.value - 1][1] !== -1) || workTimeAvailable) && (types[requestType.value - 1][1] === 2 || types[requestType.value - 1][1] === 3 || types[requestType.value - 1][1] === -1 || reasonAvailable)) {
                proceedButton.removeAttribute("disabled")
            } else {
                proceedButton.setAttribute("disabled", "")
            }
        }

        if ('{{$reqDate}}' === '') {
            timeAvailable = (requestDate.value + '') !== '';
            console.log('TIME: ' + requestDate.value)
            checkData()
        }

        const localeEs = {
            days: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
            daysShort: ['日曜', '月曜', '火曜', '水曜', '木曜', '金曜', '土曜'],
            daysMin: ['日', '月', '火', '水', '木', '金', '土'],
            months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            monthsShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            today: '今日',
            clear: 'クリア',
            dateFormat: 'yyyy-MM-dd',
            timeFormat: 'HH:mm',
            firstDay: 0
        };
        const maxDateValue = new Date()
        maxDateValue.setDate(maxDateValue.getDate() + 365)
        const minDateValue = new Date()
        minDateValue.setDate(minDateValue.getDate() - 90)
        const dateStr = "{{$reqDate ?? ""}}";
        let year = 2022
        let month = 1
        let day = 1
        if (dateStr !== "") {
            console.log("DATE STR: " + dateStr)
            const dateData = dateStr.split("-")
            year = parseInt(dateData[0])
            month = parseInt(dateData[1])
            day = parseInt(dateData[2])
            console.log("YEAR: " + year + " MONTH: " + month + " DAY: " + day)
        }
        const date = new Date(dateStr)

        new AirDatepicker('#requestDate', {
            locale: localeEs,
            multipleDates: 10,
            selectedDates: [date],
            todayButton: new Date({{$reqDate ?? ""}}),
            minDate: minDateValue,
            maxDate: maxDateValue,
            onRenderCell: function (date, cellType) {
                if (cellType === 'day') {
                    const day = date.getDay(),
                        isDisabled = disabledDays.indexOf(day) !== -1
                    return {
                        disabled: isDisabled
                    }
                }
            },
            onSelect({date}) {
                timeAvailable = (date + '') !== ''
                console.log('TIME: ' + date)
                checkData()
            }
        });
    </script>
@endsection
