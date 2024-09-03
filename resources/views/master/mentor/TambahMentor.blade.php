@extends('layouts.AdminLayouts')

@section('title')
Arutala | Tambah Mentor
@endsection

@section('style')
<style>
  .default {
        padding: 6px 12px;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 4px 0 0 4px;
        color: #495057;
        display: flex;
        align-items: center;
        justify-content: center;
        height:39px
    }

    .form-control {
        border-radius: 0 4px 4px 0;
    }
</style>
@endsection


@section('content')

<div class="pagetitle">
    <h1>Tambah Mentor</h1>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body" style="padding-top: 50px">

                    <!-- Form for Adding Mentor -->
                    <form id="addMentorForm">
                        @csrf

                        <div class="row mb-4">
                            <label for="nama_mentor" class="col-sm-3 col-form-label">Nama Mentor</label>
                            <div class="col-sm-6">
                                <input type="text" name="nama_mentor" id="nama_mentor" class="form-control">
                                <span class="text-danger" id="error-nama_mentor"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-6">
                                <input type="email" name="email" id="email" class="form-control">
                                <span class="text-danger" id="error-email"></span>
                            </div>
                        </div>

                        

                        <div class="row mb-4">
                            <label for="inputKontak" class="col-sm-3 col-form-label">Kontak</label>
                            
                            <div class="col-sm-6 d-flex">
                                <div class="default">
                                    +62
                                </div>
                                <div class="">
                                    <input type="text" name="no_kontak" id="no_kontak" class="form-control">
                                    <span class="text-danger" id="error-no_kontak"></span>
                                </div>
                            </div>
                            
                        </div>

                        <div class="row mb-4">
                            <label for="aktivitas" class="col-sm-3 col-form-label">Aktivitas</label>
                            <div class="col-sm-6">
                                <div class="custom-select-wrapper position-relative">
                                    <select name="aktivitas" id="aktivitas" class="form-control">
                                        <option value="" disabled selected>Pilih Aktivitas</option>
                                        <option value="Mahasiswa">Mahasiswa</option>
                                        <option value="Dosen">Dosen</option>
                                        <option value="Karyawan">Karyawan</option>
                                        <option value="Freelance">Freelance</option>
                                    </select>
                                    <i class="fa fa-chevron-down position-absolute"
                                        style="right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                                </div>
                                <span class="text-danger" id="error-aktivitas"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-11 text-right">
                                <button type="submit" class="btn" style="background-color: #344C92; color: white;">Submit</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('addMentorForm');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Clear previous errors
            document.getElementById('error-nama_mentor').textContent = '';
            document.getElementById('error-email').textContent = '';
            document.getElementById('error-no_kontak').textContent = '';
            document.getElementById('error-aktivitas').textContent = '';

            // Ambil nilai dari form
            const nama_mentor = document.getElementById('nama_mentor').value;
            const email = document.getElementById('email').value;
            const no_kontak = document.getElementById('no_kontak').value;
            const aktivitas = document.getElementById('aktivitas').value;

            // Kirim data menggunakan Axios
            axios.post('/api/mentor/tambah', {
                nama_mentor: nama_mentor,
                email: email,
                no_kontak: no_kontak,
                aktivitas: aktivitas
            })
            .then(function (response) {
                // Tampilkan pesan sukses dari API menggunakan SweetAlert
                Swal.fire({
                    title: 'Sukses!',
                    text: response.data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/admin/mentor';
                    }
                });
            })
            .catch(function (error) {
                console.error('Error adding mentor:', error);
                if (error.response && error.response.data && error.response.data.errors) {
                    // Tampilkan pesan error dari server
                    let errors = error.response.data.errors;
                    if (errors.nama_mentor) {
                        document.getElementById('error-nama_mentor').textContent = errors.nama_mentor[0];
                    }
                    if (errors.email) {
                        document.getElementById('error-email').textContent = errors.email[0];
                    }
                    if (errors.no_kontak) {
                        document.getElementById('error-no_kontak').textContent = errors.no_kontak[0];
                    }
                    if (errors.aktivitas) {
                        document.getElementById('error-aktivitas').textContent = errors.aktivitas[0];
                    }

                    // Tampilkan pesan error global dari API menggunakan SweetAlert
                    Swal.fire({
                        title: 'Error!',
                        text: error.response.data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menambahkan mentor.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
@endsection
