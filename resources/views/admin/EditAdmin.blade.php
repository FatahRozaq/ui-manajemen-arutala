@extends('layouts.AdminLayouts')

@section('title')
Arutala | Update Data Admin
@endsection

@section('style')
<style>
    .breadcrumb {
        background-color: transparent;
        padding-left: 0;
        padding-bottom: 0;
    }

    .breadcrumb-item {
        font-size: 12px;
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .text-danger {
        font-size: 0.875em;
    }

    /* Optional: Style for the disabled email field */
    .form-control[disabled] {
        background-color: #e9ecef;
    }
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/kelola-admin">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Update Admin</li>
    </ol>
</nav>

<form id="editAdminForm">
    @csrf
    @method('PUT')
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Update Data Admin</h1>

        <button type="submit" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">
            Save
        </button>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body" style="padding-top: 50px">

                        <!-- Nama Admin Field -->
                        <div class="row mb-4">
                            <label for="name" class="col-sm-2 col-form-label">
                                Nama Admin <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" name="name" id="name" class="form-control">
                                <span class="text-danger" id="error-name"></span>
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="row mb-4">
                            <label for="email" class="col-sm-2 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input type="email" name="email" id="email" class="form-control" disabled>
                                <span class="text-danger" id="error-email"></span>
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
        const form = document.getElementById('editAdminForm');

        // Retrieve Admin ID from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const adminId = urlParams.get('id');

        // Validation Functions
        const validateName = (name) => {
            // Example: Name must not be empty and must contain only letters and spaces
            const regex = /^[A-Za-z\s]+$/;
            return name.trim() !== '' && regex.test(name);
        };

        const validateEmail = (email) => {
            // Simple email validation regex
            const regex = /^[\w\.-]+@[a-zA-Z\d\.-]+\.(com|org|net|edu|gov|mil|int|info|co|id)$/;
            return regex.test(email);
        };

        // Function to Clear All Error Messages and Invalid Classes
        const clearErrors = () => {
            document.getElementById('error-name').textContent = '';
            document.getElementById('error-email').textContent = '';

            document.getElementById('name').classList.remove('is-invalid');
            document.getElementById('email').classList.remove('is-invalid');
        };

        // Function to Display Server-side Validation Errors
        const displayErrors = (errors) => {
            if (errors.nama) {
                document.getElementById('error-name').textContent = errors.nama[0];
                document.getElementById('name').classList.add('is-invalid');
            }
            if (errors.email) {
                document.getElementById('error-email').textContent = errors.email[0];
                document.getElementById('email').classList.add('is-invalid');
            }
        };

        // Real-time Validation for Nama Admin
        document.getElementById('name').addEventListener('input', function () {
            const nameValue = this.value;
            if (nameValue.trim() === '') {
                document.getElementById('error-name').textContent = 'Nama admin harus diisi.';
                this.classList.add('is-invalid');
            } else if (!validateName(nameValue)) {
                document.getElementById('error-name').textContent = 'Nama admin hanya boleh mengandung huruf dan spasi.';
                this.classList.add('is-invalid');
            } else {
                document.getElementById('error-name').textContent = '';
                this.classList.remove('is-invalid');
            }
        });

        // Real-time Validation for Email (if ever enabled)
        document.getElementById('email').addEventListener('input', function () {
            const emailValue = this.value;
            if (emailValue.trim() === '') {
                document.getElementById('error-email').textContent = 'Email harus diisi.';
                this.classList.add('is-invalid');
            } else if (!validateEmail(emailValue)) {
                document.getElementById('error-email').textContent = 'Email tidak valid. Harus berakhiran dengan .com, .org, .net, .edu, .gov, .mil, .int, .info, .co, .id.';
                this.classList.add('is-invalid');
            } else {
                document.getElementById('error-email').textContent = '';
                this.classList.remove('is-invalid');
            }
        });

        // Fetch Existing Admin Data and Populate the Form
        axios.get(`/api/kelola-admin/${adminId}`)
            .then(function (response) {
                const admin = response.data.data;
                document.getElementById('name').value = admin.nama;
                document.getElementById('email').value = admin.email;
            })
            .catch(function (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal mengambil data admin.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });

        // Form Submission Handler
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Clear previous errors
            clearErrors();

            // Get form values
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();

            // Validate form fields
            let isValid = true;

            // Validate Nama Admin
            if (name === '') {
                document.getElementById('error-name').textContent = 'Nama admin harus diisi.';
                document.getElementById('name').classList.add('is-invalid');
                isValid = false;
            } else if (!validateName(name)) {
                document.getElementById('error-name').textContent = 'Nama admin hanya boleh mengandung huruf dan spasi.';
                document.getElementById('name').classList.add('is-invalid');
                isValid = false;
            }

            // Validate Email (if ever enabled)
            // Since the email field is disabled, you might skip this validation.
            // Uncomment the following lines if the email field becomes editable in the future.
            /*
            if (email === '') {
                document.getElementById('error-email').textContent = 'Email harus diisi.';
                document.getElementById('email').classList.add('is-invalid');
                isValid = false;
            } else if (!validateEmail(email)) {
                document.getElementById('error-email').textContent = 'Email tidak valid. Harus berakhiran dengan .com, .org, .net, .edu, .gov, .mil, .int, .info, .co, .id.';
                document.getElementById('email').classList.add('is-invalid');
                isValid = false;
            }
            */

            if (isValid) {
                // Show confirmation before submission
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin memperbarui data admin ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Update!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Prepare data to send
                        const data = {
                            nama: name,
                            email: email, // Even though it's disabled, including it for completeness
                        };

                        // Send data via Axios
                        axios.put(`/api/kelola-admin/update/${adminId}`, data)
                            .then(function (response) {
                                Swal.fire({
                                    title: 'Sukses!',
                                    text: response.data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = `/admin/kelola-admin/detail?id=${adminId}`;
                                    }
                                });
                            })
                            .catch(function (error) {
                                if (error.response && error.response.data && error.response.data.errors) {
                                    const errors = error.response.data.errors;
                                    displayErrors(errors);
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Terjadi kesalahan saat memperbarui admin.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            });
                    }
                });
            }
        });
    });
</script>
@endsection
