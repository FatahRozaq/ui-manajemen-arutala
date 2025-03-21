@extends('layouts.AdminLayouts')
@section('title')
Arutala | Detail Data Agenda
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
        <li class="breadcrumb-item active" aria-current="page">Detail Agenda</li>
      </ol>
  </nav>

<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Detail Data Agenda Pelatihan</h1>

    <a href="" id="updateAgendaPelatihanLink">
      <button type="button" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">Update</button>
    </a>
  </div>

  <section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="padding: 20px">
                <div class="card-body">

                    <!-- General Form Elements -->
                    <form>
                        <div class="row">
                            
                        
                        <div class="col-lg-7">
                        
                        <!-- Nama Pelatihan -->
                        <div class="form-group row position-relative">
                            <label for="namaPelatihanInput" class="col-sm-3 col-form-label">Nama Pelatihan</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control disable" id="namaPelatihanInput" disabled readonly>
                            </div>
                        </div>

                        <div class="form-group row position-relative">
                            <label for="deskripsiInput" class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control disable" id="deskripsiInput" disabled readonly>
                            </div>
                        </div>

                        <!-- Materi (String) -->
                        <div id="materiContainer">
                            <!-- Investasi info fields will be populated here -->
                        </div>

                        <!-- Durasi (String) -->
                        <div id="durasiContainer">
                            <!-- Investasi info fields will be populated here -->
                        </div>

                        <div class="form-group row position-relative">
                            <label for="evaluasiInput" class="col-sm-3 col-form-label">Evaluasi</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control disable" id="evaluasiInput" disabled readonly>
                            </div>
                        </div>

                        <!-- Batch -->
                        <div class="form-group row position-relative">
                            <label for="batchInput" class="col-sm-3 col-form-label">Batch</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control disable" id="batchInput" disabled readonly>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div class="form-group row position-relative">
                            <label for="startDateInput" class="col-sm-3 col-form-label">Start Pelatihan</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control disable" id="startDateInput" aria-label="readonly input example" readonly>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="form-group row position-relative">
                            <label for="endDateInput" class="col-sm-3 col-form-label">End Pelatihan</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control disable" id="endDateInput" aria-label="readonly input example" readonly>
                            </div>
                        </div>

                        <!-- Sesi -->
                        {{-- <div id="sesiContainer">
                            <!-- Sesi fields will be populated here -->
                        </div> --}}

                        <div class="form-group row position-relative">
                            <label for="sesiInput" class="col-sm-3 col-form-label">Sesi</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control disable" id="sesiInput" disabled readonly>
                            </div>
                        </div>

                        <!-- Investasi (Numerik) -->
                        <div class="form-group row position-relative mb-3 mt-3">
                            <label for="investasiInput" class="col-sm-3 col-form-label">Investasi (Harga)</label>
                            <div class="col-sm-7 input-group">
                                <input type="number" class="form-control" id="investasiInput" aria-label="readonly input example" readonly>
                            </div>
                        </div>

                        <!-- Investasi (String) -->
                        <div id="investasiInfoContainer">
                            <!-- Investasi info fields will be populated here -->
                        </div>

                        <!-- Diskon -->
                        <div class="form-group row position-relative mt-3">
                            <label for="diskonInput" class="col-sm-3 col-form-label">Diskon %</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control disable" id="diskonInput" aria-label="readonly input example" readonly>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group row position-relative">
                            <label for="statusInput" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-7">
                                <select class="form-control" id="statusInput" disabled>
                                    <option value="Planning">Planning</option>
                                    <option value="Masa Pendaftaran">Masa Pendaftaran</option>
                                    <option value="Sedang Berlangsung">Sedang Berlangsung</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Pendaftaran Berakhir">Pendaftar Berakhir</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row position-relative">
                            <label for="endDateInput" class="col-sm-3 col-form-label">Start Pendaftaran</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control disable" id="startPendaftaranInput" aria-label="readonly input example" readonly>
                            </div>
                        </div>

                        <div class="form-group row position-relative">
                            <label for="endDateInput" class="col-sm-3 col-form-label">End Pendaftaran</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control disable" id="endPendaftaranInput" aria-label="readonly input example" readonly>
                            </div>
                        </div>

                        <!-- Link Mayar -->
                        <div class="form-group row position-relative mt-3">
                            <label for="linkMayarInput" class="col-sm-3 col-form-label">Link Mayar</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="linkMayarInput" aria-label="readonly input example" readonly>
                            </div>
                        </div>

                        <!-- Mentor -->
                        <div class="form-group row position-relative">
                            <label for="mentorInput" class="col-sm-3 col-form-label">Mentor</label>
                            <div class="col-sm-7">
                                <select id="mentorInput" class="form-mentor" multiple aria-label="readonly input example" readonly></select>
                            </div>
                        </div>
                        </div>
                        <div class="col-4">
                            {{-- <div class="form-group position-relative"> --}}
                                
                                <div class="col-sm-12">
                                    <label for="posterAgenda" class="col-form-label">Poster Agenda</label>
                                    <img id="posterAgenda" src="" alt="Poster Agenda" style="width: 100%; height: auto; border-radius: 10px;" />
                                </div>
                            {{-- </div> --}}
                                </div>
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
        // placeholder: 'Pilih mentor...',
        create: false,
        render: {
            item: function(data, escape) {
                return '<div class="item">' + escape(data.nama_mentor) + '<span class="remove" style="font-size:16px"></span></div>';
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
                $('#deskripsiInput').val(data.deskripsi);
                $('#evaluasiInput').val(data.evaluasi);
                $('#sesiInput').val(data.sesi);
                $('#batchInput').val(data.batch);
                $('#startDateInput').val(data.start_date);
                $('#endDateInput').val(data.end_date);
                $('#startPendaftaranInput').val(data.start_pendaftaran);
                $('#endPendaftaranInput').val(data.end_pendaftaran);

                var updateAgendaPelatihanLink = document.getElementById('updateAgendaPelatihanLink');
                updateAgendaPelatihanLink.href = `/admin/agendapelatihan/update?id=${data.id_agenda}`;



                // Populate investasi_info fields
                const investasiInfoContainer = $('#investasiInfoContainer');
                investasiInfoContainer.empty();  // Clear existing inputs
                if (data.investasi_info && data.investasi_info.length > 0) {
                    data.investasi_info.forEach((investasiInfoItem, index) => {
                        investasiInfoContainer.append(`
                            <div class="form-group row position-relative mb-1 ">
                                <label class="col-sm-3 col-form-label">${index === 0 ? 'Keterangan Investasi' : ''}</label>
                                <div class="col-sm-7 input-group">
                                    <input type="text" class="form-control" value="${investasiInfoItem}" aria-label="readonly input example" readonly>
                                </div>
                            </div>
                        `);
                    });
                }

                // Materi Info
                const materiContainer = $('#materiContainer');
                materiContainer.empty(); // Bersihkan input yang ada
                if (data.materi && data.materi.length > 0) {
                    data.materi.forEach((materiItem, index) => {
                        materiContainer.append(`
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label">${index === 0 ? 'Materi' : ''}</label>
                                <div class="col-sm-7 input-group">
                                    <input type="text" class="form-control" value="${materiItem}" aria-label="readonly input example" readonly>
                                </div>
                            </div>
                        `);
                    });
                }

                // Durasi Info
                const durasiContainer = $('#durasiContainer');
                durasiContainer.empty(); // Bersihkan input yang ada
                if (data.durasi && data.durasi.length > 0) {
                    data.durasi.forEach((durasiItem, index) => {
                        durasiContainer.append(`
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label">${index === 0 ? 'Durasi Pelatihan' : ''}</label>
                                <div class="col-sm-7 input-group">
                                    <input type="text" class="form-control" value="${durasiItem}" aria-label="readonly input example" readonly>
                                </div>
                            </div>
                        `);
                    });
                }


                $('#investasiInput').val(data.investasi);
                $('#diskonInput').val(data.diskon);
                $('#statusInput').val(data.status);
                $('#linkMayarInput').val(data.link_mayar);

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
});
</script>
@endsection
