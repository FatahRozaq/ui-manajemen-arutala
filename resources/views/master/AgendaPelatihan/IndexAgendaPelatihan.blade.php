@extends('layouts.AdminLayouts')

@section('content')

<div class="pagetitle">
  <h1>Data Pelatihan</h1>
</div><!-- End Page Title -->

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
    // DataTable for Agenda Pelatihan
    $('#dataAgendaPelatihanTable').DataTable({
        "ajax": {
            "url": "/api/agenda/index", // Ganti dengan URL API yang sesuai
            "dataSrc": "data"
        },
        "columns": [
            { "data": "nama_pelatihan" },
            { "data": "batch" },
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
                     <a href="/agenda/detail?id=${row.id_agenda}" class="view-icon" title="View">
                                <i class="fas fa-eye text-primary"></i>
                            </a>
                        <a href="#" class="update-icon" data-id="${row.id}" title="Update">
                            <i class="fas fa-edit text-warning"></i>
                        </a>
                        <a href="#" class="delete-icon" data-id="${row.id}" title="Delete">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </a>
                    `;
                }
            }
        ]
    });

    // Event listeners for icons
    $('#dataAgendaPelatihanTable').on('click', '.update-icon', function() {
        var id = $(this).data('id');
        alert('Update icon clicked for ID: ' + id);
    });

    $('#dataAgendaPelatihanTable').on('click', '.delete-icon', function() {
        var id = $(this).data('id');
        alert('Delete icon clicked for ID: ' + id);
    });
});
</script>
@endsection
