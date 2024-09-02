@extends('layouts.AdminLayouts')

@section('title')
Arutala | Detail Data Mentor
@endsection

@section('content')

  <div class="pagetitle">
    <h1>Detail Data Mentor</h1>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body" style="padding-top: 50px">
              <div class="row mb-4">
                <label for="inputNamaMentor" class="col-sm-3 col-form-label">Nama Mentor</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="inputNamaMentor" disabled>
                </div>
              </div>
              <div class="row mb-4">
                <label for="inputEmail" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="inputEmail" disabled>
                </div>
              </div>
              <div class="row mb-4">
                <label for="inputKontak" class="col-sm-3 col-form-label">Kontak</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="inputKontak" disabled>
                </div>
              </div>
              <div class="row mb-4">
                <label for="inputAktivitas" class="col-sm-3 col-form-label">Aktivitas</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="inputAktivitas" disabled>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-11 text-right">
                  <a href="#" id="updateMentorLink">
                    <button type="button" class="btn" style="background-color: #344C92; color: white;">Update</button>
                  </a>
                </div>
              </div>
          </div>
        </div>

      </div>
    </div>
  </section>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const agendaId = urlParams.get('id');

    // Initialize Selectize for mentor input before using it
    let selectizeControl = $('#mentorInput').selectize()[0].selectize;

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
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-3 col-form-label">${index === 0 ? 'Investasi Info' : ''}</label>
                                <div class="col-sm-9 input-group">
                                    <input type="text" class="form-control" value="${investasiInfoItem}" aria-label="readonly input example" readonly>
                                </div>
                            </div>
                        `);
                    });
                }

                $('#investasiInput').val(data.investasi);
                $('#diskonInput').val(data.diskon);
                $('#statusInput').val(data.status);
                $('#linkMayarInput').val(data.link_mayar);

                // Ensure selectizeControl is properly initialized before using it
                if (selectizeControl) {
                    selectizeControl.clearOptions();
                    if (data.id_mentor) {
                        data.id_mentor.forEach(function(mentorId) {
                            selectizeControl.addItem(mentorId);
                        });
                    }
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
