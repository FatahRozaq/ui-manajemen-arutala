@extends('layouts.AdminLayouts')

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

  .row {
    width: 100%;
  }

  .card {
    width: 60%;
    justify-items: center;
    display: flex;
    justify-content: center;
  }

  .col-lg-12 {
    display: flex;
    justify-content: center;
  }
</style>

<div class="pagetitle">
    <h1>Pendaftar Pelatihan</h1>
</div><!-- End Page Title -->

<section class="section">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Update Status Pembayaran Peserta</h5>

                <!-- Form Update Status Pembayaran -->
                <form id="updateStatusForm">
                    <!-- Nama Pelatihan -->
                    <div class="mb-3">
                        <label for="namaPelatihan" class="form-label">Nama Pelatihan</label>
                        <input type="text" id="namaPelatihan" class="form-control" readonly>
                    </div>

                    <!-- Batch -->
                    <div class="mb-3">
                        <label for="batch" class="form-label">Batch</label>
                        <input type="text" id="batch" class="form-control" readonly>
                    </div>

                    <!-- Nama Peserta -->
                    <div class="mb-3">
                        <label for="namaPeserta" class="form-label">Nama Peserta</label>
                        <input type="text" id="namaPeserta" class="form-control" readonly>
                    </div>

                    <!-- Status Pembayaran -->
                    <div class="mb-3">
                        <label for="statusPembayaran" class="form-label">Status</label>
                        <select id="statusPembayaran" class="form-select">
                            <option value="Paid">Paid</option>
                            <option value="Proses">Proses</option>
                        </select>
                    </div>

                    <!-- Tombol Update -->
                    <div class="button-submit mt-4">
                        <button class="btn btn-success col-sm-3" type="button" id="submitPelatihan">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
 document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const idPendaftaran = urlParams.get('id_pendaftaran'); // Ambil ID pendaftaran dari URL
    const idAgenda = urlParams.get('id_agenda'); // Ambil ID agenda dari URL

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
        
        axios.put(`/api/peserta-pelatihan/update-status-pembayaran/${idPendaftaran}`, {
            status_pembayaran: statusPembayaran
        })
        .then(function(response) {
            alert('Status pembayaran berhasil diupdate!');
            window.location.href = '/admin/pesertapelatihan'; 
        })
        .catch(function(error) {
            console.error('Error updating status:', error);
            alert('Gagal mengupdate status pembayaran.');
        });
    });
});

    </script>
    
@endsection
