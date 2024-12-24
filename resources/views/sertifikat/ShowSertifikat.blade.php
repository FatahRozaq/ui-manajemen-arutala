<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Digital</title>

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/ArutalaHitam.png') }}">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/sertifikat.css') }}" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <style>
        .file-sertifikat-container {
            position: relative;
            display: inline-block;
            
        }

        .file-sertifikat {
            cursor: pointer;
        }

        .hover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .file-sertifikat:hover .hover-overlay {
            opacity: 1;
        }

        #content-section {
            display: none; /* Awalnya disembunyikan */
        }
    </style>
</head>
<body>
    <div id="content-section">
    <!-- Header -->
    <header id="header" class="header fixed-top d-flex align-items-center justify-content-center" style="height: 70px;">        
        <div class="logo">
            <img src="{{ asset('assets/img/logo/ArutalaHitam.png') }}" alt="Logo">
        </div>
        <div class="title-nav">
            <h2>Sertifikat Digital Arutala Mitra Mandiri</h2>
        </div>
    </header>

    <main id="main" class="main">
        <div class="row-1">
            <div class="file-sertifikat-container">
                <div class="file-sertifikat">
                    <canvas id="pdfCanvas" class="previewCanvas"></canvas>
                    <div class="hover-overlay">
                        <span>Klik untuk memperbesar</span>
                    </div>
                </div>
            </div>
            <div class="card-pendaftar">
                <i class="fa-solid fa-circle-user"></i>
                <span class="nama" name="nama_peserta" id="namaPeserta"></span>
                <div class="id-sertifikat">
                    <label for="id_sertifikat" class="label-card-pendaftar" name="id_sertifikat">ID Sertifikat</label>
                    <span class="content-card-pendaftar" id="idSertifikat">N/A</span>
                </div>
                <div class="tanggal-sertifikat">
                    <label for="tanggal_diberikan" class="label-card-pendaftar" name="tanggal_diberikan">Tanggal Diberikan</label>
                    <span id="tanggalDiberikan" class="content-card-pendaftar">N/A</span>
                </div>
            </div>
        </div>

        <div class="row-2">
            <div class="content-pelatihan">
                <label class="title-content" name="nama_pelatihan" id="namaPelatihan">Pelatihan Arutala</label>
                <div class="deskripsi-content" id="deskripsiPelatihan">Deskripsi pelatihan</div>
                <div class="materi">
                    <label class="label-content" name="materi_pelatihan">Materi</label>
                    <ul id="materi_list"></ul>
                </div>
                <div class="durasi">
                    <label class="label-content" name="durasi_pelatihan">Durasi</label>
                    <ul id="durasi_list"></ul>
                </div>
                <div class="evaluasi">
                    <label class="label-content" name="evaluasi_pelatihan">Evaluasi</label>
                    <div id="evaluasi" class="deskripsi-content"></div>
                </div>
            </div>
        </div>
    </main>
    </div>

    <!-- Vendor JS Files -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const certificateNumber = window.location.pathname.split('/').pop();
        const apiUrl = `/api/sertifikat/detail-sertifikat/${certificateNumber}`;
        const contentSection = document.getElementById('content-section');
        axios.get(apiUrl)
            .then(function (response) {
                const data = response.data.data;
                let fileUrl = '';

                // Tentukan file URL berdasarkan logika yang diberikan
                if (data.sertifikat.certificate_number_kompetensi === certificateNumber) {
                    fileUrl = data.sertifikat.file_sertifikat;
                } else {
                    fileUrl = data.sertifikat.sertifikat_kehadiran;
                }

                const canvas = document.getElementById('pdfCanvas');
                const context = canvas.getContext('2d');
                const fileExtension = fileUrl.split('.').pop().toLowerCase(); // Ekstensi file

                if (fileExtension === 'pdf') {
                    // Tampilkan PDF menggunakan pdf.js
                    const pdfjsLib = window['pdfjs-dist/build/pdf'];
                    pdfjsLib.getDocument(fileUrl).promise.then(function (pdfDoc) {
                        pdfDoc.getPage(1).then(function (page) {
                            const viewport = page.getViewport({ scale: 1 });

                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            const renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            };

                            page.render(renderContext);
                        });
                    });
                } else if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                    // Tampilkan gambar langsung pada canvas
                    const img = new Image();
                    img.onload = function () {
                        canvas.height = img.height;
                        canvas.width = img.width;
                        context.drawImage(img, 0, 0);
                    };
                    img.src = fileUrl;
                }

                // Tambahkan event click untuk menampilkan modal preview
                document.querySelector('.file-sertifikat').addEventListener('click', function () {
                    Swal.fire({
                        title: 'Preview Sertifikat',
                        html: `
                            <div style="width: 100%; height: 500px; text-align: center;">
                                ${fileExtension === 'pdf' ? `
                                    <iframe src="${fileUrl}" style="width: 100%; height: 100%;" frameborder="0"></iframe>
                                ` : `
                                    <img src="${fileUrl}" alt="Preview Sertifikat" style="max-width: 100%; max-height: 100%;">
                                `}
                                <p style="text-align: center; margin-top: 10px;">
                                    <a href="${fileUrl}" target="_blank" style="color: #007bff; text-decoration: none;">Buka di tab baru jika tidak bisa ditampilkan</a>
                                </p>
                            </div>
                        `,
                        showCloseButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Download',
                        cancelButtonText: 'Tutup',
                        customClass: {
                            popup: 'swal2-large-popup'
                        },
                        didOpen: () => {
                            document.querySelector('.swal2-large-popup').style.width = '90%';
                        }
                    }).then(result => {
                        if (result.isConfirmed) {
                            // Unduh file menggunakan Blob
                            fetch(fileUrl)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('File tidak dapat diunduh.');
                                    }
                                    return response.blob();
                                })
                                .then(blob => {
                                    const link = document.createElement('a');
                                    const fileName = fileUrl.split('/').pop();

                                    // Buat URL untuk Blob
                                    const url = URL.createObjectURL(blob);
                                    link.href = url;
                                    link.download = fileName;

                                    // Klik otomatis untuk mengunduh
                                    document.body.appendChild(link);
                                    link.click();

                                    // Hapus URL Blob setelah unduhan selesai
                                    URL.revokeObjectURL(url);
                                    document.body.removeChild(link);
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire('Error', 'Gagal mengunduh file.', 'error');
                                });
                        }
                    });
                });

                document.getElementById('namaPeserta').textContent = data.pendaftar.nama || 'Nama Peserta';
                document.getElementById('idSertifikat').textContent = certificateNumber;

                const createdTime = data.sertifikat.created_time;
                const modifiedTime = data.sertifikat.modified_time;

                document.getElementById('tanggalDiberikan').textContent = new Date(
                    createdTime || modifiedTime
                ).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });

                const namaPelatihanElement = document.getElementById('namaPelatihan');
                namaPelatihanElement.textContent = data.pelatihan?.nama_pelatihan || 'Pelatihan Arutala';

                const deskripsiPelatihanElement = document.getElementById('deskripsiPelatihan');
                deskripsiPelatihanElement.textContent = data.agendaPelatihan?.deskripsi || data.pelatihan?.deskripsi || 'Deskripsi Pelatihan';

                const materiList = document.getElementById('materi_list');
                materiList.innerHTML = '';

                let materiArray = [];
                try {
                    if (data.agendaPelatihan?.materi) {
                        materiArray = JSON.parse(data.agendaPelatihan.materi);
                    } else if (data.pelatihan?.materi) {
                        materiArray = JSON.parse(data.pelatihan.materi);
                    }
                } catch (error) {
                    console.error("Error parsing 'materi' JSON:", error);
                }

                if (materiArray.length > 0) {
                    materiArray.forEach(materi => {
                        if (materi) {
                            const li = document.createElement('li');
                            li.textContent = materi;
                            materiList.appendChild(li);
                        }
                    });
                } else {
                    materiList.style.display = 'none';
                }

                const durasiList = document.getElementById('durasi_list');
                durasiList.innerHTML = '';

                let durasiArray = [];
                try {
                    durasiArray = JSON.parse(data.agendaPelatihan.durasi || '[]');
                } catch (error) {
                    console.error("Error parsing 'durasi' JSON:", error);
                }

                if (durasiArray.length > 0) {
                    durasiArray.forEach(durasi => {
                        if (durasi) {
                            const li = document.createElement('li');
                            li.textContent = durasi;
                            durasiList.appendChild(li);
                        }
                    });
                } else {
                    durasiList.style.display = 'none';
                }

                const evaluasiElement = document.getElementById('evaluasi');
                evaluasiElement.textContent = data.agendaPelatihan.evaluasi || '';
                if (!data.agendaPelatihan.evaluasi) {
                    evaluasiElement.style.display = 'none';
                }
                contentSection.style.display = 'block';
            })
            .catch(function (error) {
                // console.error('Error fetching certificate data:', error);
                Swal.fire({
                    title: 'Sertifikat sedang dalam proses pembuatan!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            });
    });
</script>

</body>
</html>
