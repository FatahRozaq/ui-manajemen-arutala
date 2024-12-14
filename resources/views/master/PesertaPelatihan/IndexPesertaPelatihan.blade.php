@extends('layouts.AdminLayouts')

@section('title')
Arutala | Data Peserta Pelatihan
@endsection

@section('content')

<style>
 .filter-import{
    /* margin-top:px; */
 }

 .dataTables_filter {
    justify-content: center;
    display: flex;
 }

 .disabled {
    color: red;
 }

</style>

<div class="pagetitle">
    <h1>Data Status Pembayaran Peserta</h1>
</div><!-- End Page Title -->

<!-- Button untuk membuka modal filter -->
<div class="filter-import">
    {{-- <button type="button" class="button-filter btn btn-secondary mb-2 p-2" > --}}
        <i class="button-filter bi bi-funnel ml-2" style="font-size:22px; cursor: pointer" data-bs-toggle="modal" data-bs-target="#filterModal" aria-label="Filter"></i>
    {{-- </button> --}}
</div>

<div id="loadingIndicator" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
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
                    <!-- Tambahkan Tombol Clear Filter -->
                    <button type="button" id="clearFilter" class="btn btn-secondary">Clear Filter</button>
                </form>
            </div>
        </div>
        
    </div>
</div>

<!-- Modal untuk Upload Sertifikat Kompetensi -->
<div class="modal fade sertifikat" id="uploadSertifikatModal" tabindex="-1" role="dialog" aria-labelledby="uploadSertifikatModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadSertifikatModalLabel">Upload Sertifikat Kompetensi</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <form id="uploadSertifikatForm">
                <div class="form-group">
                    <label for="file_sertifikat">Pilih File Sertifikat</label>
                    <input type="file" class="form-control" id="file_sertifikat" name="file_sertifikat" required>
                    <small class="text-danger" id="fileError" style="display:none;"></small> <!-- Untuk pesan error -->
                </div>
                    <input type="hidden" id="id_pendaftaran" name="id_pendaftaran">
                    <input type="hidden" id="id_agenda" name="id_agenda">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="uploadButton">Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Upload Sertifikat Kehadiran -->
<div class="modal fade sertifikat" id="uploadSertifikatKehadiranModal" tabindex="-1" role="dialog" aria-labelledby="uploadSertifikatKehadiranModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadSertifikatKehadiranModalLabel">Upload Sertifikat Kehadiran</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <form id="uploadSertifikatKehadiranForm">
                <div class="form-group">
                    <label for="sertifikat_kehadiran">Pilih File Sertifikat</label>
                    <input type="file" class="form-control" id="sertifikat_kehadiran" name="sertifikat_kehadiran" required>
                    <small class="text-danger" id="kehadiranFileError" style="display:none;"></small> <!-- Untuk pesan error -->
                </div>

                    <input type="hidden" id="id_pendaftaran_kehadiran" name="id_pendaftaran">
                    <input type="hidden" id="id_agenda_kehadiran" name="id_agenda">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="uploadKehadiranButton">Upload</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview Sertifikat</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <!-- Preview content will be injected here -->
            </div>
        </div>
    </div>
</div>


<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <nav>
                <div class="nav nav-tabs d-flex justify-content-between align-items-center" id="nav-tab" role="tablist">
                    <div class="d-flex">
                        <button class="nav-link active" id="nav-paid-tab" data-bs-toggle="tab" data-bs-target="#nav-paid" type="button" role="tab" aria-controls="nav-paid" aria-selected="true">Sudah Bayar</button>
                        <button class="nav-link" id="nav-unpaid-tab" data-bs-toggle="tab" data-bs-target="#nav-unpaid" type="button" role="tab" aria-controls="nav-unpaid" aria-selected="false">Belum Bayar</button>
                        <button class="nav-link" id="nav-process-tab" data-bs-toggle="tab" data-bs-target="#nav-process" type="button" role="tab" aria-controls="nav-process" aria-selected="false">Process</button>
                    </div>

                    <div class="d-flex gap-1">
                        <a href="{{ route('sertifikat.generateQR') }}"class="btn btn-primary mb-2">Generate QR Sertifikat</a>
                        <form id="exportForm" class="d-inline">
                            @csrf
                            <input type="hidden" name="nama_pelatihan" id="exportPelatihan">
                            <input type="hidden" name="batch" id="exportBatch">
                            <button type="button" id="exportButton" class="btn btn-success mb-2">
                                Export
                            </button>
                        </form>
                    </div>
                    
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
                                        <th>Nama Peserta</th>
                                        <th>Pelatihan</th>
                                        <th>Batch</th>
                                        <th>No Kontak</th>
                                        <th>Status Pembayaran</th>
                                        <th>Kompetensi</th>
                                        <th>Kehadiran</th>
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
                                        <th>Nama Peserta</th>
                                        <th>Pelatihan</th>
                                        <th>Batch</th>
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

                        <div class="tab-pane fade" id="nav-process" role="tabpanel" aria-labelledby="nav-process-tab">
                            <table id="dataDetailPelatihanTableProcess" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Peserta</th>
                                        <th>Pelatihan</th>
                                        <th>Batch</th>
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
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script> <!-- Tambahkan JS untuk responsive DataTables -->
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>


