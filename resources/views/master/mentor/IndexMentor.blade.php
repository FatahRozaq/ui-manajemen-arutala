@extends('layouts.AdminLayouts')

@section('title')
Arutala | Data Mentor
@endsection

@section('content')

<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Data Mentor</h1>
    <a href="{{ route('mentor.add') }}" class="btn btn-success d-flex align-items-center" style="border-radius: 10px;">
        <i class="fa-solid fa-circle-plus mr-2"></i>
        Tambah Mentor
    </a>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="padding: 20px">
                <div class="card-body">
                    <table id="dataPesertaTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Kontak</th>
                                <th>Aktifitas</th>
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
        $('#dataPesertaTable').DataTable({
            "ajax": {
                "url": "/api/mentor", // URL endpoint API
                "type": "GET",
                "dataSrc": function (json) {
                    return json.data; // Akses data dari response API
                }
            },
            "columns": [
                { "data": "nama_mentor" }, // Nama column sesuai dengan nama field di database
                { "data": "email" },       // Email column
                { "data": "no_kontak" },   // Kontak column
                { "data": "aktivitas" },   // Aktifitas column
                {                         // Aksi column with action icons
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                            <a href="/admin/mentor/detail?id=${row.id_mentor}" class="view-icon" title="View">
                                <i class="fas fa-eye text-primary"></i>
                            </a>
                            <a href="/admin/mentor/update?id=${row.id_mentor}" class="update-icon" title="Update">
                                <i class="fas fa-edit text-warning"></i>
                            </a>
                            <a href="#" class="delete-icon" data-id="${row.id_mentor}" title="Delete">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </a>
                        `;
                    }
                }
            ]
        });

        // Event listener for delete icon
        $('#dataPesertaTable').on('click', '.delete-icon', function() {
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
                    axios.delete(`/api/mentor/delete/${id}`)
                        .then(response => {
                            Swal.fire(
                                'Terhapus!',
                                response.data.message,
                                'success'
                            )
                            $('#dataPesertaTable').DataTable().ajax.reload(); // Reload table data
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