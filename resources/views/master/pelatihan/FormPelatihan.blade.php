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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body" style="padding-top: 50px">

                    <!-- General Form Elements -->
                    <form id="formPelatihan">
                        <!-- Nama Pelatihan -->
                        <div class="form-group row position-relative">
                            <label for="trainingInput" class="col-sm-3 col-form-label">Nama Pelatihan</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="trainingInput" name="nama_pelatihan">
                                <div class="dropdown-menu" id="trainingDropdown"></div>
                                <small id="nameError" class="text-danger" style="display:none;">Nama Pelatihan Sudah ada</small>
                            </div>
                        </div>                        

                        <!-- Image -->
                        <div class="row mb-3">
                          <label for="formFile" class="col-form-label col-sm-3">Gambar</label>
                          <div class="col-sm-6">
                            <input class="form-control" type="file" id="formFile" name="gambar_pelatihan">
                          </div>
                        </div>

                        <!-- Description -->
                        <div class="row mb-3">
                          <label for="exampleFormControlTextarea1" class="col-form-label col-sm-3">Deskripsi</label>
                          <div class="col-sm-6">
                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="deskripsi"></textarea>
                          </div>
                        </div>

                        <div id="materiContainer">
                          <div class="form-group row position-relative mb-1">
                            <label class="col-sm-3 col-form-label">Materi</label>
                            <div class="col-sm-6 input-group">
                                <input type="text" class="form-control materi" name="materi[]">
                                <div class="input-group-append">
                                  <button class="btn btn-outline-success add-materi" type="button"><i class="bi bi-plus-circle"></i></button>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div id="benefitContainer">
                          <div class="form-group row position-relative mb-1 mt-3">
                            <label class="col-sm-3 col-form-label">Benefit</label>
                            <div class="col-sm-6 input-group">
                                <input type="text" class="form-control benefit" name="benefit[]">
                                <div class="input-group-append">
                                  <button class="btn btn-outline-success add-benefit" type="button"><i class="bi bi-plus-circle"></i></button>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-11 text-right">
                                <button type="button" class="btn" id="submitPelatihan" style="background-color: #344C92; color: white;">Submit</button>
                            </div>
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

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var trainingInput = document.getElementById('trainingInput');
        var trainingDropdown = document.getElementById('trainingDropdown');
        var nameError = document.getElementById('nameError');
        var trainings = [];
    
        // Fetch data pelatihan dari API
        axios.get('/api/pelatihan/daftar-pelatihan')
            .then(function(response) {
                trainings = response.data.data.map(function(pelatihan) {
                    return pelatihan.nama_pelatihan.toLowerCase();
                });
            })
            .catch(function(error) {
                console.log('Error fetching data pelatihan:', error);
            });
    
        trainingInput.addEventListener('input', function () {
            var inputValue = trainingInput.value.toLowerCase();
            trainingDropdown.innerHTML = ''; // Kosongkan dropdown
            
            // Periksa apakah nama pelatihan sudah ada
            if (trainings.includes(inputValue)) {
                nameError.style.display = 'block'; // Tampilkan pesan kesalahan
            } else {
                nameError.style.display = 'none'; // Sembunyikan pesan kesalahan
            }
    
            if (inputValue.length > 0) {
                var matchedTrainings = trainings.filter(function (training) {
                    return training.includes(inputValue);
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
                        nameError.style.display = 'none'; // Sembunyikan pesan kesalahan
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
                        <input type="text" class="form-control materi" name="materi[]">
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
                        <input type="text" class="form-control benefit" name="benefit[]">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary remove-benefit" type="button"><i class="bi bi-dash-circle"></i></button>
                        </div>
                    </div>
                </div>
            `;
            $('#benefitContainer').append(newBenefitRow);
        });
    
        // Hapus kolom benefit
        $('#benefitContainer').on('click', '.remove-benefit', function () {
            $(this).closest('.form-group').remove();
        });
    
        // Fungsi Validasi
        function validateForm() {
            var isValid = true;
            var errorMessages = [];
    
            // Validasi Nama Pelatihan
            if ($('#trainingInput').val().trim() === '') {
                isValid = false;
                errorMessages.push('Nama Pelatihan wajib diisi.');
            }
    
            // Validasi Materi
            var materiValues = $('.materi').map(function() {
                return $(this).val().trim();
            }).get();
    
            if (materiValues.length === 0 || materiValues.every(function(value) { return value === ''; })) {
                isValid = false;
                errorMessages.push('Setidaknya satu materi wajib diisi.');
            }
    
            // Validasi Benefit
            var benefitValues = $('.benefit').map(function() {
                return $(this).val().trim();
            }).get();
    
            if (benefitValues.length === 0 || benefitValues.every(function(value) { return value === ''; })) {
                isValid = false;
                errorMessages.push('Setidaknya satu benefit wajib diisi.');
            }
    
            // Tampilkan pesan kesalahan jika ada
            if (!isValid) {
                alert(errorMessages.join('\n'));
            }
    
            return isValid;
        }
    
        // Submit form menggunakan Axios dengan validasi
        $('#submitPelatihan').click(function() {
            // Cek validasi form sebelum submit
            if (!validateForm()) {
                return; // Hentikan submit jika tidak valid
            }
    
            // Ambil data dari form
            var formData = new FormData($('#formPelatihan')[0]);
    
            // Kirim data menggunakan Axios
            axios.post('/api/pelatihan/tambah-pelatihan', formData)
                .then(function(response) {
                    alert('Pelatihan berhasil ditambahkan!');
                    console.log(response.data);
    
                    // Redirect ke halaman master pelatihan
                    window.location.href = '/admin/pelatihan';
                })
                .catch(function(error) {
                    if (error.response && error.response.data.errors && error.response.data.errors.nama_pelatihan) {
                        nameError.textContent = error.response.data.errors.nama_pelatihan[0];
                        nameError.style.display = 'block';
                    } else {
                        alert('Gagal menambahkan pelatihan. Coba lagi.');
                        console.log(error.response.data);
                    }
                });
        });
    
    });
    </script>
    
@endsection
