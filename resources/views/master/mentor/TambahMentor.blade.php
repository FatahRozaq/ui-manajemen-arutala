@extends('layouts.AdminLayouts')

@section('title')
Arutala | Tambah Mentor
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
        <li class="breadcrumb-item"><a href="/admin/mentor">Mentor</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Mentor</li>
    </ol>
</nav>
<form id="addMentorForm">
    @csrf
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Tambah Mentor</h1>

        <button type="submit" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">
            Submit
        </button>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="padding-top: 50px">
                        <div class="row mb-4">
                            <label for="nama_mentor" class="col-sm-2 col-form-label">Nama Mentor</label>
                            <div class="col-sm-6">
                                <input type="text" name="nama_mentor" id="nama_mentor" class="form-control">
                                <span class="text-danger" id="error-nama_mentor"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-6">
                                <input type="email" name="email" id="email" class="form-control">
                                <span class="text-danger" id="error-email"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="inputKontak" class="col-sm-2 col-form-label">Kontak</label>
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
                            <label for="aktivitas" class="col-sm-2 col-form-label">Aktivitas</label>
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
    const form = document.getElementById('addMentorForm');

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

    document.getElementById('nama_mentor').addEventListener('input', function () {
        if (this.value.trim() === '') {
            document.getElementById('error-nama_mentor').textContent = 'Nama mentor harus diisi.';
            this.classList.add('is-invalid');
        } else {
            document.getElementById('error-nama_mentor').textContent = '';
            this.classList.remove('is-invalid');
        }
    });

    document.getElementById('email').addEventListener('input', function () {
        if (this.value.trim() === '') {
            document.getElementById('error-email').textContent = 'Email harus diisi.';
            this.classList.add('is-invalid');
        } else if (!validateEmail(this.value)) {
            document.getElementById('error-email').textContent = 'Email tidak valid. Email harus berakhiran dengan domain valid .com, .org, .net, .edu, gov, .mil, .int, .info, .co, .id .';
            this.classList.add('is-invalid');
        } else {
            document.getElementById('error-email').textContent = '';
            this.classList.remove('is-invalid');
        }
    });

    document.getElementById('no_kontak').addEventListener('input', function () {
        if (this.value.trim() === '') {
            document.getElementById('error-no_kontak').textContent = 'Kontak harus diisi.';
            this.classList.add('is-invalid');
        } else if (!validateNoKontak(this.value)) {
            document.getElementById('error-no_kontak').textContent = 'Kontak tidak valid.';
            this.classList.add('is-invalid');
        } else {
            document.getElementById('error-no_kontak').textContent = '';
            this.classList.remove('is-invalid');
        }
    });

    document.getElementById('aktivitas').addEventListener('change', function () {
        if (this.value.trim() === '') {
            document.getElementById('error-aktivitas').textContent = 'Aktivitas harus diisi.';
        } else {
            document.getElementById('error-aktivitas').textContent = '';
        }
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        // Clear previous errors
        clearErrorMessages();

        // Validate all fields before submission
        let isValid = true;

        if (document.getElementById('nama_mentor').value.trim() === '') {
            document.getElementById('error-nama_mentor').textContent = 'Nama mentor harus diisi.';
            document.getElementById('nama_mentor').classList.add('is-invalid');
            isValid = false;
        }

        const emailValue = document.getElementById('email').value.trim();
        if (emailValue === '') {
            document.getElementById('error-email').textContent = 'Email harus diisi.';
            document.getElementById('email').classList.add('is-invalid');
            isValid = false;
        } else if (!validateEmail(emailValue)) {
            document.getElementById('error-email').textContent = 'Email tidak valid. ';
            document.getElementById('email').classList.add('is-invalid');
            isValid = false;
        }

        const noKontakValue = document.getElementById('no_kontak').value.trim();
        if (noKontakValue === '') {
            document.getElementById('error-no_kontak').textContent = 'Kontak harus diisi.';
            document.getElementById('no_kontak').classList.add('is-invalid');
            isValid = false;
        } else if (!validateNoKontak(noKontakValue)) {
            document.getElementById('error-no_kontak').textContent = 'Kontak tidak valid.';
            document.getElementById('no_kontak').classList.add('is-invalid');
            isValid = false;
        }

        if (document.getElementById('aktivitas').value.trim() === '') {
            document.getElementById('error-aktivitas').textContent = 'Aktivitas harus diisi.';
            isValid = false;
        }

        if (isValid) {
            // Show confirmation before submission
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menambahkan mentor ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tambahkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Clear previous errors
                    clearErrorMessages();

                    // Get values from the form
                    const nama_mentor = document.getElementById('nama_mentor').value;
                    const email = document.getElementById('email').value;
                    const no_kontak = document.getElementById('no_kontak').value;
                    const aktivitas = document.getElementById('aktivitas').value;

                    // Send data via Axios
                    axios.post('/admin/mentor/store', {
                        nama_mentor: nama_mentor,
                        email: email,
                        no_kontak: no_kontak,
                        aktivitas: aktivitas
                    })
                    .then(response => {
                        Swal.fire('Sukses!', response.data.message, 'success');
                        setTimeout(() => {
                            window.location.href = "/admin/mentor";
                        }, 2000);
                    })
                    .catch(error => {
                        if (error.response) {
                            Swal.fire('Error!', error.response.data.message, 'error');
                        }
                    });
                }
            });
        }
    });
});
</script>
@endsection
