<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin | Sangam Tours</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2220%22 fill=%22%234e73df%22/><text x=%2250%%22 y=%2252%%22 dominant-baseline=%22central%22 text-anchor=%22middle%22 font-family=%22Arial, sans-serif%22 font-weight=%22bold%22 font-size=%2260%22 fill=%22white%22>ST</text></svg>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-style.css') }}">
</head>
<body>

    <div class="main-wrapper">

        <nav id="sidebar" class="sidebar dark-theme">
            <div class="sidebar-header">
                <a href="#" class="brand-link">
                    <i class='bx bx-trip text-info fs-3'></i>
                    <span class="brand-text">Sangam<span class="text-info">Tours</span></span>
                </a>
                <button type="button" id="sidebarCollapseInside" class="btn text-white d-lg-none">
                    <i class='bx bx-chevron-left fs-2'></i>
                </button>
            </div>

    <ul class="list-unstyled components">
    <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard') }}">
            <i class='bx bx-grid-alt'></i> <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-label mt-3 small uppercase" style="color: #0dcaf0;">Operations</li>
    
    <li class="{{ Request::is('admin/bookings*') ? 'active' : '' }}">
        <a href="{{ route('admin.bookings.index') }}">
            <i class='bx bx-receipt'></i> <span>All Bookings</span>
        </a>
    </li>

    <li class="{{ Request::is('admin/schedules*') ? 'active' : '' }}">
        <a href="{{ route('admin.schedules.index') }}">
            <i class='bx bx-calendar-event'></i> <span>Schedules / Time-Table</span>
        </a>
    </li>

    <li class="{{ Request::is('admin/offers*') ? 'active' : '' }}">
        <a href="{{ route('admin.offers.index') }}">
            <i class='bx bxs-discount'></i> <span>Offers & Discounts</span>
        </a>
    </li>

    <li class="nav-label mt-3 small uppercase" style="color: #0dcaf0;">Masters</li>
    
    <li class="{{ Request::is('admin/packages*') ? 'active' : '' }}">
        <a href="{{ route('admin.packages.index') }}">
            <i class='bx bx-briefcase-alt-2'></i> <span>Tour Packages</span>
        </a>
    </li>

    <li class="{{ Request::is('admin/accommodation*') ? 'active' : '' }}">
        <a href="#accSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <i class='bx bx-hotel'></i> <span>Accommodations</span>
        </a>
        <ul class="collapse list-unstyled {{ Request::is('admin/accommodation*') ? 'show' : '' }}" id="accSubmenu">
            <li class="{{ Request::is('admin/accommodations') ? 'active' : '' }}">
                <a href="{{ route('admin.accommodations.index') }}">Manage Hotels</a>
            </li>
            <li class="{{ Request::is('admin/accommodation-types*') ? 'active' : '' }}">
                <a href="{{ route('admin.accommodation-types.index') }}">Accommodation Types</a>
            </li>
            <li class="{{ Request::is('admin/amenities*') ? 'active' : '' }}">
            <a href="{{ route('admin.amenities.index') }}">Hotel Amenities</a>
        </li>
        </ul>
    </li>

    <li class="{{ Request::is('admin/locations*') ? 'active' : '' }}">
        <a href="{{ route('admin.locations.index') }}">
            <i class='bx bx-map-alt'></i> <span>Locations</span>
        </a>
    </li>

    <li class="nav-label mt-3 small uppercase" style="color: #0dcaf0;">Inventory & Logistics</li>

    <li class="{{ Request::is('admin/vehicles*') ? 'active' : '' }}">
        <a href="{{ route('admin.vehicles.index') }}">
            <i class='bx bx-bus'></i> <span>Inventory / Vehicles</span>
        </a>
    </li>

    <li class="{{ Request::is('admin/routes*') ? 'active' : '' }}">
        <a href="{{ route('admin.routes.index') }}">
            <i class='bx bx-directions'></i> <span>Route Management</span>
        </a>
    </li>

    <li class="{{ Request::is('admin/vendor*') ? 'active' : '' }}">
        <a href="#vendorSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <i class='bx bx-store-alt'></i> <span>Vendors</span>
        </a>
        <ul class="collapse list-unstyled {{ Request::is('admin/vendor*') ? 'show' : '' }}" id="vendorSubmenu">
            <li class="{{ Request::is('admin/vendors') ? 'active' : '' }}">
                <a href="{{ route('admin.vendors.index') }}">Vendor Management</a>
            </li>
            <li class="{{ Request::is('admin/vendor-types*') ? 'active' : '' }}">
                <a href="{{ route('admin.vendor-types.index') }}">Vendor Types</a>
            </li>
        </ul>
    </li>

    <li class="nav-label mt-3 small uppercase" style="color: #0dcaf0;">People</li>
    
    <li class="{{ Request::is('admin/customers*') ? 'active' : '' }}">
        <a href="{{ route('admin.customers.index') }}">
            <i class='bx bx-group'></i> <span>Customers</span>
        </a>
    </li>

    <li class="nav-label mt-3 small uppercase" style="color: #0dcaf0;">Configuration</li>
    
    <li>
        <a href="#"><i class='bx bx-cog'></i> <span>Global Settings</span></a>
    </li>
</ul>
        </nav>

        <div id="content" class="content-wrapper">

            <header class="header navbar navbar-expand navbar-light bg-white px-4">
                <div class="container-fluid p-0">
                    
                    <div class="header-left d-flex align-items-center">
                        <button type="button" id="sidebarCollapse" class="btn p-0 me-3 fs-3 text-secondary">
                            <i class='bx bx-menu'></i>
                        </button>
                        <h5 class="m-0 text-dark fw-bold">Dashboard</h5>
                    </div>

                    <div class="header-right d-flex align-items-center">
                        <form class="d-none d-md-flex me-4">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" placeholder="Search booking...">
                                <button class="btn btn-sm btn-outline-secondary"><i class='bx bx-search'></i></button>
                            </div>
                        </form>

                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle user-avatar-dropdown" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar-circle">A</div>
                                <div class="user-info ms-2 d-none d-lg-block">
                                    <span class="user-name">Admin</span>
                                    <span class="user-role">Superuser</span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userMenu">
                                <li><a class="dropdown-menu-header text-muted">Manage Account</a></li>
                                <li><a class="dropdown-item" href="#"><i class='bx bx-user-circle me-2'></i> Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class='bx bx-shield-quarter me-2'></i> Security</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route("admin.logout") }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class='bx bx-log-out-circle me-2'></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <main class="page-content px-4 py-4">
                <div class="container-fluid">
                    @yield('admin_content') 
                </div>
            </main>

            <footer class="footer bg-white text-muted px-4 py-3 text-center border-top">
                <div class="small">Copyright &copy; 2025 SangamTours. All rights reserved.</div>
            </footer>
        </div>

    </div> 
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Logic: Sidebar Toggle (Close/Open)
            $('#sidebarCollapse, #sidebarCollapseInside').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });

            // Handle Mobile View Sidebar backdrop click to close
            if($(window).width() < 992) {
                 $('#content').on('click', function() {
                     if ($('#sidebar').hasClass('active')) {
                         $('#sidebar').removeClass('active');
                         $('#content').removeClass('active');
                     }
                 });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>