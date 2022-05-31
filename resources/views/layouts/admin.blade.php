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
            transform: scale(1.1);
        }

        body {
            overflow-x: hidden;
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
                position: fixed;
                transform: scale(0.985);
                opacity: 0.0;
            }

            .sidebar-base {
                background-color: #222;
                height: 0;
                z-index: -2;
                opacity: 0.0;
                transition-duration: 0.3s;
                pointer-events: none;
            }

            .sidebar-base2 {
                background-color: #222;
                z-index: 1;
                opacity: 1.0;
                transition-duration: 0.3s;
            }

            .main-card {
                border-top-left-radius: 20px;
                /*border-top-right-radius: 20px;*/
                transition-duration: 0.4s;
                transition-timing-function: cubic-bezier(0.15, 1.07, 0.76, 0.98);
            }

            .main-card2 {
                filter: brightness(0.5);
                border-top-left-radius: 20px;
                /*border-top-right-radius: 20px;*/
                transform: translateX(max(40vw, 300px));
                transition-duration: 0.4s;
                transition-timing-function: cubic-bezier(0.15, 1.07, 0.76, 0.98);
                user-select: none;
                cursor: pointer;
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
                /*transform: translateX(0);*/
                transition-duration: 0.2s;
            }

            .main-card2 {
                border-top-left-radius: 20px;
                /*border-top-right-radius: 20px;*/
                /*transform: translateX(0);*/
                transition-duration: 0.2s;
            }
        }

        .basement {
            transition-duration: 0.0s;
        }
    </style>
    <div class="container">
        <div class="row layout-base" style="width: 100%; margin-left: 0">
            <div id="sidebarBase" class="col-lg-4 col-xl-3 col-md-12 text-dark sidebar-base">
                <ul class="sidebar-data" id="sidebarData">
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
                    <li class="pointer-nonevent">
                        <hr class="bg-gray2">
                    </li>
                    <li class="sidebar-list<?php if (Request::is('home')) {
                        echo ' active';
                    }?>" onclick="href('/home')"><span style="color: #888;">●</span> 勤怠システムトップ
                    </li>
                </ul>
            </div>
            <div class="col-lg-8 col-xl-9 bg-light main-card" id="mainCard"
                 style="height: 100%; min-height: 100vh; transition-duration: 0.0s">
                <div style="margin-top: 80px" class="basement" id="basement">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <div id="loading"
         style="transition-duration: 0.5s; position: fixed; top: 0; left: 0; min-width: 100vw; min-height: 100vh; background-color: #222; z-index: 10">
        <span
            style="position: fixed; margin: 0 auto; top: 45%; width: 100vw; text-align: center; color: #bbb; font-size: 40pt">
            Loading...
        </span>
    </div>
    <script>

        const loading = document.getElementById('loading')
        const sectionTitle = document.getElementById('sectionTitle')
        const sectionTitle2 = document.getElementById('sectionTitle2')
        const sectionTitle3 = document.getElementById('sectionTitle3')
        const sidebarData = document.getElementById('sidebarData')
        const basement = document.getElementById('basement')
        const sidebarBase = document.getElementById('sidebarBase')
        const mainCard = document.getElementById('mainCard')
        const _sleepX = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

        window.onload = async () => {
            await _sleepX(100)
            loading.style.pointerEvents = "none"
            await _sleepX(200)
            loading.style.opacity = 0.0
            mainCard.style.transitionDuration = null
            sectionTitle.style.transitionDuration = "0.4s"
            sectionTitle2.style.transitionDuration = "0.2s"
            await _sleepX(500)
            loading.style.display = "none"
        }

        sectionTitle.ontransitionend = () => {
            let classList = sidebarBase.classList
            const data = classList.item(classList.length - 1)
            if (data === 'sidebar-base2') {
                //sidebarBase.style.zIndex = 1
            } else {
                //sidebarBase.style.zIndex = -1
                mainCard.style.zIndex = null
                mainCard.style.opacity = 1.0
                mainCard.style.userSelect = null
                mainCard.style.pointerEvents = null
            }
        };

        mainCard.onclick = function () {
            let classList = sidebarBase.classList
            let mainCardClassList = mainCard.classList
            const data = classList.item(classList.length - 1)
            if (data === 'sidebar-base2') {
                mainCard.style.filter = null
                sectionTitle2.style.opacity = 1.0
                sectionTitle2.style.transform = "translateX(0px)"
                sectionTitle.style.transform = "translateX(0px)"
                sidebarData.style.transform = "scale(0.985)"
                sidebarData.style.opacity = null
                sectionTitle3.style.opacity = 0.0
                sectionTitle3.style.transform = null
                basement.style.pointerEvents = null
                classList.replace("sidebar-base2", "sidebar-base")
                mainCardClassList.replace("main-card2", "main-card")
            }
        }

        sectionTitle.onclick = function () {
            let classList = sidebarBase.classList
            let mainCardClassList = mainCard.classList
            const data = classList.item(classList.length - 1)
            if (data === 'sidebar-base2') {
                mainCard.style.filter = null
                sectionTitle2.style.opacity = 1.0
                sectionTitle2.style.transform = "translateX(0px)"
                sectionTitle.style.transform = "translateX(0px)"
                sidebarData.style.transform = "scale(0.985)"
                sidebarData.style.opacity = null
                sectionTitle3.style.opacity = 0.0
                sectionTitle3.style.transform = null
                basement.style.pointerEvents = null
                classList.replace("sidebar-base2", "sidebar-base")
                mainCardClassList.replace("main-card2", "main-card")
            } else {
                sectionTitle2.style.opacity = 0.0
                sectionTitle2.style.transform = "translateX(20px)"
                sidebarData.style.opacity = 1.0
                sectionTitle3.style.opacity = 1.0
                sectionTitle3.style.transform = "translateX(-25px)"
                sidebarData.style.transform = "scale(1.0)"
                sectionTitle.style.transform = "translateX(30px)"
                basement.style.pointerEvents = "none"
                classList.replace("sidebar-base", "sidebar-base2")
                mainCardClassList.replace("main-card", "main-card2")
            }
        }
    </script>
@endsection
