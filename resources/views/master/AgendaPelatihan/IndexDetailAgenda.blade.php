@extends('layouts.AdminLayouts')

@section('content')

<div class="pagetitle">
  <h1>Data Pelatihan</h1>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
      <!-- Card for "Peserta Belum Bayar" -->
      <div class="col-lg-12">
          <div class="card" style="padding: 20px">
              <div class="card-body">
                  <h2>Peserta Belum Bayar</h2>
                  <table id="dataDetailPelatihanTableUnpaid" class="table table-striped">
                      <thead>
                          <tr>
                              <th>Pelatihan</th>
                              <th>Batch</th>
                              <th>Nama Peserta</th>
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

      <!-- Card for "Peserta Sudah Bayar" -->
      <div class="col-lg-12">
          <div class="card" style="padding: 20px">
              <div class="card-body">
                  <h2>Peserta Sudah Bayar</h2>
                  <table id="dataDetailPelatihanTablePaid" class="table table-striped">
                      <thead>
                          <tr>
                              <th>Pelatihan</th>
                              <th>Batch</th>
                              <th>Nama Peserta</th>
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
</section>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

<script>
$(document).ready(function() {
    // DataTable for unpaid participants
    $('#dataDetailPelatihanTableUnpaid').DataTable({
        "ajax": {
            "url": "{{ asset('asset/detail_data.json') }}",
            "dataSrc": function(json) {
                return json.data.filter(function(item) {
                    return item.statusPembayaran.toLowerCase() === 'belum';
                });
            }
        },
        "columns": [
            { "data": "pelatihan" },
            { "data": "batch" },
            { "data": "nama_peserta" },
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
        ]
    });

    // DataTable for paid participants
    $('#dataDetailPelatihanTablePaid').DataTable({
        "ajax": {
            "url": "{{ asset('asset/detail_data.json') }}",
            "dataSrc": function(json) {
                return json.data.filter(function(item) {
                    return item.statusPembayaran.toLowerCase() === 'sudah';
                });
            }
        },
        "columns": [
            { "data": "pelatihan" },
            { "data": "batch" },
            { "data": "nama_peserta" },
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
        ]
    });

    // Event listeners for icons
    $('#dataDetailPelatihanTablePaid, #dataDetailPelatihanTableUnpaid').on('click', '.view-icon', function() {
        var id = $(this).data('id');
        alert('View icon clicked for ID: ' + id);
        // Add your view logic here
    });

    $('#dataDetailPelatihanTablePaid, #dataDetailPelatihanTableUnpaid').on('click', '.update-icon', function() {
        var id = $(this).data('id');
        alert('Update icon clicked for ID: ' + id);
        // Add your update logic here
    });

    $('#dataDetailPelatihanTablePaid, #dataDetailPelatihanTableUnpaid').on('click', '.delete-icon', function() {
        var id = $(this).data('id');
        alert('Delete icon clicked for ID: ' + id);
        // Add your delete logic here
    });
});
</script>
@endsection
