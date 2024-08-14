@extends('layouts.AdminLayouts')

@section('style')
<link href="{{ asset('assets/css/masterPelatihan.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="pagetitle" style="d-space-between">
  <h1>Data Pelatihan</h1>
  <button class="btn btn-success"><i class="bi bi-plus-circle-fill" style="font-size:18px; margin-right:3px; margin-top:10px"></i>Tambah Pelatihan</button>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
      <div class="col-lg-12">
          <div class="card" style="padding: 20px">
              <div class="card-body">
                  <table id="dataPelatihanTable" class="table table-striped">
                      <thead>
                          <tr>
                              <th>Pelatihan</th>
                              <th>Batch</th>
                              <th>Start Date</th>
                              {{-- <th>End Date</th> --}}
                              <th>Status</th>
                              {{-- <th>Mentor</th> --}}
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
    $('#dataPelatihanTable').DataTable({
        "ajax": "{{ asset('asset/dummy_data.json') }}",
        "columns": [
            { "data": "pelatihan" },
            { "data": "batch" },
            { "data": "start_date" },
            { "data": "status" },
            {
                "data": null,
                "render": function(data, type, row) {
                    return `
                        <a href="{{ url('pelatihan') }}/${row.id}" class="view-icon" title="View">
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


      // Event listener for view icon
      $('#dataPesertaTable').on('click', '.view-icon', function() {
          var id = $(this).data('id');
          alert('View icon clicked for ID: ' + id);
          // Add your view logic here
      });

      // Event listener for update icon
      $('#dataPesertaTable').on('click', '.update-icon', function() {
          var id = $(this).data('id');
          alert('Update icon clicked for ID: ' + id);
          // Add your update logic here
      });

      // Event listener for delete icon
      $('#dataPesertaTable').on('click', '.delete-icon', function() {
          var id = $(this).data('id');
          alert('Delete icon clicked for ID: ' + id);
          // Add your delete logic here
      });
  });
</script>
@endsection