<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css"/>


<script>
    $(document).ready(function() {
        // fetchPelatihanBatchData();
        // fetchData();

        const originalConsoleError = console.error;

        // Override console.error untuk menonaktifkan log kesalahan
        console.error = function() {};
        let id_agenda = null; // Deklarasikan id_agenda sebagai variabel dinamis

        // Ambil parameter dari URL
        const storedNamaPelatihan = localStorage.getItem('selectedNamaPelatihan');
const storedBatch = localStorage.getItem('selectedBatch');

// Hanya panggil fetchDefaultData jika tidak ada data di localStorage
if (!storedNamaPelatihan || !storedBatch) {
    fetchDefaultData();
} else {
    // Jika ada data di localStorage, terapkan filter otomatis
    fetchPelatihanBatchData(() => {
        $('#pelatihan').val(storedNamaPelatihan);
        updateBatchDropdownFromName(storedNamaPelatihan, function() {
            $('#batch').val(storedBatch);

            // Otomatis memanggil fungsi untuk menerapkan filter berdasarkan nilai yang dipilih
            fetchFilteredData(storedNamaPelatihan, storedBatch);
            
            // Hapus data dari localStorage setelah digunakan agar tidak mempengaruhi navigasi berikutnya
            localStorage.removeItem('selectedNamaPelatihan');
            localStorage.removeItem('selectedBatch');
        });
    });
}

        // Jika ada parameter nama_pelatihan dan batch, lakukan fetch data
        fetchPelatihanBatchData(() => {
        // Jika ada parameter nama_pelatihan dan batch
        if (namaPelatihan && batch) {
            $('#pelatihan').val(namaPelatihan);
            updateBatchDropdownFromName(namaPelatihan, function() {
                $('#batch').val(batch);
                // Panggil fetchData untuk mengambil data berdasarkan filter
                fetchData(namaPelatihan, batch);
            });
        } else {
            // Jika tidak ada parameter, tampilkan data default
            fetchDefaultData();
        }
    });

function fetchPelatihanBatchData(callback = null) {
    axios.get('/api/peserta-pelatihan/pelatihan-batch')
        .then(function(response) {
            const pelatihanBatchData = response.data.data;

            // Isi dropdown pelatihan dengan pilihan --All--
            const pelatihanSelect = $('#pelatihan');
            pelatihanSelect.empty();
            pelatihanSelect.append('<option value="all">--All--</option>'); // Tambahkan pilihan --All--
            pelatihanBatchData.forEach(function(item) {
                pelatihanSelect.append(`<option value="${item.nama_pelatihan}">${item.nama_pelatihan}</option>`);
            });

            // Isi dropdown batch dengan pilihan --All-- saat halaman dimuat
            updateBatchDropdown(pelatihanBatchData);

            // Event listener untuk perubahan dropdown pelatihan
            pelatihanSelect.on('change', function() {
                updateBatchDropdown(pelatihanBatchData);
            });

            // Jika ada callback, panggil setelah data dimuat
            if (callback) {
                callback();
            }
        })
        .catch(function(error) {
            console.error('Error fetching pelatihan and batch data:', error);
        });
}
function updateBatchDropdown(pelatihanBatchData) {
    const selectedPelatihan = $('#pelatihan').val();
    const batchSelect = $('#batch');
    batchSelect.empty();

    // Isi dropdown batch dengan pilihan --All--
    batchSelect.append('<option value="all">--All--</option>'); // Tambahkan pilihan --All--

    // Isi dropdown batch berdasarkan pelatihan yang dipilih
    const selectedItem = pelatihanBatchData.find(item => item.nama_pelatihan === selectedPelatihan);
    if (selectedItem) {
        selectedItem.batches.forEach(function(batch) {
            batchSelect.append(`<option value="${batch}">${batch}</option>`);
        });
    }
}

        function updateBatchDropdownFromName(namaPelatihan, callback) {
            axios.get('/api/peserta-pelatihan/pelatihan-batch')
                .then(function(response) {
                    const pelatihanBatchData = response.data.data;
                    const batchSelect = $('#batch');
                    batchSelect.empty();

                    const selectedItem = pelatihanBatchData.find(item => item.nama_pelatihan === namaPelatihan);
                    if (selectedItem) {
                        selectedItem.batches.forEach(function(batch) {
                            batchSelect.append(`<option value="${batch}">${batch}</option>`);
                        });
                    }

                    if (callback) {
                        callback();
                    }
                })
                .catch(function(error) {
                    console.error('Error fetching pelatihan and batch data:', error);
                });
        }

        function fetchData(pelatihan = null, batch = null) {
    // Jika pelatihan dan batch tidak disediakan, gunakan endpoint default
    const url = pelatihan && batch 
        ? `/api/peserta-pelatihan/agenda/${id_agenda}/peserta?nama_pelatihan=${pelatihan}&batch=${batch}`
        : `/api/peserta-pembayaran`;

    axios.get(url)
        .then(function(response) {
            const filteredData = response.data.data;

            // Pisahkan data berdasarkan status pembayaran
            const paidData = filteredData.filter(item => item.status_pembayaran.toLowerCase() === 'paid' || item.status_pembayaran.toLowerCase() === 'sudah');
            const unpaidData = filteredData.filter(item => item.status_pembayaran.toLowerCase() === 'unpaid' || item.status_pembayaran.toLowerCase() === 'belum bayar');
            const processData = filteredData.filter(item => item.status_pembayaran.toLowerCase() === 'proses' || item.status_pembayaran.toLowerCase() === 'process');

            // Update DataTables dengan data yang telah dipisahkan
            tablePaid.clear().rows.add(paidData).draw();
            tableUnpaid.clear().rows.add(unpaidData).draw();
            tableProcess.clear().rows.add(processData).draw();
        })
        .catch(function(error) {
            console.error('Error fetching data:', error);
        });
}

function fetchDefaultData() {
    // Periksa apakah ada data storedNamaPelatihan dan storedBatch di localStorage
    const storedNamaPelatihan = localStorage.getItem('selectedNamaPelatihan');
    const storedBatch = localStorage.getItem('selectedBatch');

    // Jika terdapat data di localStorage, jangan jalankan fetchDefaultData
    if (storedNamaPelatihan && storedBatch) {
        console.log('Skipping fetchDefaultData because storedNamaPelatihan and storedBatch are present.');
        return; // Keluar dari fungsi jika data ditemukan di localStorage
    }

    // Jika tidak ada data di localStorage, jalankan fetchDefaultData seperti biasa
    axios.get('/api/peserta-pembayaran')
        .then(function(response) {
            const data = response.data.data;

            // Pisahkan data berdasarkan status pembayaran
            const paidData = data.filter(item => item.status_pembayaran.toLowerCase() === 'paid' || item.status_pembayaran.toLowerCase() === 'sudah');
            const unpaidData = data.filter(item => item.status_pembayaran.toLowerCase() === 'unpaid' || item.status_pembayaran.toLowerCase() === '');
            const processData = data.filter(item => item.status_pembayaran.toLowerCase() === 'process' || item.status_pembayaran.toLowerCase() === 'proses');

            // Update DataTables dengan data yang telah dipisahkan
            tablePaid.clear().rows.add(paidData).draw();
            tableUnpaid.clear().rows.add(unpaidData).draw();
            tableProcess.clear().rows.add(processData).draw();
        })
        .catch(function(error) {
            console.error('Error fetching default data:', error);
        });
}


    
    let tablePaid = $('#dataDetailPelatihanTablePaid').DataTable({
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f<"filter-import">>>rtip',
        responsive: true,
        ajax: {
            type: 'GET',
            dataSrc: function (json) {
                return json.data;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Kosongkan fungsi untuk tidak menampilkan kesalahan di konsol
            }
        },
        columns: [
            { data: 'nama_peserta' },
            { data: 'nama_pelatihan' },
            { data: 'batch' },
            { data: 'no_kontak' },
            {
                data: 'status_pembayaran',
                render: function(data, type, row) {
                    let colorClass = data.toLowerCase() === 'paid' ? 'text-success' : 'text-warning';
                    const idPendaftaran = row.id_pendaftaran || 'undefined';
                    const idAgenda = row.id_agenda || 'undefined';

                    return `
                        <span class="${colorClass}">${data}</span>
                        <a href="pesertapelatihan/updatestatus?id_pendaftaran=${idPendaftaran}&id_agenda=${idAgenda}" class="update-icon" title="Update">
                            <i class="fas fa-edit text-warning ml-2"></i>
                        </a>
                    `;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    const idPendaftaran = row.id_pendaftaran || 'undefined';
                    const idAgenda = row.id_agenda || 'undefined';
                    let disableKompetensi = row.sertifikat_kompetensi;
                    let disableKompetensiIcon = row.sertifikat_kompetensi;
                    let disableQRKompetensi = row.qr_kompetensi !== null && row.qr_kompetensi !== undefined;
                    let disableQRKompetensiIcon = row.qr_kompetensi !== null && row.qr_kompetensi !== undefined;
                    return `
                        <a href="javascript:void(0)" class="update-icon" title="Upload Sertifikat Kompetensi" onclick="openUploadModal(${idPendaftaran}, ${idAgenda})">
                            <i class="fa-solid fa-cloud-arrow-up text-info"></i>
                        </a>
                        <a href="/api/sertifikat/download-kompetensi?id_pendaftaran=${idPendaftaran}&id_agenda=${idAgenda}" class="update-icon ${disableKompetensi ? '' : 'disabled-link'}" data-id="${row.id_pendaftar}" title="Download Sertifikat Kompetensi">
                            <i class="fa-solid fa-download ${disableKompetensiIcon ? 'text-success' : 'text-secondary'}"></i>
                        </a>
                        <a href="#" class="view-cert-icon ${disableKompetensi ? '' : 'disabled-link'}" data-idpendaftaran="${idPendaftaran}" data-idagenda="${idAgenda}" title="View Sertifikat Kompetensi">
                            <i class="fa-solid fa-eye ${disableKompetensiIcon ? 'text-primary' : 'text-secondary'}"></i>
                        </a>
                        <a href="${row.qr_kompetensi}" class="${disableQRKompetensi ? '' : 'disabled-link'}" data-idpendaftaran="${idPendaftaran}" data-idagenda="${idAgenda}" title="View Web Sertifikat Kompetensi">
                            <i class="fa-solid fa-share ${disableQRKompetensiIcon ? 'text-primary' : 'text-secondary'}"></i>
                        </a>
                    `;
                }
            }
            ,
            {
                data: null,
                render: function(data, type, row) {
                    const idPendaftaran = row.id_pendaftaran || 'undefined';
                    const idAgenda = row.id_agenda || 'undefined';
                    let disableKehadiran = row.sertifikat_kehadiran;
                    let disableKehadiranIcon = row.sertifikat_kehadiran;
                    let disableQRKehadiran = row.qr_kehadiran !== null && row.qr_kehadiran !== undefined;
                    let disableQRKehadiranIcon = row.qr_kehadiran !== null && row.qr_kehadiran !== undefined;
                    return `
                        <a href="javascript:void(0)" class="update-icon" title="Upload Sertifikat Kehadiran" onclick="openUploadKehadiranModal(${idPendaftaran}, ${idAgenda})">
                            <i class="fa-solid fa-cloud-arrow-up text-info"></i>
                        </a>
                        <a href="/api/sertifikat/download-kehadiran?id_pendaftaran=${idPendaftaran}&id_agenda=${idAgenda}" class="update-icon ${disableKehadiran ? '' : 'disabled-link'}" title="Download Sertifikat Kehadiran">
                            <i class="fa-solid fa-download ${disableKehadiranIcon ? 'text-success' : 'text-secondary'}"></i>
                        </a>
                        <a href="#" class="view-cert-icon-kehadiran ${disableKehadiran ? '' : 'disabled-link'}" data-idpendaftaran="${idPendaftaran}" data-idagenda="${idAgenda}" title="View Sertifikat Kehadiran">
                            <i class="fa-solid fa-eye ${disableKehadiranIcon ? 'text-primary' : 'text-secondary'}"></i>
                        </a>
                        <a href="${row.qr_kehadiran}" class="${disableQRKehadiran ? '' : 'disabled-link'}" data-idpendaftaran="${idPendaftaran}" data-idagenda="${idAgenda}"  title="View Web Sertifikat Kehadiran">
                            <i class="fa-solid fa-share ${disableQRKehadiranIcon ? 'text-primary' : 'text-secondary'}"></i>
                        </a>
                    `;
                }
            }
            ],
            "order":  [2, "asc"]
    });



    let tableUnpaid = $('#dataDetailPelatihanTableUnpaid').DataTable({
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f<"filter-import">>>rtip',
        responsive: true,
        ajax: {
                // url: '/api/peserta-pelatihan/agenda/${id_agenda}/peserta', // URL endpoint API untuk data belum bayar
                type: 'GET',
                dataSrc: function (json) {
                    return json.data; // Mengakses data dari response API
                },
                error: function (jqXHR, textStatus, errorThrown) {
            // Kosongkan fungsi untuk tidak menampilkan kesalahan di konsol
        }
        
            },
        columns: [
            { data: 'nama_peserta' },
            { data: 'nama_pelatihan' },
            { data: 'batch' },
            { data: 'no_kontak' },
            {
                data: 'status_pembayaran',
                render: function(data) {
                    let colorClass = data.toLowerCase() === 'paid' ? 'text-success' : 'text-warning';
                    return `<span class="${colorClass}">${data}</span>`;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    const idPendaftaran = row.id_pendaftaran || 'undefined';
                    const idAgenda = row.id_agenda || 'undefined';
                    return `
                    <a href="pesertapelatihan/updatestatus?id_pendaftaran=${idPendaftaran}&id_agenda=${idAgenda}" class="update-icon" title="Update">
                        <i class="fas fa-edit text-warning"></i>
                    </a>
                    `;
                }
            }
        ],
        "order": [[1, "asc"], [2, "asc"]]
    });

    let tableProcess = $('#dataDetailPelatihanTableProcess').DataTable({
    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f<"filter-import">>>rtip',
    responsive: true,
    ajax: {
        type: 'GET',
        dataSrc: function (json) {
            return json.data;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Kosongkan fungsi untuk tidak menampilkan kesalahan di konsol
        }
    },
    columns: [
        { data: 'nama_peserta' },
        { data: 'nama_pelatihan' },
        { data: 'batch' },
        { data: 'no_kontak' },
        {
            data: 'status_pembayaran',
            render: function(data) {
                let colorClass = data.toLowerCase() === 'paid' ? 'text-success' : 'text-warning';
                return `<span class="${colorClass}">${data}</span>`;
            }
        },
        {
            data: null,
            render: function(data, type, row) {
                const idPendaftaran = row.id_pendaftaran || 'undefined';
                const idAgenda = row.id_agenda || 'undefined';
                return `
                <a href="pesertapelatihan/updatestatus?id_pendaftaran=${idPendaftaran}&id_agenda=${idAgenda}" class="update-icon" title="Update">
                    <i class="fas fa-edit text-warning"></i>
                </a>
                `;
            }
        }
    ]
});

function fetchFilteredData(pelatihan, batch) {
    // Jika pelatihan dan batch dipilih sebagai "all", tampilkan semua data tanpa filter
    if (pelatihan === 'all' && batch === 'all') {
        fetchDefaultData(); // Menampilkan data tanpa filter
        return;
    }

    // Jika batch adalah "all", gunakan API baru yang hanya memfilter berdasarkan nama pelatihan
    if (pelatihan !== 'all' && batch === 'all') {
        axios.get(`/api/peserta-pelatihan/by-pelatihan`, { params: { nama_pelatihan: pelatihan } })
            .then(function(response) {
                const data = response.data.data || [];

                // Pisahkan data berdasarkan status pembayaran
                const paidData = data.filter(item => item.status_pembayaran.toLowerCase() === 'paid' || item.status_pembayaran.toLowerCase() === 'sudah');
                const unpaidData = data.filter(item => item.status_pembayaran.toLowerCase() === 'unpaid' || item.status_pembayaran.toLowerCase() === 'belum bayar');
                const processData = data.filter(item => item.status_pembayaran.toLowerCase() === 'process' || item.status_pembayaran.toLowerCase() === 'proses');

                // Update DataTables dengan data yang telah dipisahkan
                tablePaid.clear().rows.add(paidData).draw();
                tableUnpaid.clear().rows.add(unpaidData).draw();
                tableProcess.clear().rows.add(processData).draw();
            })
            .catch(function(error) {
                console.error('Error fetching data by pelatihan:', error);
                alert('Error fetching data by pelatihan. Please try again.');
            });
    } else {
        // Jika batch memiliki nilai tertentu, gunakan API yang memfilter berdasarkan nama pelatihan dan batch
        axios.get('/api/peserta-pelatihan/get-agenda-id', { params: { nama_pelatihan: pelatihan, batch: batch } })
            .then(function(response) {
                const id_agenda = response.data.id_agenda;

                // Menggunakan API getPesertaByAgenda dengan id_agenda yang ditemukan
                axios.get(`/api/peserta-pelatihan/agenda/${id_agenda}/peserta`)
                    .then(function(eventResponse) {
                        const filteredData = eventResponse.data.data || [];

                        // Pisahkan data berdasarkan status pembayaran
                        const paidData = filteredData.filter(item => item.status_pembayaran.toLowerCase() === 'paid' || item.status_pembayaran.toLowerCase() === 'sudah');
                        const unpaidData = filteredData.filter(item => item.status_pembayaran.toLowerCase() === 'unpaid' || item.status_pembayaran.toLowerCase() === 'belum bayar');
                        const processData = filteredData.filter(item => item.status_pembayaran.toLowerCase() === 'process' || item.status_pembayaran.toLowerCase() === 'proses');

                        // Update DataTables dengan data yang telah dipisahkan
                        tablePaid.clear().rows.add(paidData).draw();
                        tableUnpaid.clear().rows.add(unpaidData).draw();
                        tableProcess.clear().rows.add(processData).draw();
                    })
                    .catch(function(error) {
                        console.error('Error fetching data by agenda:', error);
                        alert('Error fetching data by agenda. Please try again.');
                    });
            })
            .catch(function(error) {
                console.error('Error fetching agenda ID:', error);
                alert('Error fetching agenda ID. Please try again.');
            });
    }
}


    // Panggil fetch default data saat halaman dimuat
    fetchDefaultData();

        $('.button-filter').appendTo('.dataTables_filter');

        // Tambahkan event listener untuk mendeteksi perubahan ukuran layar (resize event)
        $(window).on('resize', function() {
            tablePaid.responsive.recalc();  // Panggil responsive.recalc() untuk menghitung ulang ukuran tabel
            tableUnpaid.responsive.recalc();
            tableProcess.responsive.recalc();  
        });

        // Event handler untuk tombol Terapkan
        $('#applyFilter').on('click', function() {
        const pelatihan = $('#pelatihan').val();
        const batch = $('#batch').val();

        if (pelatihan && batch) {
            fetchFilteredData(pelatihan, batch);
            $('#filterModal').modal('hide');
        } else {
            alert('Pilih pelatihan dan batch sebelum melakukan filter.');
        }
    });

    
    // Event handler untuk tombol Clear Filter
    $('#clearFilter').on('click', function() {
        // Reset dropdown pelatihan dan batch ke default
        // $('#pelatihan').val('');
        // $('#batch').empty();

        // Fetch data default
        fetchDefaultData();

        // Tutup modal filter
        $('#filterModal').modal('hide');
    });




    $('#exportButton').on('click', function() {
    // Ambil parameter pencarian hanya dari tabel aktif saat ini
    let activeTable;
    let allData = [];

    // Tentukan tabel aktif berdasarkan visibilitas (paid, unpaid, atau process)
    if ($('#dataDetailPelatihanTablePaid').is(':visible')) {
        activeTable = tablePaid;
        filePrefix = 'Sudah_Bayar_';
    } else if ($('#dataDetailPelatihanTableUnpaid').is(':visible')) {
        activeTable = tableUnpaid;
        filePrefix = 'Belum_Bayar_';
    } else if ($('#dataDetailPelatihanTableProcess').is(':visible')) {
        activeTable = tableProcess;
        filePrefix = 'Proses';
    }

    // Ambil data dari tabel aktif
    const activeData = activeTable.rows({ filter: 'applied' }).data().toArray();

    // Jika data pada tabel aktif sudah difilter
    if (activeData.length === 0) {
        alert('Tidak ada data yang tersedia untuk diekspor.');
        return;
    }

    // Gabungkan data yang difilter berdasarkan pencarian pada tabel aktif
    allData = activeData;

    // Dapatkan waktu saat ini untuk digunakan sebagai nama file
    let now = new Date();
    let day = String(now.getDate()).padStart(2, '0');
    let month = String(now.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
    let year = String(now.getFullYear()).slice(-2); // Ambil 2 digit terakhir tahun
    let hours = String(now.getHours()).padStart(2, '0');
    let minutes = String(now.getMinutes()).padStart(2, '0');
    let seconds = String(now.getSeconds()).padStart(2, '0');

    let formattedDate = `${day}-${month}-${year}_${hours}-${minutes}-${seconds}`; // Format DDMMYY_HHMMSS
    let fileName = `${filePrefix}_${formattedDate}.xlsx`;

    
    axios({
        url: '/api/peserta-pelatihan/export-filtered',
        method: 'POST',
        data: { 
            data: allData // Kirim data yang hanya dari tabel yang aktif
        },
        responseType: 'blob', // Pastikan untuk menggunakan 'blob' agar file diunduh dengan benar
    })
    .then(function(response) {
        // Buat link download untuk Blob
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', fileName); // Nama file dengan format Sudah_Bayar_DDMMYY_HHMMSS.xlsx
        document.body.appendChild(link);
        link.click();
        link.remove();
    })
    .catch(function(error) {
        console.error('Error exporting filtered data:', error);
        alert('Gagal mengekspor data.');
    });
});






        console.error = originalConsoleError;
    });

    // Function to open the upload modal
    function openUploadModal(idPendaftaran, idAgenda) {
        $('#id_pendaftaran').val(idPendaftaran);
        $('#id_agenda').val(idAgenda);
        $('#uploadSertifikatModal').modal('show');
    }

    function openUploadKehadiranModal(idPendaftaran, idAgenda) {
        $('#id_pendaftaran_kehadiran').val(idPendaftaran);
        $('#id_agenda_kehadiran').val(idAgenda);
        $('#uploadSertifikatKehadiranModal').modal('show');
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle upload button click
    $('#uploadButton').on('click', function() {
    let fileInput = $('#file_sertifikat')[0].files[0];
    let fileError = $('#fileError');
    
    // Reset pesan error dan status error
    fileError.text('').hide();
    let isValid = true;
    
    // Validasi jika file kosong
    if (!fileInput) {
        fileError.text('File tidak boleh kosong').show();
        isValid = false;
    } else {
        // Validasi tipe file (hanya gambar atau PDF)
        let allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        if (!allowedTypes.includes(fileInput.type)) {
            fileError.text('File harus berupa gambar (jpeg, jpg atau png) atau PDF').show();
            isValid = false;
        }
        
        // Validasi ukuran file (maks 4 MB)
        let maxSize = 4 * 1024 * 1024; // 4 MB dalam byte
        if (fileInput.size > maxSize) {
            fileError.text('Ukuran file harus lebih kecil dari 4 MB').show();
            isValid = false;
        }
    }

    // Jika validasi gagal, tidak perlu melanjutkan
    if (!isValid) return;

    // Jika lolos validasi, lanjutkan proses upload
    let formData = new FormData($('#uploadSertifikatForm')[0]);
    $.ajax({
        url: '/api/sertifikat/upload-kompetensi',  // API endpoint for upload
        type: 'POST',
        data: formData,
        processData: false,  // Prevent jQuery from processing the data
        contentType: false,  // Prevent jQuery from setting the content type
        success: function(response) {
            // Jika upload berhasil, tampilkan notifikasi sukses
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message,
            });

            // Close the modal
            $('#uploadSertifikatModal').modal('hide');
            tablePaid.ajax.reload(); // Reload datatable setelah upload
        },
        error: function(xhr) {
            // Jika upload gagal, tampilkan notifikasi error
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Gagal',
            //     text: xhr.responseJSON.message,
            // });
        }
    });
});


