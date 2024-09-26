@extends('layouts.AdminLayouts')
@section('title')
Arutala | Tambah Data Pelatihan
@endsection
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

  input.form-control {
    /* width: 90%; Anda bisa sesuaikan sesuai kebutuhan */
    height: 30px;
}

input.form-control, textarea.form-control {
    font-size: 12px; /* Anda bisa menggunakan ukuran dalam satuan px, em, rem, dll */
}
</style>

<div class="pagetitle row col-10 d-flex justify-content-between align-items-center">
    <h1 class="col">Form Pelatihan</h1>
    <div class="col text-right">
        <button type="button" class="btn" id="submitPelatihan" style="background-color: #344C92; color: white;">Submit</button>
    </div>
</div>
<!-- End -->



<section class="section">
    <form id="formPelatihan">
    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                
                <div class="card-body" style="padding-top: 50px">

                    <!-- General Form Elements -->
                    
                        <!-- Nama Pelatihan -->
                        <div class="form-group row position-relative">
                            <label for="trainingInput" class="col-sm-2 col-form-label">Nama Pelatihan</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="trainingInput" name="nama_pelatihan">
                                <div class="dropdown-menu" id="trainingDropdown"></div>
                                <small id="nameError" class="text-danger" style="display:none;">Nama Pelatihan Sudah ada</small>
                                <small id="namaErrorWajib" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>                        

                        <!-- Image -->
                        <div class="row mb-3">
                            <label for="formFile" class="col-form-label col-sm-2">Gambar</label>
                            <div class="col-sm-7">
                                <input class="form-control" type="file" id="formFile" name="gambar_pelatihan">
                                <small id="gambarError" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="row mb-3">
                            <label for="exampleFormControlTextarea1" class="col-form-label col-sm-2">Deskripsi</label>
                            <div class="col-sm-7">
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="deskripsi"></textarea>
                                <small id="deskripsiError" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>

                        <div id="materiContainer">
                            <div class="form-group row position-relative mb-1">
                                <label class="col-sm-2 col-form-label">Materi</label>
                                <div class="col-sm-7 input-group">
                                    <input type="text" class="form-control materi" name="materi[]">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success add-materi" type="button"><i class="bi bi-plus-circle"></i></button>
                                    </div>
                                </div>
                                <div class="col-sm-5 offset-sm-2">
                                    <small id="materiError" class="text-danger" style="display:none;"></small>
                                </div>
                            </div>
                        </div>
                        
                        <div id="benefitContainer">
                            <div class="form-group row position-relative mb-1 mt-3">
                                <label class="col-sm-2 col-form-label">Benefit</label>
                                <div class="col-sm-7 input-group">
                                    <input type="text" class="form-control benefit" name="benefit[]">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success add-benefit" type="button"><i class="bi bi-plus-circle"></i></button>
                                    </div>
                                </div>
                                <div class="col-sm-5 offset-sm-2">
                                    <small id="benefitError" class="text-danger" style="display:none;"></small>
                                </div>
                            </div>
                        </div>
                        

                        
                        </div>

                   
                </div>
            </div>

        </div>
    </form>
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
                    <div class="col-sm-6 input-group">
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
                    <div class="col-sm-6 input-group">
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

    // Clear existing error messages
    $('#namaErrorWajib').hide().text('');
    $('#gambarError').hide().text('');
    $('#deskripsiError').hide().text('');
    $('#materiError').hide().text('');
    $('#benefitError').hide().text('');

    // Validate Nama Pelatihan
    if ($('#trainingInput').val().trim() === '') {
        $('#namaErrorWajib').show().text('Nama pelatihan wajib diisi.');
        isValid = false;
    }

    // Validate Gambar Pelatihan (jika diunggah)
    var gambarInput = $('#formFile').val(); // Menggunakan id yang benar
    if (gambarInput) {
        var allowedExtensions = /(\.jpeg|\.png|\.jpg|\.gif|\.svg)$/i;
        if (!allowedExtensions.exec(gambarInput)) {
            $('#gambarError').show().text('Gambar pelatihan harus berupa file dengan format jpeg, png, jpg, gif, atau svg.');
            isValid = false;
        }
    }

    // Validate Deskripsi
    if ($('#exampleFormControlTextarea1').val().trim() === '') {
        $('#deskripsiError').show().text('Deskripsi wajib diisi.');
        isValid = false;
    }

    // Validate Materi
    var materiValues = $('.materi').map(function() {
        return $(this).val().trim();
    }).get();

    if (materiValues.length === 0 || materiValues.every(function(value) { return value === ''; })) {
        $('#materiError').show().text('Setidaknya satu materi wajib diisi.');
        isValid = false;
    }

    // Validate Benefit
    var benefitValues = $('.benefit').map(function() {
        return $(this).val().trim();
    }).get();

    if (benefitValues.length === 0 || benefitValues.every(function(value) { return value === ''; })) {
        $('#benefitError').show().text('Setidaknya satu benefit wajib diisi.');
        isValid = false;
    }

    return isValid;
}


    
        // Submit form menggunakan Axios dengan validasi
        $('#submitPelatihan').click(function() {
    // Cek validasi form sebelum submit
    if (!validateForm()) {
        return; // Hentikan submit jika tidak valid
    }

    // Tampilkan pop-up konfirmasi sebelum melakukan penambahan data
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menambahkan pelatihan ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Tambahkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna konfirmasi, lanjutkan dengan pengiriman data
            var formData = new FormData($('#formPelatihan')[0]);

            // Filter untuk array materi[] yang kosong
            var materiArray = $('input[name="materi[]"]').map(function() {
                return $(this).val().trim(); // Ambil nilai dan hapus spasi di depan/belakang
            }).get().filter(function(value) {
                return value !== ""; // Hanya masukkan nilai yang tidak kosong
            });

            // Filter untuk array benefit[] yang kosong
            var benefitArray = $('input[name="benefit[]"]').map(function() {
                return $(this).val().trim();
            }).get().filter(function(value) {
                return value !== ""; // Hanya masukkan nilai yang tidak kosong
            });

            // Hapus field existing untuk materi dan benefit dari FormData
            formData.delete('materi[]');
            formData.delete('benefit[]');

            // Tambahkan array materi yang difilter ke FormData
            materiArray.forEach(function(value) {
                formData.append('materi[]', value);
            });

            // Tambahkan array benefit yang difilter ke FormData
            benefitArray.forEach(function(value) {
                formData.append('benefit[]', value);
            });

            // Kirim data menggunakan Axios
            axios.post('/api/pelatihan/tambah-pelatihan', formData)
                .then(function(response) {
                    Swal.fire({
                        title: 'Sukses!',
                        text: 'Pelatihan berhasil ditambahkan!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Redirect ke halaman master pelatihan setelah sukses
                        window.location.href = '/admin/pelatihan';
                    });
                })
                .catch(function(error) {
                    if (error.response && error.response.data.errors && error.response.data.errors.nama_pelatihan) {
                        nameError.textContent = error.response.data.errors.nama_pelatihan[0];
                        nameError.style.display = 'block';
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal menambahkan pelatihan. Coba lagi.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        console.error(error.response.data);
                    }
                });
        }
    });
});


    });
    </script>
    
@endsection
