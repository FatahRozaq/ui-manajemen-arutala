@extends('layouts.AdminLayouts')

@section('title')
Arutala | Detail Data Peserta
@endsection

@section('style')
<style>
    h3 {
        font-size: 28px;
        font-weight: 500;
    }
</style>
@endsection

@section('content')

  <div class="pagetitle">
    <h1>Detail Data Peserta</h1>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body" style="padding-top: 50px">
            <!-- Participant Details -->
              <div class="row mb-4">
                <label for="inputText" class="col-sm-2  col-form-label">Nama Peserta</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" placeholder="John Doe" disabled>
                </div>
              </div>
              <div class="row mb-4">
                <label for="inputText" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-6">
                  <input type="email" class="form-control" placeholder="example@gmail.com" disabled>
                </div>
              </div>
              <div class="row mb-4">
                <label for="inputDate" class="col-sm-2 col-form-label">Kontak</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" placeholder="+62 81213134" disabled>
                </div>
              </div>
              <div class="row mb-4">
                <label for="inputText" class="col-sm-2  col-form-label">Linked In</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" placeholder="john.doe" disabled>
                </div>
              </div>
              <div class="row mb-4">
                <label for="inputText" class="col-sm-2  col-form-label">Aktivitas</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" placeholder="Mahasiswa" disabled>
                </div>
              </div>

              <!-- Asal Wilayah -->
              <h3>Asal Wilayah</h3>
              <div class="d-flex" style="margin-left:-15px">
                <div class="d-flex">
                    <label for="inputText" class="col-sm-5 col-form-label">Provinsi</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Jawa Barat" disabled>
                    </div>
                </div>

                <div class="mb-5 d-flex ml-4">
                    <label for="inputText" class="col-sm-4  col-form-label">Kab/Kota</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Bandung" disabled>
                    </div>
                </div>
              <!-- <div class="d-flex mt-4">
                <div class="row mb-5 mr-3">
                    <label for="inputText" class="col-sm-4  col-form-label">Provinsi</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Jawa Barat" disabled>
                    </div>
                </div>

                <div class="row mb-5">
                    <label for="inputText" class="col-sm-4  col-form-label">Kab/Kota</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Bandung" disabled>
                    </div>
                </div> -->
              </div>

              <!-- Instansi/Lembaga -->
              <h3>Instansi/Lembaga</h3>
                <div class="row mb-5 mr-3 mt-4">
                    <label for="inputText" class="col-sm-2  col-form-label">Instansi</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="Politeknik Negeri Bandung" disabled>
                    </div>
                </div>

              <!-- Pelatihan -->
              <h3>Pelatihan</h3>

              <!-- Carousel -->
              <div id="trainingCarousel" class="carousel slide mt-4" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="d-flex justify-content-around">
                            <div class="card" style="width: 15rem;">
                                <img class="card-img-top" src="{{ asset('assets/img/product-2.jpg') }}" alt="Pelatihan Image">
                                <div class="card-body">
                                    <h5 class="card-title">Nama Pelatihan</h5>
                                    <p class="card-text">Status</p>
                                </div>
                            </div>
                            <div class="card" style="width: 15rem;">
                                <img class="card-img-top" src="{{ asset('assets/img/product-1.jpg') }}" alt="Pelatihan Image">
                                <div class="card-body">
                                    <h5 class="card-title">Nama Pelatihan</h5>
                                    <p class="card-text">Status</p>
                                </div>
                            </div>
                            <div class="card" style="width: 15rem;">
                                <img class="card-img-top" src="{{ asset('assets/img/product-3.jpg') }}" alt="Pelatihan Image">
                                <div class="card-body">
                                    <h5 class="card-title">Nama Pelatihan</h5>
                                    <p class="card-text">Status</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tambahkan lebih banyak carousel-item div untuk slide tambahan -->
                </div>

                <a class="carousel-control-prev custom-carousel-control" href="#trainingCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next custom-carousel-control" href="#trainingCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
              </div>


              <div class="row">
                <div class="col-sm-11 text-right">
                <a href="{{ route('mentor.update') }}">
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
