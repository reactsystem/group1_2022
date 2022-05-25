@extends('layouts.main')

@section('content')
    <div class="container text-center">
        <h1>
            確認
        </h1>
        <hr>
        <p class="fw-bold text-danger">
            以下のリンクはシステム外へのリンクである可能性があります。<br>
            アクセスする場合は以下のリンクをクリックしてください。
        </p>
        <a href="{{$data->url}}" class="font-14 fw-bold">
            {{$data->url}}
        </a>
    </div>
@endsection
