@extends('layouts.PesertaLayouts')

@section('title')
Arutala | Profile Peserta
@endsection

@section('style')
<style>
    h3 {
        font-size: 22px;
        font-weight: 600;
    }

    hr {
        border-top: 3px solid #000000;
    }

    .form-control[disabled], .form-control[readonly] {
        background-color: #f5f5f5;
        opacity: 1;
    }

    .default {
        padding: 6px 12px;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 4px 0 0 4px;
        color: #495057;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-control {
        border-radius: 0 4px 4px 0;
    }
</style>
@endsection

@section('content')

<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Profile</h1>
    <a href="{{ route('peserta.profile.update') }}" class="btn" style="background-color: #344C92; color: white;">
        <i class="fa-regular fa-pen-to-square"></i>
        Update Profile
    </a>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body" style="padding-top: 30px">
                    <h3>Data Diri</h3>
                    <hr>
                    <div class="column mb-4">
                        <label for="namaPeserta" class="col-sm-4 col-form-label font-weight-bold">Nama Peserta</label>
                        <div class="col-sm-12">
                            <input type="text" id="namaPeserta" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="column mb-4">
                        <label for="emailPeserta" class="col-sm-4 col-form-label font-weight-bold">Email</label>
                        <div class="col-sm-12">
                            <input type="email" id="emailPeserta" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="column mb-4">
                        <label for="kontakPeserta" class="col-sm-4 col-form-label font-weight-bold">No Kontak</label>
                        <div class="col-sm-12 d-flex">
                            <div class="default">
                                +62
                            </div>
                            <input type="text" id="kontakPeserta" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="column mb-4">
                        <label for="aktivitasPeserta" class="col-sm-4 col-form-label font-weight-bold">Aktivitas</label>
                        <div class="col-sm-12">
                            <input type="text" id="aktivitasPeserta" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="column mb-4" id="instansiContainer" style="display: none;">
                        <label for="instansiPeserta" class="col-sm-8 col-form-label font-weight-bold">Nama Instansi/Lembaga</label>
                        <div class="col-sm-12">
                            <input type="text" id="instansiPeserta" class="form-control" disabled>
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
                        <label for="provinsiPeserta" class="col-sm-4 col-form-label font-weight-bold">Provinsi</label>
                        <div class="col-sm-12">
                            <input type="text" id="provinsiPeserta" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="column mb-4">
                        <label for="kabupatenPeserta" class="col-sm-4 col-form-label font-weight-bold">Kab/Kota</label>
                        <div class="col-sm-12">
                            <input type="text" id="kabupatenPeserta" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body" style="padding-top: 30px">
                    <h3>Lain Lain</h3>
                    <hr>
                    <div class="column mb-4">
                        <label for="linkedinPeserta" class="col-sm-4 col-form-label font-weight-bold">LinkedIn</label>
                        <div class="col-sm-12">
                            <input type="text" id="linkedinPeserta" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        axios.get('/api/profile', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            }
        })
        .then(function (response) {
            const data = response.data.data;

            document.getElementById('namaPeserta').value = data.nama;
            document.getElementById('emailPeserta').value = data.email;
            document.getElementById('kontakPeserta').value = data.no_kontak;
            document.getElementById('aktivitasPeserta').value = data.aktivitas;
            document.getElementById('instansiPeserta').value = data.nama_instansi;
            document.getElementById('provinsiPeserta').value = data.provinsi;
            document.getElementById('kabupatenPeserta').value = data.kab_kota;
            document.getElementById('linkedinPeserta').value = data.linkedin;

            // Tampilkan atau sembunyikan kolom nama instansi berdasarkan aktivitas
            toggleNamaInstansi(data.aktivitas);
        })
        .catch(function (error) {
            Swal.fire({
                title: 'Error!',
                text: 'Gagal memuat data profil.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });

        function toggleNamaInstansi(aktivitas) {
            const instansiContainer = document.getElementById('instansiContainer');
            if (['Pelajar', 'Mahasiswa', 'Dosen', 'Karyawan'].includes(aktivitas)) {
                instansiContainer.style.display = 'block';
            } else {
                instansiContainer.style.display = 'none';
            }
        }
    });
</script>
@endsection