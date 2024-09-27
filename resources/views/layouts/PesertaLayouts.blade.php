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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center">
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>

        
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">
                    <li class="nav-item dropdown">

                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                          <i class="bi bi-chat-left-text" style="font-size: 16px"></i>
                          <span class="badge bg-success badge-number" style="font-size: 10px; margin-top:10px; margin-right:5px;">3</span>
                        </a><!-- End Messages Icon -->
              
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                          <li class="dropdown-header">
                            You have 3 new messages
                            <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                          </li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
              
                          {{-- <li class="message-item">
                            <a href="#">
                              <img src="assets/img/messages-1.jpg" alt="" class="rounded-circle">
                              <div>
                                <h4>Maria Hudson</h4>
                                <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                <p>4 hrs. ago</p>
                              </div>
                            </a>
                          </li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
              
                          <li class="message-item">
                            <a href="#">
                              <img src="assets/img/messages-2.jpg" alt="" class="rounded-circle">
                              <div>
                                <h4>Anna Nelson</h4>
                                <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                <p>6 hrs. ago</p>
                              </div>
                            </a>
                          </li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
              
                          <li class="message-item">
                            <a href="#">
                              <img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
                              <div>
                                <h4>David Muldon</h4>
                                <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                <p>8 hrs. ago</p>
                              </div>
                            </a>
                          </li> --}}
                          <li>
                            <hr class="dropdown-divider">
                          </li>
              
                          <li class="dropdown-footer">
                            <a href="#">Show all messages</a>
                          </li>
              
                        </ul><!-- End Messages Dropdown Items -->
              
                      </li><!-- End Messages Nav -->
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <span id="navbarUserName" class="d-none d-md-block dropdown-toggle ps-2" style="font-size: 14px;">Peserta</span>
                        <i class="fa-solid fa-circle-user" style="font-size: 25px; margin-left:20px; margin-right:10px;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        
                        <li class="dropdown-header">
                            <h6 id="navbarProfileName">Peserta</h6>
                            <span>Peserta</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('peserta.profile') }}">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
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
    <div id="sidebar" class="sidebar">

        <div class="logo">
            <img src="{{ asset('assets/img/logo/ArutalaHitam.png') }}" alt="">
        </div>
        
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

        </ul>
    </div>

    <main id="main" class="main">
        @yield('content')
    </main>

    <!-- ======= Footer ======= -->
    <!-- <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </footer> -->
    <!-- End Footer -->

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
        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('auth_token');

            if (token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                axios.get('/api/profile')
                    .then(function (response) {
                        const userData = response.data.data;
                        document.getElementById('navbarUserName').textContent = userData.nama || 'Peserta';
                        document.getElementById('navbarProfileName').textContent = userData.nama || 'Peserta';
                    })
                    .catch(function (error) {
                        console.error('Error fetching profile data:', error);
                    });
            }
        });

        function performLogout() {
            Swal.fire({
                title: 'Apakah Anda yakin ingin logout?',
                text: "Anda akan keluar dari akun ini.",
                icon: 'warning',
                showCancelButton: true, 
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_user');

                    axios.post('/logout')
                        .then(response => {
                            window.location.href = '/login-page';
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
            });
        }

    </script>
</body>
</html>
