@extends('layouts.AdminLayouts')
@section('title')
Arutala | Detail Data Pelatihan
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
</style>

<div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Detail Data Pelatihan</h1>

    <a href="" id="updatePelatihanLink">
      <button type="button" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">Update</button>
    </a>
  </div>

  <section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="padding: 20px">
                <div class="card-body">
                    

                        <!-- Nama Pelatihan -->
                        <div class="form-group row position-relative">
                            <label for="trainingInput" class="col-sm-2 col-form-label">Nama Pelatihan</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="trainingInput" name="nama_pelatihan" disabled readonly>
                                <div class="dropdown-menu" id="trainingDropdown"></div>
                                <small id="nameError" class="text-danger" style="display:none;">Nama Pelatihan Sudah ada</small>
                            </div>
                        </div>                        

                        <!-- Image -->
                        <div class="row mb-3">
                          <label for="formFile" class="col-form-label col-sm-2">Gambar</label>
                          <div class="col-sm-7">
                            <img id="existingImage" src="#" alt="Gambar Pelatihan" style="width: 100px; margin-top: 10px;">
                            {{-- <input class="form-control" type="file" id="formFile" name="gambar_pelatihan"> --}}
                          </div>
                        </div>

                        <!-- Description -->
                        <div class="row mb-3">
                          <label for="exampleFormControlTextarea1" class="col-form-label col-sm-2">Deskripsi</label>
                          <div class="col-sm-7">
                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="deskripsi" disabled readonly></textarea>
                          </div>
                        </div>

                        <div id="materiContainer">
                          <div class="form-group row position-relative mb-1">
                           
                          </div>
                        </div>

                        <div id="benefitContainer">
                          <div class="form-group row position-relative mb-1 mt-3">
                           
                          </div>
                        </div>

                        
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
    var pelatihanId = window.location.pathname.split('/').pop(); // Asumsi ID pelatihan ada di URL
    var trainingInput = document.getElementById('trainingInput');
    var nameError = document.getElementById('nameError');

    console.log(pelatihanId)

    // Fetch detail pelatihan dari API
    axios.get(`/api/pelatihan/detail-pelatihan/${pelatihanId}`)
        .then(function(response) {
            var pelatihan = response.data.data;

            // Isi form dengan data pelatihan yang diterima dari API
            trainingInput.value = pelatihan.nama_pelatihan;
            // document.getElementById('formFile').src = `/storage/${pelatihan.gambar_pelatihan}`;
            document.getElementById('exampleFormControlTextarea1').value = pelatihan.deskripsi;

            var updatePelatihanLink = document.getElementById('updatePelatihanLink');
            updatePelatihanLink.href = `/admin/pelatihan/update?id=${pelatihan.id_pelatihan}`;

            const existingImage = document.getElementById('existingImage');
            if (pelatihan.gambar_pelatihan) {
                existingImage.src = pelatihan.gambar_pelatihan;
                existingImage.style.display = 'block';
            } else {
                existingImage.style.display = 'none';
            }

            // Isi materi
            var materiContainer = document.getElementById('materiContainer');
            pelatihan.materi.forEach(function(materi, index) {
                var materiRow = `
                    <div class="form-group row position-relative mb-1">
                        ${index === 0 ? '<label class="col-sm-2 col-form-label">Materi</label>' : '<label class="col-sm-2 col-form-label"></label>'}
                        <div class="col-sm-7 input-group" readonly>
                            <input type="text" class="form-control materi" name="materi[]" value="${materi}" disabled readonly>
                        
                        </div>
                    </div>
                `;
                materiContainer.insertAdjacentHTML('beforeend', materiRow);
            });

            // Isi benefit
            var benefitContainer = document.getElementById('benefitContainer');
            pelatihan.benefit.forEach(function(benefit, index) {
                var benefitRow = `
                    <div class="form-group row position-relative mb-1">
                        ${index === 0 ? '<label class="col-sm-2 col-form-label">Benefit</label>' : '<label class="col-sm-2 col-form-label"></label>'}
                        <div class="col-sm-7 input-group">
                            <input type="text" class="form-control benefit" name="benefit[]" value="${benefit}" disabled readonly>
                            
                        </div>
                    </div>
                `;
                benefitContainer.insertAdjacentHTML('beforeend', benefitRow);
            });

        })
        .catch(function(error) {
            console.log('Error fetching detail pelatihan:', error);
            alert('Gagal menampilkan detail pelatihan. Coba lagi nanti.');
        });

    // Tambah kolom baru pada Materi
    $('#materiContainer').on('click', '.add-materi', function () {
        var newMateriRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-7 input-group">
                    <input type="text" class="form-control materi" name="materi[]">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-materi" type="button">
                            <i class="bi bi-dash-circle"></i>
                        </button>
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

    // Tambah kolom baru pada Benefit
    $('#benefitContainer').on('click', '.add-benefit', function () {
        var newBenefitRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-7 input-group">
                    <input type="text" class="form-control benefit" name="benefit[]">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-benefit" type="button">
                            <i class="bi bi-dash-circle"></i>
                        </button>
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

    // Submit form untuk Update pelatihan
    $('#submitPelatihan').click(function() {
        // Ambil data dari form
        var formData = new FormData($('#formPelatihan')[0]);

        // Kirim data menggunakan Axios dengan metode PUT
        axios.put(`/api/pelatihan/update-pelatihan/${pelatihanId}`, formData)
            .then(function(response) {
                alert('Pelatihan berhasil diperbarui!');
                console.log(response.data);

                // Redirect ke halaman master pelatihan
                // window.location.href = '/master-pelatihan';
            })
            .catch(function(error) {
                if (error.response && error.response.data.errors && error.response.data.errors.nama_pelatihan) {
                    nameError.textContent = error.response.data.errors.nama_pelatihan[0];
                    nameError.style.display = 'block';
                } else {
                    alert('Gagal memperbarui pelatihan. Coba lagi.');
                    console.log(error.response.data);
                }
            });
    });

});

</script>
@endsection
