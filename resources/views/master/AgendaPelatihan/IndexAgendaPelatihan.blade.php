@extends('layouts.AdminLayouts')
@section('title')
Arutala | Data Agenda
@endsection
@section('style')
<!-- DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css"/> <!-- Tambahkan CSS untuk responsive DataTables -->
@endsection

@section('content')

<style>
    #dataAgendaPelatihanTable tbody tr {
        cursor: pointer; /* Ubah cursor menjadi pointer */
    }

    .disabled-link {
        pointer-events: none; /* Mencegah klik */
        cursor: not-allowed;  /* Ubah cursor menjadi tidak diizinkan */
    }

</style>

<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Data Pelatihan</h1>
    <a href="agendapelatihan/tambah" class="btn btn-success d-flex align-items-center" style="border-radius: 5px;">
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
          <table id="dataAgendaPelatihanTable" class="table table-striped table-bordered dt-responsive nowrap" style="width: 100%;"> <!-- Tambahkan kelas responsive -->
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
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script> <!-- Tambahkan JS untuk responsive DataTables -->
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable untuk Agenda Pelatihan dengan fitur responsif
        $('#dataAgendaPelatihanTable').DataTable({
            "responsive": true,
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
                        return `<span class="clickable-cell" >${data}</span>`;
                    }
                },
                { "data": "jumlah_peserta" },
                {
                    "data": "start_date",
                    "render": function(data, type, row) {
                        let dateObj = new Date(data);
                        let day = String(dateObj.getDate()).padStart(2, '0');
                        let month = String(dateObj.getMonth() + 1).padStart(2, '0');
                        let year = String(dateObj.getFullYear()).slice(-2);
                        let formattedDate = `${day}-${month}-${year}`;
                        return formattedDate;
                    }
                },
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
                        let isDisabled = (row.status.toLowerCase() === 'selesai' || row.status.toLowerCase() === 'berjalan');
                        let isDisabledDelete = (row.status.toLowerCase() === 'selesai' || row.status.toLowerCase() === 'sedang berlangsung'|| row.status.toLowerCase() === 'pendaftaran berakhir' || row.status.toLowerCase() === 'masa pendaftaran');
                        let updateIcon = `
                            <a href="agendapelatihan/update?id=${row.id_agenda}" title="Update" class="${isDisabled ? 'disabled-link' : ''}">
                                <i class="fas fa-edit ${isDisabled ? 'text-secondary' : 'text-warning'}"></i>
                            </a>
                        `;
                        return `
                            <a href="agendapelatihan/detail?id=${row.id_agenda}" class="view-icon" title="View">
                                <i class="fas fa-eye text-primary"></i>
                            </a>
                            ${updateIcon}
                            <a href="#" class="delete-icon ${isDisabledDelete ? 'disabled-link' : ''}" data-id="${row.id_agenda}" title="Delete">
                                <i class="fas fa-trash-alt ${isDisabledDelete ? 'text-secondary' : 'text-danger'}"></i>
                            </a>
                        `;
                    }
                }
            ],
            // Sorting based on nama_pelatihan first, then batch
            "order": [[0, 'asc'], [1, 'asc']]
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
                // Simpan nama_pelatihan dan batch ke localStorage sebelum redirect
                localStorage.setItem('selectedNamaPelatihan', namaPelatihan);
                localStorage.setItem('selectedBatch', batch);

                // Redirect ke halaman indexpesertapelatihan tanpa parameter
                window.location.href = `/admin/pesertapelatihan`;

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
