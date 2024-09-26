@extends('layouts.AdminLayouts')

@section('title')
Arutala | Detail Data Mentor
@endsection

@section('content')

  <div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Detail Data Mentor</h1>

    <a href="#" id="updateMentorLink">
      <button type="button" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">Update</button>
    </a>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body" style="padding-top: 50px">
              <div class="row mb-4">
                <label for="inputNamaMentor" class="col-sm-3 col-form-label">Nama Mentor</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="inputNamaMentor" style="height: 30px; font-size: 12px" disabled>
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
                <div class="col-sm-6 d-flex">
                  <div class="default-internal">+62</div>
                  <input type="text" class="form-control" id="inputKontak" disabled>
                </div>
              </div>
              <div class="row mb-4">
                <label for="inputAktivitas" class="col-sm-3 col-form-label">Aktivitas</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="inputAktivitas" disabled>
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
        const urlParams = new URLSearchParams(window.location.search);
        const mentorId = urlParams.get('id');
        const apiUrl = `/api/mentor/${mentorId}`;
        const updateMentorLink = document.getElementById('updateMentorLink');

        updateMentorLink.href = `/admin/mentor/update?id=${mentorId}`;

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Populate the form fields with the fetched data
                    document.getElementById('inputNamaMentor').value = data.data.nama_mentor;
                    document.getElementById('inputEmail').value = data.data.email;
                    document.getElementById('inputKontak').value = data.data.no_kontak;
                    document.getElementById('inputAktivitas').value = data.data.aktivitas;
                } else {
                    console.error(data.message);
                    alert('Failed to fetch mentor details.');
                }
            })
            .catch(error => {
                console.error('Error fetching mentor data:', error);
                alert('An error occurred while fetching mentor data.');
            });
    });
</script>
@endsection
