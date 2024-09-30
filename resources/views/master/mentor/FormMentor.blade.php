@extends('layouts.AdminLayouts')

@section('content')

<style>
  .breadcrumb {
    background-color: transparent;
  }
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/admin/mentor">Mentor</a></li>
      <li class="breadcrumb-item active" aria-current="page">Tambah Mentor</li>
    </ol>
</nav>

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
                <label for="inputText" class="col-sm-3  col-form-label">Nama Mentor</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputDate" class="col-sm-3 col-form-label">Kontak</label>
                <div class="col-sm-9">
                  <input type="date" class="form-control">
                </div>
              </div>
              
            </form><!-- End General Form Elements -->

          </div>
        </div>

      </div>

     
    </div>
  </section>

@endsection