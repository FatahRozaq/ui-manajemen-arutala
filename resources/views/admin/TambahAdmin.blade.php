@extends('layouts.AdminLayouts')

@section('title')
Arutala | Tambah Admin
@endsection

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/kelola-admin">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Admin</li>
    </ol>
</nav>
<form id="addAdminForm">
    @csrf
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Tambah Admin</h1>

        <button type="submit" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">
            Submit
        </button>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="padding-top: 50px">
                        <!-- Nama Field -->
                        <div class="row mb-4">
                            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-6">
                                <input type="text" name="nama" id="nama" class="form-control">
                                <span class="text-danger" id="error-nama"></span>
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="row mb-4">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-6">
                                <input type="email" name="email" id="email" class="form-control">
                                <span class="text-danger" id="error-email"></span>
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="row mb-4">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-6">
                                <input type="password" name="password" id="password" class="form-control">
                                <span class="text-danger" id="error-password"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('addAdminForm');

        // Validation Functions
        const validateNama = (nama) => {
            return nama.trim() !== '';
        };

        const validateEmail = (email) => {
            const regex = /^[\w\.-]+@[a-zA-Z\d\.-]+\.(com|org|net|edu|gov|mil|int|info|co|id)$/;
            return regex.test(email);
        };

        const validatePassword = (password) => {
            // At least 8 characters, one uppercase, one lowercase, one number
            const regex = /^.{8,}$/;
            return regex.test(password);
        };

        const clearErrorMessages = () => {
            document.getElementById('error-nama').textContent = '';
            document.getElementById('error-email').textContent = '';
            document.getElementById('error-password').textContent = '';

            document.getElementById('nama').classList.remove('is-invalid');
            document.getElementById('email').classList.remove('is-invalid');
            document.getElementById('password').classList.remove('is-invalid');
        };

        // Real-time Validation Event Listeners
        document.getElementById('nama').addEventListener('input', function () {
            if (!validateNama(this.value)) {
                document.getElementById('error-nama').textContent = 'Nama harus diisi.';
                this.classList.add('is-invalid');
            } else {
                document.getElementById('error-nama').textContent = '';
                this.classList.remove('is-invalid');
            }
        });

        document.getElementById('email').addEventListener('input', function () {
            if (this.value.trim() === '') {
                document.getElementById('error-email').textContent = 'Email harus diisi.';
                this.classList.add('is-invalid');
            } else if (!validateEmail(this.value)) {
                document.getElementById('error-email').textContent = 'Email tidak valid. Harus berakhiran dengan .com, .org, .net, .edu, .gov, .mil, .int, .info, .co, .id.';
                this.classList.add('is-invalid');
            } else {
                document.getElementById('error-email').textContent = '';
                this.classList.remove('is-invalid');
            }
        });

        document.getElementById('password').addEventListener('input', function () {
            if (this.value.trim() === '') {
                document.getElementById('error-password').textContent = 'Password harus diisi.';
                this.classList.add('is-invalid');
            } else if (!validatePassword(this.value)) {
                document.getElementById('error-password').textContent = 'Password minimal 8 karakter';
                this.classList.add('is-invalid');
            } else {
                document.getElementById('error-password').textContent = '';
                this.classList.remove('is-invalid');
            }
        });

        // Form Submission Handler
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Clear previous errors
            clearErrorMessages();

            // Get form values
            const nama = document.getElementById('nama').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            // Validate all fields
            let isValid = true;

            if (!validateNama(nama)) {
                document.getElementById('error-nama').textContent = 'Nama harus diisi.';
                document.getElementById('nama').classList.add('is-invalid');
                isValid = false;
            }

            if (email === '') {
                document.getElementById('error-email').textContent = 'Email harus diisi.';
                document.getElementById('email').classList.add('is-invalid');
                isValid = false;
            } else if (!validateEmail(email)) {
                document.getElementById('error-email').textContent = 'Email tidak valid. Harus berakhiran dengan .com, .org, .net, .edu, .gov, .mil, .int, .info, .co, .id.';
                document.getElementById('email').classList.add('is-invalid');
                isValid = false;
            }

            if (password === '') {
                document.getElementById('error-password').textContent = 'Password harus diisi.';
                document.getElementById('password').classList.add('is-invalid');
                isValid = false;
            } else if (!validatePassword(password)) {
                document.getElementById('error-password').textContent = 'Password minimal 8 karakter.';
                document.getElementById('password').classList.add('is-invalid');
                isValid = false;
            }

            if (isValid) {
                // Show confirmation before submission
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menambahkan admin ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Tambah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Prepare data
                        const data = {
                            nama: nama,
                            email: email,
                            password: password,
                        };

                        // Send data via Axios
                        axios.post('/api/register-admin', data)
                            .then(function (response) {
                                Swal.fire({
                                    title: 'Sukses!',
                                    text: response.data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '/admin/kelola-admin/';
                                    }
                                });
                            })
                            .catch(function (error) {
                                console.error('Error adding admin:', error);
                                if (error.response && error.response.data && error.response.data.errors) {
                                    // Tampilkan pesan error dari server
                                    displayErrors(error.response.data.errors);
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Terjadi kesalahan saat menambahkan admin.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            });
                    }
                });
            }
        });

        // Function to display server-side validation errors
        function displayErrors(errors) {
            if (errors.nama) {
                document.getElementById('error-nama').textContent = errors.nama[0];
                document.getElementById('nama').classList.add('is-invalid');
            }
            if (errors.email) {
                document.getElementById('error-email').textContent = errors.email[0];
                document.getElementById('email').classList.add('is-invalid');
            }
            if (errors.password) {
                document.getElementById('error-password').textContent = errors.password[0];
                document.getElementById('password').classList.add('is-invalid');
            }
        }
    });
</script>
@endsection
