<!doctype html>
<html lang="zxx">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Links Of CSS File -->
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar-menu.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/simplebar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/prism.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/quill.snow.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jsvectormap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" />

    <!-- Title -->
    <title>Fila - Bootstrap 5 Admin Dashboard Template</title>
    <style>
        #layout-menu .menu-item>.menu-toggle::after {
            transform: translateY(-50%) rotate(45deg);
        }

        #layout-menu .menu-item.open>.menu-toggle::after {
            transform: translateY(-50%) rotate(135deg);
        }

        #layout-menu .menu-link.active,
        #layout-menu .menu-item.open>.menu-link,
        #layout-menu .menu-sub .menu-link.active {
            color: #f97316;
        }

        #layout-menu .menu-link.active .menu-icon,
        #layout-menu .menu-item.open>.menu-link .menu-icon {
            color: #f97316;
        }
    </style>
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

    <!-- Start Sidebar Area -->
    <div class="sidebar-area" id="sidebar-area">
        <div class="logo position-relative d-flex align-items-center justify-content-between">
            <a href="index.html" class="d-block text-decoration-none position-relative">
                <img src="{{ asset('assets/images/logo-icon.png') }}" alt="logo-icon" />
                <span class="logo-text text-secondary fw-semibold">Fila</span>
            </a>
            <button
                class="sidebar-burger-menu-close bg-transparent py-3 border-0 opacity-0 z-n1 position-absolute top-50 end-0 translate-middle-y"
                id="sidebar-burger-menu-close">
                <span class="border-1 d-block for-dark-burger"
                    style="
              border-bottom: 1px solid #475569;
              height: 1px;
              width: 25px;
              transform: rotate(45deg);
            "></span>
                <span class="border-1 d-block for-dark-burger"
                    style="
              border-bottom: 1px solid #475569;
              height: 1px;
              width: 25px;
              transform: rotate(-45deg);
            "></span>
            </button>
            <button class="sidebar-burger-menu bg-transparent p-0 border-0" id="sidebar-burger-menu">
                <span class="border-1 d-block for-dark-burger"
                    style="border-bottom: 1px solid #475569; height: 1px; width: 25px"></span>
                <span class="border-1 d-block for-dark-burger"
                    style="
              border-bottom: 1px solid #475569;
              height: 1px;
              width: 25px;
              margin: 6px 0;
            "></span>
                <span class="border-1 d-block for-dark-burger"
                    style="border-bottom: 1px solid #475569; height: 1px; width: 25px"></span>
            </button>
        </div>

        <aside id="layout-menu" class="layout-menu menu-vertical menu active" data-simplebar>
            <ul class="menu-inner">
                <li class="menu-title small text-uppercase">
                    <span class="menu-title-text">MAIN</span>
                </li>
                <li class="menu-item open">
                    <a href="javascript:void(0);" class="menu-link">
                        <span class="material-symbols-outlined menu-icon">dashboard</span>
                        <span class="title">Dashboard</span>
                    </a>

                </li>

                {{-- ORDERS --}}
                <li class="menu-title small text-uppercase">
                    <span class="menu-title-text">ORDERS</span>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">shopping_cart</span>
                        <span class="title">Order Management</span>
                        <span class="count">2</span>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="index.html" class="menu-link">All Orders</a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link"> Customer CRIB </a>
                        </li>
                    </ul>
                </li>


                <li class="menu-item ">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">published_with_changes</span>
                        <span class="title">Status Update</span>
                        <span class="count">2</span>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="index.html" class="menu-link">Bulk Delivery Update</a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link"> Bulk Remind Orders Update </a>
                        </li>
                    </ul>
                </li>

                {{-- PAYMENTS --}}
                <li class="menu-title small text-uppercase">
                    <span class="menu-title-text">PAYMENTS</span>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">payments</span>
                        <span class="title">Generate Payments</span>
                        <span class="count">1</span>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="index.html" class="menu-link">Courier Payment Generate</a>
                        </li>
                    </ul>
                </li>
                {{-- End PAYMENTS --}}

                {{-- Start PAYOUTS --}}
                <li class="menu-title small text-uppercase">
                    <span class="menu-title-text">PAYOUTS</span>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">account_balance_wallet</span>
                        <span class="title">Generate Payouts</span>
                        <span class="count">3</span>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="index.html" class="menu-link"> Reseller Payouts </a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link"> Supplier Payouts </a>
                        </li>
                        <li class="menu-item">
                            <a href="project-management.html" class="menu-link">
                                Company Bonus Payouts
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">receipt_long</span>
                        <span class="title">Payout Invoice</span>
                        <span class="count">3</span>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="index.html" class="menu-link"> Reseller Invoice </a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link"> Supplier Invoice </a>
                        </li>
                        <li class="menu-item">
                            <a href="project-management.html" class="menu-link">
                                Company Bonus Invoice
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- End PAYOUTS --}}

                {{-- Start PRODUCTS --}}
                <li class="menu-title small text-uppercase">
                    <span class="menu-title-text">PRODUCTS</span>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">inventory_2</span>
                        <span class="title">Product Management</span>
                        <span class="count">2</span>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="index.html" class="menu-link"> Manage Products </a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link"> Product Details </a>
                        </li>
                    </ul>
                </li>
                {{-- End PRODUCTS --}}

                {{-- Start CREATE --}}
                <li class="menu-title small text-uppercase">
                    <span class="menu-title-text">CREATE</span>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">add_circle</span>
                        <span class="title">Create Options</span>
                        <span class="count">3</span>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="index.html" class="menu-link"> Create Delivery Services </a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link"> Create Note </a>
                        </li>
                        <li class="menu-item">
                            <a href="index.html" class="menu-link"> Create Ban Customer </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">person_add</span>
                        <span class="title">Create Profiles</span>
                        <span class="count">3</span>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="index.html" class="menu-link"> Create Company Profile </a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link"> Create Reseller Profile </a>
                        </li>
                        <li class="menu-item">
                            <a href="index.html" class="menu-link"> Create Supplier Profile </a>
                        </li>
                    </ul>
                </li>
                {{-- End PRODUCTS --}}

                {{-- Start ACCOUNTS --}}
                <li class="menu-title small text-uppercase">
                    <span class="menu-title-text">ACCOUNTS</span>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">manage_accounts</span>
                        <span class="title">Manage Account</span>
                        <span class="count">2</span>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="index.html" class="menu-link">Reseller Profile Manage</a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link">Supplier Profile Manage</a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">supervisor_account</span>
                        <span class="title">Manage Profiles</span>
                        <span class="count">3</span>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="index.html" class="menu-link">Reseller User Profile</a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link">Supplier User Profile</a>
                        </li>
                        <li class="menu-item">
                            <a href="index.html" class="menu-link">Feeder User Profile</a>
                        </li>
                    </ul>
                </li>
                {{-- End ACCOUNTS --}}

                {{-- Start OVERVIEW --}}
                <li class="menu-title small text-uppercase">
                    <span class="menu-title-text">OVERVIEW</span>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">insights</span>
                        <span class="title">Orders Overview</span>
                        <span class="count">2</span>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="index.html" class="menu-link">Supplier Orders Overview</a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link">Reseller Orders Overview</a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item">
                    <a href="contacts.html" class="menu-link">
                        <span class="material-symbols-outlined menu-icon">groups</span>
                        <span class="title">Team Structure Overview</span>
                    </a>
                </li>
                {{-- End OVERVIEW --}}

                {{-- Start REPORTS --}}
                <li class="menu-title small text-uppercase">
                    <span class="menu-title-text">REPORTS</span>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">bar_chart</span>
                        <span class="title">Reports & Analytics</span>
                        <span class="count">4</span>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="index.html" class="menu-link">Delivery Services Report</a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link">Supplier Reports</a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link">Reseller Reports</a>
                        </li>
                        <li class="menu-item">
                            <a href="crm.html" class="menu-link">Feeder Earning Report</a>
                        </li>
                    </ul>
                </li>
                {{-- End REPORTS --}}

                {{-- Start TAX & VAT --}}
                <li class="menu-title small text-uppercase">
                    <span class="menu-title-text">TAX & VAT</span>
                </li>

                <li class="menu-item">
                    <a href="to-do-list.html" class="menu-link">
                        <span class="material-symbols-outlined menu-icon">gavel</span>
                        <span class="title">TAX</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="to-do-list.html" class="menu-link">
                        <span class="material-symbols-outlined menu-icon">request_quote</span>
                        <span class="title">VAT</span>
                    </a>
                </li>
                {{-- End TAX & VAT --}}


                <li class="menu-item">
                    <a href="logout.html" class="menu-link">
                        <span class="material-symbols-outlined menu-icon">logout</span>
                        <span class="title">Logout</span>
                    </a>
                </li>
            </ul>
        </aside>
    </div>
    <!-- End Sidebar Area -->

    <!-- Start Main Content Area -->
    <main>
        @yield('content')
    </main>
    <!-- Start Main Content Area -->

    <!-- Link Of JS File -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('assets/js/quill.min.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/prism.js') }}"></script>
    <script src="{{ asset('assets/js/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/fullcalendar.main.js') }}"></script>
    <script src="{{ asset('assets/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/world-merc.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/custom/echarts.js') }}"></script>
    <script src="{{ asset('assets/js/custom/maps.js') }}"></script>
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var menuRoot = document.querySelector('#layout-menu .menu-inner');

            if (!menuRoot) {
                return;
            }

            function setSingleActive(currentToggleLink) {
                var toggleLinks = menuRoot.querySelectorAll('.menu-item > .menu-link.menu-toggle');
                toggleLinks.forEach(function(link) {
                    link.classList.remove('active');
                });

                if (currentToggleLink) {
                    currentToggleLink.classList.add('active');
                }
            }

            function closeOtherOpenItems(currentItem) {
                var openItems = menuRoot.querySelectorAll('.menu-item.open');

                openItems.forEach(function(item) {
                    if (item === currentItem) {
                        return;
                    }

                    var submenu = item.querySelector('.menu-sub');
                    item.classList.remove('open');

                    if (submenu) {
                        submenu.style.display = 'none';
                        submenu.style.height = '';
                    }
                });
            }

            menuRoot.addEventListener('click', function(event) {
                var clickedToggle = event.target.closest('.menu-item > .menu-link.menu-toggle');

                if (!clickedToggle || !menuRoot.contains(clickedToggle)) {
                    return;
                }

                var clickedItem = clickedToggle.parentElement;

                // Let the template menu script finish its own toggle first.
                requestAnimationFrame(function() {
                    closeOtherOpenItems(clickedItem);

                    if (clickedItem.classList.contains('open')) {
                        setSingleActive(clickedToggle);
                    } else {
                        setSingleActive(null);
                    }
                });
            });
        });
    </script>
</body>

</html>
