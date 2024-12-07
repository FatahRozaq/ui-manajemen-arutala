@extends('layouts.AdminLayouts')
@section('title')
Arutala | Update Data Pelatihan
@endsection
@section('style')
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
    justify-content: end;
  }
</style>
@endsection

@section('content')

<style>
    .breadcrumb {
      background-color: transparent;
      padding-left: 0;
      padding-bottom: 0;
    }

    .breadcrumb-item {
        font-size: 12px;
    }
  </style>
  
  <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/pelatihan">Pelatihan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Update Pelatihan</li>
      </ol>
  </nav>

<form id="formUpdatePelatihan">
    @csrf
    @method('PUT')

<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Update Pelatihan</h1>

    <button type="submit" class="btn d-flex align-items-center custom-btn" id="updatePelatihan" style="background-color: #344C92; color: white;">
        Save
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body" style="padding-top: 50px">

                    <!-- Update Form Elements -->
                   

                        <!-- Nama Pelatihan -->
                        <div class="form-group row position-relative">
                            <label for="trainingInput" class="col-sm-2 col-form-label">Nama Pelatihan</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="trainingInput" name="nama_pelatihan">
                                <div class="dropdown-menu" id="trainingDropdown"></div>
                                <span id="error-name" class="text-danger" style="display:none;">Nama Pelatihan Sudah ada</span>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="row mb-3">
                          <label for="formFile" class="col-form-label col-sm-2">Gambar</label>
                          <div class="col-sm-7">
                            <input class="form-control" type="file" id="formFile" name="gambar_pelatihan">
                            <img id="existingImage" src="#" alt="Gambar Pelatihan" style="width: 100px; margin-top: 10px;">
                            <span id="error-image" class="text-danger" style="display:none;"></span>
                          </div>
                        </div>

                        <!-- Description -->
                        <div class="row mb-3">
                          <label for="exampleFormControlTextarea1" class="col-form-label col-sm-2">Deskripsi</label>
                          <div class="col-sm-7">
                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="deskripsi"></textarea>
                          <span id="error-deskripsi" class="text-danger" style="display:none;"></span>
                          </div>
                        </div>

                        <!-- Materi Field -->
                        <div id="materiContainer">
                            <div class="form-group row position-relative mb-1">
                            <label class="col-sm-2 col-form-label">Materi</label>
                            <div class="col-sm-7 input-group">
                                <input type="text" class="form-control materi" name="materi[]">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-success add-materi" type="button"><i class="bi bi-plus-circle"></i></button>
                                </div>
                                <!-- Error message container -->
                                <span class="text-danger materi-error" style="display:none;"></span>
                            </div>
                            </div>
                        </div>
                        

                       <!-- Benefit Field -->
                        <div id="benefitContainer">
                            <div class="form-group row position-relative mb-1 mt-3">
                            <label class="col-sm-2 col-form-label">Benefit</label>
                            <div class="col-sm-7 input-group">
                                <input type="text" class="form-control benefit" name="benefit[]">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-success add-benefit" type="button"><i class="bi bi-plus-circle"></i></button>
                                </div>
                                <!-- Error message container -->
                                <span class="text-danger benefit-error" style="display:none;"></span>
                            </div>
                            </div>
                        </div>
                        

                        {{-- <div class="button-submit mt-4">
                          <button class="btn btn-success col-sm-2" type="submit" id="updatePelatihan">Update</button>
                        </div> --}}
                        {{-- <div class="row">
                            <div class="col-sm-11 text-right">
                                <button type="submit" class="btn" id="updatePelatihan" style="background-color: #344C92; color: white;">Submit</button>
                            </div>
                        </div> --}}

                    
                </div>
            </div>

        </div>
    </div>