$('#uploadKehadiranButton').on('click', function() {
    let fileInput = $('#sertifikat_kehadiran')[0].files[0];
    let fileError = $('#kehadiranFileError');
    
    // Reset pesan error dan status error
    fileError.text('').hide();
    let isValid = true;
    
    // Validasi jika file kosong
    if (!fileInput) {
        fileError.text('File tidak boleh kosong').show();
        isValid = false;
    } else {
        // Validasi tipe file (hanya gambar atau PDF)
        let allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        if (!allowedTypes.includes(fileInput.type)) {
            fileError.text('File harus berupa gambar (jpeg, png, gif) atau PDF').show();
            isValid = false;
        }
        
        // Validasi ukuran file (maks 4 MB)
        let maxSize = 4 * 1024 * 1024; // 4 MB dalam byte
        if (fileInput.size > maxSize) {
            fileError.text('Ukuran file harus lebih kecil dari 4 MB').show();
            isValid = false;
        }
    }

    // Jika validasi gagal, tidak perlu melanjutkan
    if (!isValid) return;

    // Jika lolos validasi, lanjutkan proses upload
    let formData = new FormData($('#uploadSertifikatKehadiranForm')[0]);
    $.ajax({
        url: '/api/sertifikat/upload-kehadiran',  // API endpoint for upload
        type: 'POST',
        data: formData,
        processData: false,  // Prevent jQuery from processing the data
        contentType: false,  // Prevent jQuery from setting the content type
        success: function(response) {
            // Jika upload berhasil, tampilkan notifikasi sukses
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message,
            });

            // Close the modal
            $('#uploadSertifikatKehadiranModal').modal('hide');
            tablePaid.ajax.reload(); // Reload datatable setelah upload
        },
        error: function(xhr) {
            // Jika upload gagal, tampilkan notifikasi error
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Gagal',
            //     text: xhr.responseJSON.message,
            // });
        }
    });
});


