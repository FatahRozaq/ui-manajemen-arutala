@extends('layouts.AdminLayouts')

@section('content')

<style>
    #dataAgendaPelatihanTable tbody tr {
        cursor: pointer; /* Ubah cursor menjadi pointer */
    }
</style>

{{-- <div class="pagetitle">
  <h1>Data Pelatihan</h1>
  <a href="agendapelatihan/tambah" class="btn btn-success d-flex align-items-center" style="border-radius: 10px;">
    <i class="bi bi-plus-circle-fill" style="font-size:18px; margin-right:3px; margin-top:10px"></i>
    Tambah Agenda
  </a>
</div><!-- End Page Title --> --}}

<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Data Pelatihan</h1>
    <a href="agendapelatihan/tambah" class="btn btn-success d-flex align-items-center" style="border-radius: 10px;">
        <i class="fa-solid fa-circle-plus mr-2"></i>
        Tambah Agenda
    </a>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card" style="padding: 20px">
        <div class="card-body">

          <!-- Tabel Data Pelatihan -->
          <table id="dataAgendaPelatihanTable" class="table table-striped">
              <thead>
                  <tr>
                      <th>Nama Pelatihan</th>
                      <th>Batch</th>
                      <th>Jumlah Peserta</th>
                      <th>Start Date</th>
                      <th>Status</th>
                      <th>Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  <!-- Data akan diisi oleh DataTables -->
              </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@section('scripts')

<!-- jQuery, DataTables, and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable untuk Agenda Pelatihan
        $('#dataAgendaPelatihanTable').DataTable({
            "ajax": {
                "url": "/api/agenda/index", // Ganti dengan URL API yang sesuai
                "dataSrc": "data"
            },
            "columns": [
                { 
                    "data": "nama_pelatihan",
                    "render": function(data, type, row) {
                        return `<span class="clickable-cell" style="text-decoration:underline;">${data}</span>`;
                    }
                },
                { 
                    "data": "batch",
                    "render": function(data, type, row) {
                        return `<span class="clickable-cell" style="text-decoration:underline;">${data}</span>`;
                    }
                },
                { "data": "jumlah_peserta" },
                { "data": "start_date" },
                { 
                    "data": "status",
                    "render": function(data, type, row) {
                        let colorClass = data.toLowerCase();
                        return `<span class="${colorClass}">${data}</span>`;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                         <a href="agendapelatihan/detail?id=${row.id_agenda}" class="view-icon" title="View">
                                    <i class="fas fa-eye text-primary"></i>
                                </a>
                            <a href="agendapelatihan/update?id=${row.id_agenda}" title="Update">
                                <i class="fas fa-edit text-warning"></i>
                            </a>
                            <a href="#" class="delete-icon" data-id="${row.id_agenda}" title="Delete">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </a>
                        `;
                    }
                }
            ]
        });
    
        // Event listener untuk kolom yang dapat diklik saja
        $('#dataAgendaPelatihanTable tbody').on('click', '.clickable-cell', function() {
            var table = $('#dataAgendaPelatihanTable').DataTable();
            var row = $(this).closest('tr'); // Dapatkan baris dari elemen yang diklik
            var data = table.row(row).data(); // Ambil data baris yang diklik

            if (data) {
                var namaPelatihan = data.nama_pelatihan;
                var batch = data.batch;
                // Redirect ke halaman Peserta Pelatihan dengan parameter nama_pelatihan dan batch
                window.location.href = `/admin/pesertapelatihan?nama_pelatihan=${namaPelatihan}&batch=${batch}`;
            }
        });
    
        $('#dataAgendaPelatihanTable').on('click', '.delete-icon', function(event) {
            event.stopPropagation(); // Mencegah event click baris dari dieksekusi
            var id = $(this).data('id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/agenda/delete-agenda/${id}`) // Sesuaikan URL endpoint delete
                        .then(response => {
                            Swal.fire(
                                'Terhapus!',
                                response.data.message,
                                'success'
                            )
                            $('#dataAgendaPelatihanTable').DataTable().ajax.reload(); // Reload table data
                        })
                        .catch(error => {
                            Swal.fire(
                                'Gagal!',
                                'Gagal menghapus data: ' + error.response.data.message,
                                'error'
                            );
                        });
                }
            });
        });
    
    });
</script>

    
@endsection
