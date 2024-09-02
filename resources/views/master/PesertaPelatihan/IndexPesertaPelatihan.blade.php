@extends('layouts.AdminLayouts')

@section('content')

<style>


</style>

<div class="pagetitle">
    <h1>Data Pelatihan</h1>
</div><!-- End Page Title -->

<!-- Button untuk membuka modal filter -->
<div class="filter-import">
    <button type="button" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#filterModal">
        Filter
    </button>
    <form id="exportForm" class="d-inline">
        @csrf
        <input type="hidden" name="nama_pelatihan" id="exportPelatihan">
        <input type="hidden" name="batch" id="exportBatch">
        <button type="button" id="exportButton" class="btn btn-success mb-2">
            Export
        </button>
    </form>
    
</div>

<!-- Modal untuk Filter -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">FILTER</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="mb-3">
                        <label for="pelatihan" class="form-label">Pelatihan:</label>
                        <select id="pelatihan" class="form-select">
                            <!-- Pilihan pelatihan akan dimuat dengan JavaScript -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="batch" class="form-label">Batch:</label>
                        <select id="batch" class="form-select">
                            <!-- Pilihan batch akan dimuat dengan JavaScript -->
                        </select>
                    </div>
                    <button type="button" id="applyFilter" class="btn btn-success">Terapkan</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" />
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

<script>
    $(document).ready(function() {
        let id_agenda = null; // Deklarasikan id_agenda sebagai variabel dinamis

        // Ambil data pelatihan dan batch dari server
        axios.get('/api/peserta-pelatihan/pelatihan-batch')
            .then(function(response) {
                const pelatihanBatchData = response.data.data;
                
                // Isi dropdown pelatihan
                const pelatihanSelect = $('#pelatihan');
                pelatihanBatchData.forEach(function(item) {
                    pelatihanSelect.append(`<option value="${item.nama_pelatihan}">${item.nama_pelatihan}</option>`);
                });

                // Event listener untuk perubahan dropdown pelatihan
                pelatihanSelect.on('change', function() {
                    const selectedPelatihan = $(this).val();
                    const batchSelect = $('#batch');
                    batchSelect.empty();
                    batchSelect.append(`<option value="">-- Pilih Batch --</option>`);

                    // Isi dropdown batch berdasarkan pelatihan yang dipilih
                    const selectedItem = pelatihanBatchData.find(item => item.nama_pelatihan === selectedPelatihan);
                    if (selectedItem) {
                        selectedItem.batches.forEach(function(batch) {
                            batchSelect.append(`<option value="${batch}">${batch}</option>`);
                        });
                    }
                });
            })
            .catch(function(error) {
                console.error('Error fetching pelatihan and batch data:', error);
            });

        function fetchData(pelatihan, batch) {
            // Dapatkan id_agenda berdasarkan pelatihan dan batch
            axios.get(`/api/peserta-pelatihan/get-agenda-id`, {
                params: {
                    nama_pelatihan: pelatihan,
                    batch: batch
                }
            })
            .then(function(response) {
                id_agenda = response.data.id_agenda; // Set id_agenda berdasarkan respons API

                // Fetch data peserta setelah mendapatkan id_agenda
                axios.get(`/api/peserta-pelatihan/agenda/${id_agenda}/peserta`, {
                    params: {
                        nama_pelatihan: pelatihan,
                        batch: batch
                    }
                })
                .then(function(response) {
                    const filteredData = response.data.data;

                    // Pisahkan data berdasarkan status pembayaran
                    const paidData = filteredData.filter(item => item.status_pembayaran.toLowerCase() === 'paid');
                    const unpaidData = filteredData.filter(item => item.status_pembayaran.toLowerCase() === 'proses');

                    // Update DataTables dengan data yang telah dipisahkan
                    $('#dataDetailPelatihanTablePaid').DataTable().clear().rows.add(paidData).draw();
                    $('#dataDetailPelatihanTableUnpaid').DataTable().clear().rows.add(unpaidData).draw();
                })
                .catch(function(error) {
                    console.error('Error fetching filtered data:', error);
                });
            })
            .catch(function(error) {
                console.error('Error fetching agenda ID:', error);
            });
        }

        // Inisialisasi DataTables
        $('#dataDetailPelatihanTablePaid').DataTable({
            columns: [
                { data: 'nama_pelatihan' },
                { data: 'batch' },
                { data: 'nama_peserta' },
                { data: 'no_kontak' },
                {
                    data: 'status_pembayaran',
                    render: function(data, type, row) {
                        let colorClass = data.toLowerCase() === 'paid' ? 'text-success' : 'text-warning';
                        return `<span class="${colorClass}">${data}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        const idPendaftaran = row.id_pendaftaran || 'undefined'; // Pastikan id_pendaftaran tersedia
                        const idAgenda = row.id_agenda || 'undefined'; // Pastikan id_agenda tersedia
                        return `
                        <a href="/updatestatus?id_pendaftaran=${idPendaftaran}&id_agenda=${idAgenda}" class="update-icon" title="Update">
                            <i class="fas fa-edit text-warning"></i>
                        </a>
                        <a href="#" class="delete-icon" data-id="${idPendaftaran}" title="Delete">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </a>
                        `;
                    }
                }
            ]
        });

        $('#dataDetailPelatihanTableUnpaid').DataTable({
            columns: [
                { data: 'nama_pelatihan' },
                { data: 'batch' },
                { data: 'nama_peserta' },
                { data: 'no_kontak' },
                {
                    data: 'status_pembayaran',
                    render: function(data, type, row) {
                        let colorClass = data.toLowerCase() === 'paid' ? 'text-success' : 'text-warning';
                        return `<span class="${colorClass}">${data}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        const idPendaftaran = row.id_pendaftaran || 'undefined'; // Pastikan id_pendaftaran tersedia
                        const idAgenda = row.id_agenda || 'undefined'; // Pastikan id_agenda tersedia
                        return `
                        <a href="/updatestatus?id_pendaftaran=${idPendaftaran}&id_agenda=${idAgenda}" class="update-icon" title="Update">
                            <i class="fas fa-edit text-warning"></i>
                        </a>
                        <a href="#" class="delete-icon" data-id="${idPendaftaran}" title="Delete">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </a>
                        `;
                    }
                }
            ]
        });

        // Event handler untuk tombol Terapkan
        $('#applyFilter').on('click', function() {
            const pelatihan = $('#pelatihan').val();
            const batch = $('#batch').val();
            fetchData(pelatihan, batch);
            $('#filterModal').modal('hide');
        });
    });

    $(document).ready(function() {
    // Handler untuk tombol "Terapkan" (apply filter)
    $('#applyFilter').on('click', function() {
        const pelatihan = $('#pelatihan').val();
        const batch = $('#batch').val();

        // Set nilai input hidden untuk ekspor
        $('#exportPelatihan').val(pelatihan);
        $('#exportBatch').val(batch);

        // Panggil fungsi fetchData untuk memuat data yang difilter ke tabel
        fetchData(pelatihan, batch);
        $('#filterModal').modal('hide');
    });

    // Handler untuk tombol "Export" (export data)
    $('#exportButton').on('click', function() {
        const pelatihan = $('#pelatihan').val();
        const batch = $('#batch').val();

        if (pelatihan && batch) {
            // Redirect untuk ekspor data sesuai filter
            window.location.href = `/api/peserta-pelatihan/export?nama_pelatihan=${pelatihan}&batch=${batch}`;
        } else {
            alert('Harap pilih filter pelatihan dan batch sebelum mengimpor data.');
        }
    });
});

</script>


@endsection

