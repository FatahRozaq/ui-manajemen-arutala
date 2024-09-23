@extends('layouts.AdminLayouts')

@section('title')
Arutala | Update Data Mentor
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
    <h1>Update Data Mentor</h1>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body" style="padding-top: 50px">

                    <!-- Form for Editing Mentor Details -->
                    <form id="editMentorForm">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <label for="name" class="col-sm-3 col-form-label">Nama Mentor</label>
                            <div class="col-sm-6">
                                <input type="text" name="name" id="name" class="form-control">
                                <span class="text-danger" id="error-name"></span>
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
                                    <input type="text" name="contact" id="contact" class="form-control">
                                    <span class="text-danger" style="" id="error-contact"></span>
                                </div>
                            </div>
                            
                        </div>

                        <div class="row mb-4">
                            <label for="activity" class="col-sm-3 col-form-label">Aktivitas</label>
                            <div class="col-sm-6">
                                <div class="custom-select-wrapper position-relative">
                                    <select name="activity" id="activity" class="form-control">
                                        <option value="" disabled>Pilih Aktivitas</option>
                                        <option value="Mahasiswa">Mahasiswa</option>
                                        <option value="Dosen">Dosen</option>
                                        <option value="Karyawan">Karyawan</option>
                                        <option value="Freelance">Freelance</option>
                                    </select>
                                    <i class="fa fa-chevron-down position-absolute"
                                        style="right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                                </div>
                                <span class="text-danger" id="error-activity"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-11 text-right">
                                <button type="submit" class="btn" style="background-color: #344C92; color: white;">Save</button>
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
        const urlParams = new URLSearchParams(window.location.search);
        const mentorId = urlParams.get('id');

        const form = document.getElementById('editMentorForm');

        axios.get(`/api/mentor/${mentorId}`)
            .then(function (response) {
                const mentor = response.data.data;
                document.getElementById('name').value = mentor.nama_mentor;
                document.getElementById('email').value = mentor.email;
                document.getElementById('contact').value = mentor.no_kontak;
                document.getElementById('activity').value = mentor.aktivitas;
            })
            .catch(function (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal mengambil data mentor.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Tampilkan pesan konfirmasi sebelum mengupdate data
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin memperbarui data mentor ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Clear previous errors
                    document.getElementById('error-name').textContent = '';
                    document.getElementById('error-email').textContent = '';
                    document.getElementById('error-contact').textContent = '';
                    document.getElementById('error-activity').textContent = '';

                    const name = document.getElementById('name').value;
                    const email = document.getElementById('email').value;
                    const contact = document.getElementById('contact').value;
                    const activity = document.getElementById('activity').value;

                    axios.put(`/api/mentor/update/${mentorId}`, {
                        nama_mentor: name,
                        email: email,
                        no_kontak: contact,
                        aktivitas: activity
                    })
                    .then(function (response) {
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
                        console.error('Error updating mentor:', error);
                        if (error.response && error.response.data && error.response.data.errors) {
                            const errors = error.response.data.errors;
                            if (errors.nama_mentor) {
                                document.getElementById('error-name').textContent = errors.nama_mentor[0];
                            }
                            if (errors.email) {
                                document.getElementById('error-email').textContent = errors.email[0];
                            }
                            if (errors.no_kontak) {
                                document.getElementById('error-contact').textContent = errors.no_kontak[0];
                            }
                            if (errors.aktivitas) {
                                document.getElementById('error-activity').textContent = errors.aktivitas[0];
                            }

                            Swal.fire({
                                title: 'Error!',
                                text: error.response.data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memperbarui mentor.',
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
