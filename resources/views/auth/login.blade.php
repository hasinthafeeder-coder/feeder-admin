<!DOCTYPE html>
<html lang="zxx">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Links Of CSS File -->
    <link rel="stylesheet" href="assets/css/sidebar-menu.css">
    <link rel="stylesheet" href="assets/css/simplebar.css">
    <link rel="stylesheet" href="assets/css/prism.css">
    <link rel="stylesheet" href="assets/css/quill.snow.css">
    <link rel="stylesheet" href="assets/css/remixicon.css">
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/jsvectormap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">

    <!-- Title -->
    <title>Fila - Bootstrap 5 Admin Dashboard Template</title>
</head>

<body class="bg-body-bg">

    <!-- Start Preloader Area -->
    <div class="preloader" id="preloader">
        <div class="preloader">
            <div class="waviy position-relative">
                <span class="d-inline-block">F</span>
                <span class="d-inline-block">I</span>
                <span class="d-inline-block">L</span>
                <span class="d-inline-block">A</span>
            </div>
        </div>
    </div>
    <!-- End Preloader Area -->

    <div class="container-fluid">
        <div class="main-content d-flex flex-column p-0">
            <div class="m-lg-auto my-auto w-930 py-4">
                <div class="card bg-white border rounded-10 border-white py-100 px-130">
                    <div class="p-md-5 p-4 p-lg-0">
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/img/feeder.png') }}" alt="FEEDER" class="d-block mx-auto mb-3"
                                style="max-width: 220px; width: 100%; height: auto;">
                            <h3 class="fs-26 fw-medium" style="margin-bottom: 6px;">Sign In</h3>
                        </div>

                        <form method="POST" action="{{ route('login.store') }}">
                            @csrf
                            <div class="mb-20">
                                <label class="label fs-16 mb-2">Email Address</label>
                                <div class="form-floating">
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control" id="email" placeholder="Enter email address *">
                                    <label for="email">Enter email address *</label>
                                </div>
                            </div>
                            <div class="mb-20">
                                <label class="label fs-16 mb-2">Your Password</label>
                                <div class="form-group" id="password-show-hide">
                                    <div class="password-wrapper position-relative password-container">
                                        <input type="password" name="password"
                                            class="form-control text-secondary password" id="password"
                                            placeholder="Enter password *">
                                        <i style="color: #A9A9C8; font-size: 22px; right: 15px;"
                                            class="ri-eye-off-line password-toggle-icon translate-middle-y top-50 position-absolute cursor text-secondary"
                                            aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" value="1"
                                            id="flexCheckDefault">
                                        <label class="form-check-label fs-16" for="flexCheckDefault">
                                            Remember me
                                        </label>
                                    </div>
                                    <a href="forgot-password.html"
                                        class="fs-16 text-primary fw-normal text-decoration-none">Forgot Password?</a>
                                </div>
                            </div>

                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary fw-normal text-white w-100"
                                    style="padding-top: 18px; padding-bottom: 18px;">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="switch-toggle dark-btn p-0 bg-transparent lh-0 border-0" id="switch-toggle"></button>

    <!-- Link Of JS File -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sidebar-menu.js"></script>
    <script src="assets/js/quill.min.js"></script>
    <script src="assets/js/data-table.js"></script>
    <script src="assets/js/prism.js"></script>
    <script src="assets/js/clipboard.min.js"></script>
    <script src="assets/js/simplebar.min.js"></script>
    <script src="assets/js/apexcharts.min.js"></script>
    <script src="assets/js/echarts.min.js"></script>
    <script src="assets/js/swiper-bundle.min.js"></script>
    <script src="assets/js/fullcalendar.main.js"></script>
    <script src="assets/js/jsvectormap.min.js"></script>
    <script src="assets/js/world-merc.js"></script>
    <script src="assets/js/custom/apexcharts.js"></script>
    <script src="assets/js/custom/echarts.js"></script>
    <script src="assets/js/custom/maps.js"></script>
    <script src="assets/js/custom/custom.js"></script>
</body>

</html>
