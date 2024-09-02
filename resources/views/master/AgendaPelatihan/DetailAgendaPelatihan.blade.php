@extends('layouts.AdminLayouts')

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

    .selectize-control .selectize-input.items.has-items .item .remove:hover {
        color: #ff0000;
    }
</style>

<div class="pagetitle">
    <h1>Detail Agenda Pelatihan</h1>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Detail Agenda</h5>

                    <!-- General Form Elements -->
                    <form>
                        <!-- Nama Pelatihan -->
                        <div class="form-group row position-relative">
                            <label for="namaPelatihanInput" class="col-sm-3 col-form-label">Nama Pelatihan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control disable" id="namaPelatihanInput" disabled readonly>
                            </div>
                        </div>

                        <!-- Batch -->
                        <div class="form-group row position-relative">
                            <label for="batchInput" class="col-sm-3 col-form-label">Batch</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control disable" id="batchInput" disabled readonly>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div class="form-group row position-relative">
                            <label for="startDateInput" class="col-sm-3 col-form-label">Start</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control disable" id="startDateInput" aria-label="readonly input example" readonly>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="form-group row position-relative">
                            <label for="endDateInput" class="col-sm-3 col-form-label">End</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control disable" id="endDateInput" aria-label="readonly input example" readonly>
                            </div>
                        </div>

                        <!-- Sesi -->
                        <div id="sesiContainer">
                            <div class="form-group row position-relative">
                                <label for="sesiInput" class="col-sm-3 col-form-label">Sesi</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control disable" id="sesiInput" aria-label="readonly input example" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Investasi (Numerik) -->
                        <div class="form-group row position-relative mb-3 mt-3">
                            <label for="investasiInput" class="col-sm-3 col-form-label">Investasi</label>
                            <div class="col-sm-9 input-group">
                                <input type="number" class="form-control" id="investasiInput" aria-label="readonly input example" readonly>
                            </div>
                        </div>

                        <!-- Investasi (String) -->
                        <div id="investasiInfoContainer">
                            <div class="form-group row position-relative mb-1 mt-3">
                                <label for="investasiInfoInput" class="col-sm-3 col-form-label">Investasi Info</label>
                                <div class="col-sm-9 input-group">
                                    <input type="text" class="form-control" id="investasiInfoInput" aria-label="readonly input example" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Diskon -->
                        <div class="form-group row position-relative mt-3">
                            <label for="diskonInput" class="col-sm-3 col-form-label">Diskon %</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control disable" id="diskonInput" aria-label="readonly input example" readonly>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group row position-relative">
                            <label for="statusInput" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="statusInput" disabled>
                                    <option value="Planning">Planning</option>
                                    <option value="Masa Pendaftaran">Masa Pendaftaran</option>
                                    <option value="Sedang Berlangsung">Sedang Berlangsung</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                        </div>

                        <!-- Link Mayar -->
                        <div class="form-group row position-relative mt-3">
                            <label for="linkMayarInput" class="col-sm-3 col-form-label">Link Mayar</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="linkMayarInput" aria-label="readonly input example" readonly>
                            </div>
                        </div>

                        <!-- Mentor -->
                        <div class="form-group row position-relative">
                            <label for="mentorInput" class="col-sm-3 col-form-label">Mentor</label>
                            <div class="col-sm-9">
                                <select id="mentorInput" class="form" multiple></select>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="button-submit mt-4">
                            <button class="btn btn-success col-sm-3" type="button">Submit</button>
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
        valueField: 'id',
        labelField: 'name',
        searchField: ['name'],
        placeholder: 'Pilih mentor...',
        create: false,
        render: {
            item: function(data, escape) {
                return '<div class="item">' + escape(data.name) + '<span class="remove bi bi-x" style="font-size:16px"></span></div>';
            }
        }
    })[0].selectize;

    // Fetch mentor data from the API
    axios.get('/api/mentor')  // Adjust this API endpoint to match your actual mentor data endpoint
        .then(function(response) {
            const mentors = response.data.data; // Adjust according to your API response structure
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

                // Populate sesi fields
                const sesiContainer = $('#sesiContainer');
                sesiContainer.empty();  // Clear existing inputs
                if (data.sesi && data.sesi.length > 0) {
                    data.sesi.forEach((sesiItem, index) => {
                        sesiContainer.append(`
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label">${index === 0 ? 'Sesi' : ''}</label>
                                <div class="col-sm-9 input-group">
                                    <input type="text" class="form-control" value="${sesiItem}" aria-label="readonly input example" readonly>
                                    <div class="input-group-append">
                                        
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                }

                // Populate investasi_info fields
                const investasiInfoContainer = $('#investasiInfoContainer');
                investasiInfoContainer.empty();  // Clear existing inputs
                if (data.investasi_info && data.investasi_info.length > 0) {
                    data.investasi_info.forEach((investasiInfoItem, index) => {
                        investasiInfoContainer.append(`
                            <div class="form-group row position-relative mb-1 ">
                                <label class="col-sm-3 col-form-label">${index === 0 ? 'Investasi Info' : ''}</label>
                                <div class="col-sm-9 input-group">
                                    <input type="text" class="form-control" value="${investasiInfoItem}" aria-label="readonly input example" readonly>
                                    <div class="input-group-append">
                                        
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                }

                $('#investasiInput').val(data.investasi);
                $('#diskonInput').val(data.diskon);
                $('#statusInput').val(data.status);
                $('#linkMayarInput').val(data.link_mayar);

                // Populate mentor selectize field with selected mentors
                if (data.mentors && data.mentors.length > 0) {
                    data.mentors.forEach(function(mentor) {
                        selectizeControl.addItem(mentor.id);
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

    // Remove sesi field
    $(document).on('click', '.remove-sesi', function() {
        $(this).closest('.form-group').remove();
    });

    // Remove investasi info field
    $(document).on('click', '.remove-investasi', function() {
        $(this).closest('.form-group').remove();
    });
});


</script>
@endsection
