@extends('layouts.AdminLayouts')

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

<div class="pagetitle">
    <h1>Update Agenda Pelatihan</h1>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Informasi Agenda Pelatihan</h5>

                    <!-- Update Form -->
                    <form id="updateAgendaForm">
                        @csrf
                        @method('PUT')

                        <!-- Nama Pelatihan -->
                        <div class="form-group row position-relative">
                            <label for="namaPelatihanInput" class="col-sm-3 col-form-label">Nama Pelatihan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="namaPelatihanInput" name="nama_pelatihan" readonly>
                            </div>
                        </div>

                        <!-- Batch -->
                        <div class="form-group row position-relative">
                            <label for="batchInput" class="col-sm-3 col-form-label">Batch</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="batchInput" name="batch" readonly>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div class="form-group row position-relative">
                            <label for="startDateInput" class="col-sm-3 col-form-label">Start</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="startDateInput" name="start_date">
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="form-group row position-relative">
                            <label for="endDateInput" class="col-sm-3 col-form-label">End</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="endDateInput" name="end_date">
                            </div>
                        </div>

                        <!-- Sesi -->
                        <div id="sesiContainer">
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label">Sesi</label>
                                <div class="col-sm-9 input-group">
                                    <input type="text" class="form-control" name="sesi[]">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success add-sesi" type="button"><i class="bi bi-plus-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Investasi Info -->
                        <div id="investasiInfoContainer">
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label">Investasi Info</label>
                                <div class="col-sm-9 input-group">
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
                            <div class="col-sm-9 input-group">
                                <input type="number" class="form-control" id="investasiInput" name="investasi">
                            </div>
                        </div>

                        <!-- Diskon -->
                        <div class="form-group row position-relative mt-3">
                            <label for="diskonInput" class="col-sm-3 col-form-label">Diskon %</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="diskonInput" name="diskon">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group row position-relative">
                            <label for="statusInput" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="statusInput" name="status">
                                    <option value="Planning">Planning</option>
                                    <option value="Masa Pendaftaran">Masa Pendaftaran</option>
                                    <option value="Sedang Berlangsung">Sedang Berlangsung</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                        </div>

                        <!-- Start Pendaftaran -->
                        <div class="form-group row position-relative">
                            <label for="startPendaftaranInput" class="col-sm-3 col-form-label">Start Pendaftaran</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="startPendaftaranInput" name="start_pendaftaran">
                            </div>
                        </div>

                        <!-- End Pendaftaran -->
                        <div class="form-group row position-relative">
                            <label for="endPendaftaranInput" class="col-sm-3 col-form-label">End Pendaftaran</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="endPendaftaranInput" name="end_pendaftaran">
                            </div>
                        </div>

                        <!-- Link Pembayaran -->
                        <div class="form-group row position-relative mt-3">
                            <label for="linkMayarInput" class="col-sm-3 col-form-label">Link Pembayaran</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="linkMayarInput" name="link_mayar">
                            </div>
                        </div>

                        <!-- Mentor -->
                        <div class="form-group row position-relative">
                            <label for="mentorInput" class="col-sm-3 col-form-label">Mentor</label>
                            <div class="col-sm-9 form-mentor">
                                <select id="mentorInput" name="id_mentor[]" class="form" multiple></select>
                            </div>
                        </div>

                        <!-- Update Button -->
                        <div class="button-submit mt-4">
                            <button class="btn btn-success col-sm-3" type="submit" id="updateButton">Update</button>
                        </div>
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

                // Populate sesi fields
                data.sesi.forEach(function(sesi, index) {
                    if (index === 0) {
                        $('input[name="sesi[]"]').val(sesi);
                    } else {
                        $('#sesiContainer').append(`
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9 input-group">
                                    <input type="text" class="form-control" name="sesi[]" value="${sesi}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary remove-sesi" type="button"><i class="bi bi-dash-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                });

                // Populate investasi_info fields
                data.investasi_info.forEach(function(info, index) {
                    if (index === 0) {
                        $('input[name="investasi_info[]"]').val(info);
                    } else {
                        $('#investasiInfoContainer').append(`
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9 input-group">
                                    <input type="text" class="form-control" name="investasi_info[]" value="${info}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary remove-investasi-info" type="button"><i class="bi bi-dash-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                });

                $('#investasiInput').val(data.investasi);
                $('#diskonInput').val(data.diskon);
                $('#statusInput').val(data.status);
                $('#linkMayarInput').val(data.link_mayar);

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
                <div class="col-sm-9 input-group">
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
                <div class="col-sm-9 input-group">
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

    // Handle Update Button Click
    $('#updateAgendaForm').submit(function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        axios.post(`/api/agenda/update-agenda/${agendaId}`, formData)
            .then(function(response) {
                alert('Agenda pelatihan berhasil diperbarui!');
                window.location.href = '/admin/agendapelatihan'; // Ganti dengan path tujuan setelah update
            })
            .catch(function(error) {
                console.error('Error updating agenda:', error);
                alert('Gagal memperbarui agenda. Silakan coba lagi.');
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
