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
    <h1>Update Pelatihan</h1>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Update Form Elements</h5>

                    <!-- Update Form Elements -->
                    <form id="formUpdatePelatihan">
                        <!-- Nama Pelatihan -->
                        <div class="form-group row position-relative">
                            <label for="trainingInput" class="col-sm-3 col-form-label">Nama Pelatihan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="trainingInput" name="nama_pelatihan">
                                <div class="dropdown-menu" id="trainingDropdown"></div>
                                <small id="nameError" class="text-danger" style="display:none;">Nama Pelatihan Sudah ada</small>
                            </div>
                        </div>                        

                        <!-- Image -->
                        <div class="row mb-3">
                          <label for="formFile" class="col-form-label col-sm-3">Gambar</label>
                          <div class="col-sm-9">
                            <input class="form-control" type="file" id="formFile" name="gambar_pelatihan">
                            <img id="existingImage" src="#" alt="Gambar Pelatihan" style="width: 100px; margin-top: 10px;">
                          </div>
                        </div>

                        <!-- Description -->
                        <div class="row mb-3">
                          <label for="exampleFormControlTextarea1" class="col-form-label col-sm-3">Deskripsi</label>
                          <div class="col-sm-9">
                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="deskripsi"></textarea>
                          </div>
                        </div>

                        <div id="materiContainer">
                          <div class="form-group row position-relative mb-1">
                            <label class="col-sm-3 col-form-label">Materi</label>
                            <div class="col-sm-9 input-group">
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
                            <div class="col-sm-9 input-group">
                                <input type="text" class="form-control benefit" name="benefit[]">
                                <div class="input-group-append">
                                  <button class="btn btn-outline-success add-benefit" type="button"><i class="bi bi-plus-circle"></i></button>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="button-submit mt-4">
                          <button class="btn btn-success col-sm-3" type="button" id="updatePelatihan">Update</button>
                        </div>

                        </form>
                      </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
 document.addEventListener('DOMContentLoaded', function () {
    var pelatihanId = {{ $id }}; // ID pelatihan yang akan diupdate

    // Fetch detail pelatihan dari API dan isi form
    axios.get(`/api/pelatihan/detail-pelatihan/${pelatihanId}`)
        .then(function(response) {
            var data = response.data.data;
            $('#trainingInput').val(data.nama_pelatihan);
            $('#existingImage').attr('src', `/uploads/${data.gambar_pelatihan}`);
            $('#exampleFormControlTextarea1').val(data.deskripsi);
            
            // Isi materi
            data.materi.forEach(function(materi, index) {
                if (index === 0) {
                    $('input[name="materi[]"]').val(materi);
                } else {
                    $('#materiContainer').append(`
                        <div class="form-group row position-relative mb-1">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9 input-group">
                                <input type="text" class="form-control materi" name="materi[]" value="${materi}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary remove-materi" type="button"><i class="bi bi-dash-circle"></i></button>
                                </div>
                            </div>
                        </div>
                    `);
                }
            });

            // Isi benefit
            data.benefit.forEach(function(benefit, index) {
                if (index === 0) {
                    $('input[name="benefit[]"]').val(benefit);
                } else {
                    $('#benefitContainer').append(`
                        <div class="form-group row position-relative mb-1">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9 input-group">
                                <input type="text" class="form-control benefit" name="benefit[]" value="${benefit}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary remove-benefit" type="button"><i class="bi bi-dash-circle"></i></button>
                                </div>
                            </div>
                        </div>
                    `);
                }
            });
        })
        .catch(function(error) {
            console.log('Error fetching detail pelatihan:', error);
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

    // Tambah kolom baru pada Benefit
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

    // Submit form update menggunakan Axios
    $('#updatePelatihan').click(function() {
        var formData = new FormData($('#formUpdatePelatihan')[0]);

        axios.put(`/api/pelatihan/update-pelatihan/${pelatihanId}`, formData)
            .then(function(response) {
                alert('Pelatihan berhasil diupdate!');
                console.log(response.data);

                // Redirect ke halaman master pelatihan
                window.location.href = '/master-pelatihan';
            })
            .catch(function(error) {
                console.log('Error updating pelatihan:', error);
                alert('Gagal mengupdate pelatihan. Coba lagi.');
            });
    });
});
  
</script>
@endsection