$('#dataDetailPelatihanTablePaid').on('click', '.view-cert-icon', function(e) {
    e.preventDefault();

    const idPendaftaran = $(this).data('idpendaftaran');
    const idAgenda = $(this).data('idagenda');

    $.ajax({
        url: `/api/sertifikat/view-kompetensi/${idPendaftaran}`, // Adjust URL as needed
        type: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                const fileUrl = response.data.file_url;
                const fileExtension = fileUrl.split('.').pop().toLowerCase(); // Get file extension

                // Conditional rendering based on file type
                let previewContent = '';

                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    previewContent = `
                        <img src="${fileUrl}" class="img-fluid" style="object-fit: contain; width: 100%; max-height: 80vh;" />
                    `;
                } else if (fileExtension === 'pdf') {
                    previewContent = `
                        <iframe src="${fileUrl}" class="w-100" style="height:80vh;" frameborder="0"></iframe>
                    `;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'File tidak didukung',
                        text: 'Format file tidak dikenali.',
                        confirmButtonText: 'Tutup'
                    });
                    return;
                }

                // Inject content into modal
                $('#previewModal .modal-body').html(previewContent);
                $('#previewModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengambil Sertifikat',
                    text: 'Sertifikat tidak ditemukan atau belum di upload',
                    confirmButtonText: 'Tutup'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Gagal memuat sertifikat atau sertifikat belum di upload. Silakan coba lagi.',
                confirmButtonText: 'Tutup'
            });
        }
    });
});

