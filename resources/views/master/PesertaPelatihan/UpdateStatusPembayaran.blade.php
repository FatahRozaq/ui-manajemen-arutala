@extends('layouts.AdminLayouts')

@section('title')
Arutala | Update Data Peserta Pelatihan
@endsection

@section('content')
<style>
  .dropdown-menu {
      width: 90%;
      max-height: 150px;
      overflow-y: auto;
  }
  .dropdown-item:hover {
      background-color: #f8f9fa;
  }

  .button-submit {
    width: 100%;
    display: flex;
    flex: end;
    justify-content: end;
  }


</style>

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
        <li class="breadcrumb-item"><a href="/admin/pesertapelatihan">Peserta Pelatihan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Update Pembayaran</li>
      </ol>
  </nav>

{{-- <div class="pagetitle">
    <h1>Pendaftar Pelatihan</h1>
</div><!-- End Page Title -->

<div class="button-submit mt-4">
    <button class="btn btn-success col-sm-3" type="button" id="submitPelatihan">Update</button>
</div> --}}
<form id="updateStatusForm">
<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Update Status Pembayaran</h1>

    <button type="button" class="btn d-flex align-items-center custom-btn" id="submitPelatihan" style="background-color: #344C92; color: white;">
        Save
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body" style="padding-top: 50px">

                <!-- Form Update Status Pembayaran -->
                
                    <!-- Nama Pelatihan -->
                    <div class="form-group row position-relative">
                        <label for="trainingInput" class="col-sm-2 col-form-label">Nama Pelatihan</label>
                        <div class="col-sm-6">
                        <input type="text" id="namaPelatihan" class="form-control" readonly>
                        </div>
                    </div>

                    <!-- Batch -->
                    <div class="form-group row position-relative">
                        <label  for="trainingInput" class="col-sm-2 col-form-label">Batch</label>
                        <div class="col-sm-6">
                        <input type="text" id="batch" class="form-control" readonly>
                        </div>
                    </div>

                    <!-- Nama Peserta -->
                    <div class="form-group row position-relative">
                        <label  for="trainingInput" class="col-sm-2 col-form-label">Nama Peserta</label>
                        <div class="col-sm-6">
                        <input type="text" id="namaPeserta" class="form-control" readonly>
                        </div>
                    </div>

                    <!-- Status Pembayaran -->
                    <div class="form-group row position-relative">
                        <label for="trainingInput" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-6">
                        <select id="statusPembayaran" class="form-select">
                            <option value="Paid">Paid</option>
                            <option value="Belum Bayar">Belum Bayar</option>
                        </select>
                        </div>
                    </div>

                    <!-- Tombol Update -->
                    
                
            </div>
        </div>
    </div>
</section>
</form>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const idPendaftaran = urlParams.get('id_pendaftaran'); // Ambil ID pendaftaran dari URL
    const idAgenda = urlParams.get('id_agenda'); // Ambil ID agenda dari URL
    let namaPelatihan = urlParams.get('nama_pelatihan'); // Ambil nama pelatihan dari URL
    let batch = urlParams.get('batch'); // Ambil batch dari URL

    // Periksa apakah idAgenda telah diambil dengan benar
    if (!idAgenda) {
        console.error('ID agenda tidak ditemukan di URL.');
        alert('ID agenda tidak ditemukan di URL.');
        return; // Hentikan eksekusi jika id_agenda tidak ditemukan
    }

    // Fungsi untuk memuat data awal
    function loadInitialData() {
        // Gunakan id_pendaftaran dan id_agenda untuk memfilter hasil
        axios.get(`/api/peserta-pelatihan/agenda/${idAgenda}/peserta`, {
            params: { id_pendaftaran: idPendaftaran }
        })
        .then(function(response) {
            // Ambil data peserta sesuai dengan id_pendaftaran
            if (response.data.data.length > 0) {
                const peserta = response.data.data[0]; // Ambil peserta pertama jika ada
                document.getElementById('namaPelatihan').value = peserta.nama_pelatihan;
                document.getElementById('batch').value = peserta.batch;
                document.getElementById('namaPeserta').value = peserta.nama_peserta;
                document.getElementById('statusPembayaran').value = peserta.status_pembayaran;

                // Perbarui nilai variabel namaPelatihan dan batch jika belum di-set
                if (!namaPelatihan) namaPelatihan = peserta.nama_pelatihan;
                if (!batch) batch = peserta.batch;

            } else {
                console.error('Data peserta tidak ditemukan.');
                alert('Data peserta tidak ditemukan.');
            }
        })
        .catch(function(error) {
            console.error('Error fetching data:', error);
        });
    }

    // Memuat data awal
    loadInitialData();

    // Fungsi untuk mengupdate status pembayaran
    document.getElementById('submitPelatihan').addEventListener('click', function() {
    const statusPembayaran = document.getElementById('statusPembayaran').value;

    // Tampilkan pop-up konfirmasi sebelum melakukan update
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda tidak akan dapat mengembalikan ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, update!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna konfirmasi, lanjutkan dengan update
            axios.put(`/api/peserta-pelatihan/update-status-pembayaran/${idPendaftaran}`, {
                status_pembayaran: statusPembayaran
            })
            .then(function(response) {
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Status pembayaran berhasil diupdate!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    // Pastikan namaPelatihan dan batch tidak null atau undefined sebelum redirect
                    if (namaPelatihan && batch) {
                        window.location.href = `/admin/pesertapelatihan?nama_pelatihan=${encodeURIComponent(namaPelatihan)}&batch=${batch}`;
                    } else {
                        // Fallback ke URL default jika parameter tidak tersedia
                        window.location.href = '/admin/pesertapelatihan';
                    }
                });
            })
            .catch(function(error) {
                console.error('Error updating status:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal mengupdate status pembayaran.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
});

});
</script>


    
@endsection