</section>
</form>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const pelatihanId = urlParams.get('id');

    // Fetch detail pelatihan dari API dan isi form
    axios.get(`/api/pelatihan/detail-pelatihan/${pelatihanId}`)
        .then(function(response) {
            const data = response.data.data;
            document.getElementById('trainingInput').value = data.nama_pelatihan;
            document.getElementById('exampleFormControlTextarea1').value = data.deskripsi;

            const existingImage = document.getElementById('existingImage');

            // Set gambar sesuai dengan gambar yang tersedia
            if (data.gambar_pelatihan) {
                existingImage.src = data.gambar_pelatihan;
            } else {
                existingImage.src = '/assets/images/default-pelatihan.jpg';
            }

            // Event listener jika gambar gagal dimuat
            existingImage.onerror = function() {
                this.src = '/assets/images/default-pelatihan.jpg';
                this.style.display = 'block';  // Pastikan gambar tetap ditampilkan
            };

            // Pastikan gambar ditampilkan
            existingImage.style.display = 'block';

            // Isi materi
            data.materi.forEach(function(materi, index) {
                if (index === 0) {
                    document.querySelector('input[name="materi[]"]').value = materi;
                } else {
                    $('#materiContainer').append(`
                        <div class="form-group row position-relative mb-1">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-7 input-group">
                                <input type="text" class="form-control materi" name="materi[]" value="${materi}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary remove-materi" type="button"><i class="bi bi-dash-circle"></i></button>
                                </div>
                                <span class="text-danger materi-error" style="display:none;"></span>
                            </div>
                        </div>
                    `);
                }
            });

            // Isi benefit
            data.benefit.forEach(function(benefit, index) {
                if (index === 0) {
                    document.querySelector('input[name="benefit[]"]').value = benefit;
                } else {
                    $('#benefitContainer').append(`
                        <div class="form-group row position-relative mb-1">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-7 input-group">
                                <input type="text" class="form-control benefit" name="benefit[]" value="${benefit}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary remove-benefit" type="button"><i class="bi bi-dash-circle"></i></button>
                                </div>
                                <span class="text-danger benefit-error" style="display:none;"></span>
                            </div>
                        </div>
                    `);
                }
            });
        })
        .catch(function(error) {
            Swal.fire({
                title: 'Error!',
                text: 'Gagal mengambil data pelatihan.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });

    // Tambah kolom baru pada Materi
    $('#materiContainer').on('click', '.add-materi', function () {
        var newMateriRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-7 input-group">
                    <input type="text" class="form-control materi" name="materi[]">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-materi" type="button"><i class="bi bi-dash-circle"></i></button>
                    </div>
                    <span class="text-danger materi-error" style="display:none;"></span>
                </div>
            </div>
        `;
        $('#materiContainer').append(newMateriRow);
    });

    // Hapus kolom materi
    $('#materiContainer').on('click', '.remove-materi', function () {
        $(this).closest('.form-group').remove();
    });

    // Tambah kolom baru pada Benefit
    $('#benefitContainer').on('click', '.add-benefit', function () {
        var newBenefitRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-7 input-group">
                    <input type="text" class="form-control benefit" name="benefit[]">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-benefit" type="button"><i class="bi bi-dash-circle"></i></button>
                    </div>
                    <span class="text-danger benefit-error" style="display:none;"></span>
                </div>
            </div>
        `;
        $('#benefitContainer').append(newBenefitRow);
    });

    // Hapus kolom benefit
    $('#benefitContainer').on('click', '.remove-benefit', function () {
        $(this).closest('.form-group').remove();
    });

    // Submit form update menggunakan Axios
    $('#formUpdatePelatihan').submit(function(event) {
        event.preventDefault();

        // Sembunyikan semua pesan error sebelumnya
        document.querySelectorAll('.text-danger').forEach(function (element) {
            element.textContent = '';
            element.style.display = 'none';
        });

        let isValid = true;

        // Validasi gambar
        var gambarPelatihan = document.getElementById('formFile').files[0];
        if (gambarPelatihan) {
            // Validasi format gambar
            var allowedExtensions = /(\.jpeg|\.png|\.jpg|\.gif|\.svg)$/i;
            if (!allowedExtensions.exec(gambarPelatihan.name)) {
                document.getElementById('error-image').textContent = 'Gambar harus berupa file dengan format jpeg, png, jpg, gif, atau svg.';
                document.getElementById('error-image').style.display = 'block';
                isValid = false;  // Set isValid ke false jika validasi gagal
            }

           
            if (gambarPelatihan.size > 5242880) { 
                document.getElementById('error-image').textContent = 'Ukuran gambar tidak boleh lebih dari 5MB.';
                document.getElementById('error-image').style.display = 'block';
                isValid = false;  // Set isValid ke false jika validasi gagal
            }
        }

        // Validasi deskripsi tidak boleh lebih dari 1000 karakter
        var deskripsi = document.getElementById('exampleFormControlTextarea1').value.trim();
        if (deskripsi.length > 1000) {
            document.getElementById('error-deskripsi').textContent = 'Deskripsi tidak boleh lebih dari 1000 karakter.';
            document.getElementById('error-deskripsi').style.display = 'block';
            isValid = false;  // Set isValid ke false jika validasi gagal
        }

        // Jika ada error, jangan lanjutkan
        if (!isValid) {
            return;
        }


        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, update!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var formData = new FormData($('#formUpdatePelatihan')[0]);

                // Ambil dan filter materi
                var materiArray = $('input[name="materi[]"]').map(function() {
                    return $(this).val().trim();
                }).get().filter(function(materi) {
                    return materi !== ''; 
                });

                // Ambil dan filter benefit
                var benefitArray = $('input[name="benefit[]"]').map(function() {
                    return $(this).val().trim();
                }).get().filter(function(benefit) {
                    return benefit !== ''; 
                });

                // Hapus elemen yang ada di formData yang kosong
                formData.delete('materi[]');
                materiArray.forEach(function(materi) {
                    formData.append('materi[]', materi);
                });

                formData.delete('benefit[]');
                benefitArray.forEach(function(benefit) {
                    formData.append('benefit[]', benefit);
                });

                axios.post(`/api/pelatihan/update-pelatihan/${pelatihanId}`, formData)
                    .then(function(response) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/pelatihan';
                            }
                        });
                    })
                    .catch(function(error) {
                        if (error.response && error.response.data && error.response.data.errors) {
                            const errors = error.response.data.errors;

                            // Tampilkan error untuk nama pelatihan
                            if (errors.nama_pelatihan) {
                                document.getElementById('error-name').textContent = errors.nama_pelatihan[0];
                                document.getElementById('error-name').style.display = 'block';
                            }

                            var gambarPelatihan = document.getElementById('formFile').files[0];
                            var deskripsi = document.getElementById('exampleFormControlTextarea1').value.trim();

                            // Validasi deskripsi tidak boleh lebih dari 1000 karakter
                            if (deskripsi.length > 1000) {
                                document.getElementById('error-deskripsi').textContent = 'Deskripsi tidak boleh lebih dari 1000 karakter.';
                                document.getElementById('error-deskripsi').style.display = 'block';
                                return;
                            }

                            if (gambarPelatihan && gambarPelatihan.size > 5242880) {
                                document.getElementById('error-image').textContent = 'Ukuran gambar tidak boleh lebih dari 5MB.';
                                document.getElementById('error-image').style.display = 'block';
                                return;
                            }

                            // Tangani error untuk setiap elemen materi
                            Object.keys(errors).forEach(function (key) {
                                if (key.startsWith('materi.')) {
                                    const index = key.split('.')[1];  
                                    const materiError = errors[key][0]; 
                                    const materiInput = document.querySelectorAll('input[name="materi[]"]')[index];
                                    if (materiInput) {
                                        let errorSpan = materiInput.parentNode.querySelector('.materi-error');
                                        if (errorSpan) {
                                            errorSpan.textContent = materiError;  
                                            errorSpan.style.display = 'block';  
                                        }
                                    }
                                }
                            });

                            // Tangani error untuk setiap elemen benefit
                            Object.keys(errors).forEach(function (key) {
                                if (key.startsWith('benefit.')) {
                                    const index = key.split('.')[1];  
                                    const benefitError = errors[key][0]; 
                                    const benefitInput = document.querySelectorAll('input[name="benefit[]"]')[index];
                                    if (benefitInput) {
                                        let errorSpan = benefitInput.parentNode.querySelector('.benefit-error');
                                        if (errorSpan) {
                                            errorSpan.textContent = benefitError;  
                                            errorSpan.style.display = 'block';  
                                        }
                                    }
                                }
                            });

                            Swal.fire({
                                title: 'Error!',
                                text: error.response.data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memperbarui pelatihan.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
            }
        });
    });
});
</script>


@endsection
