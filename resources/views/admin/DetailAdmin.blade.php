@extends('layouts.AdminLayouts')

@section('title')
Arutala | Detail Data Admin
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
    <li class="breadcrumb-item"><a href="/admin/kelola-admin">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Admin</li>
  </ol>
</nav>
  <div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Detail Data Admin</h1>

    <a href="#" id="updateAdminLink">
      <button type="button" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">Update</button>
    </a>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body" style="padding-top: 50px">
              <div class="row mb-4">
                <label for="inputNamaAdmin" class="col-sm-2 col-form-label">Nama Admin</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="inputNamaAdmin" disabled>
                </div>
              </div>
              <div class="row mb-4">
                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="inputEmail" disabled>
                </div>
              </div>
          </div>
        </div>

      </div>
    </div>
  </section>

@endsection

@section('scripts')
<!-- Tambahkan ini untuk memuat jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const adminId = urlParams.get('id');
        const updateMentorLink = document.getElementById('updateAdminLink');
        updateMentorLink.href = `/admin/kelola-admin/update?id=${adminId}`;
        if (adminId) {
            axios.get(`/api/kelola-admin/${adminId}`)
                .then(function(response) {
                    const data = response.data.data; // Ambil objek data dari respons
                    console.log(data); // Debug log untuk melihat data yang diterima
                    $('#inputNamaAdmin').val(data.nama); // Mengisi input Nama Admin
                    $('#inputEmail').val(data.email); // Mengisi input Email
                })
                .catch(function(error) {
                    console.error('Error fetching admin data:', error);
                    alert('Gagal mengambil detail admin.');
                });
        } else {
            console.error('Invalid or missing admin ID.');
            alert('Admin ID tidak valid atau tidak ditemukan.');
        }
    });
</script>
@endsection
