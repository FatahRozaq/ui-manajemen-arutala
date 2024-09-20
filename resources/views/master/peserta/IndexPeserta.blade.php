@extends('layouts.AdminLayouts')

@section('title')
Arutala | Data Peserta
@endsection

@section('content')

<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Data Peserta Pelatihan</h1>
    
    <div class="button-group d-flex">
        <a href="{{ route('peserta.download') }}" class="btn btn-info d-flex align-items-center" style="margin-right: 10px;">
            <i class="fa-solid fa-file-export mr-2"></i>
            Download Import Template
        </a>
        <!-- Form untuk Import Excel -->
        <form id="importForm" action="{{ url('api/pendaftar/import/excel') }}" method="POST" enctype="multipart/form-data" style="display: inline; margin-right:10px">
            @csrf
            <input type="file" name="file" style="display: none;" id="fileInput">
            <button type="button" class="btn btn-primary d-flex align-items-center" onclick="document.getElementById('fileInput').click();">
                <i class="fa-solid fa-upload mr-2"></i>
                Import Data
            </button>
        </form>
        
        <!-- Tautan untuk Export Excel -->
        <a id="exportBtn" href="{{ url('api/pendaftar/export/excel') }}" class="btn btn-success d-flex align-items-center">
            <i class="fa-solid fa-file-export mr-2"></i>
            Export Data
        </a>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="padding: 20px">
                <div class="card-body">
                    <table id="dataPesertaTable" class="table table-striped table-bordered dt-responsive nowrap" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jumlah Pelatihan</th>
                                <th>Email</th>
                                <th>Kontak</th>
                                <th>Aktifitas</th>
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
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables CSS dan JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<!-- DataTables Responsive CSS dan JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<!-- Font Awesome untuk ikon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    $(document).ready(function() {
        // Set CSRF Token untuk Axios
        axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

        // Inisialisasi DataTables dengan responsif
        $('#dataPesertaTable').DataTable({
            "responsive": true,
            "ajax": {
                "url": "{{ url('api/pendaftar') }}", // Endpoint API untuk memuat data
                "dataSrc": "data"
            },
            "columns": [
                { "data": "nama" },
                { "data": "jumlah_pelatihan" },
                { "data": "email" },
                { "data": "no_kontak" },
                { "data": "aktivitas" },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                            <a href="{{ url('admin/peserta/detail?idPendaftar=${row.id_pendaftar}') }}" class="view-icon" title="View">
                                <i class="fas fa-eye text-primary"></i>
                            </a>
                            <a href="{{ url('admin/peserta/edit?idPendaftar=${row.id_pendaftar}') }}" class="update-icon" data-id="${row.id_pendaftar}" title="Update">
                                <i class="fas fa-edit text-warning"></i>
                            </a>
                            <a href="" class="delete-icon" data-id="${row.id_pendaftar}" title="Delete">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </a>
                        `;
                    }
                }
            ]
        });

        // Event listener untuk ikon delete
        $('#dataPesertaTable').on('click', '.delete-icon', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/pendaftar/${id}`)
                        .then(response => {
                            Swal.fire(
                                'Dihapus!',
                                response.data.message,
                                'success'
                            );
                            $('#dataPesertaTable').DataTable().ajax.reload(); // Reload tabel setelah hapus
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

        // Menangani proses impor
        $('#fileInput').change(function() {
            var formData = new FormData($('#importForm')[0]);
            
            axios.post("{{ url('api/pendaftar/import/excel') }}", formData)
                .then(response => {
                    Swal.fire(
                        'Berhasil!',
                        'Data berhasil diimpor.',
                        'success'
                    );
                })
                .catch(error => {
                    Swal.fire(
                        'Gagal!',
                        'Gagal mengimpor data: ' + (error.response.data.message || 'Terjadi kesalahan.'),
                        'error'
                    );
                });
        });

        // Menangani proses ekspor
        $('#exportBtn').click(function(event) {
            event.preventDefault();
            axios.get("{{ url('api/pendaftar/export/excel') }}", { responseType: 'blob' })
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'Pendaftar.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    Swal.fire(
                        'Berhasil!',
                        'Data berhasil diekspor.',
                        'success'
                    );
                })
                .catch(error => {
                    Swal.fire(
                        'Gagal!',
                        'Gagal mengekspor data: ' + (error.response.data.message || 'Terjadi kesalahan.'),
                        'error'
                    );
                });
        });
    });
</script>
@endsection
