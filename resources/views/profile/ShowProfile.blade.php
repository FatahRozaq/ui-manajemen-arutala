@extends('layouts.PesertaLayouts')

@section('title')
Arutala | Profile Peserta
@endsection

@section('style')
<style>
    h3 {
        font-size: 22px;
        font-weight: 600;
    }

    hr {
        border-top: 3px solid #000000;
    }
</style>
@endsection

@section('content')

    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Profile</h1>
        <a href="{{ route('peserta.profile.update') }}" class="btn" style="background-color: #344C92; color: white;">
            <i class="fa-regular fa-pen-to-square"></i>
            Update Profile
        </a>
    </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body" style="padding-top: 30px">
            <h3>Data Diri</h3>
            <hr>
              <div class="column mb-4">
                <label for="inputText" class="col-sm-4  col-form-label font-weight-bold">Nama Peserta</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" placeholder="John Doe" disabled>
                </div>
              </div>

              <div class="column mb-4">
                <label for="inputText" class="col-sm-4  col-form-label font-weight-bold">Email</label>
                <div class="col-sm-12">
                  <input type="email" class="form-control" placeholder="example@gmail.com" disabled>
                </div>
              </div>

              <div class="column mb-4">
                <label for="inputText" class="col-sm-4  col-form-label font-weight-bold">No Kontak</label>
                <div class="col-sm-12">
                  <input type="number" class="form-control" placeholder="+62 123123" disabled>
                </div>
              </div>

              <div class="column mb-4">
                <label for="inputText" class="col-sm-4  col-form-label font-weight-bold">Aktivitas</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" placeholder="Mahasiswa" disabled>
                </div>
              </div>

              <div class="column mb-4">
                <label for="inputText" class="col-sm-8  col-form-label font-weight-bold">Nama Instansi/Lembaga</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" placeholder="Politeknik Negeri Bandung" disabled>
                </div>
              </div>

          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card">
          <div class="card-body" style="padding-top: 30px">
            <h3>Asal Wilayah</h3>
            <hr>
              <div class="column mb-4">
                <label for="inputText" class="col-sm-4  col-form-label font-weight-bold">Provinsi</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" placeholder="Jawa Barat" disabled>
                </div>
              </div>

              <div class="column mb-4">
                <label for="inputText" class="col-sm-4  col-form-label font-weight-bold">Kab/Kota</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" placeholder="Bandung" disabled>
                </div>
              </div>

          </div>
        </div>

        <div class="card">
          <div class="card-body" style="padding-top: 30px">
            <h3>Lain Lain</h3>
            <hr>
              <div class="column mb-4">
                <label for="inputText" class="col-sm-4  col-form-label font-weight-bold">Linked in</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" placeholder="john.doe" disabled>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection