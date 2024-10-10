@extends('layouts.AdminLayouts')

@section('title')
Arutala | Update Data Admin
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
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/admin">Admin</a></li>
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

                        <!-- Form for Editing Admin Details -->
                        
                        <div class="row mb-4">
                            <label for="name" class="col-sm-2 col-form-label">Nama Admin</label>
                            <div class="col-sm-6">
                                <input type="text" name="name" id="name" class="form-control">
                                <span class="text-danger" id="error-name"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
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
        const urlParams = new URLSearchParams(window.location.search);
        const adminId = urlParams.get('id');

        const form = document.getElementById('editAdminForm');

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

        form.addEventListener('submit', function (event) {
            event.preventDefault();

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
                    document.getElementById('error-name').textContent = '';
                    document.getElementById('error-email').textContent = '';

                    const name = document.getElementById('name').value;
                    const email = document.getElementById('email').value;

                    axios.put(`/api/kelola-admin/update/${adminId}`, {
                        nama: name,
                        email: email,
                    })
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
                            if (errors.nama) {
                                document.getElementById('error-name').textContent = errors.nama[0];
                            }
                            if (errors.email) {
                                document.getElementById('error-email').textContent = errors.email[0];
                            }

                            // Swal.fire({
                            //     title: 'Error!',
                            //     text: error.response.data.message,
                            //     icon: 'error',
                            //     confirmButtonText: 'OK'
                            // });
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
        });
    });
</script>
@endsection
