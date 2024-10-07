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
            <ul class="d-flex notif-user">
                <li class="nav-item dropdown pe-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" id="messagesDropdown">
                            <i class="bi bi-bell" style="font-size: 22px"></i>
                            <span class="badge bg-success badge-number" id="notification-badge" style="font-size: 10px; margin-top:10px; margin-right:5px;">0</span>
                        </a><!-- End Messages Icon -->
                    
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                            <li class="dropdown-header">
                                <span id="notification-count">0</span> pelatihan yang belum dibaca
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                    
                            <div id="notification-list">
                                <!-- Notification items will be loaded here -->
                            </div>
                    
                            <li class="dropdown-footer">
                                <a href="{{ route('event.history') }}">Lihat semua pelatihan anda</a>
                            </li>
                        </ul>
                    </li>
                    
                    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        function loadNotifications() {
                            const authToken = localStorage.getItem('auth_token'); // Ambil token dari localStorage
                            if (!authToken) {
                                console.error('No auth token found');
                                return;
                            }
                    
                            // Ambil notifikasi yang sudah dibaca dari localStorage
                            let readNotifications = JSON.parse(localStorage.getItem('read_notifications')) || [];
                            console.log("Read notifications from localStorage on page load:", readNotifications); // Debugging
                    
                            // Pastikan semua ID disimpan sebagai string
                            readNotifications = readNotifications.map(String);
                    
                            // Lakukan request ke API untuk mengambil notifikasi
                            axios.get('/api/my-notifications', {
                                headers: {
                                    'Authorization': `Bearer ${authToken}` // Kirim token di header Authorization
                                }
                            })
                            .then(function(response) {
                                const notifications = response.data.data; // Ambil data notifikasi dari response
                                const notificationList = document.getElementById('notification-list');
                                notificationList.innerHTML = ''; // Kosongkan daftar notifikasi sebelum diisi ulang
                    
                                // Filter notifikasi yang belum dibaca
                                const unreadNotifications = notifications.filter(function(notification) {
                                    return !readNotifications.includes(String(notification.id_pendaftaran)); // Perbandingan dengan string
                                });
                    
                                console.log("Unread notifications:", unreadNotifications); // Debugging
                    
                                // Update jumlah notifikasi unread pada badge
                                const unreadCount = unreadNotifications.length;
                                document.getElementById('notification-badge').textContent = unreadCount;
                                document.getElementById('notification-count').textContent = unreadCount;
                    
                                // Hanya tampilkan notifikasi yang belum dibaca
                                if (unreadNotifications.length > 0) {
                                    unreadNotifications.forEach(function(notification) {
                                        // Buat item HTML untuk setiap notifikasi dengan latar belakang berbeda
                                        const listItem = `
                                            <li class="message-item unread" data-id="${notification.id_pendaftaran}">
                                                <a href="javascript:void(0)">
                                                    <img 
                                                        src="${notification.gambar_pelatihan ? notification.gambar_pelatihan : '/assets/images/default-pelatihan.jpg'}"
                                                        alt="Pelatihan"
                                                        width="40"
                                                        height="auto"
                                                        onerror="this.onerror=null;this.src='/assets/images/default-pelatihan.jpg';"
                                                    >
                                                    <div>
                                                        <h4>${notification.nama_pelatihan}</h4>
                                                        <p style="color: red;">Belum dibayar</p>
                                                        <p>${new Date(notification.start_date).toLocaleDateString()} - ${new Date(notification.end_date).toLocaleDateString()}</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                        `;
                                        // Tambahkan item ke dalam daftar
                                        notificationList.insertAdjacentHTML('beforeend', listItem);
                                    });
                                } else {
                                    // Jika tidak ada notifikasi yang belum dibaca, tampilkan pesan kosong
                                    notificationList.innerHTML = `
                                        <li class="message-item">
                                            <div>
                                                <p></p>
                                            </div>
                                        </li>
                                    `;
                                }
                    
                                // Tambahkan event listener untuk mengklik notifikasi
                                document.querySelectorAll('.message-item.unread').forEach(function(item) {
                                    item.addEventListener('click', function() {
                                        const notificationId = this.getAttribute('data-id');
                                        console.log("Clicked notification ID:", notificationId); // Debugging
                    
                                        // Simpan ID notifikasi yang sudah dibaca ke localStorage
                                        if (!readNotifications.includes(notificationId)) {
                                            readNotifications.push(String(notificationId)); // Simpan sebagai string
                                            localStorage.setItem('read_notifications', JSON.stringify(readNotifications));
                                            console.log("Updated read notifications in localStorage:", readNotifications); // Debugging
                                        }
                    
                                        // Redirect ke halaman event history
                                        window.location.href = "{{ route('event.history') }}"; // Arahkan ke halaman history
                                    });
                                });
                            })
                            .catch(function(error) {
                                console.error('Error fetching notifications:', error);
                                const notificationList = document.getElementById('notification-list');
                                notificationList.innerHTML = '<p class="error">Gagal memuat data notifikasi. Silakan coba lagi nanti.</p>';
                            });
                        }
                    
                        // Panggil fungsi loadNotifications saat halaman dimuat
                        loadNotifications();
                    });
                    
                    </script>
                    
                    <style>
                        /* Warna latar belakang untuk notifikasi yang belum dibaca */
                        .message-item.unread {
                            background-color: #f0f0f0; /* Warna abu-abu untuk yang belum dibaca */
                        }
                    </style>
                    
                    
                    
                    
                    

                    <a class="nav-link nav-profile d-flex pe-0 mr-5" href="#" data-bs-toggle="dropdown">
                        <span id="navbarUserName" class="d-none d-md-block dropdown-toggle ps-2" style="font-size: 14px;">Peserta</span>
                        <i class="fa-solid fa-circle-user" style="font-size: 25px; margin-left:20px; margin-right:20px;"></i>
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
                <a class="nav-link {{ request()->is('daftar-produk*') ? '' : 'collapsed' }}" href="{{ route('event.produk') }}">
                    <i class="bi bi-list-check"></i>
                    <span>Daftar Produk</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('daftar-event*', 'peserta/pendaftaran*', 'event*') ? '' : 'collapsed' }}" href="{{ route('event.index') }}">
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
