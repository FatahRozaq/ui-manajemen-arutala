@extends('layouts.AdminLayouts')

@section('title')
Arutala | Update Data Pendaftar
@endsection

@section('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .training-card {
        margin-bottom: 20px;
    }
    @media (min-width: 768px) {
        .training-card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .training-card {
            width: 48%;
        }
    }
    @media (max-width: 767px) {
        .training-card {
            width: 100%;
        }
    }

    h3 {
        margin-top: 50px;
        font-weight: bold;
    }

    .form-control.is-invalid {
        border-color: red;
    }
    .text-danger {
        color: red;
        font-size: 0.875rem;
    }

    .form-control {
        border-radius: 0 4px 4px 0;
    }
</style>
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
        <li class="breadcrumb-item"><a href="/admin/pendaftar">Pendaftar</a></li>
        <li class="breadcrumb-item active" aria-current="page">Update Pendaftar</li>
    </ol>
</nav>

<form id="updatePesertaForm">
    @csrf
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Update Data Pendaftar</h1>
        <button type="submit" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">
            <i class="fa-regular fa-floppy-disk"></i>
            Simpan Perubahan
        </button>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body" style="padding-top: 20px">
                        
                        <div class="row mb-4">
                            <label for="namaPeserta" class="col-sm-2 col-form-label">Nama Peserta</label>
                            <div class="col-sm-6">
                                <input type="text" id="namaPeserta" name="nama" class="form-control">
                                <span class="text-danger" id="error-nama"></span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="emailPeserta" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-6">
                                <input type="email" id="emailPeserta" name="email" class="form-control" disabled>
                                <span class="text-danger" id="error-email"></span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="kontakPeserta" class="col-sm-2 col-form-label">Kontak</label>
                            <div class="col-sm-6 d-flex">
                                <div class="default-internal">+62</div>
                                <div class="">
                                    <input type="text" id="kontakPeserta" name="no_kontak" class="form-control col-sm-12">
                                    <span class="text-danger" id="error-no_kontak"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="linkedinPeserta" class="col-sm-2 col-form-label">LinkedIn</label>
                            <div class="col-sm-6">
                                <input type="text" id="linkedinPeserta" name="linkedin" class="form-control">
                                <span class="text-danger" id="error-linkedin"></span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="aktivitasPeserta" class="col-sm-2 col-form-label">Aktivitas</label>
                            <div class="col-sm-6 ">
                                <div class="custom-select-wrapper position-relative">
                                    <select name="aktivitas" id="aktivitasPeserta" class="form-control">
                                        <option value="" disabled selected>Pilih Aktivitas</option>
                                        <option value="Pelajar">Pelajar</option>
                                        <option value="Mahasiswa">Mahasiswa</option>
                                        <option value="Fresh Graduate">Fresh Graduate</option>
                                        <option value="Dosen">Dosen</option>
                                        <option value="Karyawan">Karyawan</option>
                                        <option value="Pencari Kerja">Pencari Kerja</option>
                                        <option value="Lain-lain">Lain-lain</option>
                                    </select>
                                    <i class="fas fa-chevron-down position-absolute" style="right: 30px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                                </div>
                                <span class="text-danger" id="error-aktivitas"></span>
                            </div>
                        </div>

                        <!-- Asal Wilayah -->
                        <h3>Asal Wilayah</h3>
                        <div class="row" style="margin-left: -15px;">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="provinsiPeserta" class="col-form-label">Provinsi</label>
                                <select name="provinsi" id="provinsiPeserta" class="form-control select2">
                                </select>
                                <span class="text-danger" id="error-provinsi"></span>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="kabkotaPeserta" class="col-form-label">Kab/Kota</label>
                                <select name="kab_kota" id="kabkotaPeserta" class="form-control select2">
                                </select>
                                <span class="text-danger" id="error-kab_kota"></span>
                            </div>
                        </div>

                        <!-- Instansi/Lembaga -->
                        <h3 id="namaInstansiTitle" style="display: none;">Instansi/Lembaga</h3>
                        <div class="row mb-5 mr-3 mt-4" id="namaInstansiContainer" style="display: none;">
                            <label for="instansiPeserta" class="col-sm-2 col-form-label">Instansi</label>
                            <div class="col-sm-6">
                                <input type="text" id="instansiPeserta" name="nama_instansi" class="form-control">
                                <span class="text-danger" id="error-nama_instansi"></span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

</form>

