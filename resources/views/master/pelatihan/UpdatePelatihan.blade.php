@extends('layouts.AdminLayouts')

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
                        @csrf
                        @method('PUT')

                        <!-- Nama Pelatihan -->
                        <div class="form-group row position-relative">
                            <label for="trainingInput" class="col-sm-3 col-form-label">Nama Pelatihan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="trainingInput" name="nama_pelatihan">
                                <div class="dropdown-menu" id="trainingDropdown"></div>
                                <span id="error-name" class="text-danger" style="display:none;">Nama Pelatihan Sudah ada</span>
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
                          <button class="btn btn-success col-sm-3" type="submit" id="updatePelatihan">Update</button>
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
        const urlParams = new URLSearchParams(window.location.search);
        const pelatihanId = urlParams.get('id');
        console.log(urlParams)
        // Fetch detail pelatihan dari API dan isi form
        axios.get(`/api/pelatihan/detail-pelatihan/${pelatihanId}`)
            .then(function(response) {
                const data = response.data.data;
                document.getElementById('trainingInput').value = data.nama_pelatihan;
                document.getElementById('exampleFormControlTextarea1').value = data.deskripsi;

                const existingImage = document.getElementById('existingImage');
            if (data.gambar_pelatihan) {
                existingImage.src = data.gambar_pelatihan;
                existingImage.style.display = 'block'; // Tampilkan gambar
            } else {
                existingImage.style.display = 'none'; // Sembunyikan jika gambar tidak ada
            }

                // Isi materi
                data.materi.forEach(function(materi, index) {
                    if (index === 0) {
                        document.querySelector('input[name="materi[]"]').value = materi;
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
                        document.querySelector('input[name="benefit[]"]').value = benefit;
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
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal mengambil data pelatihan.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
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
        $('#formUpdatePelatihan').submit(function(event) {
    event.preventDefault();

    document.getElementById('error-name').style.display = 'none';

    // Tampilkan popup konfirmasi sebelum melakukan update
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
            // Jika pengguna mengonfirmasi, lakukan update pelatihan
            var formData = new FormData($('#formUpdatePelatihan')[0]);

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
                    console.error('Error updating pelatihan:', error);
                    if (error.response && error.response.data && error.response.data.errors) {
                        const errors = error.response.data.errors;
                        if (errors.nama_pelatihan) {
                            document.getElementById('error-name').textContent = errors.nama_pelatihan[0];
                            document.getElementById('error-name').style.display = 'block';
                        }

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
