@extends('layouts.AdminLayouts')
@section('title')
Arutala | Tambah Data Agenda
@endsection
@section('content')
<style>
    .dropdown-menu {
        width: 90%;
        max-height: 150px;
        overflow-y: auto;
        border-radius: 20px;
    }
    .dropdown-item:hover {
        background-color: white;
    }

    .button-submit {
        width: 100%;
        display: flex;
        flex: end;
        justify-content: end;
    }

    .selectize-control.multi .selectize-input > .item {
        background: #e2e2e2;
        color: #000;
        border-radius: 20px;
        padding-right: 25px;
        position: relative;
        border: none;
    }

    .selectize-control .selectize-input.items.has-items .item .remove {
        position: absolute;
        top: 50%;
        right: 8px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #000;
    }
</style>

<style>
    .breadcrumb {
      background-color: transparent;
      padding-left: 0;
      padding-bottom: 0;
    }

    .breadcrumb-item {
        font-size: 12px;
    }
  </style>
  
  <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/agendapelatihan">Agenda Pelatihan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Agenda</li>
      </ol>
  </nav>

<form id="agendaForm" method="POST" action="{{ route('agenda.tambah') }}">
    @csrf

    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Tambah Agenda Pelatihan</h1>
    
          <button type="button" id="submitAgenda" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">Submit</button>
        </a>
      </div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body" style="padding-top: 50px">

                        {{-- <div class="row">
                            <div class="col-sm-11 text-right">
                                <button type="button" class="btn" id="submitAgenda" style="background-color: #344C92; color: white;">Submit</button>
                            </div>
                        </div> --}}
                        
                        <!-- Nama Pelatihan -->
                        <div class="form-group row position-relative">
                            <label for="namaPelatihan" class="col-sm-3 col-form-label">Nama Pelatihan</label>
                            <div class="col-sm-6">
                                <select name="nama_pelatihan" id="namaPelatihan" class="form-control">
                                    <!-- Options will be populated by JavaScript -->
                                </select>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div class="form-group row position-relative">
                            <label for="startDate" class="col-sm-3 col-form-label">Start Date</label>
                            <div class="col-sm-6">
                                <input type="date" name="start_date" class="form-control" id="startDate">
                                <small id="startDateError" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="form-group row position-relative">
                            <label for="endDate" class="col-sm-3 col-form-label">End Date</label>
                            <div class="col-sm-6">
                                <input type="date" name="end_date" class="form-control" id="endDate">
                                <small id="endDateError" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>

                        <!-- Sesi -->
                        <div id="sesiContainer">
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label">Sesi</label>
                                <div class="col-sm-6 input-group">
                                    <input type="text" name="sesi[]" class="form-control">
                                    
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success add-sesi" type="button"><i class="bi bi-plus-circle"></i></button>
                                    </div>
                                </div>
                                <div class="col-sm-6 offset-sm-3">
                                <small id="sesiError" class="text-danger" style="display:none;"></small>
                                </div>
                            </div>
                        </div>

                        <!-- Investasi Numerik -->
                        <div class="form-group row position-relative mb-1">
                            <label class="col-sm-3 col-form-label">Investasi</label>
                            <div class="col-sm-6 input-group">
                                <input type="number" name="investasi" class="form-control">
                                
                            </div>
                            <div class="col-sm-6 offset-sm-3">
                            <small id="investasiError" class="text-danger" style="display:none;"></small>
                            </div>

                        </div>
                    
                        <!-- Investasi String -->
                        <div id="investasiContainer">
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label">Investasi Info</label>
                                <div class="col-sm-6 input-group">
                                    <input type="text" name="investasi_info[]" class="form-control">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success add-investasi" type="button"><i class="bi bi-plus-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Diskon -->
                        <div class="form-group row position-relative mt-3">
                            <label for="diskon" class="col-sm-3 col-form-label">Diskon %</label>
                            <div class="col-sm-6">
                                <input type="number" name="diskon" class="form-control" id="diskon">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group row position-relative">
                            <label for="status" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-6">
                                <select name="status" class="form-control" id="status">
                                    <option value="Planning">Planning</option>
                                    <option value="Masa Pendaftaran">Masa Pendaftaran</option>
                                    <option value="Sedang Berlangsung">Sedang Berlangsung</option>
                                    <option value="Selesai">Selesai</option>
                                    
                                </select>
                            </div>
                        </div>

                        <div class="form-group row position-relative">
                            <label for="startPendaftaran" class="col-sm-3 col-form-label">Start Pendaftaran</label>
                            <div class="col-sm-6">
                                <input type="date" name="start_pendaftaran" class="form-control" id="startPendaftaran">
                                <small id="startPendaftaranError" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>

                        <div class="form-group row position-relative">
                            <label for="endPendaftaran" class="col-sm-3 col-form-label">End Pendaftaran</label>
                            <div class="col-sm-6">
                                <input type="date" name="end_pendaftaran" class="form-control" id="endPendaftaran">
                                <small id="endPendaftaranError" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>

                        <!-- Link Pembayaran -->
                        <div class="form-group row position-relative mt-3">
                            <label for="linkMayar" class="col-sm-3 col-form-label">Link Mayar</label>
                            <div class="col-sm-6">
                                <input type="text" name="link_mayar" class="form-control" id="linkMayar">
                                <small id="linkMayarError" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>

                        <!-- Mentor -->
                        <div class="form-group row position-relative">
                            <label for="mentorInput" class="col-sm-3">Mentor</label>
                            <div class="col-sm-6">
                                <select id="mentorInput" name="id_mentor[]" class="form" multiple>
                                    <!-- Options will be populated by JavaScript -->
                                </select>
                                <small id="mentorError" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>

                        <!-- Submit -->
                        {{-- <div class="button-submit mt-4">
                            <button class="btn btn-success col-sm-3" type="button" id="submitAgenda">Submit</button>
                        </div> --}}
                        

                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
