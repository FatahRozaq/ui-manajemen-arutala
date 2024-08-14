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
            <!-- <h5 class="card-title">General Form Elements</h5> -->
              <div class="row mb-4">
                <label for="inputText" class="col-sm-3  col-form-label">Nama Mentor</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" placeholder="John Doe" disabled>
                </div>
              </div>
              <div class="row mb-4">
                <label for="inputText" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" placeholder="example@gmail.com" disabled>
                </div>
              </div>
              <div class="row mb-4">
                <label for="inputDate" class="col-sm-3 col-form-label">Kontak</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" placeholder="+62 81213134" disabled>
                </div>
              </div>
              <div class="row mb-4">
                <label for="inputText" class="col-sm-3  col-form-label">Aktivitas</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" placeholder="Dosen" disabled>
                </div>
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