$('#dataDetailPelatihanTablePaid').on('click', '.view-cert-icon-kehadiran', function(e) {
    e.preventDefault();

    const idPendaftaran = $(this).data('idpendaftaran');
    const idAgenda = $(this).data('idagenda');

    $.ajax({
        url: `/api/sertifikat/view-kehadiran/${idPendaftaran}`, // Adjust URL as needed
        type: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                const fileUrl = response.data.file_url;
                const fileExtension = fileUrl.split('.').pop().toLowerCase(); // Get file extension

                // Conditional rendering based on file type
                let previewContent = '';

                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    previewContent = `
                        <img src="${fileUrl}" class="img-fluid" style="object-fit: contain; width: 100%; max-height: 80vh;" />
                    `;
                } else if (fileExtension === 'pdf') {
                    previewContent = `
                        <iframe src="${fileUrl}" class="w-100" style="height:80vh;" frameborder="0"></iframe>
                    `;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'File tidak didukung',
                        text: 'Format file tidak dikenali.',
                        confirmButtonText: 'Tutup'
                    });
                    return;
                }

                // Inject content into modal
                $('#previewModal .modal-body').html(previewContent);
                $('#previewModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengambil Sertifikat',
                    text: 'Sertifikat tidak ditemukan atau belum di upload',
                    confirmButtonText: 'Tutup'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Gagal memuat sertifikat atau sertifikat belum di upload. Silakan coba lagi.',
                confirmButtonText: 'Tutup'
            });
        }
    });
});



$(document).on('click', 'a[title="Download Sertifikat"]', function (e) {
    e.preventDefault(); // Mencegah perilaku default dari elemen link
    const url = $(this).attr('href');

    $.ajax({
        url: url,
        method: 'GET',
        xhrFields: {
            responseType: 'blob' // Mengharapkan respon blob untuk file
        },
        success: function (data, status, xhr) {
            const filename = xhr.getResponseHeader('Content-Disposition')
                .split('filename=')[1]
                .replace(/['"]/g, '');
            const url = window.URL.createObjectURL(new Blob([data]));
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            a.remove();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Tampilkan SweetAlert untuk pesan error
            Swal.fire({
                icon: 'error',
                title: 'Download Gagal',
                text: 'Terjadi kesalahan saat mendownload sertifikat. Silakan coba lagi.',
            });
        }
    });
});

</script>

@endsection