@extends('layouts.2colb')

@section('styles_basic')
    <style>
        @media screen and (max-width: 991.999px) {
            #app {
                background-color: #222;
            }
        }

        @media screen and (min-width: 992px) {
        }
    </style>
@endsection

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

        @media screen and (max-width: 991.9999px) {
            .sidebar-data {
                list-style: none;
                font-size: 16pt;
                font-weight: bold;
                cursor: pointer;
                margin-top: 80px;
                padding-left: 0;
                transition-duration: 0.3s;
            }

            .sidebar-base {
                background-color: #222;
                height: 0;
                transition-duration: 0.3s;
            }

            .sidebar-base2 {
                background-color: #222;
                height: 370px;
                transition-duration: 0.3s;
            }

            .main-card {
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
            }
        }

        @media screen and (min-width: 992px) {
            .sidebar-data {
                list-style: none;
                font-size: 16pt;
                font-weight: bold;
                cursor: pointer;
                margin-top: 80px;
                padding-left: 0;
                position: fixed;
                transition-duration: 0.3s;
            }

            .sidebar-base {
                height: 100vh;
                background-color: #222;
                transition-duration: 0.3s;
            }

            .sidebar-base2 {
                height: 100vh;
                background-color: #222;
                transition-duration: 0.3s;
            }

            .main-card {
                border-radius: 0;
            }
        }
    </style>
    <div class="container">
        <div class="row" style="width: 100%">
            <div id="sidebarBase" class="col-lg-4 col-xl-3 col-md-12 text-dark sidebar-base">
                <ul class="sidebar-data">
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
            <div class="col-lg-8 col-xl-9 bg-light main-card" style="height: 100%; min-height: 100vh">
                <div style="margin-top: 80px">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script>

        const sectionTitle = document.getElementById('sectionTitle')
        const sidebarBase = document.getElementById('sidebarBase')

        sectionTitle.onclick = function () {
            let classList = sidebarBase.classList
            const data = classList.item(classList.length - 1)
            if (data === 'sidebar-base2') {
                sectionTitle.style.translate = "rotate(0deg)"
                sectionTitle.innerText = "▶"
                classList.replace("sidebar-base2", "sidebar-base")
            } else {
                sectionTitle.style.translate = "rotate(90deg)"
                sectionTitle.innerText = "▼"
                classList.replace("sidebar-base", "sidebar-base2")
            }
        }

        function href(url) {
            location = url
        }
    </script>
@endsection
