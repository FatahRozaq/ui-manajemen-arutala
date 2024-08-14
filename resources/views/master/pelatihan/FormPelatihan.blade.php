@extends('layouts.AdminLayouts')

@section('content')
<style>
  .dropdown-menu {
      width: 90%;
      max-height: 150px;
      overflow-y: auto;
  }
  .dropdown-item:hover {
      background-color: #f8f9fa;
  }

  .button-submit {
    width: 100%;
    display: flex;
    flex: end;
    justify-content: end
  }
</style>

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
                        <!-- Nama Pelatihan -->
                        <div class="form-group row position-relative">
                          <label for="trainingInput" class="col-sm-3 col-form-label">Nama Pelatihan</label>
                          <div class="col-sm-9">
                              <input type="text" class="form-control" id="trainingInput">
                              <div class="dropdown-menu" id="trainingDropdown"></div>
                          </div>
                      </div>

                        <!-- Batch -->
                        <div class="row mb-3">
                          <label for="formFile" class="col-form-label col-sm-3">image</label>
                          <div class="col-sm-9">
                            <input class="form-control" type="file" id="formFile">
                          </div>
                        </div>

                        <!-- Start Date -->
                        <div class="row mb-3">
                          <label for="exampleFormControlTextarea1" class="col-form-label col-sm-3">Description</label>
                          <div class="col-sm-9">
                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                          </div>
                        </div>

                        <div id="materiContainer">
                          <div class="form-group row position-relative mb-1">
                            <label class="col-sm-3 col-form-label">Materi</label>
                            <div class="col-sm-9 input-group">
                                <input type="text" class="form-control">
                                <div class="input-group-append">
                                  <button class="btn btn-outline-success add-materi" type="button"><i class="bi bi-plus-circle"></i></button>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div id="benefitContainer">
                          <div class="form-group row position-relative mb-1 mt-3">
                            <label class="col-sm-3 col-form-label">Benefit</label>
                            <div class="col-sm-9 input-group">
                                <input type="text" class="form-control">
                                <div class="input-group-append">
                                  <button class="btn btn-outline-success add-benefit" type="button"><i class="bi bi-plus-circle"></i></button>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="button-submit mt-4">
                          <button class="btn btn-success col-sm-3" type="button">Submit</button>
                        </div>

                        </form>
                      </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<!-- Pastikan jQuery dimuat sebelum Selectize -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.default.css">
<script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      var trainingInput = document.getElementById('trainingInput');
      var trainingDropdown = document.getElementById('trainingDropdown');
      
      // Daftar nama pelatihan yang tersedia
      var trainings = ['Pelatihan 1', 'Pelatihan 2', 'Pelatihan 3'];
      
      trainingInput.addEventListener('input', function () {
          var inputValue = trainingInput.value.toLowerCase();
          trainingDropdown.innerHTML = ''; // Kosongkan dropdown
          
          if (inputValue.length > 0) {
              var matchedTrainings = trainings.filter(function (training) {
                  return training.toLowerCase().includes(inputValue);
              });
              
              matchedTrainings.forEach(function (training) {
                  var option = document.createElement('a');
                  option.classList.add('dropdown-item');
                  option.href = '#';
                  option.textContent = training;
                  option.addEventListener('click', function (e) {
                      e.preventDefault();
                      trainingInput.value = training;
                      trainingDropdown.classList.remove('show');
                  });
                  trainingDropdown.appendChild(option);
              });
              
              if (matchedTrainings.length === 0) {
                  var noMatchOption = document.createElement('a');
                  noMatchOption.classList.add('dropdown-item', 'disabled');
                  noMatchOption.href = '#';
                  noMatchOption.textContent = 'Tambah: ' + trainingInput.value;
                  trainingDropdown.appendChild(noMatchOption);
              }
              
              trainingDropdown.classList.add('show');
          } else {
              trainingDropdown.classList.remove('show');
          }
      });
      
      document.addEventListener('click', function (e) {
          if (!trainingInput.contains(e.target) && !trainingDropdown.contains(e.target)) {
              trainingDropdown.classList.remove('show');
          }
      });

      // Tambah kolom baru pada Materi
      $('#materiContainer').on('click', '.add-materi', function () {
        var newMateriRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-9 input-group">
                    <input type="text" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-materi" type="button"><i class="bi bi-dash-circle"></i></button>
                    </div>
                </div>
            </div>
        `;
        $('#materiContainer').append(newMateriRow);
    });

    // Hapus kolom materi
    $('#materiContainer').on('click', '.remove-materi', function () {
        $(this).closest('.form-group').remove();
    });

    $('#benefitContainer').on('click', '.add-benefit', function () {
        var newBenefitRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-9 input-group">
                    <input type="text" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-benefit" type="button"><i class="bi bi-dash-circle"></i></button>
                    </div>
                </div>
            </div>
        `;
        $('#benefitContainer').append(newBenefitRow);
    });

    // Hapus kolom materi
    $('#benefitContainer').on('click', '.remove-benefit', function () {
        $(this).closest('.form-group').remove();
    });

  });

  
</script>
@endsection
