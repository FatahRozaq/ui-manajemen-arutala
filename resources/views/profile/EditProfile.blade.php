@extends('layouts.PesertaLayouts')

@section('title')
Arutala | Profile Peserta
@endsection

@section('style')
<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
@endsection

@section('content')
<form action="">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Profile</h1>
        <a href="{{ route('mentor.add') }}" class="btn" style="background-color: #344C92; color: white;">
            <i class="fa-regular fa-floppy-disk"></i>
            Simpan Perubahan
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
                    <input type="text" class="form-control">
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
                    <div class="col-sm-12 d-flex">
                        <div class="default">
                            <label>+62</label>
                        </div>
                        <input type="number" class="form-control col-sm-8">
                    </div>
                </div>

                <div class="column mb-4">
                    <label for="aktivitas" class="col-sm-4 col-form-label font-weight-bold">Aktivitas</label>
                    <div class="col-sm-12 position-relative">
                        <select id="aktivitas" class="form-control">
                            <option value="Pelajar">Pelajar</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Pencari Kerja">Pencari Kerja</option>
                            <option value="Lain-lain">Lain-lain</option>
                        </select>
                        <i class="fas fa-chevron-down position-absolute" style="right: 30px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                    </div>
                </div>

                <div class="column mb-4">
                    <label for="inputText" class="col-sm-8  col-form-label font-weight-bold">Nama Instansi/Lembaga</label>
                    <div class="col-sm-12">
                    <input type="text" class="form-control">
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
                    <label for="provinsi" class="col-sm-4 col-form-label font-weight-bold">Provinsi</label>
                    <div class="col-sm-12 position-relative">
                        <select id="provinsi" class="form-control">
                            <option value="Jawa Barat">Jawa Barat</option>
                            <option value="Jawa Tengah">Jawa Tengah</option>
                            <option value="Jawa Timur">Jawa Timur</option>
                            <option value="DKI Jakarta">DKI Jakarta</option>
                            <option value="Bali">Bali</option>
                            <!-- Tambahkan lebih banyak opsi provinsi jika diperlukan -->
                        </select>
                        <i class="fas fa-chevron-down position-absolute" style="right: 30px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                    </div>
                </div>

                <div class="column mb-4">
                    <label for="kabupaten" class="col-sm-4 col-form-label font-weight-bold">Kab/Kota</label>
                    <div class="col-sm-12 position-relative">
                        <select id="kabupaten" class="form-control">
                            <option value="Bandung">Bandung</option>
                            <option value="Surabaya">Surabaya</option>
                            <option value="Semarang">Semarang</option>
                            <option value="Jakarta Pusat">Jakarta Pusat</option>
                            <option value="Denpasar">Denpasar</option>
                            <!-- Tambahkan lebih banyak opsi kabupaten/kota jika diperlukan -->
                        </select>
                        <i class="fas fa-chevron-down position-absolute" style="right: 30px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
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
                    <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    
  </section>
</form>
@endsection
