@extends('layouts.AdminLayouts')

@section('title')
Arutala | Data Admin
@endsection

@section('content')

<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Data Admin</h1>
    <a href="{{ route('admin.add') }}" class="btn btn-success d-flex align-items-center">
        <i class="fa-solid fa-circle-plus mr-2"></i>
        Tambah Admin
    </a>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="padding: 20px">
                <div class="card-body">
                    <table id="dataAdminTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
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
        $('#dataAdminTable').DataTable({
            "ajax": {
                "url": "/api/kelola-admin", // URL endpoint API
                "type": "GET",
                "dataSrc": function (json) {
                    return json.data; // Akses data dari response API
                }
            },
            "columns": [
                { "data": "nama" }, // Nama column sesuai dengan nama field di database
                { "data": "email" },  // Aktifitas column
                {                         // Aksi column with action icons
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                            <a href="/admin/kelola-admin/detail?id=${row.id_admin}" class="view-icon" title="View">
                                <i class="fas fa-eye text-primary"></i>
                            </a>
                            <a href="#" class="delete-icon" data-id="${row.id_admin}" title="Delete">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </a>
                        `;
                    }
                }
            ]
        });

        // Event listener for delete icon
        $('#dataAdminTable').on('click', '.delete-icon', function() {
            var id = $(this).data('id');
            console.log('id');
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
                    axios.delete(`/api/kelola-admin/delete/${id}`)
                        .then(response => {
                            Swal.fire(
                                'Terhapus!',
                                response.data.message,
                                'success'
                            ).then(() => {
                                location.reload(); 
                            });
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
