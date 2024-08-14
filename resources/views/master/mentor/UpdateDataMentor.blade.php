@extends('layouts.AdminLayouts')

@section('title')
Arutala | Edit Data Mentor
@endsection

@section('content')

  <div class="pagetitle">
    <h1>Edit Data Mentor</h1>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body" style="padding-top: 50px">

            <!-- Form for Editing Mentor Details -->
            <form action="" method="POST">
              @csrf
              @method('PUT')

              <div class="row mb-4">
                <label for="name" class="col-sm-3 col-form-label">Nama Mentor</label>
                <div class="col-sm-6">
                  <input type="text" name="name" id="name" class="form-control" required>
                </div>
              </div>

              <div class="row mb-4">
                <label for="email" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                  <input type="email" name="email" id="email" class="form-control" required>
                </div>
              </div>

              <div class="row mb-4">
                <label for="contact" class="col-sm-3 col-form-label">Kontak</label>
                <div class="col-sm-6">
                  <input type="text" name="contact" id="contact" class="form-control" required>
                </div>
              </div>

              <div class="row mb-4">
                  <label for="activity" class="col-sm-3 col-form-label">Aktivitas</label>
                  <div class="col-sm-6">
                      <div class="custom-select-wrapper position-relative">
                          <select name="activity" id="activity" class="form-control" required>
                              <option value="" disabled selected>Pilih Aktivitas</option>
                              <option value="Dosen">Dosen</option>
                              <option value="Peneliti">Peneliti</option>
                              <option value="Konsultan">Konsultan</option>
                              <option value="Trainer">Trainer</option>
                          </select>
                          <i class="fa fa-chevron-down position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                      </div>
                  </div>
              </div>



              <div class="row">
                <div class="col-sm-11 text-right">
                    <button type="submit" class="btn" style="background-color: #344C92; color: white;">Update</button>
                </div>
              </div>

            </form>
          </div>
        </div>

      </div>
    </div>
  </section>

@endsection
