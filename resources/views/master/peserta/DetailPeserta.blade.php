@extends('layouts.AdminLayouts')

@section('title')
Arutala | Detail Data Peserta
@endsection

@section('style')
<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
</style>
@endsection

@section('content')

<div class="pagetitle">
    <h1>Detail Data Peserta</h1>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body" style="padding-top: 20px">
                    <div class="row mb-4">
                        <label for="inputText" class="col-sm-2 col-form-label">Nama Peserta</label>
                        <div class="col-sm-6">
                            <input type="text" id="namaPeserta" class="form-control" placeholder="John Doe" disabled>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="inputText" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-6">
                            <input type="email" id="emailPeserta" class="form-control" placeholder="example@gmail.com" disabled>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="inputDate" class="col-sm-2 col-form-label">Kontak</label>
                        <div class="col-sm-6">
                            <input type="text" id="kontakPeserta" class="form-control" placeholder="+62 81213134" disabled>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="inputText" class="col-sm-2 col-form-label">Linked In</label>
                        <div class="col-sm-6">
                            <input type="text" id="linkedinPeserta" class="form-control" placeholder="john.doe" disabled>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="inputText" class="col-sm-2 col-form-label">Aktivitas</label>
                        <div class="col-sm-6">
                            <input type="text" id="aktivitasPeserta" class="form-control" placeholder="Mahasiswa" disabled>
                        </div>
                    </div>

                    <!-- Asal Wilayah -->
                    <h3>Asal Wilayah</h3>
                    <div class="row" style="margin-left: -15px;">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="provinsiPeserta" class="col-form-label">Provinsi</label>
                            <input type="text" id="provinsiPeserta" class="form-control" placeholder="Jawa Barat" disabled>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="kabkotaPeserta" class="col-form-label">Kab/Kota</label>
                            <input type="text" id="kabkotaPeserta" class="form-control" placeholder="Bandung" disabled>
                        </div>
                    </div>


                    <!-- Instansi/Lembaga -->
                    <h3>Instansi/Lembaga</h3>
                    <div class="row mb-5 mr-3 mt-4">
                        <label for="inputText" class="col-sm-2 col-form-label">Instansi</label>
                        <div class="col-sm-6">
                            <input type="text" id="instansiPeserta" class="form-control" placeholder="Politeknik Negeri Bandung" disabled>
                        </div>
                    </div>

                    <!-- Pelatihan -->
                    <h3>Pelatihan</h3>
                    <div class="training-card-container mt-4" id="trainingCards">
                        <!-- Data dari API akan ditambahkan di sini -->
                    </div>

                    <!-- <div class="row">
                        <div class="col-sm-11 text-right">
                            <a href="{{ route('mentor.update') }}">
                                <button type="button" class="btn" style="background-color: #344C92; color: white;">Update</button>
                            </a>
                        </div>
                    </div> -->
                </div>
            </div>

        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const idPendaftar = urlParams.get('idPendaftar');
        const apiUrl = `/api/pendaftar/${idPendaftar}`;

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('namaPeserta').value = data.data.nama;
                    document.getElementById('emailPeserta').value = data.data.email;
                    document.getElementById('kontakPeserta').value = data.data.no_kontak;
                    document.getElementById('linkedinPeserta').value = data.data.linkedin;
                    document.getElementById('aktivitasPeserta').value = data.data.aktivitas;
                    document.getElementById('provinsiPeserta').value = data.data.provinsi;
                    document.getElementById('kabkotaPeserta').value = data.data.kab_kota;
                    document.getElementById('instansiPeserta').value = data.data.nama_instansi;

                    let pelatihanHTML = '';
                    data.data.pendaftaran_event.forEach(function(event, index) {
                        pelatihanHTML += `
                        <div class="training-card">
                            <div class="card">
                                <img class="card-img-top" src="{{ asset('${event.agenda_pelatihan.pelatihan.gambar_pelatihan}') }}" alt="${event.agenda_pelatihan.pelatihan.nama_pelatihan}">
                                <div class="card-body">
                                    <h5 class="card-title">${event.agenda_pelatihan.pelatihan.nama_pelatihan}</h5>
                                    <p class="card-text">Status Pembayaran: ${event.status_pembayaran}</p>
                                    <p class="card-text">Tanggal: ${event.agenda_pelatihan.start_date} - ${event.agenda_pelatihan.end_date}</p>
                                </div>
                            </div>
                        </div>`;
                    });

                    document.getElementById('trainingCards').innerHTML = pelatihanHTML;
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
