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
    document.addEventListener('DOMContentLoaded', function () {
        // Ambil ID mentor dari query parameter di URL
        const urlParams = new URLSearchParams(window.location.search);
        const mentorId = urlParams.get('id');

        if (mentorId) {
            axios.get(`/api/mentor/${mentorId}`)
                .then(function (response) {
                    var mentor = response.data.data;
                    document.getElementById('inputNamaMentor').value = mentor.nama_mentor;
                    document.getElementById('inputEmail').value = mentor.email;
                    document.getElementById('inputKontak').value = mentor.no_kontak;
                    document.getElementById('inputAktivitas').value = mentor.aktivitas;

                    // Set href untuk tombol update
                    document.getElementById('updateMentorLink').href = `/admin/mentor/update?id=${mentorId}`;
                })
                .catch(function (error) {
                    console.error('Error fetching mentor data:', error);
                });
        } else {
            console.error('Mentor ID not found in URL');
        }
    });
</script>
@endsection
