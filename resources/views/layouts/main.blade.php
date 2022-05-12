@extends('layouts.2col')

@section('data')
    <style>
        li.active {
            background-color: #222;
            color: #fff;
            transition-duration: 0.3s;
        }

        .sidebar-list {
            background-color: rgba(0, 0, 0, 0);
            color: #222;
            transition-duration: 0.15s;
            margin-top: 5px;
            padding-top: 2px;
            margin-bottom: 5px;
            padding-bottom: 2px;
            border-radius: 4px;
            padding-left: 10px;
        }

        .sidebar-list:hover {
            background-color: #444;
            color: #fff;
            transition-duration: 0.05s;
        }
    </style>
    <div class="container">
        <div class="row" style="width: 100%">
            <div class="col-lg-3 col-md-12 text-dark d-none d-sm-none d-lg-inline-block"
                 style="height: 100vh; background-color: #BBB;">
                <ul style="list-style: none; font-size: 16pt; font-weight: bold; cursor: pointer; margin-top: 80px; padding-left: 0; position: fixed; width: 13rem">
                    <li class="sidebar-list<?php if (Request::is('home')) {
                        echo ' active';
                    }?>" onclick="href('/home')"><span style="color: #888;">●</span> トップページ
                    </li>
                    <li class="sidebar-list<?php if (Request::is('attends*')) {
                        echo ' active';
                    }?>" onclick="href('/attends')"><span style="color: #888;">●</span> 出勤・退勤入力
                    </li>
                    <li class="sidebar-list<?php if (Request::is('attend-manage*')) {
                        echo ' active';
                    }?>" onclick="href('/attend-manage')"><span style="color: #888;">●</span> 勤怠情報確認
                    </li>
                    <li class="sidebar-list<?php if (Request::is('request*')) {
                        echo ' active';
                    }?>" onclick="href('/request')"><span style="color: #888;">●</span> 各種申請
                    </li>
                    <li class="sidebar-list<?php if (Request::is('account*')) {
                        echo ' active';
                    }?>" onclick="href('/account')"><span style="color: #888;">●</span> ユーザー管理
                    </li>
                    <li class="sidebar-list<?php if (Request::is('admin')) {
                        echo ' active';
                    }?>" onclick="href('/admin')"><span style="color: #888;">●</span> 管理者CP
                    </li>
                </ul>
            </div>
            <div class="col-lg-9 col-md-12 bg-light" style="height: 100%; min-height: 100vh">
                <div style="margin-top: 80px">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script>
        function href(url){
            location = url
        }
    </script>
@endsection
