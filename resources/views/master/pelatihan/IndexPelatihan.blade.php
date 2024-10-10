@extends('layouts.AdminLayouts')
@section('title')
Arutala | Data Pelatihan
@endsection
@section('style')
<link href="{{ asset('assets/css/masterPelatihan.css') }}" rel="stylesheet">
<!-- DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css"/> <!-- Tambahkan CSS untuk responsive DataTables -->
@endsection

@section('content')

<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Data Pelatihan</h1>
    <a href="pelatihan/tambah" class="btn btn-success d-flex align-items-center" style="border-radius: 5px;">
        <i class="fa-solid fa-circle-plus mr-2"></i>
        Tambah Pelatihan
    </a>
</div>

<section class="section">
  <div class="row">
      <div class="col-lg-12">
          <div class="card" style="padding: 20px">
              <div class="card-body">
                  <table id="dataPelatihanTable" class="table table-striped table-bordered dt-responsive nowrap" style="width: 100%;"> <!-- Tambahkan kelas responsive -->
                      <thead>
                          <tr>
                              
                              <th>Pelatihan</th>
                              <th>Jumlah Batch</th>
                              <th>Aksi</th>
                          </tr>
                      </thead>
                      <tbody>
                          
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
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    $(document).ready(function() {
        $('#dataPelatihanTable').DataTable({
            "responsive": true, <!-- Aktifkan responsive DataTables -->
            "ajax": {
                "url": "/api/pelatihan/daftar-pelatihan", // URL endpoint API
                "type": "GET",
                "dataSrc": function (json) {
                    return json.data; // Akses data dari response API
                }
            },
            "columns": [
                { 
        "data": "nama_pelatihan",
        "render": function(data, type, row) {
            let imageUrl = row.gambar_pelatihan && row.gambar_pelatihan !== 'null' && row.gambar_pelatihan !== 'undefined' ? row.gambar_pelatihan : '/assets/images/default-pelatihan.jpg';
            
            // Memastikan gambar muncul hanya di tampilan, tetapi sorting menggunakan nama pelatihan
            if (type === 'display') {
                return `
                    <div class="d-flex align-items-center">
                        <img src="${imageUrl}" alt="Gambar Pelatihan" 
                            onerror="this.onerror=null;this.src='/assets/images/default-pelatihan.jpg';"
                            style="width: 20px; height: 20px; margin-right: 10px; border-radius: 50%; object-fit: cover;">
                        <span>${data}</span>
                    </div>`;
            }

            // Saat sorting, hanya gunakan nama pelatihan
            return data;
        },
        "orderable": true // Pastikan sorting tetap aktif
    },
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
