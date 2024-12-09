@extends('layouts.AdminLayouts')

@section('title')
Arutala | Generate QR Sertifikat
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

    .list-group-item {
        font-size: 14px;
    }
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/pesertapelatihan">Peserta Pelatihan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Generate QR Sertifikat</li>
    </ol>
</nav>
<form id="generateQRForm">
    @csrf
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Generate QR Sertifikat</h1>

        <button type="submit" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">
            Submit
        </button>
    </div>

    <section class="section">
        <div class="row">
            <!-- Card Input Data -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body" style="padding-top: 20px;">
                        <h5 class="card-title">Form Input Data</h5>

                        <div class="row mb-4">
                            <label for="nama_pelatihan" class="col-sm-4 col-form-label">Nama Pelatihan</label>
                            <div class="col-sm-8">
                                <select name="nama_pelatihan" id="namaPelatihan" class="form-control">
                                    <!-- Options will be populated by JavaScript -->
                                </select>
                                <span class="text-danger" id="error-nama-pelatihan"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="batch" class="col-sm-4 col-form-label">Batch</label>
                            <div class="col-sm-8">
                                <input type="number" name="batch" id="batch" class="form-control">
                                <span class="text-danger" id="error-batch"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="jenis_sertifikat" class="col-sm-4 col-form-label">Jenis Sertifikat</label>
                            <div class="col-sm-8">
                                <select name="jenis_sertifikat" id="jenisSertifikat" class="form-control">
                                    <option value="" disabled selected>Pilih Jenis Sertifikat</option>
                                    <option value="kompetensi">Kompetensi</option>
                                    <option value="kehadiran">Kehadiran</option>
                                </select>
                                <span class="text-danger" id="error-jenis-sertifikat"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="certificate_number" class="col-sm-4 col-form-label">Certificate Number</label>
                            <div class="col-sm-8">
                                <input type="text" name="certificate_number" id="certificateNumber" class="form-control">
                                <span class="text-danger" id="error-certificate-number"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Keterangan -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body" style="padding-top: 20px;">
                        <h5 class="card-title">Keterangan Pengisian</h5>
                        <ul class="list-group">
                            <li class="list-group-item">1. **Certificate Number** ditulis dalam format seperti: <code>BC2024.UI/UX.A.11</code>.</li>
                            <li class="list-group-item"><code>BC2024</code> Jenis Pelatihan dan Tahun Pelaksanaan</li>
                            <li class="list-group-item"><code>UI/UX</code> Kode Nama Pelatihan</li>
                            <li class="list-group-item"><code>C</code> Jenis Sertifikat. C = Competency</li>
                            <li class="list-group-item"><code>11</code> Bulan Terbit</li>
                            <li class="list-group-item">2. **Certificate Number** tidak perlu mengisi nomor urut, akan digenerate otomatis oleh sistem berdasarkan abjad nama peserta</li>
                        </ul>
                        <small class="text-muted d-block mt-3">Pastikan semua data terisi dengan benar sebelum menekan tombol submit.</small>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Mengambil data pelatihan dan mentor menggunakan Axios
    axios.get('/api/pelatihan-mentor-data') // Endpoint untuk mendapatkan data pelatihan dan mentor
        .then(function (response) {
            var pelatihans = response.data.pelatihans;
            var mentors = response.data.mentors;

            // Mengisi dropdown Nama Pelatihan
            var pelatihanSelect = $('#namaPelatihan');
            pelatihans.forEach(function(pelatihan) {
                pelatihanSelect.append('<option value="' + pelatihan.nama_pelatihan + '">' + pelatihan.nama_pelatihan + '</option>');
            });

            // Mengisi dropdown Mentor dengan Selectize
            var mentorSelectize = $('#mentorInput').selectize({
                options: mentors.map(function(mentor) {
                    return { id: mentor.id_mentor, name: mentor.nama_mentor };
                }),
                create: false,
                placeholder: 'Pilih mentor...',
                labelField: 'name',
                valueField: 'id',
                searchField: ['name'],
                render: {
                    item: function(data, escape) {
                        return '<div class="item">' + escape(data.name) + '<span class="remove bi bi-x" style="font-size:16px"></span></div>';
                    }
                },
                onItemRemove: function(value) {
                    var selectizeInstance = this;
                    selectizeInstance.refreshOptions(false);
                }
            });

            $(document).on('click', '.remove', function() {
                var $item = $(this).closest('.item');
                var value = $item.attr('data-value');
                mentorSelectize[0].selectize.removeItem(value);
            });

        })
        .catch(function (error) {
            console.error('Gagal mengambil data:', error);
        });

    // Event submit form dengan konfirmasi SweetAlert
    $('#generateQRForm').submit(function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin generate QR Code?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Generate',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Loading',
                    text: 'Proses generate sedang berlangsung...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                let formData = {
                    nama_pelatihan: $('#namaPelatihan').val(),
                    batch: $('#batch').val(),
                    jenis_sertifikat: $('#jenisSertifikat').val(),
                    certificate_number: $('#certificateNumber').val(),
                };

                axios.post('/api/sertifikat/generateQR', formData)
                    .then(function(response) {
                        Swal.close(); // Tutup loading SweetAlert

                        Swal.fire({
                            title: 'Berhasil',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'Unduh',
                        }).then(() => {
                            // Download otomatis setelah SweetAlert sukses
                            const link = document.createElement('a');
                            link.href = response.data.download_url;
                            link.download = 'QR_Code.zip';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        });
                    })
                    .catch(function(error) {
                        Swal.close(); // Tutup loading SweetAlert

                        if (error.response.status === 422) {
                            let errors = error.response.data.errors;
                            $('#error-nama-pelatihan').text(errors.nama_pelatihan?.[0] || '');
                            $('#error-batch').text(errors.batch?.[0] || '');
                            $('#error-jenis-sertifikat').text(errors.jenis_sertifikat?.[0] || '');
                            $('#error-certificate-number').text(errors.certificate_number?.[0] || '');

                            Swal.fire({
                                title: 'Gagal',
                                text: 'Validasi data tidak valid. Mohon periksa kembali isian Anda.',
                                icon: 'error',
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat generate. Coba lagi.',
                                icon: 'error',
                            });
                        }
                    });
            }
        });
    });
});

</script>
@endsection