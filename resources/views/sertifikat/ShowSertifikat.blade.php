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
    </style>
</head>
<body>
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
                    <!-- <label class="label-content" name="durasi_pelatihan">Durasi</label> -->
                    <ul id="durasi_list"></ul>
                </div>
                <div class="evaluasi">
                    <!-- <label class="label-content" name="evaluasi_pelatihan">Evaluasi</label> -->
                    <div id="evaluasi" class="deskripsi-content"></div>
                </div>
            </div>
        </div>
    </main>

    <!-- Vendor JS Files -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const certificateNumber = window.location.pathname.split('/').pop();
            const apiUrl = `/api/sertifikat/detail-sertifikat/${certificateNumber}`;
            let fileUrl;
            axios.get(apiUrl)
                .then(function (response) {
                    const data = response.data.data;
                    if(data.sertifikat.certificate_number_kompetensi == certificateNumber)
                    {
                        fileUrl = data.sertifikat.file_sertifikat;
                    } else {
                        fileUrl = data.sertifikat.sertifikat_kehadiran;
                    }
                    

                    const canvas = document.getElementById('pdfCanvas');
                    const pdfjsLib = window['pdfjs-dist/build/pdf'];

                    pdfjsLib.getDocument(fileUrl).promise.then(function (pdfDoc) {
                        pdfDoc.getPage(1).then(function (page) {
                            const viewport = page.getViewport({ scale: 1 });
                            const context = canvas.getContext('2d');

                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            const renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            };

                            page.render(renderContext);
                        });
                    });

                    document.querySelector('.file-sertifikat').addEventListener('click', function () {
                        Swal.fire({
                            title: 'Preview Sertifikat',
                            html: `
                                <div style="width: 100%; height: 500px;">
                                    <iframe src="${fileUrl}" style="width: 100%; height: 100%;" frameborder="0"></iframe>
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
                                popup: 'swal2-large-popup' // Tambahkan kelas kustom
                            },
                            didOpen: () => {
                                // Tambahkan style secara langsung ke popup setelah dibuka
                                document.querySelector('.swal2-large-popup').style.width = '90%'; // Lebar 90%
                            }
                        }).then(result => {
                            if (result.isConfirmed) {
                                const link = document.createElement('a');
                                link.href = fileUrl;
                                link.download = fileUrl.split('/').pop();
                                link.click();
                            }
                        });
                    });


                    document.getElementById('namaPeserta').textContent = data.pendaftar.nama || 'Nama Peserta';
                    if(certificateNumber === data.sertifikat.certificate_number_kompetensi){
                        document.getElementById('idSertifikat').textContent = data.sertifikat.certificate_number_kompetensi || 'N/A';
                    } else if(certificateNumber === data.sertifikat.certificate_number_kehadiran){
                        document.getElementById('idSertifikat').textContent = data.sertifikat.certificate_number_kehadiran || 'N/A';
                    }
                    document.getElementById('tanggalDiberikan').textContent = new Date(data.sertifikat.created_time).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });

                    document.getElementById('namaPelatihan').textContent = data.pelatihan.nama_pelatihan || 'Pelatihan Arutala';
                    document.getElementById('deskripsiPelatihan').textContent = data.pelatihan.deskripsi || 'Pelatihan Arutala';

                    const materiList = document.getElementById('materi_list');
                    materiList.innerHTML = '';

                    // Parse JSON string from 'materi'
                    let materiArray = [];
                    try {
                        materiArray = JSON.parse(data.pelatihan.materi);
                    } catch (error) {
                        console.error("Error parsing 'materi' JSON:", error);
                    }

                    // Tampilkan setiap item materi
                    materiArray.forEach(materi => {
                        const li = document.createElement('li');
                        li.textContent = materi;
                        materiList.appendChild(li);
                    });

                    const durasiList = document.getElementById('durasi_list');
                    durasiList.innerHTML = '';
                    (data.durasi || []).forEach(durasi => {
                        const li = document.createElement('li');
                        li.textContent = durasi;
                        durasiList.appendChild(li);
                    });

                    document.getElementById('evaluasi').textContent = data.evaluasi || '';
                })
                .catch(function (error) {
                    console.error('Error fetching certificate data:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal memuat data sertifikat.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        });
    </script>
</body>
</html>
