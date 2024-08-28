@extends('layouts/AdminLayouts')

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

<script>
    $(document).ready(function() {
        $('#dataPesertaTable').DataTable({
            "ajax": "{{ asset('data/DataPeserta.json') }}",
            "columns": [
                { "data": "name" },         // Name column
                { "data": "email" },        // Email column
                { "data": "kontak" },       // Kontak column
                { "data": "aktifitas" },    // Aktifitas column
                {                          // Aksi column with action icons
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                            <a href="{{ route('mentor.detail') }}" class="view-icon" data-id="${row.id}" title="View">
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
        // $('#dataPesertaTable').on('click', '.view-icon', function() {
        //     var id = $(this).data('id');
        //     alert('View icon clicked for ID: ' + id);
        //     // Add your view logic here
        // });

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
