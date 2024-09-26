@extends('layouts.AdminLayouts')

@section('title')
Arutala | Tambah Admin
@endsection

@section('style')
<style>
    .form-control {
        border-radius: 0 4px 4px 0;
    }
</style>
@endsection

@section('content')
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
                            <div class="row mb-4">
                                <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-6">
                                    <input type="text" name="nama" id="nama" class="form-control">
                                    <span class="text-danger" id="error-nama"></span>
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

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            
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
                    // Clear previous errors
                    clearErrors();

                    // Ambil nilai dari form
                    const nama = document.getElementById('nama').value;
                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;

                    // Kirim data menggunakan Axios
                    axios.post('/api/register-admin', {
                        nama: nama,
                        email: email,
                        password: password,
                    })
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
        });

        function displayErrors(errors) {
            // Tampilkan pesan error di setiap kolom yang relevan
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

        function clearErrors() {
            // Bersihkan pesan error sebelumnya
            document.getElementById('error-nama').textContent = '';
            document.getElementById('error-email').textContent = '';
            document.getElementById('error-password').textContent = '';

            // Hapus kelas is-invalid
            document.getElementById('nama').classList.remove('is-invalid');
            document.getElementById('email').classList.remove('is-invalid');
            document.getElementById('password').classList.remove('is-invalid');
        }
    });

</script>
@endsection
