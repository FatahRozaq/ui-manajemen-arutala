@extends('layouts.AdminLayouts')
@section('title')
Arutala | Update Data Agenda
@endsection
@section('style')
<style>
  .dropdown-menu {
      width: 90%;
      max-height: 150px;
      overflow-y: auto;
  }
  .dropdown-item:hover {
      background-color: #f8f9fa;
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

    .selectize-control .selectize-input.items.has-items .item .remove:hover {
        color: #ff0000;
    }

    /* CSS untuk membuat form-mentor tidak dapat diinput */
.form-mentor.selectize-control.multi .selectize-input {
    pointer-events: none; /* Menonaktifkan interaksi pengguna */
    color: #000; /* Warna teks hitam */
    cursor: not-allowed; /* Mengubah kursor menjadi tanda larangan */
}

.form-mentor.selectize-control.multi .selectize-input > .item {
    background-color: #e2e2e2; /* Warna abu-abu untuk item */
    color: #000; /* Warna hitam untuk teks item */
    border-radius: 20px;
    padding-right: 25px;
    position: relative;
    border: none;
}
</style>
@endsection

@section('content')

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
        <li class="breadcrumb-item active" aria-current="page">Update Agenda</li>
      </ol>
  </nav>

<form id="updateAgendaForm">
    @csrf
    @method('PUT')
<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Update Agenda Pelatihan</h1>

    <button type="submit" class="btn d-flex align-items-center custom-btn" id="updateButton" style="background-color: #344C92; color: white;">
        Save
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body" style="padding-top: 50px">

                    <!-- Update Form -->
                   
                    <div class="col-lg-12 row">
                        <div class="col-lg-7">

                    <div class="form-group row position-relative">
                        <label for="posterAgenda" class="col-sm-3 col-form-label">Poster Agenda</label>
                        <div class="col-sm-7">
                            <input type="file" class="form-control" name="poster_agenda" accept="image/*">
                            <small id="error-posterAgenda" class="text-danger" style="display: none;"></small>
                            
                        </div>
                    </div>
                    

                        <!-- Nama Pelatihan -->
                        <div class="form-group row  position-relative">
                            <label for="namaPelatihanInput" class="col-sm-3 col-form-label">Nama Pelatihan</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="namaPelatihanInput" name="nama_pelatihan" readonly>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group row position-relative mt-3">
                            <label for="deskripsiInput" class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="deskripsiInput" name="deskripsi">
                                <small id="error-deskripsi" class="text-danger" style="display: none;"></small>
                            </div>
                        </div>

                        <!-- Materi -->
                        <div id="materiContainer">
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label">Materi</label>
                                <div class="col-sm-7 input-group">
                                    <input type="text" class="form-control" name="materi[]">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success add-materi" type="button"><i class="bi bi-plus-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Durasi -->
                        <div id="durasiContainer">
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label">Durasi</label>
                                <div class="col-sm-7 input-group">
                                    <input type="text" class="form-control" name="durasi[]">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success add-durasi" type="button"><i class="bi bi-plus-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Evaluasi -->
                        <div class="form-group row position-relative mt-3">
                            <label for="evaluasiInput" class="col-sm-3 col-form-label">Evaluasi</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="evaluasiInput" name="evaluasi">
                                <small id="error-evaluasi" class="text-danger" style="display: none;"></small>
                            </div>
                        </div>

                        <!-- Batch -->
                        <div class="form-group row position-relative">
                            <label for="batchInput" class="col-sm-3 col-form-label">Batch</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="batchInput" name="batch" readonly>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div class="form-group row position-relative">
                            <label for="startDateInput" class="col-sm-3 col-form-label">Start Pelatihan</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control" id="startDateInput" name="start_date">
                                <small id="error-startDate" class="text-danger" style="display: none;"></small>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="form-group row position-relative">
                            <label for="endDateInput" class="col-sm-3 col-form-label">End Pelatihan</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control" id="endDateInput" name="end_date">
                                <small id="error-endDate" class="text-danger" style="display: none;"></small>
                            </div>
                        </div>

                        <!-- Sesi -->
                        <div id="sesiContainer">
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label">Sesi</label>
                                <div class="col-sm-7 input-group">
                                    <input type="text" class="form-control"  id="sesiInput" name="sesi">
                                    <small id="error-sesi" class="text-danger" style="display: none;"></small>
                                    
                                </div>
                            </div>
                        </div>

                        <!-- Investasi Info -->
                        <div id="investasiInfoContainer">
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label">Investasi Info</label>
                                <div class="col-sm-7 input-group">
                                    <input type="text" class="form-control" name="investasi_info[]">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success add-investasi-info" type="button"><i class="bi bi-plus-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Investasi Numerik -->
                        <div class="form-group row position-relative mb-1">
                            <label for="investasiInput" class="col-sm-3 col-form-label">Investasi</label>
                            <div class="col-sm-7 input-group">
                                <input type="number" class="form-control" id="investasiInput" name="investasi">
                                <small id="error-investasi" class="text-danger" style="display: none;"></small>
                            </div>
                        </div>

                        <!-- Diskon -->
                        <div class="form-group row position-relative mt-3">
                            <label for="diskonInput" class="col-sm-3 col-form-label">Diskon %</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" id="diskonInput" name="diskon">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group row position-relative">
                            <label for="statusInput" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-7">
                                <select class="form-control" id="statusInput" name="status">
                                    <option value="Planning">Planning</option>
                                    <option value="Masa Pendaftaran">Masa Pendaftaran</option>
                                    <option value="Sedang Berlangsung">Sedang Berlangsung</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Pendaftaran Berakhir">Pendaftaran Berakhir</option>
                                </select>
                            </div>
                        </div>

                        <!-- Start Pendaftaran -->
                        <div class="form-group row position-relative">
                            <label for="startPendaftaranInput" class="col-sm-3 col-form-label">Start Pendaftaran</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control" id="startPendaftaranInput" name="start_pendaftaran">
                                <small id="error-startPendaftaran" class="text-danger" style="display: none;"></small>
                            </div>
                        </div>

                        <!-- End Pendaftaran -->
                        <div class="form-group row position-relative">
                            <label for="endPendaftaranInput" class="col-sm-3 col-form-label">End Pendaftaran</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control" id="endPendaftaranInput" name="end_pendaftaran">
                                <small id="error-endPendaftaran" class="text-danger" style="display: none;"></small>
                            </div>
                        </div>

                        <!-- Link Pembayaran -->
                        <div class="form-group row position-relative mt-3">
                            <label for="linkMayarInput" class="col-sm-3 col-form-label">Link Pembayaran</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="linkMayarInput" name="link_mayar">
                                <small id="error-linkMayar" class="text-danger" style="display: none;"></small>
                            </div>
                        </div>

                        <!-- Mentor -->
                        <div class="form-group row position-relative">
                            <label for="mentorInput" class="col-sm-3 col-form-label">Mentor</label>
                            <div class="col-sm-7 form-mentor">
                                <select id="mentorInput" name="id_mentor[]" class="form" multiple></select>
                                <small id="error-idMentor" class="text-danger" style="display: none;"></small>
                            </div>
                        </div>
                        </div>

                        <div class="col-4">

                        <div class="col-sm-12">
                            <label for="posterAgenda" class="col-form-label">Poster Agenda</label>
                            <img id="posterAgenda" src="" alt="Poster Agenda" style="width: 100%; height: auto; border-radius: 10px;" />
                        </div>
                        </div>

                    </form>
                </div>
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
    const urlParams = new URLSearchParams(window.location.search);
    const agendaId = urlParams.get('id');

    // Initialize Selectize for mentor selection
    let selectizeControl = $('#mentorInput').selectize({
        valueField: 'id_mentor',
        labelField: 'nama_mentor',
        searchField: ['nama_mentor'],
        create: false,
        render: {
            item: function(data, escape) {
                return '<div class="item">' + escape(data.nama_mentor) + '<span class="remove bi bi-x" style="font-size:16px"></span></div>';
            }
        }
    })[0].selectize;

    // var isValid = true
    // var mentor = $('#mentorInput').val();
    // if (!mentor || mentor.length === 0) {
    //     $('#mentorError').show().text('Mentor wajib diisi.');
    //     isValid = false;
    // } else {
    //     $('#mentorError').hide();
    // }

    // // Jika validasi gagal, jangan kirim form
    // if (!isValid) {
    //     return;
    // }

    // Fetch mentor data from the API
    axios.get('/api/mentor')
        .then(function(response) {
            const mentors = response.data.data;
            selectizeControl.addOption(mentors);
        })
        .catch(function(error) {
            console.error('Error fetching mentors:', error);
            alert('Gagal mengambil data mentor.');
        });

    if (agendaId) {
        axios.get(`/api/agenda/detail-agenda/${agendaId}`)
            .then(function(response) {
                const data = response.data.data;

                // Populate form fields with data from the response
                $('#namaPelatihanInput').val(data.nama_pelatihan);
                $('#batchInput').val(data.batch);
                $('#startDateInput').val(data.start_date);
                $('#endDateInput').val(data.end_date);
                $('#startPendaftaranInput').val(data.start_pendaftaran);
                $('#endPendaftaranInput').val(data.end_pendaftaran);

                $('#sesiInput').val(data.sesi);

                // Populate investasi_info fields
                if (data.investasi_info && data.investasi_info.length > 0) {
                    data.investasi_info.forEach(function(info, index) {
                        if (index === 0) {
                            $('input[name="investasi_info[]"]').val(info);
                        } else {
                            $('#investasiInfoContainer').append(`
                                <div class="form-group row position-relative mb-1">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-7 input-group">
                                        <input type="text" class="form-control" name="investasi_info[]" value="${info}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary remove-investasi-info" type="button"><i class="bi bi-dash-circle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            `);
                        }
                    });
                } else {
                    // Jika investasi_info null, maka kosongkan input pertama
                    $('input[name="investasi_info[]"]').val('');
                }


                $('#investasiInput').val(data.investasi);
                $('#diskonInput').val(data.diskon);
                $('#statusInput').val(data.status);
                $('#linkMayarInput').val(data.link_mayar);

                $('#deskripsiInput').val(data.deskripsi);

                if (data.materi && data.materi.length > 0) {
                    data.materi.forEach(function(info, index) {
                        if (index === 0) {
                            $('input[name="materi[]"]').val(info);
                        } else {
                            $('#materiContainer').append(`
                                <div class="form-group row position-relative mb-1">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-7 input-group">
                                        <input type="text" class="form-control" name="materi[]" value="${info}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary remove-materi" type="button"><i class="bi bi-dash-circle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            `);
                        }
                    });
                } else {
                    // Jika materi null, maka kosongkan input pertama
                    $('input[name="materi[]"]').val('');
                }

                if (data.durasi && data.durasi.length > 0) {
                    data.durasi.forEach(function(info, index) {
                        if (index === 0) {
                            $('input[name="durasi[]"]').val(info);
                        } else {
                            $('#durasiContainer').append(`
                                <div class="form-group row position-relative mb-1">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-7 input-group">
                                        <input type="text" class="form-control" name="durasi[]" value="${info}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary remove-durasi" type="button"><i class="bi bi-dash-circle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            `);
                        }
                    });
                } else {
                    // Jika durasi null, maka kosongkan input pertama
                    $('input[name="durasi[]"]').val('');
                }


                $('#evaluasiInput').val(data.evaluasi);

                if (data.poster_agenda) {
                    $('#posterAgenda').attr('src', data.poster_agenda)
                        .off('error') // Hapus penanganan error sebelumnya jika ada
                        .on('error', function() {
                            $(this).attr('src', '/assets/images/default-pelatihan.jpg');
                        });  // Set the poster URL dan tambahkan penanganan error
                } else {
                    $('#posterAgenda').attr('src', '/assets/images/default-pelatihan.jpg');  // Default image if no poster
                }

                // Populate mentor selectize field with selected mentors
                if (data.mentors && data.mentors.length > 0) {
                    data.mentors.forEach(function(mentor) {
                        selectizeControl.addItem(mentor.id_mentor);
                    });
                }

            })
            .catch(function(error) {
                console.error('Error fetching agenda data:', error);
                alert('Gagal mengambil detail agenda.');
            });
    } else {
        console.error('Invalid or missing agenda ID.');
        alert('Agenda ID tidak valid atau tidak ditemukan.');
    }

    // Handle Add and Remove Sesi
    $('#sesiContainer').on('click', '.add-sesi', function () {
        var newSesiRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-7 input-group">
                    <input type="text" name="sesi[]" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-sesi" type="button"><i class="bi bi-dash-circle"></i></button>
                    </div>
                </div>
            </div>
        `;
        $('#sesiContainer').append(newSesiRow);
    });

    $('#sesiContainer').on('click', '.remove-sesi', function () {
        $(this).closest('.form-group').remove();
    });

    // Handle Add and Remove Investasi Info
    $('#investasiInfoContainer').on('click', '.add-investasi-info', function () {
        var newInvestasiRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-7 input-group">
                    <input type="text" name="investasi_info[]" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-investasi-info" type="button"><i class="bi bi-dash-circle"></i></button>
                    </div>
                </div>
            </div>
        `;
        $('#investasiInfoContainer').append(newInvestasiRow);
    });

    $('#investasiInfoContainer').on('click', '.remove-investasi-info', function () {
        $(this).closest('.form-group').remove();
    });

    $('#materiContainer').on('click', '.add-materi', function () {
        var newMateriRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-7 input-group">
                    <input type="text" name="materi[]" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-materi" type="button"><i class="bi bi-dash-circle"></i></button>
                    </div>
                </div>
            </div>
        `;
        $('#materiContainer').append(newMateriRow);
    });

    $('#materiContainer').on('click', '.remove-materi', function () {
        $(this).closest('.form-group').remove();
    });

    $('#durasiContainer').on('click', '.add-durasi', function () {
        var newDurasiRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-7 input-group">
                    <input type="text" name="durasi[]" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-durasi" type="button"><i class="bi bi-dash-circle"></i></button>
                    </div>
                </div>
            </div>
        `;
        $('#durasiContainer').append(newDurasiRow);
    });

    $('#durasiContainer').on('click', '.remove-durasi', function () {
        $(this).closest('.form-group').remove();
    });


    // Handle Update Button Click
    $('#updateAgendaForm').submit(function(event) {
    event.preventDefault();
    $('#error-posterAgenda').hide().text('');
    $('input[name="poster_agenda"]').removeClass('is-invalid');

    const fileInput = $('input[name="poster_agenda"]')[0];
    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (!allowedTypes.includes(file.type)) {
            event.preventDefault();
            $('#error-posterAgenda').show().text('Format file harus PNG, JPEG, atau JPG.');
            $('input[name="poster_agenda"]').addClass('is-invalid');
            return;
        }

        if (file.size > maxSize) {
            event.preventDefault();
            $('#error-posterAgenda').show().text('Ukuran file tidak boleh lebih dari 5MB.');
            $('input[name="poster_agenda"]').addClass('is-invalid');
            return;
        }
    }

    // Hapus pesan error lama dan class is-invalid sebelum validasi baru
    $('.is-invalid').removeClass('is-invalid'); // Menghapus class is-invalid
    $('small.text-danger').remove(); // Menghapus elemen <small> dengan pesan error


        // Tampilkan pop-up konfirmasi sebelum melakukan update
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, update!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna konfirmasi, lanjutkan dengan update
                const formData = new FormData($('#updateAgendaForm')[0]);

                axios.post(`/api/agenda/update-agenda/${agendaId}`, formData)
                    .then(function(response) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: 'Agenda pelatihan berhasil diperbarui!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/agendapelatihan'; // Ganti dengan path tujuan setelah update
                            }
                        });
                    })
                    .catch(function(error) {
                        if (error.response && error.response.status === 422) {
                            const errors = error.response.data.errors;

                            

                            // Tampilkan error untuk start_date
                            if (errors.start_date) {
                                $('#startDateInput').addClass('is-invalid');
                                $('<small class="text-danger">' + errors.start_date[0] + '</small>').insertAfter('#startDateInput');
                            }

                            if (errors.start_pendaftaran) {
                                $('#startPendaftaranInput').addClass('is-invalid');
                                $('<small class="text-danger">' + errors.start_pendaftaran[0] + '</small>').insertAfter('#startPendaftaranInput');
                            }

                            // Tampilkan error untuk end_date
                            if (errors.end_date) {
                                $('#endDateInput').addClass('is-invalid');
                                $('<small class="text-danger">' + errors.end_date[0] + '</small>').insertAfter('#endDateInput');
                            }

                            if (errors.end_pendaftaran) {
                                $('#endPendaftaranInput').addClass('is-invalid');
                                $('<small class="text-danger">' + errors.end_pendaftaran[0] + '</small>').insertAfter('#endPendaftaranInput');
                            }

                            if (errors.link_mayar) {
                                $('#linkMayarInput').addClass('is-invalid');
                                $('<small class="text-danger">' + errors.link_mayar[0] + '</small>').insertAfter('#linkMayarInput');
                            }

                            // Tampilkan error untuk sesi
                            if (errors.sesi) {
                                $('input[name="sesi[]"]').each(function(index) {
                                    $(this).addClass('is-invalid');
                                    if (index === 0) {
                                        $('<small class="text-danger">' + errors.sesi[0] + '</small>').insertAfter(this);
                                    }
                                });
                            }

                            // Tampilkan error untuk investasi
                            if (errors.investasi) {
                                $('#investasiInput').addClass('is-invalid');
                                $('<small class="text-danger">' + errors.investasi[0] + '</small>').insertAfter('#investasiInput');
                            }

                            if (errors.id_mentor) {
                                $('#mentorInput').addClass('is-invalid');
                                $('<small class="text-danger">' + errors.id_mentor[0] + '</small>').insertAfter('#mentorInput');
                            }

                            if (errors.deskripsi) {
                                $('#deskripsiInput').addClass('is-invalid');
                                $('<small class="text-danger">' + errors.deskripsi[0] + '</small>').insertAfter('#deskripsiInput');
                            }

                            if (errors.materi) {
                                $('input[name="materi[]"]').each(function(index) {
                                    $(this).addClass('is-invalid');
                                    if (index === 0) {
                                        $('<small class="text-danger">' + errors.materi[0] + '</small>').insertAfter(this);
                                    }
                                });
                            }

                            if (errors.sesi) {
                                $('input[name="durasi[]"]').each(function(index) {
                                    $(this).addClass('is-invalid');
                                    if (index === 0) {
                                        $('<small class="text-danger">' + errors.durasi[0] + '</small>').insertAfter(this);
                                    }
                                });
                            }

                            if (errors.evaluasi) {
                                $('#evaluasiInput').addClass('is-invalid');
                                $('<small class="text-danger">' + errors.evaluasi[0] + '</small>').insertAfter('#evaluasiInput');
                            }

                            if (errors.poster_agenda) {
                        $('#posterAgenda').addClass('is-invalid');
                        $('<small class="text-danger">' + errors.poster_agenda[0] + '</small>').insertAfter('#posterAgenda');
                    }

                            // Tambahkan validasi untuk field lainnya jika diperlukan
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Gagal memperbarui agenda. Silakan coba lagi.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
            }
        });
    });

    // Handle remove mentor from selectize
    $(document).on('click', '.remove', function() {
        var $item = $(this).closest('.item');
        var value = $item.attr('data-value');
        selectizeControl.removeItem(value);
    });

});

</script>
@endsection
