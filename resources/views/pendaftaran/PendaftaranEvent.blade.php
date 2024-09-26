@extends('layouts.PesertaLayouts')

@section('title')
Arutala | Pendaftaran Event
@endsection

@section('style')
<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<form id="registrationForm">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Pendaftaran Event</h1>

        <button type="submit" class="btn d-flex align-items-center custom-btn" style="background-color: #344C92; color: white;">
            Daftar
        </button>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body" style="padding-top: 30px">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Event</h3>
                        </div>
                        <hr>
                        <div class="text-center mb-3">
                            <!-- Gambar akan diperbarui dengan URL dari MinIO -->
                            <img src="" class="img-fluid" alt="Event Image" id="eventImage" style="width: 500px; height: auto;">
                        </div>
                        <h4 class="mb-2 font-weight-bold" id="eventName"></h4>
                        <h5 class="font-weight-bold mb-1" style="color: #000A65;">Materi :</h5>
                        <ul id="eventMateri"></ul>
                        <h5 class="font-weight-bold mb-1" style="color: #000A65;">Benefit :</h5>
                        <ul id="eventBenefit"></ul>
                        
                        <div class="d-flex flex-column ml-2">
                            <div class="d-flex mb-1 font-weight-bold align-items-center">
                                <i class="fas fa-calendar-alt" style="width:5%"></i>
                                <span class="date-label" style="width:20%" id="startDateLabel">Start Date</span>
                                <span style="width:2%">:</span>
                                <span id="eventStartDate"></span>
                            </div>

                            <div class="d-flex mb-1 font-weight-bold align-items-center">
                                <i class="fas fa-calendar-alt" style="width:5%"></i>
                                <span class="date-label" style="width:20%" id="endDateLabel">End Date</span>
                                <span style="width:2%">:</span>
                                <span id="eventEndDate"></span>
                            </div>

                            <div class="d-flex mb-1 font-weight-bold align-items-center">
                                <i class="fas fa-clock" style="width:5%"></i>
                                <span style="width:20%">Sesi</span>
                                <span style="width:2%">:</span>
                                <span id="eventSession"></span>
                            </div>
                        </div>

                        <div class="d-flex mt-4">
                            <h5 class="text-danger font-weight-bold mb-1 mr-4" style="font-size: 18px;" id="eventPrice"></h5>
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
                            <label for="nama" class="col-sm-4 col-form-label font-weight-bold">Nama Lengkap</label>
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
                                <div class="default-internal">+62</div>
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
            const imageUrl = data.image_url;

            document.getElementById('id_agenda').value = agenda.id_agenda;
            document.getElementById('eventName').textContent = pelatihan.nama_pelatihan;
            
            const eventImage = document.getElementById('eventImage');
            eventImage.src = imageUrl;
            eventImage.onerror = function() {
                this.src = '/assets/images/default-pelatihan.jpg'; 
            };

            const materiArray = JSON.parse(agenda.materi || pelatihan.materi);
            const benefitArray = JSON.parse(agenda.benefit || pelatihan.benefit);

            const materi = materiArray.map(item => `<li>${item.trim()}</li>`).join('');
            const benefit = benefitArray.map(item => `<li>${item.trim()}</li>`).join('');

            document.getElementById('eventMateri').innerHTML = materi;
            document.getElementById('eventBenefit').innerHTML = benefit;

            document.getElementById('eventStartDate').textContent = formatDate(agenda.start_date);
            document.getElementById('eventEndDate').textContent = formatDate(agenda.end_date);
            
            // Mengonversi string JSON ke array
            let sessions;
            try {
                sessions = JSON.parse(agenda.sesi); // Mengonversi string ke array
            } catch (e) {
                console.error('Error parsing sessions:', e);
                sessions = []; // Atur ke array kosong jika parsing gagal
            }

            const eventSession = document.getElementById('eventSession');
            if (sessions.length > 0) {
                // Gabungkan sesi menjadi string dengan pemisah koma
                eventSession.textContent = sessions.join(', ');
            } else {
                eventSession.textContent = "No sessions available";
            }

            document.getElementById('eventPrice').textContent = `Rp${agenda.investasi}`;
            document.getElementById('eventDiscountedPrice').textContent = agenda.diskon ? `Rp${agenda.diskon}` : ' ';

            document.getElementById('nama').value = pendaftar.nama || '';
            document.getElementById('email').value = pendaftar.email || '';
            document.getElementById('no_kontak').value = pendaftar.no_kontak.replace('+62', '') || '';
            document.getElementById('aktivitas').value = pendaftar.aktivitas || '';
            document.getElementById('nama_instansi').value = pendaftar.nama_instansi || '';
            document.getElementById('linkedin').value = pendaftar.linkedin || ''; 

            selectedProvinsi = pendaftar.provinsi;
            selectedKabupaten = pendaftar.kab_kota;

            toggleNamaInstansi(pendaftar.aktivitas);

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

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Pastikan data yang diisi sudah benar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Daftar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
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

        function formatDate(dateString) {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const date = new Date(dateString);
            const dayName = days[date.getDay()];
            const day = date.getDate();
            const month = months[date.getMonth()];
            const year = date.getFullYear();

            return `${dayName}, ${day} ${month} ${year}`;
        }

        function updateLabels() {
            const startDateLabel = document.getElementById('startDateLabel');
            const endDateLabel = document.getElementById('endDateLabel');
            
            if (window.innerWidth < 576) {
                startDateLabel.textContent = 'Start';
                endDateLabel.textContent = 'End';
            } else {
                startDateLabel.textContent = 'Start Date';
                endDateLabel.textContent = 'End Date';
            }
        }

        updateLabels();

        window.addEventListener('resize', updateLabels);

    });
</script>

@endsection
