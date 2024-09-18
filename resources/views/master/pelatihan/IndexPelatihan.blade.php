@extends('layouts.AdminLayouts')

@section('style')
<link href="{{ asset('assets/css/masterPelatihan.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Data Pelatihan</h1>
    <a href="pelatihan/tambah" class="btn btn-success d-flex align-items-center" style="border-radius: 10px;">
      <i class="bi bi-plus-circle-fill" style="font-size:18px; margin-right:3px; margin-top:10px"></i>
      Tambah Pelatihan
    </a>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
      <div class="col-lg-12">
          <div class="card" style="padding: 20px">
              <div class="card-body">
                  <table id="dataPelatihanTable" class="table table-striped">
                      <thead>
                          <tr>
                              <th>Gambar</th>
                              <th>Pelatihan</th>
                              <th>Jumlah Batch</th>
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
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    $(document).ready(function() {
        $('#dataPelatihanTable').DataTable({
            "ajax": {
                "url": "/api/pelatihan/daftar-pelatihan", // URL endpoint API
                "type": "GET",
                "dataSrc": function (json) {
                    return json.data; // Akses data dari response API
                }
            },
            "columns": [
                { 
                    "data": "gambar_pelatihan",
                    "render": function(data, type, row) {
            // Gunakan URL gambar yang dikembalikan dari API
                        return `<img src="${data}" alt="Gambar Pelatihan" style="width: 70px; height: auto;">`;
                    },
                    "orderable": false
                },
                { "data": "nama_pelatihan" }, // Nama pelatihan
                { "data": "jumlah_batch" },   // Jumlah batch
                {                         
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                            <a href="pelatihan/detail/${row.id_pelatihan}" class="view-icon" title="View">
                                <i class="fas fa-eye text-primary"></i>
                            </a>
                            <a href="pelatihan/update?id=${row.id_pelatihan}" class="update-icon" title="Update">
                                <i class="fas fa-edit text-warning"></i>
                            </a>
                            <a href="#" class="delete-icon" data-id="${row.id_pelatihan}" title="Delete">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </a>
                        `;
                    }
                }
            ]
        });

        // Event listener for delete icon
        $('#dataPelatihanTable').on('click', '.delete-icon', function() {
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
                    axios.delete(`/api/pelatihan/delete-pelatihan/${id}`) // Sesuaikan URL endpoint delete
                        .then(response => {
                            Swal.fire(
                                'Terhapus!',
                                response.data.message,
                                'success'
                            )
                            $('#dataPelatihanTable').DataTable().ajax.reload(); // Reload table data
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
