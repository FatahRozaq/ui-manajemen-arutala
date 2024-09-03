@extends('layouts.PesertaLayouts')

@section('title')
Arutala | Pendaftaran Event
@endsection

@section('style')
<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .default {
        width: 60px;
        padding: 6px 12px;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-right: none;
        border-radius: 4px 0 0 4px;
        color: #495057;
    }
    .form-control.is-invalid {
        border-color: red;
    }
    .text-error {
        color: red;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
@endsection

@section('content')
<form id="registrationForm">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Pendaftaran Event</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body" style="padding-top: 30px">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Event</h3>
                            <button type="submit" class="btn" style="background-color: #344C92; color: white;">
                                Daftar
                            </button>
                        </div>
                        <hr>
                        <div class="text-center mb-3">
                            <img src="{{ asset('assets/images/manual-testing.png') }}" class="img-fluid" alt="Event Image" style="width: 500px; height: auto;">
                        </div>
                        <h4 class="card-subtitle mb-2" id="eventName"></h4>
                        <h5 class="font-weight-bold mb-1" style="color: #344C92;">Materi :</h5>
                        <ul id="eventMateri"></ul>
                        <h5 class="font-weight-bold mb-1" style="color: #344C92;">Benefit :</h5>
                        <ul id="eventBenefit"></ul>
                        <div class="d-flex flex-column ml-2">
                            <p class="mb-1">
                                <i class="fas fa-calendar-alt"></i> Start Date: <span id="eventStartDate"></span>
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-calendar-alt"></i> End Date: <span id="eventEndDate"></span>
                            </p>
                            <p class="mb-3">
                                <i class="fas fa-clock"></i> Sesi: <span id="eventSession"></span>
                            </p>
                        </div>
                        <div class="d-flex">
                            <h5 class="text-danger font-weight-bold mb-1 mr-4" id="eventPrice"></h5>
                            <p class="text-muted text-decoration-line-through" id="eventDiscountedPrice"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body" style="padding-top: 30px">
                        <h3>Data Diri</h3>
                        <hr>
                        <input type="hidden" name="id_agenda" id="id_agenda" value="">
                        <div class="column mb-4">
                            <label for="nama" class="col-sm-4 col-form-label font-weight-bold">Nama Peserta</label>
                            <div class="col-sm-12">
                                <input type="text" name="nama" id="nama" class="form-control" value="">
                                <span class="text-error" id="error-nama"></span>
                            </div>
                        </div>
                        <div class="column mb-4">
                            <label for="email" class="col-sm-4 col-form-label font-weight-bold">Email</label>
                            <div class="col-sm-12">
                                <input type="email" name="email" id="email" class="form-control" placeholder="example@gmail.com">
                                <span class="text-error" id="error-email"></span>
                            </div>
                        </div>
                        <div class="column mb-4">
                            <label for="no_kontak" class="col-sm-4 col-form-label font-weight-bold">No Kontak</label>
                            <div class="col-sm-12 d-flex">
                                <div class="default">+62</div>
                                <input type="number" name="no_kontak" id="no_kontak" class="form-control col-sm-8" value="">
                            </div>
                            <span class="text-error" id="error-no_kontak"></span>
                        </div>
                        <div class="column mb-4">
                            <label for="aktivitas" class="col-sm-4 col-form-label font-weight-bold">Aktivitas</label>
                            <div class="col-sm-12 position-relative">
                                <select name="aktivitas" id="aktivitas" class="form-control">
                                    <option value="" disabled selected>Pilih Aktivitas</option>
                                    <option value="Pelajar">Pelajar</option>
                                    <option value="Mahasiswa">Mahasiswa</option>
                                    <option value="Dosen">Dosen</option>
                                    <option value="Karyawan">Karyawan</option>
                                    <option value="Pencari Kerja">Pencari Kerja</option>
                                    <option value="Lain-lain">Lain-lain</option>
                                </select>
                                <i class="fas fa-chevron-down position-absolute" style="right: 30px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                                <span class="text-error" id="error-aktivitas"></span>
                            </div>
                        </div>
                        <div class="column mb-4" id="namaInstansiContainer" style="display: none;">
                            <label for="nama_instansi" class="col-sm-8 col-form-label font-weight-bold">Nama Instansi/Lembaga</label>
                            <div class="col-sm-12">
                                <input type="text" name="nama_instansi" id="nama_instansi" class="form-control" value="">
                                <span class="text-error" id="error-nama_instansi"></span>
                            </div>
                        </div>
                        <h3>Asal Wilayah</h3>
                        <hr>
                        <div class="column mb-4">
                            <label for="provinsi" class="col-sm-4 col-form-label font-weight-bold">Provinsi</label>
                            <div class="col-sm-12 position-relative">
                                <select name="provinsi" id="provinsi" class="form-control select2">
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                </select>
                                <span class="text-error" id="error-provinsi"></span>
                            </div>
                        </div>
                        <div class="column mb-4">
                            <label for="kab_kota" class="col-sm-4 col-form-label font-weight-bold">Kab/Kota</label>
                            <div class="col-sm-12 position-relative">
                                <select name="kab_kota" id="kab_kota" class="form-control select2">
                                    <option value="" disabled selected>Pilih Kab/Kota</option>
                                </select>
                                <span class="text-error" id="error-kab_kota"></span>
                            </div>
                        </div>
                        <h3>Opsional</h3>
                        <hr>
                        <div class="column mb-4">
                            <label for="linkedin" class="col-sm-4 col-form-label font-weight-bold">LinkedIn</label>
                            <div class="col-sm-12">
                                <input type="text" name="linkedin" id="linkedin" class="form-control" value="">
                                <span class="text-error" id="error-linkedin"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: 'Pilih opsi...',
            allowClear: true
        });

        let selectedProvinsi = null;
        let selectedKabupaten = null;

        // Ambil data profil saat halaman dimuat
        const token = localStorage.getItem('auth_token');
        const urlParams = new URLSearchParams(window.location.search);
        const idAgenda = urlParams.get('idAgenda');

        axios.get(`/api/pendaftaran-event/${idAgenda}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(function(response) {
            const data = response.data;
            const pendaftar = data.pendaftar;
            const agenda = data.agenda;
            const pelatihan = data.pelatihan;

            document.getElementById('id_agenda').value = agenda.id_agenda;
            document.getElementById('eventName').textContent = pelatihan.nama_pelatihan;

            const materi = (agenda.materi || pelatihan.materi).replace(/[\[\]]/g, '').split(',').map(item => `<li>${item.trim()}</li>`).join('');
            const benefit = (agenda.benefit || pelatihan.benefit).replace(/[\[\]]/g, '').split(',').map(item => `<li>${item.trim()}</li>`).join('');

            document.getElementById('eventMateri').innerHTML = materi;
            document.getElementById('eventBenefit').innerHTML = benefit;

            document.getElementById('eventStartDate').textContent = agenda.start_date;
            document.getElementById('eventEndDate').textContent = agenda.end_date;
            document.getElementById('eventSession').textContent = agenda.sesi;
            document.getElementById('eventPrice').textContent = `Rp${agenda.investasi}`;
            document.getElementById('eventDiscountedPrice').textContent = `Rp${agenda.diskon}`;

            document.getElementById('nama').value = pendaftar.nama || '';
            document.getElementById('email').value = pendaftar.email || '';
            document.getElementById('no_kontak').value = pendaftar.no_kontak.replace('+62', '') || '';
            document.getElementById('aktivitas').value = pendaftar.aktivitas || '';
            document.getElementById('nama_instansi').value = pendaftar.nama_instansi || '';

            selectedProvinsi = pendaftar.provinsi;
            selectedKabupaten = pendaftar.kab_kota;

            toggleNamaInstansi(pendaftar.aktivitas);

            // Panggil fungsi untuk memuat data provinsi dan kabupaten
            loadProvinsi(selectedProvinsi, selectedKabupaten);
        })
        .catch(function(error) {
            Swal.fire({
                title: 'Error!',
                text: 'Gagal memuat data event atau profil.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });

        function loadProvinsi(selectedProvinsi, selectedKabupaten) {
            fetch('https://ibnux.github.io/data-indonesia/provinsi.json')
            .then(response => response.json())
            .then(data => {
                data.forEach(provinsi => {
                    const isSelected = (provinsi.nama === selectedProvinsi) ? 'selected' : '';
                    $('#provinsi').append(`<option value="${provinsi.id}" ${isSelected}>${provinsi.nama}</option>`);
                });

                if (selectedProvinsi) {
                    $('#provinsi').val(data.find(prov => prov.nama === selectedProvinsi).id).trigger('change');
                }
            });
        }

        $('#provinsi').change(function() {
            const provinsiId = $(this).val();
            $('#kab_kota').empty().append('<option value="" disabled selected>Pilih Kab/Kota</option>');

            if (provinsiId) {
                fetch(`https://ibnux.github.io/data-indonesia/kabupaten/${provinsiId}.json`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(kabupaten => {
                        const isSelected = (kabupaten.nama === selectedKabupaten) ? 'selected' : '';
                        $('#kab_kota').append(`<option value="${kabupaten.id}" ${isSelected}>${kabupaten.nama}</option>`);
                    });

                    if (selectedKabupaten) {
                        $('#kab_kota').val(data.find(kab => kab.nama === selectedKabupaten).id).trigger('change');
                    }
                });
            }
        });

        document.getElementById('aktivitas').addEventListener('change', function () {
            toggleNamaInstansi(this.value);
        });

        function toggleNamaInstansi(aktivitas) {
            const namaInstansiContainer = document.getElementById('namaInstansiContainer');
            if (['Pelajar', 'Mahasiswa', 'Dosen', 'Karyawan'].includes(aktivitas)) {
                namaInstansiContainer.style.display = 'block';
            } else {
                namaInstansiContainer.style.display = 'none';
                document.getElementById('nama_instansi').value = '';
            }
        }

        document.getElementById('registrationForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = {
                id_agenda: document.getElementById('id_agenda').value,
                nama: document.getElementById('nama').value,
                email: document.getElementById('email').value,
                no_kontak: document.getElementById('no_kontak').value,
                aktivitas: document.getElementById('aktivitas').value,
                nama_instansi: document.getElementById('nama_instansi').value,
                provinsi: $('#provinsi option:selected').text(),
                kab_kota: $('#kab_kota option:selected').text(),
                status_pembayaran: 'Belum Bayar'
            };

            axios.post('/api/pendaftaran-event/daftar', formData, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(function(response) {
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Pendaftaran berhasil dilakukan.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        window.location.href = '/daftar-event';
                    }
                });
            })
            .catch(function(error) {
                if (error.response && error.response.data && error.response.data.errors) {
                    displayErrors(error.response.data.errors);
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat pendaftaran.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        function displayErrors(errors) {
            for (let key in errors) {
                if (errors.hasOwnProperty(key)) {
                    const errorElement = document.getElementById(`error-${key}`);
                    const inputElement = document.getElementById(key);
                    if (errorElement && inputElement) {
                        errorElement.textContent = errors[key][0];
                        inputElement.classList.add('is-invalid');
                    }
                }
            }
        }
    });
</script>
@endsection
