@extends('layouts.PesertaLayouts')

@section('title')
Arutala | Profile Peserta
@endsection

@section('style')
<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .form-control.is-invalid {
        border-color: red;
    }

    .text-danger {
        color: red;
        font-size: 0.875rem;
    }
</style>
@endsection

@section('content')
<form id="profileForm">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Profile</h1>
        <button type="submit" class="btn" style="background-color: #344C92; color: white;">
            <i class="fa-regular fa-floppy-disk"></i>
            Simpan Perubahan
        </button>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body" style="padding-top: 30px">
                        <h3>Data Diri</h3>
                        <hr>
                        <div class="column mb-4">
                            <label for="nama" class="col-sm-4 col-form-label font-weight-bold">Nama Peserta</label>
                            <div class="col-sm-12">
                                <input type="text" name="nama" id="nama" class="form-control" value="">
                                <span class="text-danger" id="error-nama"></span>
                            </div>
                        </div>

                        <div class="column mb-4">
                            <label for="email" class="col-sm-4 col-form-label font-weight-bold">Email</label>
                            <div class="col-sm-12">
                                <input type="email" name="email" id="email" class="form-control" placeholder="example@gmail.com">
                                <span class="text-danger" id="error-email"></span>
                            </div>
                        </div>

                        <div class="column mb-4">
                            <label for="no_kontak" class="col-sm-4 col-form-label font-weight-bold">No Kontak</label>
                            <div class="col-sm-12 d-flex">
                                <div class="default-internal" style="padding-top: 15px;">
                                    <label>+62</label>
                                </div>
                                <input type="number" name="no_kontak" id="no_kontak" class="form-control col-sm-8" value="">
                                <span class="text-danger" id="error-no_kontak"></span>
                            </div>
                        </div>

                        <div class="column mb-4">
                            <label for="aktivitas" class="col-sm-4 col-form-label font-weight-bold">Aktivitas</label>
                            <div class="col-sm-12 position-relative">
                                <select name="aktivitas" id="aktivitas" class="form-control">
                                    <option value="" disabled selected>Pilih Aktivitas</option>
                                    <option value="Pelajar">Pelajar</option>
                                    <option value="Mahasiswa">Mahasiswa</option>
                                    <option value="Dosen">Dosen</option>
                                    <option value="Karyawan">Karyawan</option>
                                    <option value="Pencari Kerja">Pencari Kerja</option>
                                    <option value="Lain-lain">Lain-lain</option>
                                </select>
                                <i class="fas fa-chevron-down position-absolute" style="right: 30px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                                <span class="text-danger" id="error-aktivitas"></span>
                            </div>
                        </div>

                        <div class="column mb-4" id="namaInstansiContainer" style="display: none;">
                            <label for="nama_instansi" class="col-sm-8 col-form-label font-weight-bold">Nama Instansi/Lembaga</label>
                            <div class="col-sm-12">
                                <input type="text" name="nama_instansi" id="nama_instansi" class="form-control" value="">
                                <span class="text-danger" id="error-nama_instansi"></span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body" style="padding-top: 30px">
                        <h3>Asal Wilayah</h3>
                        <hr>
                        <div class="column mb-4">
                            <label for="provinsi" class="col-sm-4 col-form-label font-weight-bold">Provinsi</label>
                            <div class="col-sm-12">
                                <select name="provinsi" id="provinsi" class="form-control select2">
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                </select>
                                <span class="text-danger" id="error-provinsi"></span>
                            </div>
                        </div>

                        <div class="column mb-4">
                            <label for="kab_kota" class="col-sm-4 col-form-label font-weight-bold">Kab/Kota</label>
                            <div class="col-sm-12">
                                <select name="kab_kota" id="kab_kota" class="form-control select2">
                                    <option value="" disabled selected>Pilih Kab/Kota</option>
                                </select>
                                <span class="text-danger" id="error-kab_kota"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body" style="padding-top: 30px">
                        <h3>Lain Lain</h3>
                        <hr>
                        <div class="column mb-4">
                            <label for="linkedin" class="col-sm-4 col-form-label font-weight-bold">LinkedIn</label>
                            <div class="col-sm-12">
                                <input type="text" name="linkedin" id="linkedin" class="form-control" value="">
                                <span class="text-danger" id="error-linkedin"></span>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: 'Pilih opsi...',
            allowClear: true
        });

        let selectedProvinsi = null;
        let selectedKabupaten = null;

        // Ambil data profil saat halaman dimuat
        const token = localStorage.getItem('auth_token');
        axios.get('/api/profile', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(function(response) {
            const profile = response.data.data;
            document.getElementById('nama').value = profile.nama || '';
            document.getElementById('email').value = profile.email || '';
            document.getElementById('no_kontak').value = profile.no_kontak || '';
            document.getElementById('aktivitas').value = profile.aktivitas || '';
            document.getElementById('nama_instansi').value = profile.nama_instansi || '';
            document.getElementById('linkedin').value = profile.linkedin || '';

            selectedProvinsi = profile.provinsi;
            selectedKabupaten = profile.kab_kota;

            toggleNamaInstansi(profile.aktivitas);

            // Panggil fungsi untuk memuat data provinsi dan kabupaten setelah profil dimuat
            loadProvinsi(selectedProvinsi, selectedKabupaten);
        })
        .catch(function(error) {
            console.error('Error fetching profile data:', error);
        });

        function loadProvinsi(selectedProvinsi, selectedKabupaten) {
            fetch('https://ibnux.github.io/data-indonesia/provinsi.json')
            .then(response => response.json())
            .then(data => {
                data.forEach(provinsi => {
                    const isSelected = (provinsi.nama === selectedProvinsi) ? 'selected' : '';
                    $('#provinsi').append(`<option value="${provinsi.id}" ${isSelected}>${provinsi.nama}</option>`);
                });

                if (selectedProvinsi) {
                    $('#provinsi').val(data.find(prov => prov.nama === selectedProvinsi).id).trigger('change');
                }
            });
        }

        $('#provinsi').change(function() {
            const provinsiId = $(this).val();
            $('#kab_kota').empty().append('<option value="" disabled selected>Pilih Kab/Kota</option>');

            if (provinsiId) {
                fetch(`https://ibnux.github.io/data-indonesia/kabupaten/${provinsiId}.json`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(kabupaten => {
                        const isSelected = (kabupaten.nama === selectedKabupaten) ? 'selected' : '';
                        $('#kab_kota').append(`<option value="${kabupaten.id}" ${isSelected}>${kabupaten.nama}</option>`);
                    });

                    if (selectedKabupaten) {
                        $('#kab_kota').val(data.find(kab => kab.nama === selectedKabupaten).id).trigger('change');
                    }
                });
            }
        });
    });

    document.getElementById('aktivitas').addEventListener('change', function () {
        toggleNamaInstansi(this.value);
    });

    function toggleNamaInstansi(aktivitas) {
        const namaInstansiContainer = document.getElementById('namaInstansiContainer');
        if (['Pelajar', 'Mahasiswa', 'Dosen', 'Karyawan'].includes(aktivitas)) {
            namaInstansiContainer.style.display = 'block';
        } else {
            namaInstansiContainer.style.display = 'none';
            document.getElementById('nama_instansi').value = '';
        }
    }

    document.getElementById('profileForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Mencegah submit otomatis

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Anda akan merubah data profil!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user menekan 'Ya, simpan', lanjutkan dengan submit form
                clearErrors();

                const token = localStorage.getItem('auth_token');
                const formData = {
                    nama: document.getElementById('nama').value,
                    email: document.getElementById('email').value,
                    no_kontak: document.getElementById('no_kontak').value,
                    aktivitas: document.getElementById('aktivitas').value,
                    nama_instansi: document.getElementById('nama_instansi').value,
                    provinsi: $('#provinsi option:selected').text(),
                    kab_kota: $('#kab_kota option:selected').text(),
                    linkedin: document.getElementById('linkedin').value,
                };

                axios.put('/api/profile/update', formData, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(function(response) {
                    Swal.fire({
                        title: 'Sukses!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            window.location.href = '/peserta/profile/';
                        }
                    });
                })
                .catch(function(error) {
                    if (error.response && error.response.data && error.response.data.errors) {
                        displayErrors(error.response.data.errors);
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat memperbarui profil.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });


    function displayErrors(errors) {
        for (let key in errors) {
            if (errors.hasOwnProperty(key)) {
                const errorElement = document.getElementById(`error-${key}`);
                const inputElement = document.getElementById(key);

                if (errorElement && inputElement) {
                    errorElement.textContent = errors[key][0];
                    inputElement.classList.add('is-invalid');
                }
            }
        }
    }

    function clearErrors() {
        const errorElements = document.querySelectorAll('.text-danger');
        const inputElements = document.querySelectorAll('.form-control');

        errorElements.forEach(element => element.textContent = '');
        inputElements.forEach(element => element.classList.remove('is-invalid'));
    }
</script>
@endsection
