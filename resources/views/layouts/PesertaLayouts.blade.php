<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="{{ asset('assets/img/logo.png') }}" alt="">
                <span class="d-none d-lg-block">NiceAdmin</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <div class="profile" style="width:20%; display:flex; align-items:center;">
                    <h5 style="margin-right: 1rem">Hello, Nama</h5>
                    <i class="bi bi-person-circle" style="font-size: 34px; color:blue"></i>
                </div>
            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->is('daftar-event*', 'peserta/pendaftaran*', 'detail-event*') ? '' : 'collapsed' }}" href="{{ route('event.index') }}">
                    <i class="bi bi-card-list"></i>
                    <span>Daftar Event</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('my-event*') ? '' : 'collapsed' }}" href="{{ route('event.history') }}">
                    <i class="bi bi-card-checklist"></i>
                    <span>My Event</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('peserta/sertifikat*') ? '' : 'collapsed' }}" href="{{ route('peserta.sertifikat') }}">
                    <i class="bi bi-file-earmark-check"></i>
                    <span>My Certificate</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('peserta/profile*') ? '' : 'collapsed' }}" href="{{ route('peserta.profile') }}">
                    <i class="bi bi-person-square"></i>
                    <span>Data Diri</span>
                </a>
            </li>

            <!-- Link Logout menggunakan performLogout() -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" onclick="event.preventDefault(); performLogout();">
                    <i class="bi bi-box-arrow-right" style="color: red;"></i>
                    <span>Logout</span>
                </a>
            </li>

        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        @yield('content')
    </main>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </footer><!-- End Footer -->

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    @yield('style')

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Script Logout -->
    <script>
        function performLogout() {
            // Menghapus token dari localStorage
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');

            // Kirim permintaan logout ke server
            axios.post('/logout')
                .then(response => {
                    Swal.fire({
                        title: 'Logout Berhasil!',
                        text: 'Anda telah keluar dari website Arutala',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/login-page';
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
