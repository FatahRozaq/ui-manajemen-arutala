@extends('layouts.AdminLayouts')

@section('content')


<div class="pagetitle">
  <h1>Data Pelatihan</h1>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <button class="nav-link active" id="nav-paid-tab" data-bs-toggle="tab" data-bs-target="#nav-paid" type="button" role="tab" aria-controls="nav-paid" aria-selected="true">Sudah Bayar</button>
              <button class="nav-link" id="nav-unpaid-tab" data-bs-toggle="tab" data-bs-target="#nav-unpaid" type="button" role="tab" aria-controls="nav-unpaid" aria-selected="false">Belum Bayar</button>
            </div>
          </nav>
      <div class="card" style="padding: 20px">
        <div class="card-body">

          <!-- Tab content -->
          <div class="tab-content" id="nav-tabContent">  
            <!-- Sudah Bayar Tab -->
            <div class="tab-pane fade show active" id="nav-paid" role="tabpanel" aria-labelledby="nav-paid-tab">
              {{-- <h2>Pendaftar Sudah Bayar</h2> --}}
              <table id="dataDetailPelatihanTablePaid" class="table table-striped">
                  <thead>
                      <tr>
                          <th>Pelatihan</th>
                          <th>Batch</th>
                          <th>Nama Peserta</th>
                          <th>No Kontak</th>
                          <th>Status Pembayaran</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                      <!-- Data will be populated by DataTables -->
                  </tbody>
              </table>
            </div>

            <!-- Belum Bayar Tab -->
            <div class="tab-pane fade" id="nav-unpaid" role="tabpanel" aria-labelledby="nav-unpaid-tab">
              <h2>Pendaftar Belum Bayar</h2>
              <table id="dataDetailPelatihanTableUnpaid" class="table table-striped">
                  <thead>
                      <tr>
                          <th>Pelatihan</th>
                          <th>Batch</th>
                          <th>Nama Peserta</th>
                          <th>No Kontak</th>
                          <th>Status Pembayaran</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                      <!-- Data will be populated by DataTables -->
                  </tbody>
              </table>
            </div>

          </div>
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
    // DataTable for Sudah Bayar
    $('#dataDetailPelatihanTablePaid').DataTable({
        "ajax": {
            "url": "{{ asset('asset/detail_data.json') }}",
            "dataSrc": function(json) {
                return json.data.filter(function(item) {
                    return item.statusPembayaran.toLowerCase() === 'sudah';
                });
            }
        },
        "columns": getColumnsConfig()
    });

    // DataTable for Belum Bayar
    $('#dataDetailPelatihanTableUnpaid').DataTable({
        "ajax": {
            "url": "{{ asset('asset/detail_data.json') }}",
            "dataSrc": function(json) {
                return json.data.filter(function(item) {
                    return item.statusPembayaran.toLowerCase() === 'belum';
                });
            }
        },
        "columns": getColumnsConfig()
    });

    function getColumnsConfig() {
        return [
            { "data": "pelatihan" },
            { "data": "batch" },
            { "data": "nama_peserta" },
            { "data": "no_kontak" },
            { 
                "data": "statusPembayaran",
                "render": function(data, type, row) {
                    let colorClass = data.toLowerCase() === 'sudah' ? 'text-success' : 'text-danger';
                    return `<span class="${colorClass}">${data}</span>`;
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return `
                        <a href="#" class="update-icon" data-id="${row.id}" title="Update">
                            <i class="fas fa-edit text-warning"></i>
                        </a>
                        <a href="#" class="delete-icon" data-id="${row.id}" title="Delete">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </a>
                    `;
                }
            }
        ];
    }

    // Event listeners for icons
    $('#dataDetailPelatihanTablePaid, #dataDetailPelatihanTableUnpaid').on('click', '.update-icon', function() {
        var id = $(this).data('id');
        alert('Update icon clicked for ID: ' + id);
    });

    $('#dataDetailPelatihanTablePaid, #dataDetailPelatihanTableUnpaid').on('click', '.delete-icon', function() {
        var id = $(this).data('id');
        alert('Delete icon clicked for ID: ' + id);
    });

    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});

});
</script>
@endsection
