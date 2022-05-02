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
            transition-duration: 0.2s;
            margin-top: 5px;
            padding-top: 2px;
            margin-bottom: 5px;
            padding-bottom: 2px;
            border-radius: 8px;
            padding-left: 10px;
        }

        .sidebar-list:hover {
            background-color: #444;
            color: #fff;
            transition-duration: 0.1s;
        }
    </style>
    <div class="container">
        <div class="row" style="width: 100%">
            <div class="col-md-3 text-dark" style="height: 100vh; background-color: #BBB;">
                <ul style="list-style: none; font-size: 18pt; font-weight: bold; cursor: pointer; margin-top: 80px;">
                    <li class="sidebar-list<?php if(Request::is('top')){ echo ' active'; }?>"><span style="color: #888;">●</span> トップページ</li>
                    <li class="sidebar-list<?php if(Request::is('attend')){ echo ' active'; }?>"><span style="color: #888;">●</span> 出勤・退勤入力</li>
                    <li class="sidebar-list<?php if(Request::is('attend-manage')){ echo ' active'; }?>"><span style="color: #888;">●</span> 勤怠情報確認</li>
                    <li class="sidebar-list<?php if(Request::is('request')){ echo ' active'; }?>"><span style="color: #888;">●</span> 各種申請</li>
                    <li class="sidebar-list<?php if(Request::is('account')){ echo ' active'; }?>"><span style="color: #888;">●</span> ユーザー管理</li>
                    <li class="sidebar-list<?php if(Request::is('admin')){ echo ' active'; }?>"><span style="color: #888;">●</span> 管理者CP</li>
                </ul>
            </div>
            <div class="col-md-9 bg-light" style="height: 100vh">
                <div style="margin-top: 80px">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endsection
