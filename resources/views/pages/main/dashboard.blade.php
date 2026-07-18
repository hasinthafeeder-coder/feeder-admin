@extends('layout_main.app')

@section('content')
    <style>
        @media (max-width: 767.98px) {

            .top-selling-filter-card,
            .top-selling-filter-row {
                width: 100%;
            }

            .top-selling-filter-hint {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .top-selling-filter-form {
                width: 100%;
                flex-wrap: wrap !important;
                gap: 0.75rem !important;
            }

            .top-selling-date-field {
                width: 100%;
            }

            .top-selling-date-control,
            .top-selling-filter-input {
                width: 100%;
            }

            .top-selling-filter-actions {
                width: 100%;
                flex-wrap: nowrap !important;
            }

            .top-selling-filter-actions .btn {
                flex: 1 1 50%;
                width: 50%;
                min-width: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                white-space: nowrap;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <!-- Start Header Area -->
            <header class="header-area bg-white mb-4 rounded-10 border border-white" id="header-area">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="left-header-content">
                            <ul
                                class="d-flex align-items-center ps-0 mb-0 list-unstyled justify-content-center justify-content-md-start">
                                <li class="d-xl-none">
                                    <button class="header-burger-menu bg-transparent p-0 border-0 position-relative top-3"
                                        id="header-burger-menu">
                                        <span class="border-1 d-block for-dark-burger"
                                            style="
                          border-bottom: 1px solid #475569;
                          height: 1px;
                          width: 25px;
                        "></span>
                                        <span class="border-1 d-block for-dark-burger"
                                            style="
                          border-bottom: 1px solid #475569;
                          height: 1px;
                          width: 25px;
                          margin: 6px 0;
                        "></span>
                                        <span class="border-1 d-block for-dark-burger"
                                            style="
                          border-bottom: 1px solid #475569;
                          height: 1px;
                          width: 25px;
                        "></span>
                                    </button>
                                </li>
                                <li>
                                    <form class="src-form position-relative">
                                        <input type="text" class="form-control" placeholder="Search here..." />
                                        <div
                                            class="src-btn position-absolute top-50 start-0 translate-middle-y bg-transparent p-0 border-0">
                                            <span class="material-symbols-outlined">search</span>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="right-header-content mt-3 mt-md-0">
                            <ul
                                class="d-flex align-items-center justify-content-center justify-content-md-end ps-0 mb-0 list-unstyled">
                                <li class="header-right-item language-item">
                                    <div class="dropdown notifications language">
                                        <button class="btn btn-secondary dropdown-toggle border-0 p-0 position-relative"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="material-symbols-outlined" style="font-size: 19px">translate</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-lg p-0 border-0 dropdown-menu-end">
                                            <span class="fw-medium fs-16 text-secondary d-block title"
                                                style="padding-top: 20px; padding-bottom: 20px">Choose Language</span>
                                            <div class="max-h-275" data-simplebar>
                                                <div class="notification-menu">
                                                    <a href="javascript:void(0);" class="dropdown-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ asset('assets/images/usa.png') }}"
                                                                    class="wh-30 rounded-circle" alt="usa" />
                                                            </div>
                                                            <div class="flex-grow-1 ms-10">
                                                                <span class="text-secondary fw-medium fs-15">English</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="notification-menu">
                                                    <a href="javascript:void(0);" class="dropdown-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ asset('assets/images/australia.png') }}"
                                                                    class="wh-30 rounded-circle" alt="australia" />
                                                            </div>
                                                            <div class="flex-grow-1 ms-10">
                                                                <span
                                                                    class="text-secondary fw-medium fs-15">Australia</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="notification-menu">
                                                    <a href="javascript:void(0);" class="dropdown-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ asset('assets/images/spain.png') }}"
                                                                    class="wh-30 rounded-circle" alt="spain" />
                                                            </div>
                                                            <div class="flex-grow-1 ms-10">
                                                                <span class="text-secondary fw-medium fs-15">Spanish</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="notification-menu">
                                                    <a href="javascript:void(0);" class="dropdown-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ asset('assets/images/france.png') }}"
                                                                    class="wh-30 rounded-circle" alt="portugal" />
                                                            </div>
                                                            <div class="flex-grow-1 ms-10">
                                                                <span class="text-secondary fw-medium fs-15">France</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="notification-menu mb-0">
                                                    <a href="javascript:void(0);" class="dropdown-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ asset('assets/images/germany.png') }}"
                                                                    class="wh-30 rounded-circle" alt="Germany" />
                                                            </div>
                                                            <div class="flex-grow-1 ms-10">
                                                                <span class="text-secondary fw-medium fs-15">Spain</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="header-right-item light-dark-item">
                                    <div class="light-dark">
                                        <button class="switch-toggle dark-btn p-0 bg-transparent lh-0 border-0"
                                            id="switch-toggle">
                                            <span class="dark"><i class="material-symbols-outlined">dark_mode</i></span>
                                            <span class="light"><i class="material-symbols-outlined">light_mode</i></span>
                                        </button>
                                    </div>
                                </li>
                                <li class="header-right-item calendar-item">
                                    <div class="dropdown notifications">
                                        <a href="calendar.html" class="btn btn-secondary border-0 p-0 position-relative">
                                            <span class="material-symbols-outlined"
                                                style="font-size: 19px">calendar_today</span>
                                        </a>
                                    </div>
                                </li>
                                <li class="header-right-item messages-item">
                                    <div class="dropdown notifications noti messages">
                                        <button class="btn btn-secondary border-0 p-0 position-relative" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="material-symbols-outlined">mail</span>
                                            <span class="count bg-primary">5</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-lg p-0 border-0 p-0 dropdown-menu-end">
                                            <div class="d-flex justify-content-between align-items-center title">
                                                <span class="fw-medium fs-16 text-secondary">Messages
                                                    <span class="fw-normal text-body fs-16">(03)</span></span>
                                                <button
                                                    class="p-0 m-0 bg-transparent border-0 fs-15 text-primary fw-medium">
                                                    Mark all as read
                                                </button>
                                            </div>

                                            <div style="max-height: 300px" data-simplebar>
                                                <div class="notification-menu unseen">
                                                    <a href="chat.html" class="dropdown-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ asset('assets/images/user1.jpg') }}"
                                                                    class="rounded-circle"
                                                                    style="width: 44px; height: 44px" alt="images" />
                                                            </div>
                                                            <div class="flex-grow-1 ms-10">
                                                                <p class="fs-16 fw-medium text-secondary mb-2">
                                                                    Jacob Liwiski
                                                                    <span class="fs-14 fw-normal text-body ms-2">35 min
                                                                        ago</span>
                                                                </p>
                                                                <span class="fs-14-5 fw-medium d-inline-block"
                                                                    style="line-height: 1.4">Hey Victor! Could you
                                                                    please...</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="notification-menu unseen">
                                                    <a href="chat.html" class="dropdown-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ asset('assets/images/user2.jpg') }}"
                                                                    class="rounded-circle"
                                                                    style="width: 44px; height: 44px" alt="images" />
                                                            </div>
                                                            <div class="flex-grow-1 ms-10">
                                                                <p class="fs-16 fw-medium text-secondary mb-2">
                                                                    Angela Carter
                                                                    <span class="fs-14 fw-normal text-body ms-2">1 day
                                                                        ago</span>
                                                                </p>
                                                                <span class="fs-14-5 fw-medium d-inline-block"
                                                                    style="line-height: 1.4">How are you Angela? Would
                                                                    you
                                                                    please...</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="notification-menu">
                                                    <a href="chat.html" class="dropdown-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ asset('assets/images/user3.jpg') }}"
                                                                    class="rounded-circle"
                                                                    style="width: 44px; height: 44px" alt="images" />
                                                            </div>
                                                            <div class="flex-grow-1 ms-10">
                                                                <p class="fs-16 fw-medium text-secondary mb-2">
                                                                    Brad Traversy
                                                                    <span class="fs-14 fw-normal text-body ms-2">2 days
                                                                        ago</span>
                                                                </p>
                                                                <span class="fs-14-5 fw-medium d-inline-block"
                                                                    style="line-height: 1.4">Hey Brad Traversy! Could
                                                                    you
                                                                    please...</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>

                                            <a href="chat.html"
                                                class="dropdown-item text-center text-primary d-block view-all fw-medium rounded-bottom-3">
                                                <span>See All Messages</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="header-right-item">
                                    <div class="dropdown notifications noti">
                                        <button class="btn btn-secondary border-0 p-0 position-relative" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="material-symbols-outlined">notifications</span>
                                            <span class="count">3</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-lg p-0 border-0 p-0 dropdown-menu-end">
                                            <div class="d-flex justify-content-between align-items-center title">
                                                <span class="fw-medium fs-16 text-secondary">Notifications
                                                    <span class="fw-normal text-body fs-16">(03)</span></span>
                                                <button
                                                    class="p-0 m-0 bg-transparent border-0 fs-15 text-primary fw-medium">
                                                    Clear All
                                                </button>
                                            </div>

                                            <div style="max-height: 300px" data-simplebar>
                                                <div class="notification-menu unseen">
                                                    <a href="notifications.html" class="dropdown-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="material-symbols-outlined text-primary">sms</i>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <p class="fs-16 fw-medium text-secondary">
                                                                    You have requested to withdrawal
                                                                </p>
                                                                <span class="fs-14 fw-medium">2 hrs ago</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="notification-menu unseen">
                                                    <a href="notifications.html" class="dropdown-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="material-symbols-outlined text-info">person</i>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <p class="fs-16 fw-medium text-secondary">
                                                                    A new user added in Fila
                                                                </p>
                                                                <span class="fs-14 fw-medium">3 hrs ago</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="notification-menu">
                                                    <a href="notifications.html" class="dropdown-item">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i
                                                                    class="material-symbols-outlined text-success">mark_email_unread</i>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <p class="fs-16 fw-medium text-secondary">
                                                                    You have requested to withdrawal
                                                                </p>
                                                                <span class="fs-14 fw-medium">1 day ago</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>

                                            <a href="notifications.html"
                                                class="dropdown-item text-center text-primary d-block view-all fw-medium rounded-bottom-3">
                                                <span>See All Notifications </span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="header-right-item">
                                    <div class="dropdown admin-profile">
                                        <div class="d-xxl-flex align-items-center bg-transparent border-0 text-start p-0 cursor dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <div class="flex-shrink-0 position-relative">
                                                <img class="rounded-circle admin-img-width-for-mobile"
                                                    style="width: 40px; height: 40px"
                                                    src="{{ asset('assets/images/admin.png') }}" alt="admin" />
                                                <span
                                                    class="d-block bg-success-60 border border-2 border-white rounded-circle position-absolute end-0 bottom-0"
                                                    style="width: 11px; height: 11px"></span>
                                            </div>
                                        </div>

                                        <div class="dropdown-menu border-0 bg-white dropdown-menu-end">
                                            <div class="d-flex align-items-center info">
                                                <div class="flex-shrink-0">
                                                    <img class="rounded-circle admin-img-width-for-mobile"
                                                        style="width: 40px; height: 40px"
                                                        src="{{ asset('assets/images/admin.png') }}" alt="admin" />
                                                </div>
                                                <div class="flex-grow-1 ms-10">
                                                    <h3 class="fw-medium fs-17 mb-0">Mateo Luca</h3>
                                                    <span class="fs-15 fw-medium">Admin</span>
                                                </div>
                                            </div>
                                            <ul class="admin-link mb-0 list-unstyled">
                                                <li>
                                                    <a class="dropdown-item admin-item-link d-flex align-items-center text-body"
                                                        href="my-profile.html">
                                                        <i class="material-symbols-outlined">person</i>
                                                        <span class="ms-2">My Profile</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item admin-item-link d-flex align-items-center text-body"
                                                        href="settings.html">
                                                        <i class="material-symbols-outlined">settings</i>
                                                        <span class="ms-2">Settings</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item admin-item-link d-flex align-items-center text-body"
                                                        href="tickets.html">
                                                        <i class="material-symbols-outlined">info</i>
                                                        <span class="ms-2">Support</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item admin-item-link d-flex align-items-center text-body"
                                                        href="logout.html">
                                                        <i class="material-symbols-outlined">logout</i>
                                                        <span class="ms-2">Logout</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
            <!-- End Header Area -->

            <div class="main-content-container overflow-hidden">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <h3>Total Sales</h3>

                                <div class="dropdown select-dropdown without-border">
                                    <button class="dropdown-toggle bg-transparent text-secondary fs-15"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Year 2025
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end bg-white border-0 box-shadow rounded-10"
                                        data-simplebar>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                Year 2025
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                Year 2025
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                Year 2023
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div id="total_sales_chart" style="margin-bottom: -16px; margin-top: -1.5px"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md-6 col-lg-12">
                                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <h3 class="mb-10">Today Total Sale</h3>
                                            <h2 class="fs-26 fw-medium mb-0 lh-1">$20,705</h2>
                                        </div>
                                        <div class="flex-shrink-0 ms-3">
                                            <div
                                                class="text-white text-center rounded-circle d-block theme-basic theme-basic-icon">
                                                <i class="material-symbols-outlined fs-40">point_of_sale</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center"
                                        style="margin-top: 21px">
                                        <p class="mb-0 fs-14">
                                            4.75% Increase in orders last week
                                        </p>
                                        <span
                                            class="d-flex align-content-center gap-1 bg-success bg-opacity-10 border border-success"
                                            style="padding: 3px 5px">
                                            <i class="material-symbols-outlined fs-14 text-success">trending_up</i>
                                            <span class="lh-1 fs-14 text-success">4.75%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-12">
                                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <h3 class="mb-10">Paid Orders Value</h3>
                                            <h2 class="fs-26 fw-medium mb-0 lh-1">$84,127</h2>
                                        </div>
                                        <div class="flex-shrink-0 ms-3">
                                            <div
                                                class="text-white text-center rounded-circle d-block theme-basic theme-basic-icon">
                                                <i class="material-symbols-outlined fs-40">payments</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center"
                                        style="margin-top: 21px">
                                        <p class="mb-0 fs-14">
                                            Total visitors decreased by 1.25%
                                        </p>
                                        <span
                                            class="d-flex align-content-center gap-1 bg-danger bg-opacity-10 border border-danger"
                                            style="padding: 3px 5px">
                                            <i class="material-symbols-outlined fs-14 text-danger">trending_down</i>
                                            <span class="lh-1 fs-14 text-danger">1.25%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <h3 class="mb-10">Paid Orders</h3>
                                            <h2 class="fs-26 fw-medium mb-0 lh-1">5,278</h2>
                                        </div>
                                        <div class="flex-shrink-0 ms-3">
                                            <div
                                                class="text-white text-center rounded-circle d-block theme-basic theme-basic-icon-revenue">
                                                <i class="material-symbols-outlined fs-50">shopping_bag</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center"
                                        style="margin-top: 23px">
                                        <p class="mb-0 fs-14">Revenue increases this month</p>
                                        <span
                                            class="d-flex align-content-center gap-1 bg-success bg-opacity-10 border border-success"
                                            style="padding: 3px 5px">
                                            <i class="material-symbols-outlined fs-14 text-success">trending_up</i>
                                            <span class="lh-1 fs-14 text-success">3.15%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-20 border rounded-10 mb-4 theme-basic">
                                    <h3 class="text-white mb-12">Orders Overview</h3>
                                    <div class="d-flex flex-wrap gap-2 justify-content-between mb-14">
                                        <div>
                                            <span class="fs-14 text-white mb-1 d-block">Pending</span>
                                            <h2 class="fs-20 fw-medium lh-1 text-white mb-0">
                                                9,586
                                            </h2>
                                        </div>
                                        <div>
                                            <span class="fs-14 text-white mb-1 d-block">Confirmed</span>
                                            <h2 class="fs-20 fw-medium lh-1 text-white mb-0">
                                                3,507
                                            </h2>
                                        </div>
                                        <div>
                                            <span class="fs-14 text-white mb-1 d-block">Dispatched</span>
                                            <h2 class="fs-20 fw-medium lh-1 text-white mb-0">
                                                357
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="progress rounded-0 mb-6 sales-overview-progress" role="progressbar"
                                        aria-label="Basic example" aria-valuenow="80" aria-valuemin="0"
                                        aria-valuemax="100">
                                        <div class="progress-bar rounded-0 bg-white sales-overview-progress-bar">
                                        </div>
                                    </div>
                                    <span class="fs-14 text-white d-block" style="margin-bottom: -6px">20% Increase in
                                        last month</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <h3 class="mb-10">Money Recived Orders</h3>
                                            <h2 class="fs-26 fw-medium mb-0 lh-1">278</h2>
                                        </div>
                                        <div class="flex-shrink-0 ms-3">
                                            <div
                                                class="text-white text-center rounded-circle d-block theme-basic theme-basic-icon-revenue">
                                                <i class="material-symbols-outlined fs-50">paid</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center"
                                        style="margin-top: 23px">
                                        <p class="mb-0 fs-14">Revenue increases this month</p>
                                        <span
                                            class="d-flex align-content-center gap-1 bg-success bg-opacity-10 border border-success"
                                            style="padding: 3px 5px">
                                            <i class="material-symbols-outlined fs-14 text-success">trending_up</i>
                                            <span class="lh-1 fs-14 text-success">3.15%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <h3 class="mb-10">Deliverd Orders</h3>
                                            <h2 class="fs-26 fw-medium mb-0 lh-1">578</h2>
                                        </div>
                                        <div class="flex-shrink-0 ms-3">
                                            <div
                                                class="text-white text-center rounded-circle d-block theme-basic theme-basic-icon-revenue">
                                                <i class="material-symbols-outlined fs-50">local_shipping</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center"
                                        style="margin-top: 23px">
                                        <p class="mb-0 fs-14">Revenue increases this month</p>
                                        <span
                                            class="d-flex align-content-center gap-1 bg-success bg-opacity-10 border border-success"
                                            style="padding: 3px 5px">
                                            <i class="material-symbols-outlined fs-14 text-success">trending_up</i>
                                            <span class="lh-1 fs-14 text-success">3.15%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                                        <h3>Profit</h3>

                                        <div class="dropdown select-dropdown without-border">
                                            <button class="dropdown-toggle bg-transparent text-secondary fs-15"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Last Month
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end bg-white border-0 box-shadow rounded-10"
                                                data-simplebar>
                                                <li>
                                                    <button class="dropdown-item text-secondary">
                                                        Last Day
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item text-secondary">
                                                        Last Week
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item text-secondary">
                                                        Last Month
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item text-secondary">
                                                        Last Year
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <h2 class="lh-1 fs-26 fw-medium" style="margin-bottom: -10px">
                                        $359K
                                    </h2>

                                    <div id="profit_chart" style="margin-bottom: -17px"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <h3 class="mb-10">Today Company Bonus</h3>
                                            <h2 class="fs-26 fw-medium mb-0 lh-1">$15,278</h2>
                                        </div>
                                        <div class="flex-shrink-0 ms-3">
                                            <div
                                                class="text-white text-center rounded-circle d-block theme-basic theme-basic-icon-revenue">
                                                <i class="material-symbols-outlined fs-50">workspace_premium</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center"
                                        style="margin-top: 23px">
                                        <p class="mb-0 fs-14">Revenue increases this month</p>
                                        <span
                                            class="d-flex align-content-center gap-1 bg-success bg-opacity-10 border border-success"
                                            style="padding: 3px 5px">
                                            <i class="material-symbols-outlined fs-14 text-success">trending_up</i>
                                            <span class="lh-1 fs-14 text-success">3.15%</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <h3 class="mb-10">Collected Service Charge</h3>
                                            <h2 class="fs-26 fw-medium mb-0 lh-1">$15,278</h2>
                                        </div>
                                        <div class="flex-shrink-0 ms-3">
                                            <div
                                                class="text-white text-center rounded-circle d-block theme-basic theme-basic-icon-revenue">
                                                <i class="material-symbols-outlined fs-50">receipt</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center"
                                        style="margin-top: 23px">
                                        <p class="mb-0 fs-14">Revenue increases this month</p>
                                        <span
                                            class="d-flex align-content-center gap-1 bg-success bg-opacity-10 border border-success"
                                            style="padding: 3px 5px">
                                            <i class="material-symbols-outlined fs-14 text-success">trending_up</i>
                                            <span class="lh-1 fs-14 text-success">3.15%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- Best Seller Card --}}
                            <div class="col-md-6">
                                <div class="card p-20 bg-light-40 rounded-10 border-light-40 mb-4 best-seller-card">
                                    <div class="d-flex align-items-start justify-content-between gap-3 mb-18">
                                        <div>
                                            <h3 class="mb-12">Best Seller Of The Month</h3>
                                            <h2 class="mt-3 best-seller-name">Michael Marquez</h2>
                                            <span class="best-seller-role">Top Performer</span>
                                        </div>
                                        <div class="pe-3 pt-3">
                                            <div class="best-seller-avatar-wrap">
                                                <img src="{{ asset('assets/images/user6.jpg') }}"
                                                    class="best-seller-avatar" alt="Best seller Michael Marquez" />
                                            </div>
                                        </div>
                                    </div>

                                    <h2 class="lh-1 fs-26 fw-medium mb-10">
                                        $3.5K <span class="fs-16 text-body">Sales</span>
                                    </h2>

                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <span class="fs-14 text-body">1,248 successful orders</span>
                                        <a href="#"
                                            class="fw-medium fs-16 text-secondary hover-text d-inline-block">
                                            Details View
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card p-20 bg-white rounded-10 border border-white mb-4 position-relative z-1">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                                        <h3>Pending Sellers</h3>

                                        <span
                                            class="d-flex align-content-center gap-1 bg-success bg-opacity-10 border border-success"
                                            style="padding: 3px 5px">
                                            <i class="material-symbols-outlined fs-14 text-success">trending_up</i>
                                            <span class="lh-1 fs-14 text-success">2.75%</span>
                                        </span>
                                    </div>
                                    <h2 class="lh-1 fs-26 fw-medium">2,537</h2>
                                    <div style="margin-top: 55px">
                                        <ul class="p-0 mb-0 list-unstyled d-flex last-child-none global-right-list">
                                            <li style="margin-right: -20px">
                                                <img src="{{ asset('assets/images/user12.jpg') }}"
                                                    class="border border-3 border-white rounded-circle"
                                                    style="width: 52px; height: 52px" alt="user12" />
                                            </li>
                                            <li style="margin-right: -20px">
                                                <img src="{{ asset('assets/images/user13.jpg') }}"
                                                    class="border border-3 border-white rounded-circle"
                                                    style="width: 52px; height: 52px" alt="user13" />
                                            </li>
                                            <li style="margin-right: -20px">
                                                <img src="{{ asset('assets/images/user14.jpg') }}"
                                                    class="border border-3 border-white rounded-circle"
                                                    style="width: 52px; height: 52px" alt="user14" />
                                            </li>
                                            <li style="margin-right: -20px">
                                                <img src="{{ asset('assets/images/user15.jpg') }}"
                                                    class="border border-3 border-white rounded-circle"
                                                    style="width: 52px; height: 52px" alt="user15" />
                                            </li>
                                            <li style="margin-right: -20px">
                                                <img src="{{ asset('assets/images/user16.jpg') }}"
                                                    class="border border-3 border-white rounded-circle"
                                                    style="width: 52px; height: 52px" alt="user16" />
                                            </li>
                                            <li class="border border-3 border-white rounded-circle bg-primary text-center"
                                                style="margin-right: -20px;width: 52px;height: 52px;line-height: 49px; ">
                                                <span class="text-white fs-16 fw-medium">27</span>
                                            </li>
                                        </ul>
                                        <a href="/"><span class="fs-16 text-body d-block mb-10">Review
                                                Requests</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-6 col-xxxl-12">
                        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                            <div
                                class="d-flex justify-content-between align-items-center flex-wrap flex-md-nowrap gap-3 mb-20">
                                <h3 class="text-nowrap">Top Selling Products</h3>

                                <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2 justify-content-start justify-content-md-end top-products-filter-controls"
                                    style="width: 100%; flex: 1 1 auto;">
                                    <style>
                                        @media (max-width: 767px) {
                                            input[type="date"].form-control {
                                                width: 100% !important;
                                            }
                                        }

                                        @media (min-width: 768px) {
                                            input[type="date"].form-control {
                                                width: 160px;
                                            }
                                        }
                                    </style>
                                    <input type="date" class="form-control form-control-sm"
                                        aria-label="Top selling products start date" style="min-width: 140px;" />
                                    <input type="date" class="form-control form-control-sm"
                                        aria-label="Top selling products end date" style="min-width: 140px;" />
                                    <button type="button"
                                        class="btn btn-primary text-white btn-sm top-products-filter-btn"
                                        style="min-width: 88px;">Filter</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm top-products-reset-btn"
                                        style="min-width: 88px;">Reset</button>
                                </div>
                            </div>

                            <div class="default-table-area without-header table-top-selling-products">
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <tbody>
                                            <tr>
                                                <td class="text-body fw-medium text-nowrap"
                                                    style="padding-right: 0.35rem; width: 40px;">01.</td>
                                                <td class="text-start" style="padding-left: 0.35rem;">
                                                    <a href="product-details.html"
                                                        class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/product1.png') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="product1" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal hover-text">
                                                                Smart Watch
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">953 Items
                                                                Sold</span>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="text-body">$90,954</td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium text-nowrap"
                                                    style="padding-right: 0.35rem;">02.</td>
                                                <td class="text-start" style="padding-left: 0.35rem;">
                                                    <a href="product-details.html"
                                                        class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/product2.png') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="product2" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal hover-text">
                                                                Mobile Phone
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">876 Items
                                                                Sold</span>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="text-body">$85,648</td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium text-nowrap"
                                                    style="padding-right: 0.35rem;">03.</td>
                                                <td class="text-start" style="padding-left: 0.35rem;">
                                                    <a href="product-details.html"
                                                        class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/product3.png') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="product3" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal hover-text">
                                                                Laptop Device
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">823 Items
                                                                Sold</span>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="text-body">$79,852</td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium text-nowrap"
                                                    style="padding-right: 0.35rem;">04.</td>
                                                <td class="text-start" style="padding-left: 0.35rem;">
                                                    <a href="product-details.html"
                                                        class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/product4.png') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="product4" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal hover-text">
                                                                Black T-Shirt
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">743 Items
                                                                Sold</span>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="text-body">$73,624</td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium text-nowrap"
                                                    style="padding-right: 0.35rem;">05.</td>
                                                <td class="text-start" style="padding-left: 0.35rem;">
                                                    <a href="product-details.html"
                                                        class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/product5.png') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="product5" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal hover-text">Headphones</h3>
                                                            <span class="fs-14 text-body fw-normal">693 Items
                                                                Sold</span>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="text-body">$65,973</td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium text-nowrap"
                                                    style="padding-right: 0.35rem;">06.</td>
                                                <td class="text-start" style="padding-left: 0.35rem;">
                                                    <a href="product-details.html"
                                                        class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/product6.png') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="product6" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal hover-text">Hand Watch</h3>
                                                            <span class="fs-14 text-body fw-normal">654 Items
                                                                Sold</span>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="text-body">$42,455</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div
                                    class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap pt-15">
                                    <span class="fs-15">Showing 1 to 5 of 50 entries</span>

                                    <nav class="custom-pagination" aria-label="Page navigation example">
                                        <ul class="pagination mb-0 justify-content-center">
                                            <li class="page-item">
                                                <button class="page-link icon" aria-label="Previous">
                                                    <i class="material-symbols-outlined">west</i>
                                                </button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link active">1</button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link">2</button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link">3</button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link icon" aria-label="Next">
                                                    <i class="material-symbols-outlined">east</i>
                                                </button>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                                <h3>Top Sellers</h3>

                                <div class="dropdown select-dropdown without-border">
                                    <button class="dropdown-toggle bg-transparent text-secondary fs-15"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        This Week
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end bg-white border-0 box-shadow rounded-10"
                                        data-simplebar>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                This Day
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                This Week
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                This Month
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                This Year
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="default-table-area without-header table-top-sellers">
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <tbody>
                                            <tr>
                                                <td class="text-body fw-medium">01.</td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/user6.jpg') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="user6" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal mb-1">Mark Stjohn
                                                                <span class="fs-13 text-body">(#76431)</span>
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">342 Orders</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-1">$128,400</h3>
                                                    <span
                                                        class="text-success bg-success bg-opacity-10 fs-13 fw-medium d-inline-block default-badge">Return:
                                                        1.8%</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium">02.</td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/user7.jpg') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="user7" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal mb-1">Joan Stanley
                                                                <span class="fs-13 text-body">(#64815)</span>
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">318 Orders</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-1">$117,950</h3>
                                                    <span
                                                        class="text-success bg-success bg-opacity-10 fs-13 fw-medium d-inline-block default-badge">Return:
                                                        2.3%</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium">03.</td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/user8.jpg') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="user8" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal mb-1">Jacob Bell
                                                                <span class="fs-13 text-body">(#34581)</span>
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">286 Orders</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-1">$104,720</h3>
                                                    <span
                                                        class="text-warning bg-warning bg-opacity-10 fs-13 fw-medium d-inline-block default-badge">Return:
                                                        3.1%</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium">04.</td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/user9.jpg') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="user9" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal mb-1">Donald Bryan
                                                                <span class="fs-13 text-body">(#67941)</span>
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">264 Orders</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-1">$98,460</h3>
                                                    <span
                                                        class="text-warning bg-warning bg-opacity-10 fs-13 fw-medium d-inline-block default-badge">Return:
                                                        3.8%</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium">05.</td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/user10.jpg') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="user10" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal mb-1">Kristina Blomquist
                                                                <span class="fs-13 text-body">(#36985)</span>
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">239 Orders</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-1">$90,315</h3>
                                                    <span
                                                        class="text-danger bg-danger bg-opacity-10 fs-13 fw-medium d-inline-block default-badge">Return:
                                                        4.6%</span>
                                                </td>
                                            </tr>
                                            <tr class="last-child-border-none">
                                                <td class="text-body fw-medium">06.</td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/user11.jpg') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="user11" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal mb-1">Jeffrey Morrison
                                                                <span class="fs-13 text-body">(#26985)</span>
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">221 Orders</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-1">$82,740</h3>
                                                    <span
                                                        class="text-danger bg-danger bg-opacity-10 fs-13 fw-medium d-inline-block default-badge">Return:
                                                        5.4%</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div
                                    class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap pt-15">
                                    <span class="fs-15">Showing 1 to 6 of 24 entries</span>

                                    <nav class="custom-pagination" aria-label="Top sellers pagination">
                                        <ul class="pagination mb-0 justify-content-center">
                                            <li class="page-item">
                                                <button class="page-link icon" aria-label="Previous">
                                                    <i class="material-symbols-outlined">west</i>
                                                </button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link active">1</button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link">2</button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link">3</button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link icon" aria-label="Next">
                                                    <i class="material-symbols-outlined">east</i>
                                                </button>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                                <h3>Bottom Sellers</h3>

                                <div class="dropdown select-dropdown without-border">
                                    <button class="dropdown-toggle bg-transparent text-secondary fs-15"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        This Week
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end bg-white border-0 box-shadow rounded-10"
                                        data-simplebar>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                This Day
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                This Week
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                This Month
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                This Year
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="default-table-area without-header table-top-sellers">
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <tbody>
                                            <tr>
                                                <td class="text-body fw-medium">01.</td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/user6.jpg') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="user6" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal mb-1">Mark Stjohn
                                                                <span class="fs-13 text-body">(#76431)</span>
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">126 Orders</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-1">$42,680</h3>
                                                    <span
                                                        class="text-warning bg-warning bg-opacity-10 fs-13 fw-medium d-inline-block default-badge">Return:
                                                        6.1%</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium">02.</td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/user7.jpg') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="user7" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal mb-1">Joan Stanley
                                                                <span class="fs-13 text-body">(#64815)</span>
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">119 Orders</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-1">$39,210</h3>
                                                    <span
                                                        class="text-warning bg-warning bg-opacity-10 fs-13 fw-medium d-inline-block default-badge">Return:
                                                        6.8%</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium">03.</td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/user8.jpg') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="user8" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal mb-1">Jacob Bell
                                                                <span class="fs-13 text-body">(#34581)</span>
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">108 Orders</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-1">$35,540</h3>
                                                    <span
                                                        class="text-danger bg-danger bg-opacity-10 fs-13 fw-medium d-inline-block default-badge">Return:
                                                        7.5%</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium">04.</td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/user9.jpg') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="user9" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal mb-1">Donald Bryan
                                                                <span class="fs-13 text-body">(#67941)</span>
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">97 Orders</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-1">$32,980</h3>
                                                    <span
                                                        class="text-danger bg-danger bg-opacity-10 fs-13 fw-medium d-inline-block default-badge">Return:
                                                        8.2%</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium">05.</td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/user10.jpg') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="user10" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal mb-1">Kristina Blomquist
                                                                <span class="fs-13 text-body">(#36985)</span>
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">88 Orders</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-1">$29,640</h3>
                                                    <span
                                                        class="text-danger bg-danger bg-opacity-10 fs-13 fw-medium d-inline-block default-badge">Return:
                                                        9.1%</span>
                                                </td>
                                            </tr>
                                            <tr class="last-child-border-none">
                                                <td class="text-body fw-medium">06.</td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center text-decoration-none">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/user11.jpg') }}"
                                                                class="rounded-circle" style="width: 50px; height: 50px"
                                                                alt="user11" />
                                                        </div>
                                                        <div class="flex-grow-1 ms-12">
                                                            <h3 class="fw-normal mb-1">Jeffrey Morrison
                                                                <span class="fs-13 text-body">(#26985)</span>
                                                            </h3>
                                                            <span class="fs-14 text-body fw-normal">79 Orders</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-1">$26,170</h3>
                                                    <span
                                                        class="text-danger bg-danger bg-opacity-10 fs-13 fw-medium d-inline-block default-badge">Return:
                                                        10.4%</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div
                                    class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap pt-15">
                                    <span class="fs-15">Showing 1 to 6 of 24 entries</span>

                                    <nav class="custom-pagination" aria-label="Bottom sellers pagination">
                                        <ul class="pagination mb-0 justify-content-center">
                                            <li class="page-item">
                                                <button class="page-link icon" aria-label="Previous">
                                                    <i class="material-symbols-outlined">west</i>
                                                </button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link active">1</button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link">2</button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link">3</button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link icon" aria-label="Next">
                                                    <i class="material-symbols-outlined">east</i>
                                                </button>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                                <h3>Supplier Details</h3>

                                <div class="dropdown select-dropdown without-border">
                                    <button class="dropdown-toggle bg-transparent text-secondary fs-15"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        This Week
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end bg-white border-0 box-shadow rounded-10"
                                        data-simplebar>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                This Day
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                This Week
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                This Month
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-secondary">
                                                This Year
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="default-table-area mx-minus-1 table-top-sellers">
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="fw-medium text-nowrap"></th>
                                                <th scope="col" class="fw-medium text-nowrap text-start"
                                                    style="padding-left: 0px;">Name</th>
                                                <th scope="col" class="fw-medium text-nowrap">Orders</th>
                                                <th scope="col" class="fw-medium text-nowrap">Pending</th>
                                                <th scope="col" class="fw-medium text-nowrap">Dispatch</th>
                                                <th scope="col" class="fw-medium text-nowrap text-end">Revenue</th>
                                                <th scope="col" class="fw-medium text-nowrap"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-body fw-medium">01.</td>
                                                <td class="ps-0">
                                                    <h3 class="fw-normal mb-0">Nova Supply Co.</h3>
                                                </td>
                                                <td class="text-body">421</td>
                                                <td class="text-body">39</td>
                                                <td class="text-body">382</td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-0">$164,820</h3>
                                                </td>
                                                <td class="text-end">
                                                    <a href="#"
                                                        class="text-decoration-none text-secondary hover-text"
                                                        aria-label="View report for Nova Supply Co.">
                                                        <i class="material-symbols-outlined fs-20">assessment</i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium">02.</td>
                                                <td class="ps-0">
                                                    <h3 class="fw-normal mb-0">Prime Source Ltd.</h3>
                                                </td>
                                                <td class="text-body">389</td>
                                                <td class="text-body">34</td>
                                                <td class="text-body">355</td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-0">$152,970</h3>
                                                </td>
                                                <td class="text-end">
                                                    <a href="#"
                                                        class="text-decoration-none text-secondary hover-text"
                                                        aria-label="View report for Prime Source Ltd.">
                                                        <i class="material-symbols-outlined fs-20">assessment</i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium">03.</td>
                                                <td class="ps-0">
                                                    <h3 class="fw-normal mb-0">Eastline Traders</h3>
                                                </td>
                                                <td class="text-body">347</td>
                                                <td class="text-body">31</td>
                                                <td class="text-body">316</td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-0">$138,560</h3>
                                                </td>
                                                <td class="text-end">
                                                    <a href="#"
                                                        class="text-decoration-none text-secondary hover-text"
                                                        aria-label="View report for Eastline Traders">
                                                        <i class="material-symbols-outlined fs-20">assessment</i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium">04.</td>
                                                <td class="ps-0">
                                                    <h3 class="fw-normal mb-0">BluePeak Wholesale</h3>
                                                </td>
                                                <td class="text-body">316</td>
                                                <td class="text-body">27</td>
                                                <td class="text-body">289</td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-0">$124,905</h3>
                                                </td>
                                                <td class="text-end">
                                                    <a href="#"
                                                        class="text-decoration-none text-secondary hover-text"
                                                        aria-label="View report for BluePeak Wholesale">
                                                        <i class="material-symbols-outlined fs-20">assessment</i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-body fw-medium">05.</td>
                                                <td class="ps-0">
                                                    <h3 class="fw-normal mb-0">Atlas Vendor Hub</h3>
                                                </td>
                                                <td class="text-body">284</td>
                                                <td class="text-body">24</td>
                                                <td class="text-body">260</td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-0">$112,440</h3>
                                                </td>
                                                <td class="text-end">
                                                    <a href="#"
                                                        class="text-decoration-none text-secondary hover-text"
                                                        aria-label="View report for Atlas Vendor Hub">
                                                        <i class="material-symbols-outlined fs-20">assessment</i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="last-child-border-none">
                                                <td class="text-body fw-medium">06.</td>
                                                <td class="ps-0">
                                                    <h3 class="fw-normal mb-0">Sterling Imports</h3>
                                                </td>
                                                <td class="text-body">251</td>
                                                <td class="text-body">22</td>
                                                <td class="text-body">229</td>
                                                <td class="text-end">
                                                    <h3 class="fs-16 fw-medium text-secondary mb-0">$97,615</h3>
                                                </td>
                                                <td class="text-end">
                                                    <a href="#"
                                                        class="text-decoration-none text-secondary hover-text"
                                                        aria-label="View report for Sterling Imports">
                                                        <i class="material-symbols-outlined fs-20">assessment</i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div
                                    class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap pt-15">
                                    <span class="fs-15">Showing 1 to 6 of 18 entries</span>

                                    <nav class="custom-pagination" aria-label="Supplier details pagination">
                                        <ul class="pagination mb-0 justify-content-center">
                                            <li class="page-item">
                                                <button class="page-link icon" aria-label="Previous">
                                                    <i class="material-symbols-outlined">west</i>
                                                </button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link active">1</button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link">2</button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link">3</button>
                                            </li>
                                            <li class="page-item">
                                                <button class="page-link icon" aria-label="Next">
                                                    <i class="material-symbols-outlined">east</i>
                                                </button>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-grow-1"></div>

            <!-- Start Footer Area -->
            <footer class="footer-area bg-white text-center rounded-10 rounded-bottom-0">
                <p class="fs-16 text-body">
                    © <span class="text-secondary">Fila</span> is Proudly Owned by
                    <a href="https://envytheme.com/" target="_blank"
                        class="text-decoration-none text-primary">EnvyTheme</a>
                </p>
            </footer>
            <!-- End Footer Area -->
        </div>
    </div>
@endsection