$(document).ready(function() {
    // Mengambil data pelatihan dan mentor menggunakan Axios
    axios.get('/api/pelatihan-mentor-data') // Endpoint untuk mendapatkan data pelatihan dan mentor
        .then(function (response) {
            var pelatihans = response.data.pelatihans;
            var mentors = response.data.mentors;

            // Mengisi dropdown Nama Pelatihan
            var pelatihanSelect = $('#namaPelatihan');
            pelatihans.forEach(function(pelatihan) {
                pelatihanSelect.append('<option value="' + pelatihan.nama_pelatihan + '">' + pelatihan.nama_pelatihan + '</option>');
            });

            // Mengisi dropdown Mentor dengan Selectize menggunakan data dari API
            var mentorSelectize = $('#mentorInput').selectize({
                options: mentors.map(function(mentor) {
                    return { id: mentor.id_mentor, name: mentor.nama_mentor };
                }),
                create: false,
                placeholder: 'Pilih mentor...',
                labelField: 'name',
                valueField: 'id',
                searchField: ['name'],
                render: {
                    item: function(data, escape) {
                        return '<div class="item">' + escape(data.name) + '<span class="remove bi bi-x" style="font-size:16px"></span></div>';
                    }
                },
                onItemRemove: function(value) {
                    var selectizeInstance = this;
                    selectizeInstance.refreshOptions(false);
                }
            });

            $(document).on('click', '.remove', function() {
                var $item = $(this).closest('.item');
                var value = $item.attr('data-value');
                mentorSelectize[0].selectize.removeItem(value);
            });

        })
        .catch(function (error) {
            console.error('Gagal mengambil data:', error);
        });

    // Tambah kolom baru pada Sesi
    $('#sesiContainer').on('click', '.add-sesi', function () {
        var newSesiRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-6 input-group">
                    <input type="text" name="sesi[]" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-sesi" type="button"><i class="bi bi-dash-circle"></i></button>
                    </div>
                </div>
            </div>
        `;
        $('#sesiContainer').append(newSesiRow);
    });

    // Hapus kolom sesi
    $('#sesiContainer').on('click', '.remove-sesi', function () {
        $(this).closest('.form-group').remove();
    });

    // Tambah kolom baru pada Investasi
    $('#investasiContainer').on('click', '.add-investasi', function () {
        var newInvestasiRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-6 input-group">
                    <input type="text" name="investasi_info[]" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-investasi" type="button"><i class="bi bi-dash-circle"></i></button>
                    </div>
                </div>
            </div>
        `;
        $('#investasiContainer').append(newInvestasiRow);
    });

    // Hapus kolom investasi
    $('#investasiContainer').on('click', '.remove-investasi', function () {
        $(this).closest('.form-group').remove();
    });

    // Submit form menggunakan Axios dengan konfirmasi
    $('#submitAgenda').click(function() {
        // Validasi form sebelum menampilkan konfirmasi
        var isValid = true;

        // Sembunyikan semua pesan error
        $('small.text-danger').hide().text('');

        // Validasi Nama Pelatihan
        var namaPelatihan = $('#namaPelatihan').val();
        if (!namaPelatihan) {
            $('#namaPelatihanError').show().text('Nama pelatihan wajib diisi.');
            isValid = false;
        }

        // Validasi Start Date
        var startDate = $('#startDate').val();
        if (!startDate) {
            $('#startDateError').show().text('Start date wajib diisi.');
            isValid = false;
        }

        // Validasi End Date
        var endDate = $('#endDate').val();
        if (!endDate) {
            $('#endDateError').show().text('End date wajib diisi.');
            isValid = false;
        } else if (endDate < startDate) {
            $('#endDateError').show().text('End date tidak boleh lebih awal dari start date.');
            isValid = false;
        }

        var startPendaftaran = $('#startPendaftaran').val();
        if (!startPendaftaran) {
            $('#startPendaftaranError').show().text('Start Pendaftaran wajib diisi.');
            isValid = false;
        }

        // Validasi End Date Pendaftaran
        var endPendaftaran = $('#endPendaftaran').val();
        if (!endPendaftaran) {
            $('#endPendaftaranError').show().text('End Pendaftaran wajib diisi.');
            isValid = false;
        } else if (endPendaftaran < startPendaftaran) {
            $('#endPendaftaranError').show().text('End Pendaftaran tidak boleh lebih awal dari start pendaftaran.');
            isValid = false;
        }

        // Validasi Investasi
        var investasi = $('input[name="investasi"]').val();
        if (!investasi || parseInt(investasi) < 0) {
            $('#investasiError').show().text('Investasi wajib diisi dan harus berupa angka positif.');
            isValid = false;
        }

        // Validasi Sesi
        var sesiValues = $('input[name="sesi[]"]').map(function() {
            return $(this).val().trim();
        }).get();
        if (sesiValues.every(function(sesi) { return sesi === ''; })) {
            $('#sesiError').show().text('Setidaknya satu sesi harus diisi.');
            isValid = false;
        }

        // Validasi Link Pembayaran
        var linkMayar = $('#linkMayar').val();
        if (!linkMayar) {
            $('#linkMayarError').show().text('Link pembayaran wajib diisi.');
            isValid = false;
        }

        // Jika validasi gagal, jangan kirim form
        if (!isValid) {
            return;
        }

        // Tampilkan popup konfirmasi
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menambahkan pelatihan ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Tambahkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengonfirmasi, kirim form menggunakan Axios
                var formData = new FormData($('#agendaForm')[0]);

                axios.post('/api/agenda/tambah-agenda', formData)
                    .then(function(response) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: 'Data berhasil ditambahkan!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '/admin/agendapelatihan'; // Redirect ke halaman agenda pelatihan setelah berhasil
                        });
                    })
                    .catch(function(error) {
                        console.error('Gagal menambahkan data:', error);
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Gagal menambahkan data. Coba lagi.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            }
        });
    });
});

</script>
@endsection
