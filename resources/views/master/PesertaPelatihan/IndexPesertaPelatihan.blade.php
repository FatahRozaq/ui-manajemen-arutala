@extends('layouts.AdminLayouts')

@section('title')
Arutala | Data Peserta Pelatihan
@endsection

@section('content')

<style>


</style>

<div class="pagetitle">
    <h1>Data Status Pembayaran Peserta</h1>
</div><!-- End Page Title -->

<!-- Button untuk membuka modal filter -->
<div class="filter-import">
    {{-- <button type="button" class="button-filter btn btn-secondary mb-2 p-2" > --}}
        <i class="button-filter bi bi-three-dots ml-2" style="font-size:16px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#filterModal" aria-label="Filter"></i>
    {{-- </button> --}}
    
    
    
    
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
                <div class="nav nav-tabs d-flex justify-content-between align-items-center" id="nav-tab" role="tablist">
                    <div class="d-flex">
                        <button class="nav-link active" id="nav-paid-tab" data-bs-toggle="tab" data-bs-target="#nav-paid" type="button" role="tab" aria-controls="nav-paid" aria-selected="true">Sudah Bayar</button>
                        <button class="nav-link" id="nav-unpaid-tab" data-bs-toggle="tab" data-bs-target="#nav-unpaid" type="button" role="tab" aria-controls="nav-unpaid" aria-selected="false">Belum Bayar</button>
                    </div>
                    <form id="exportForm" class="d-inline">
                        @csrf
                        <input type="hidden" name="nama_pelatihan" id="exportPelatihan">
                        <input type="hidden" name="batch" id="exportBatch">
                        <button type="button" id="exportButton" class="btn btn-success mb-2">
                            Export
                        </button>
                    </form>
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

<script>
    $(document).ready(function() {
        // fetchPelatihanBatchData();
        // fetchData();

        const originalConsoleError = console.error;

        // Override console.error untuk menonaktifkan log kesalahan
        console.error = function() {};
        let id_agenda = null; // Deklarasikan id_agenda sebagai variabel dinamis

        // Ambil parameter dari URL
        const urlParams = new URLSearchParams(window.location.search);
        const namaPelatihan = urlParams.get('nama_pelatihan');
        const batch = urlParams.get('batch');
        
        if (namaPelatihan && batch) {
            fetchFilteredData(namaPelatihan, batch);
        } else {
            // Ambil data default jika parameter tidak ada
            fetchDefaultData();
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

                    // Isi dropdown pelatihan
                    const pelatihanSelect = $('#pelatihan');
                    pelatihanSelect.empty();
                    pelatihanBatchData.forEach(function(item) {
                        pelatihanSelect.append(`<option value="${item.nama_pelatihan}">${item.nama_pelatihan}</option>`);
                    });

                    // Isi dropdown batch saat halaman dimuat
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
            const unpaidData = filteredData.filter(item => item.status_pembayaran.toLowerCase() === 'proses' || item.status_pembayaran.toLowerCase() === 'belum bayar');

            // Update DataTables dengan data yang telah dipisahkan
            tablePaid.clear().rows.add(paidData).draw();
            tableUnpaid.clear().rows.add(unpaidData).draw();
        })
        .catch(function(error) {
            console.error('Error fetching data:', error);
        });
}

function fetchDefaultData() {
        axios.get('/api/peserta-pembayaran')
            .then(function(response) {
                const data = response.data.data;

                // Pisahkan data berdasarkan status pembayaran
                const paidData = data.filter(item => item.status_pembayaran.toLowerCase() === 'paid' || item.status_pembayaran.toLowerCase() === 'sudah');
                const unpaidData = data.filter(item => item.status_pembayaran.toLowerCase() === 'proses' || item.status_pembayaran.toLowerCase() === 'belum bayar');

                // Update DataTables dengan data yang telah dipisahkan
                tablePaid.clear().rows.add(paidData).draw();
                tableUnpaid.clear().rows.add(unpaidData).draw();
            })
            .catch(function(error) {
                console.error('Error fetching default data:', error);
            });
    }


        // Inisialisasi DataTables
        let tablePaid = $('#dataDetailPelatihanTablePaid').DataTable({
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f<"filter-import">>>rtip',
        responsive: true,
        ajax: {
                // url: '/api/peserta-pelatihan/daftar-peserta?status=paid', // URL endpoint API untuk data sudah bayar
                type: 'GET',
                dataSrc: function (json) {
                    return json.data; // Mengakses data dari response API
                }, error: function (jqXHR, textStatus, errorThrown) {
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
        ]
    });

    function fetchFilteredData(pelatihan, batch) {
    // Dapatkan id_agenda berdasarkan pelatihan dan batch
    axios.get(`/api/peserta-pelatihan/get-agenda-id`, {
        params: {
            nama_pelatihan: pelatihan,
            batch: batch
        }
    })
    .then(function(response) {
        id_agenda = response.data.id_agenda;

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
            const paidData = filteredData.filter(item => item.status_pembayaran.toLowerCase() === 'paid' || item.status_pembayaran.toLowerCase() === 'sudah');
            const unpaidData = filteredData.filter(item => item.status_pembayaran.toLowerCase() === 'proses' || item.status_pembayaran.toLowerCase() === 'belum bayar');

            // Update DataTables dengan data yang telah dipisahkan
            tablePaid.clear().rows.add(paidData).draw();
            tableUnpaid.clear().rows.add(unpaidData).draw();
        })
        .catch(function(error) {
            console.error('Error fetching filtered data:', error);
        });
    })
    .catch(function(error) {
        console.error('Error fetching agenda ID:', error);
    });
}


    // Panggil fetch default data saat halaman dimuat
    fetchDefaultData();

        $('.button-filter').appendTo('.dataTables_filter');

        // Tambahkan event listener untuk mendeteksi perubahan ukuran layar (resize event)
        $(window).on('resize', function() {
            tablePaid.responsive.recalc();  // Panggil responsive.recalc() untuk menghitung ulang ukuran tabel
            tableUnpaid.responsive.recalc();  // Lakukan hal yang sama untuk tabel unpaid
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

        // Handler untuk tombol "Export"
        // Handler untuk tombol "Export"
        $('#exportButton').on('click', function() {
    // Ambil data dari DataTables saat ini
    const paidData = tablePaid.rows({ filter: 'applied' }).data().toArray();
    const unpaidData = tableUnpaid.rows({ filter: 'applied' }).data().toArray();
    
    // Gabungkan kedua data dari tabel "Sudah Bayar" dan "Belum Bayar"
    const allData = paidData.concat(unpaidData);

    if (allData.length === 0) {
        alert('Tidak ada data yang tersedia untuk diekspor.');
        return;
    }

    // Ambil parameter pencarian
    const searchText = $('.dataTables_filter input[type="search"]').val();

    // Kirim request POST untuk mengekspor data dengan parameter pencarian
    axios({
        url: '/api/peserta-pelatihan/export-filtered',
        method: 'POST',
        data: { 
            data: allData,
            search: searchText // Tambahkan parameter pencarian
        },
        responseType: 'blob', // Pastikan untuk menggunakan 'blob' agar file diunduh dengan benar
    })
    .then(function(response) {
        // Buat link download untuk Blob
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'filtered_data.xlsx'); // Nama file yang akan diunduh
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
</script>

@endsection

