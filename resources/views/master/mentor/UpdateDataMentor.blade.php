@extends('layouts.AdminLayouts')

@section('title')
Arutala | Update Data Mentor
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

    .default-internal {
        padding: 0.375rem 0.75rem;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem 0 0 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-control {
        border-radius: 0 0.25rem 0.25rem 0;
    }
</style>
  
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/admin/mentor">Mentor</a></li>
      <li class="breadcrumb-item active" aria-current="page">Update Mentor</li>
    </ol>
</nav>

<form id="editMentorForm">
@csrf
@method('PUT')
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Update Data Mentor</h1>

        <button type="submit" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">
            Save
        </button>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body" style="padding-top: 50px">

                        <!-- Form for Editing Mentor Details -->

                        <div class="row mb-4">
                            <label for="nama_mentor" class="col-sm-2 col-form-label">
                                Nama Mentor <span class="text-danger">*</span> 
                            </label>
                            <div class="col-sm-6">
                                <input type="text" name="nama_mentor" id="nama_mentor" class="form-control">
                                <span class="text-danger" id="error-nama_mentor"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="email" class="col-sm-2 col-form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="email" name="email" id="email" class="form-control">
                                <span class="text-danger" id="error-email"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="no_kontak" class="col-sm-2 col-form-label">
                                Kontak <span class="text-danger">*</span>
                            </label>
                            
                            <div class="col-sm-6 d-flex">
                                <div class="default-internal">
                                    +62
                                </div>
                                <div class="">
                                    <input type="text" name="no_kontak" id="no_kontak" class="form-control">
                                    <span class="text-danger" id="error-no_kontak"></span>
                                </div>
                            </div>
                            
                        </div>

                        <div class="row mb-4">
                            <label for="aktivitas" class="col-sm-2 col-form-label">
                                Aktivitas <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <div class="custom-select-wrapper position-relative">
                                    <select name="aktivitas" id="aktivitas" class="form-control">
                                        <option value="" disabled>Pilih Aktivitas</option>
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
    const form = document.getElementById('editMentorForm');

    // Function to retrieve mentor ID from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const mentorId = urlParams.get('id');

    // Validation Functions
    const validateEmail = (email) => {
        const regex = /^[\w\.-]+@[a-zA-Z\d\.-]+\.(com|org|net|edu|gov|mil|int|info|co|id)$/;
        return regex.test(email);
    };

    const validateNoKontak = (no_kontak) => {
        const regex = /^\+?[1-9][0-9]{9,14}$/;
        return regex.test(no_kontak);
    };

    const clearErrorMessages = () => {
        document.getElementById('error-nama_mentor').textContent = '';
        document.getElementById('error-email').textContent = '';
        document.getElementById('error-no_kontak').textContent = '';
        document.getElementById('error-aktivitas').textContent = '';
    };

    // Real-time Validation Event Listeners
    document.getElementById('nama_mentor').addEventListener('input', function () {
        if (this.value.trim() === '') {
            document.getElementById('error-nama_mentor').textContent = 'Nama mentor harus diisi.';
        } else {
            document.getElementById('error-nama_mentor').textContent = '';
        }
    });

    document.getElementById('email').addEventListener('input', function () {
        if (this.value.trim() === '') {
            document.getElementById('error-email').textContent = 'Email harus diisi.';
        } else if (!validateEmail(this.value)) {
            document.getElementById('error-email').textContent = 'Email tidak valid. Email harus berakhiran dengan domain valid .com, .org, .net, .edu, gov, .mil, .int, .info, .co, .id.';
        } else {
            document.getElementById('error-email').textContent = '';
        }
    });

    document.getElementById('no_kontak').addEventListener('input', function () {
        if (this.value.trim() === '') {
            document.getElementById('error-no_kontak').textContent = 'Kontak harus diisi.';
        } else if (!validateNoKontak(this.value)) {
            document.getElementById('error-no_kontak').textContent = 'Kontak tidak valid.';
        } else {
            document.getElementById('error-no_kontak').textContent = '';
        }
    });

    document.getElementById('aktivitas').addEventListener('change', function () {
        if (this.value.trim() === '') {
            document.getElementById('error-aktivitas').textContent = 'Aktivitas harus diisi.';
        } else {
            document.getElementById('error-aktivitas').textContent = '';
        }
    });

    // Fetch Existing Mentor Data
    axios.get(`/api/mentor/${mentorId}`)
        .then(function (response) {
            const mentor = response.data.data;
            document.getElementById('nama_mentor').value = mentor.nama_mentor;
            document.getElementById('email').value = mentor.email;
            document.getElementById('no_kontak').value = mentor.no_kontak;
            document.getElementById('aktivitas').value = mentor.aktivitas;
        })
        .catch(function (error) {
            Swal.fire({
                title: 'Error!',
                text: 'Gagal mengambil data mentor.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });

    // Form Submission Handler
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        // Clear previous errors
        clearErrorMessages();

        // Validate all fields before submission
        let isValid = true;

        const namaMentor = document.getElementById('nama_mentor').value.trim();
        if (namaMentor === '') {
            document.getElementById('error-nama_mentor').textContent = 'Nama mentor harus diisi.';
            isValid = false;
        }

        const emailValue = document.getElementById('email').value.trim();
        if (emailValue === '') {
            document.getElementById('error-email').textContent = 'Email harus diisi.';
            isValid = false;
        } else if (!validateEmail(emailValue)) {
            document.getElementById('error-email').textContent = 'Email tidak valid. Email harus berakhiran dengan domain valid .com, .org, .net, .edu, gov, .mil, .int, .info, .co, .id.';
            isValid = false;
        }

        const noKontakValue = document.getElementById('no_kontak').value.trim();
        if (noKontakValue === '') {
            document.getElementById('error-no_kontak').textContent = 'Kontak harus diisi.';
            isValid = false;
        } else if (!validateNoKontak(noKontakValue)) {
            document.getElementById('error-no_kontak').textContent = 'Kontak tidak valid.';
            isValid = false;
        }

        const aktivitasValue = document.getElementById('aktivitas').value.trim();
        if (aktivitasValue === '') {
            document.getElementById('error-aktivitas').textContent = 'Aktivitas harus diisi.';
            isValid = false;
        }

        if (isValid) {
            // Show confirmation before submission
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
                    // Get values from the form
                    const data = {
                        nama_mentor: namaMentor,
                        email: emailValue,
                        no_kontak: noKontakValue,
                        aktivitas: aktivitasValue
                    };

                    // Send data via Axios
                    axios.put(`/api/mentor/update/${mentorId}`, data)
                    .then(function (response) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `/admin/mentor/detail?id=${mentorId}`;
                            }
                        });
                    })
                    .catch(function (error) {
                        if (error.response && error.response.data && error.response.data.errors) {
                            const errors = error.response.data.errors;
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
        }
    });
});
</script>
@endsection
