@extends('layouts.2colb')

@section('data')
    <style>
        li.active {
            background-color: #eee;
            color: #222;
            transition-duration: 0.3s;
        }

        .sidebar-list {
            background-color: rgba(0, 0, 0, 0);
            color: #eee;
            transition-duration: 0.15s;
            margin-top: 5px;
            margin-bottom: 5px;
            border-radius: 4px;
            padding: 2px 10px;
        }

        .sidebar-list:hover {
            background-color: #ddd;
            color: #222;
            transition-duration: 0.05s;
        }
    </style>
    <div class="container">
        <div class="row" style="width: 100%">
            <div class="col-lg-4 col-xl-3 col-md-12 text-dark d-lg-inline d-md-none d-sm-none d-none"
                 style="height: 100vh; background-color: #22;">
                <ul style="list-style: none; font-size: 16pt; font-weight: bold; cursor: pointer; margin-top: 80px; padding-left: 0; position: fixed">
                    <li class="sidebar-list<?php if (Request::is('admin')) {
                        echo ' active';
                    }?>" onclick="href('/admin')"><span style="color: #888;">●</span> 管理者CPトップ
                    </li>
                    <li class="sidebar-list<?php if (Request::is('admin/attends*')) {
                        echo ' active';
                    }?>" onclick="href('/admin/attends')"><span style="color: #888;">●</span> 社員情報管理
                    </li>
                    <li class="sidebar-list<?php if (Request::is('admin/attend-manage*')) {
                        echo ' active';
                    }?>" onclick="href('/admin/attend-manage')"><span style="color: #888;">●</span> 勤怠情報管理
                    </li>
                    <li class="sidebar-list<?php if (Request::is('admin/request*')) {
                        echo ' active';
                    }?>" onclick="href('/admin/request')"><span style="color: #888;">●</span> 各種申請管理
                    </li>
                    <li class="sidebar-list<?php if (Request::is('admin/settings*')) {
                        echo ' active';
                    }?>" onclick="href('/admin/settings')"><span style="color: #888;">●</span> システム設定
                    </li>
                    <li class="sidebar-list<?php if (Request::is('home')) {
                        echo ' active';
                    }?>" onclick="href('/home')"><span style="color: #888;">●</span> 勤怠システムトップ
                    </li>
                </ul>
            </div>
            <div class="col-lg-8 col-xl-9 bg-light" style="height: 100%; min-height: 100vh">
                <div style="margin-top: 80px">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script>
        function href(url) {
            location = url
        }
    </script>
@endsection