<!-- Include Axios and SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        const urlParams = new URLSearchParams(window.location.search);
        const idPendaftar = urlParams.get('idPendaftar');
        const apiUrl = `/api/pendaftar/${idPendaftar}`;

        // Fetch data peserta untuk mengisi form
        axios.get(apiUrl)
            .then(response => {
                if (response.data.status === 'success') {
                    const data = response.data.data;
                    document.getElementById('namaPeserta').value = data.nama;
                    document.getElementById('emailPeserta').value = data.email;
                    document.getElementById('kontakPeserta').value = data.no_kontak.replace('+62', '');
                    document.getElementById('linkedinPeserta').value = data.linkedin;
                    document.getElementById('aktivitasPeserta').value = data.aktivitas;
                    document.getElementById('provinsiPeserta').value = data.provinsi || '';
                    document.getElementById('kabkotaPeserta').value = data.kab_kota;
                    document.getElementById('instansiPeserta').value = data.nama_instansi;

                    selectedProvinsi = data.provinsi;
                    selectedKabupaten = data.kab_kota;
                    toggleNamaInstansi(data.aktivitas);

                    loadProvinsi(selectedProvinsi, selectedKabupaten);
                } else {
                    console.error(response.data.message);
                }
            })
            .catch(error => console.error('Error:', error));

        function loadProvinsi(selectedProvinsi, selectedKabupaten) {
            fetch('https://ibnux.github.io/data-indonesia/provinsi.json')
            .then(response => response.json())
            .then(data => {
                data.forEach(provinsi => {
                    const isSelected = (provinsi.nama === selectedProvinsi) ? 'selected' : '';
                    $('#provinsiPeserta').append(`<option value="${provinsi.id}" ${isSelected}>${provinsi.nama}</option>`);
                });

                if (selectedProvinsi) {
                    $('#provinsiPeserta').val(data.find(prov => prov.nama === selectedProvinsi).id).trigger('change');
                }
            });
        }

        function loadKabupaten(provinsiId, selectedKabupaten) {
            fetch(`https://ibnux.github.io/data-indonesia/kabupaten/${provinsiId}.json`)
            .then(response => response.json())
            .then(data => {
                $('#kabkotaPeserta').empty();
                $('#kabkotaPeserta').append('<option value="" disabled selected>Pilih Kab/Kota</option>');
                data.forEach(kab => {
                    const isSelected = (kab.id === selectedKabupaten) ? 'selected' : '';
                    $('#kabkotaPeserta').append(`<option value="${kab.id}" ${isSelected}>${kab.nama}</option>`);
                });
            });
        }

        $('#provinsiPeserta').change(function() {
            const provinsiId = $(this).val();
            $('#kabkotaPeserta').empty().append('<option value="" disabled selected>Pilih Kab/Kota</option>');

            if (provinsiId) {
                fetch(`https://ibnux.github.io/data-indonesia/kabupaten/${provinsiId}.json`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(kabupaten => {
                        const isSelected = (kabupaten.nama === selectedKabupaten) ? 'selected' : '';
                        $('#kabkotaPeserta').append(`<option value="${kabupaten.id}" ${isSelected}>${kabupaten.nama}</option>`);
                    });

                    if (selectedKabupaten) {
                        $('#kabkotaPeserta').val(data.find(kab => kab.nama === selectedKabupaten).id).trigger('change');
                    }
                });
            }
        });

        function toggleNamaInstansi(value) {
            if (value === 'Mahasiswa' || value === 'Dosen' || value === 'Karyawan') {
                $('#namaInstansiContainer').show();
                $('#namaInstansiTitle').show();
            } else {
                $('#namaInstansiContainer').hide();
                $('#namaInstansiTitle').hide();
            }
        }

        $('#aktivitasPeserta').on('change', function() {
            const value = $(this).val();
            toggleNamaInstansi(value);
        });

        // Validasi Real-time
        $('#namaPeserta').on('input', function() {
            validateField('namaPeserta', 'error-nama', 'Nama tidak boleh kosong');
        });

        $('#emailPeserta').on('input', function() {
            validateEmail('emailPeserta', 'error-email');
        });

        $('#kontakPeserta').on('input', function() {
            validateContact('kontakPeserta', 'error-no_kontak');
        });

        // $('#linkedinPeserta').on('input', function() {
        //     validateField('linkedinPeserta', 'error-linkedin', 'LinkedIn tidak boleh kosong');
        // });

        $('#aktivitasPeserta').on('change', function() {
            validateField('aktivitasPeserta', 'error-aktivitas', 'Aktivitas harus dipilih');
        });

        $('#provinsiPeserta').on('change', function() {
            validateField('provinsiPeserta', 'error-provinsi', 'Provinsi harus dipilih');
        });

        $('#kabkotaPeserta').on('change', function() {
            validateField('kabkotaPeserta', 'error-kab_kota', 'Kabupaten/Kota harus dipilih');
        });

        function validateField(inputId, errorId, errorMessage) {
            const input = $(`#${inputId}`).val();
            if (!input) {
                $(`#${errorId}`).text(errorMessage);
                $(`#${inputId}`).addClass('is-invalid');
                return false;
            } else {
                $(`#${errorId}`).text('');
                $(`#${inputId}`).removeClass('is-invalid');
                return true;
            }
        }

        function validateContact(inputId, errorId) {
            const contact = $(`#${inputId}`).val();
            const contactPattern = /^[1-9][0-9]{9,14}$/; // Adjusted to remove '+' as it's already prefixed

            if (!contact) {
                $(`#${errorId}`).text('Kontak harus diisi');
                $(`#${inputId}`).addClass('is-invalid');
                return false;
            } else if (typeof contact !== 'string') {
                $(`#${errorId}`).text('Kontak harus berupa teks');
                $(`#${inputId}`).addClass('is-invalid');
                return false;
            } else if (contact.length < 10) {
                $(`#${errorId}`).text('Kontak harus minimal 10 digit.');
                $(`#${inputId}`).addClass('is-invalid');
                return false;
            } else if (contact.length > 15) {
                $(`#${errorId}`).text('Kontak tidak boleh lebih dari 15 digit.');
                $(`#${inputId}`).addClass('is-invalid');
                return false;
            } else if (!contactPattern.test(contact)) {
                $(`#${errorId}`).text('Kontak tidak boleh diawali dengan 0 dan tidak boleh memakai spesial karakter');
                $(`#${inputId}`).addClass('is-invalid');
                return false;
            } else {
                $(`#${errorId}`).text('');
                $(`#${inputId}`).removeClass('is-invalid');
                return true;
            }
        }

        function validateEmail(inputId, errorId) {
            const email = $(`#${inputId}`).val();
            const emailPattern = /^[\w\.-]+@[a-zA-Z\d\.-]+\.(com|org|net|edu|gov|mil|int|info|co|id|ac\.id)$/;
            if (!emailPattern.test(email)) {
                $(`#${errorId}`).text('Email tidak valid');
                $(`#${inputId}`).addClass('is-invalid');
                return false;
            } else {
                $(`#${errorId}`).text('');
                $(`#${inputId}`).removeClass('is-invalid');
                return true;
            }
        }

        function validateInstansi() {
            const aktivitas = $('#aktivitasPeserta').val();
            if (aktivitas === 'Mahasiswa' || aktivitas === 'Dosen' || aktivitas === 'Karyawan') {
                return validateField('instansiPeserta', 'error-nama_instansi', 'Instansi harus diisi');
            } else {
                // If instansi is not required, clear any previous errors
                $('#error-nama_instansi').text('');
                $('#instansiPeserta').removeClass('is-invalid');
                return true;
            }
        }

        function validateForm() {
            let isValid = true;

            // Validate each field and update isValid accordingly
            if (!validateField('namaPeserta', 'error-nama', 'Nama tidak boleh kosong')) isValid = false;
            if (!validateEmail('emailPeserta', 'error-email')) isValid = false;
            if (!validateContact('kontakPeserta', 'error-no_kontak')) isValid = false;
            // Uncomment if LinkedIn is required
            // if (!validateField('linkedinPeserta', 'error-linkedin', 'LinkedIn tidak boleh kosong')) isValid = false;
            if (!validateField('aktivitasPeserta', 'error-aktivitas', 'Aktivitas harus dipilih')) isValid = false;
            if (!validateField('provinsiPeserta', 'error-provinsi', 'Provinsi harus dipilih')) isValid = false;
            if (!validateField('kabkotaPeserta', 'error-kab_kota', 'Kabupaten/Kota harus dipilih')) isValid = false;
            if (!validateInstansi()) isValid = false;

            return isValid;
        }

        $('#updatePesertaForm').submit(function(e) {
            e.preventDefault();

            // First, validate the form
            if (validateForm()) {
                // If valid, show confirmation popup
                Swal.fire({
                    title: 'Yakin ingin mengupdate data?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, update!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        clearErrors();

                        const formData = {
                            nama: document.getElementById('namaPeserta').value,
                            email: document.getElementById('emailPeserta').value,
                            no_kontak: '+62' + document.getElementById('kontakPeserta').value, // Added '+62' prefix
                            aktivitas: document.getElementById('aktivitasPeserta').value,
                            nama_instansi: document.getElementById('instansiPeserta').value,
                            provinsi: $('#provinsiPeserta option:selected').text(),
                            kab_kota: $('#kabkotaPeserta option:selected').text(),
                            linkedin: document.getElementById('linkedinPeserta').value,
                            modified_by: 'Admin'
                        };

                        axios.put(`/api/pendaftar/${idPendaftar}`, formData)
                            .then(response => {
                                if (response.data.status === 'success') {
                                    Swal.fire({
                                        title: 'Sukses!',
                                        text: response.data.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(function(result) {
                                        if (result.isConfirmed) {
                                            window.location.href = `/admin/pendaftar/detail?idPendaftar=${idPendaftar}`;
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: response.data.message,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Terjadi kesalahan saat mengupdate data',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }
                });
            } else {
                // If not valid, do not show popup and errors are already displayed by validateForm
                // Optionally, you can scroll to the first error field
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 500);
            }
        });

        function displayErrors(errors) {
            for (let key in errors) {
                if (errors.hasOwnProperty(key)) {
                    const errorElement = document.getElementById(`error-${key}`);
                    if (errorElement) {
                        errorElement.textContent = errors[key][0];
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

    });
</script>
@endsection
