@extends('layouts.PesertaLayouts')

@section('title')
Arutala | Ubah Password
@endsection

@section('content')
<style>
    .breadcrumb {
        background-color: transparent;
        padding-left: 0;
        padding-bottom: 0;
    }

    .breadcrumb-item {
        font-size: 12px;
    }

    .toggle-password {
        cursor: pointer;
    }
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/peserta/profile">Profile</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ubah Password</li>
    </ol>
</nav>
<form id="changePasswordForm">
    @csrf
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Ubah Password</h1>

        <button type="submit" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">
            Save
        </button>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="padding-top: 50px">
                        <div class="row mb-4">
                            <label for="current_password" class="col-sm-2 col-form-label">
                                Password Saat Ini <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6 position-relative">
                                <input type="password" name="current_password" id="current_password" class="form-control">
                                <span class="text-danger" id="error-current_password"></span>
                                <i class="fa-regular fa-eye toggle-password" id="toggleCurrentPassword" style="position: absolute; right: 30px; top: 10px;"></i>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="new_password" class="col-sm-2 col-form-label">
                                Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6 position-relative">
                                <input type="password" name="new_password" id="new_password" class="form-control">
                                <span class="text-danger" id="error-new_password"></span>
                                <i class="fa-regular fa-eye toggle-password" id="toggleNewPassword" style="position: absolute; right: 30px; top: 10px;"></i>
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
        const form = document.getElementById('changePasswordForm');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin mengubah password?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Clear previous errors
                    document.getElementById('error-current_password').textContent = '';
                    document.getElementById('error-new_password').textContent = '';

                    // Get form values
                    const current_password = document.getElementById('current_password').value;
                    const new_password = document.getElementById('new_password').value;

                    // Send request using Axios
                    axios.post('/api/profile/change-password', {
                        current_password: current_password,
                        new_password: new_password
                    })
                    .then(function (response) {
                        // Show success message from API
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/peserta/profile';
                            }
                        });
                    })
                    .catch(function (error) {
                        console.error('Error changing password:', error);
                        if (error.response && error.response.data && error.response.data.errors) {
                            // Display validation errors from server
                            let errors = error.response.data.errors;
                            if (errors.current_password) {
                                document.getElementById('error-current_password').textContent = errors.current_password[0];
                            }
                            if (errors.new_password) {
                                document.getElementById('error-new_password').textContent = errors.new_password[0];
                            }

                            // Show global error message
                            Swal.fire({
                                title: 'Error!',
                                text: error.response.data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat mengubah password. Kemungkinan Password Saat ini masih salah',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        // Toggle password visibility
        document.getElementById('toggleCurrentPassword').addEventListener('click', function () {
            const passwordField = document.getElementById('current_password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash'); // Change icon to eye slash if showing password
        });

        document.getElementById('toggleNewPassword').addEventListener('click', function () {
            const passwordField = document.getElementById('new_password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash'); // Change icon to eye slash if showing password
        });
    });
</script>
@endsection
