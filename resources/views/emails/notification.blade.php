<h2>{{$mail_param['title']}}</h2>
<h6 style="color: #888">作成日時: {{$push_data['created_at']}}</h6>
<hr>
{{ $mail_param['data'] }}<br>
<hr>
<a href="{{url('/')}}/account/notifications/{{$push_data['id']}}">通知を詳しく見るにはここをクリック</a><br>
<br>
{{config('app.name', '勤怠管理システム')}}
