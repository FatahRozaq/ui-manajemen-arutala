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
<form id="updatePesertaForm">
@csrf
<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Update Data Peserta</h1>
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
                                <input type="email" id="emailPeserta" name="email" class="form-control">
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
                                <input type="text" id="linkedinPeserta" name="linkedin" class="form-control" >
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
                        <h3>Instansi/Lembaga</h3>
                        <div class="row mb-5 mr-3 mt-4" id="namaInstansiContainer" style="display: none;">
                            <label for="instansiPeserta" class="col-sm-2 col-form-label">Instansi</label>
                            <div class="col-sm-6">
                                <input type="text" id="instansiPeserta" name="nama_instansi" class="form-control">
                                <span class="text-danger" id="error-nama_instansi"></span>
                            </div>
                        </div>

                        
                    </form>

                    <!-- Pelatihan -->
                    <h3>Pelatihan</h3>
                    <div class="training-card-container mt-4" id="trainingCards">
                        <!-- Data dari API akan ditambahkan di sini -->
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

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

        document.getElementById('aktivitasPeserta').addEventListener('change', function () {
            toggleNamaInstansi(this.value);
        });

        function toggleNamaInstansi(aktivitas) {
            const namaInstansiContainer = document.getElementById('namaInstansiContainer');
            if (['Pelajar', 'Mahasiswa', 'Dosen', 'Karyawan'].includes(aktivitas)) {
                namaInstansiContainer.style.display = 'block';
            } else {
                namaInstansiContainer.style.display = 'none';
                document.getElementById('instansiPeserta').value = '';
            }
        }

        document.getElementById('updatePesertaForm').addEventListener('submit', function (e) {
            e.preventDefault(); 

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Perubahan pada data peserta akan disimpan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Tidak, batalkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    clearErrors();

                    const formData = {
                        nama: document.getElementById('namaPeserta').value,
                        email: document.getElementById('emailPeserta').value,
                        no_kontak: document.getElementById('kontakPeserta').value,
                        aktivitas: document.getElementById('aktivitasPeserta').value,
                        nama_instansi: document.getElementById('instansiPeserta').value,
                        provinsi: $('#provinsiPeserta option:selected').text(),
                        kab_kota: $('#kabkotaPeserta option:selected').text(),
                        linkedin: document.getElementById('linkedinPeserta').value,
                        modified_by: 'Admin'
                    };

                    axios.put(`/api/pendaftar/${idPendaftar}`, formData)
                        .then(function(response) {
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
                        })
                        .catch(function(error) {
                            if (error.response && error.response.data && error.response.data.errors) {
                                displayErrors(error.response.data.errors);
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat memperbarui data peserta.',
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
