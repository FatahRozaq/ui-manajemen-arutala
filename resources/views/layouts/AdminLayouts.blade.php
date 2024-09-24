<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/ArutalaHitam.png') }}">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    @yield('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.default.css">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center" style="width:230px">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="{{ asset('assets/img/logo/ArutalaHitam.png') }}" alt="" style="width:50px; height:50px">
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
    
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <span id="navbarAdminName" class="d-none d-md-block dropdown-toggle ps-2" style="font-size: 14px;">Admin</span>
                        <i class="fa-solid fa-circle-user" style="font-size: 30px; margin-left:20px; margin-right:10px;"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6 id="navbarProfileName">Admin</h6>
                            <span>Admin</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#" onclick="event.preventDefault(); performLogout();">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/dashboard*') ? '' : 'collapsed' }}" href="{{ route('dashboard.index') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- Additional menu items -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/pelatihan*') ? '' : 'collapsed' }}" href="{{ route('pelatihan.index') }}">
                    <i class="fa-solid fa-table-list"></i>
                    <span>Master Pelatihan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/pendaftar*') ? '' : 'collapsed' }}" href="{{ route('pendaftar.index') }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Master Pendaftar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/mentor*') ? '' : 'collapsed' }}" href="{{ route('mentor.index') }}">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>Master Mentor</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/agendapelatihan*') ? '' : 'collapsed' }}" href="{{ route('agenda.index') }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Agenda Pelatihan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/pesertapelatihan*') ? '' : 'collapsed' }}" href="{{ route('peserta.index') }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Peserta Pelatihan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/kelola-admin*') ? '' : 'collapsed' }}" href="{{ route('admin.index') }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Kelola Admin</span>
                </a>
            </li>
        </ul>
    </aside>

    <main id="main" class="main">
        @yield('content')
    </main>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>
    
    @yield('style')

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('auth_token');

            if (token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                axios.get('/api/admin-profile')
                    .then(function (response) {
                        const adminData = response.data.data;
                        document.getElementById('navbarAdminName').textContent = adminData.nama || 'Admin';
                        document.getElementById('navbarProfileName').textContent = adminData.nama || 'Admin';
                    })
                    .catch(function (error) {
                        console.error('Error fetching admin profile data:', error);
                    });
            }
        });

        function performLogout() {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');

            axios.post('/logout')
                .then(response => {
                    Swal.fire({
                        title: 'Logout Berhasil!',
                        text: 'Anda telah keluar dari website Arutala',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/admin';
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat logout.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        }
    </script>
</body>
</html>
