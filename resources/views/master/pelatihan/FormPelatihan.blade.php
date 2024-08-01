@extends('layouts.AdminLayouts')

@section('content')

<div class="pagetitle">
    <h1>Form Pelatihan</h1>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-8">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">General Form Elements</h5>

            <!-- General Form Elements -->
            <form>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-3  col-form-label">Nama Pelatihan</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-3 col-form-label">Batch</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputDate" class="col-sm-3 col-form-label">Start</label>
                <div class="col-sm-9">
                  <input type="date" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputDate" class="col-sm-3 col-form-label">End</label>
                <div class="col-sm-9">
                  <input type="date" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-9">
                  <select class="form-select" aria-label="Default select example">
                    <option selected>Open this select menu</option>
                    <option value="1">Selesai</option>
                    <option value="2">Masa Pendaftaran</option>
                    <option value="3">Planning</option>
                    <option value="3">Sedang Berlangsung</option>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-3  col-form-label">Nama Mentor</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control">
                </div>
              </div>
      

            </form><!-- End General Form Elements -->

          </div>
        </div>

      </div>

     
    </div>
  </section>

@endsection