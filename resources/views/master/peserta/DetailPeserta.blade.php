@extends('layouts.AdminLayouts')

@section('title')
Arutala | Detail Data Pendaftar
@endsection

@section('style')
<style>
    .training-card {
        margin-bottom: 20px;
    }

    .training-card .card {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .training-card .card:hover {
        transform: translateY(-10px);
    }

    .card-body {
        padding: 20px;
        font-family: 'Arial', sans-serif;
    }

    .card-title {
        font-weight: 700;
        margin-bottom: 15px;
        color: #344C92;
    }

    .card-text {
        color: #555;
    }

    .card-text.font-weight-bold {
        font-weight: 600;
        color: #333;
    }

    .training-card img {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        object-fit: cover;
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

<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Detail Data Pendaftar</h1>
    <button onclick="update()" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">
        <i class="fa-regular fa-regular fa-pen-to-square"></i>
        Update Data
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body" style="padding-top: 20px">
                    <div class="row mb-4">
                        <label for="inputText" class="col-sm-2 col-form-label">Nama Peserta</label>
                        <div class="col-sm-6">
                            <input type="text" id="namaPeserta" class="form-control"  disabled>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="inputText" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-6">
                            <input type="email" id="emailPeserta" class="form-control"  disabled>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="inputDate" class="col-sm-2 col-form-label">Kontak</label>
                        <div class="col-sm-6">
                            <input type="text" id="kontakPeserta" class="form-control"  disabled>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="inputText" class="col-sm-2 col-form-label">Linked In</label>
                        <div class="col-sm-6">
                            <input type="text" id="linkedinPeserta" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="inputText" class="col-sm-2 col-form-label">Aktivitas</label>
                        <div class="col-sm-6">
                            <input type="text" id="aktivitasPeserta" class="form-control" disabled>
                        </div>
                    </div>

                    <!-- Asal Wilayah -->
                    <h3>Asal Wilayah</h3>
                    <div class="row" style="margin-left: -15px;">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="provinsiPeserta" class="col-form-label">Provinsi</label>
                            <input type="text" id="provinsiPeserta" class="form-control" disabled>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="kabkotaPeserta" class="col-form-label">Kab/Kota</label>
                            <input type="text" id="kabkotaPeserta" class="form-control" disabled>
                        </div>
                    </div>


                    <!-- Instansi/Lembaga -->
                    <h3>Instansi/Lembaga</h3>
                    <div class="row mb-5 mr-3 mt-4">
                        <label for="inputText" class="col-sm-2 col-form-label">Instansi</label>
                        <div class="col-sm-6">
                            <input type="text" id="instansiPeserta" class="form-control" disabled>
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
                    let tanggalMulai = formatTanggal(event.agenda_pelatihan.start_date);
                    let tanggalSelesai = formatTanggal(event.agenda_pelatihan.end_date);
                    
                    pelatihanHTML += `
                    <div class="training-card">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('${event.agenda_pelatihan.pelatihan.gambar_pelatihan}') }}" 
     alt="${event.agenda_pelatihan.pelatihan.nama_pelatihan}" 
     onerror="this.onerror=null; this.src='{{ asset('assets/images/default-pelatihan.jpg') }}';">

                            <div class="card-body">
                                <h5 class="card-title">${event.agenda_pelatihan.pelatihan.nama_pelatihan}</h5>
                                <p class="card-text">Status Pembayaran: ${event.status_pembayaran}</p>
                                <p class="card-text">Tanggal Mulai: ${tanggalMulai}</p>
                                <p class="card-text">Tanggal Selesai: ${tanggalSelesai}</p>
                            </div>
                        </div>
                    </div>
                    `;

                });


                    document.getElementById('trainingCards').innerHTML = pelatihanHTML;
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });

    function update()
    {
        const urlParams = new URLSearchParams(window.location.search);
        const idPendaftar = urlParams.get('idPendaftar');

        window.location.href = `/admin/pendaftar/edit?idPendaftar=${idPendaftar}`
    }

    function formatTanggal(tanggal) {
        const bulan = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni", 
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        const tanggalObj = new Date(tanggal);
        const hari = tanggalObj.getDate();
        const bulanNama = bulan[tanggalObj.getMonth()];
        const tahun = tanggalObj.getFullYear();

        return `${hari} ${bulanNama} ${tahun}`;
    }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
