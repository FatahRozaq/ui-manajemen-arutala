@extends('layouts.PesertaLayouts')

@section('title')
Arutala | Sertifikat Peserta
@endsection

@section('style')
<style>
    .card.fixed-size {
        height: 350px; /* Fixed height to ensure uniformity */
        max-width: 100%;
    }

    .card .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .card-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 0.3s ease;
        margin-bottom: -10px; /* Adjusted margin to reduce space between title and batch */
    }

    /* On hover, show full name */
    .card:hover .card-title {
        white-space: normal;
        height: auto;
        overflow: visible;
    }

    /* Ensure buttons are the same size */
    .btn-custom {
        width: 48%;
        display: flex;
        justify-content: center;
        gap: 5px;
        align-items: center;
    }

    /* Buttons side-by-side unless on small screen */
    .button-group {
        display: flex;
        gap: 4%;
        margin-top: 10px;
    }

    @media (max-width: 576px) {
        .button-group {
            flex-direction: column;
            gap: 10px;
        }
        .btn-custom {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')

<div class="pagetitle">
    <h1>My Certificate</h1>
</div>

<section class="section">
    <div class="row" id="certificate-list">
        <!-- Certificate cards will be appended here -->
    </div>
</section>

<!-- Modal for certificate preview -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview Sertifikat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center" style="min-height: 80vh;">
                <!-- Preview content will be injected here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        fetchCertificates();

        function fetchCertificates() {
            axios.get('/api/sertifikat/peserta')
                .then(response => {
                    const certificates = response.data.data;
                    const certificateList = $('#certificate-list');

                    if (certificates.length === 0) {
                        certificateList.append('<p>Anda belum memiliki sertifikat.</p>');
                    } else {
                        certificates.forEach(certificate => {
                            const card = `
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card h-100 fixed-size">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                <h6 class="card-title">${certificate.pendaftaran.agenda_pelatihan.pelatihan.nama_pelatihan}</h6>
                                                <small class="text-muted">Batch ${certificate.pendaftaran.agenda_pelatihan.batch}</small>
                                                <p class="text-muted"><i class="fas fa-calendar-alt"></i> ${new Date(certificate.pendaftaran.agenda_pelatihan.start_date).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })} - ${new Date(certificate.pendaftaran.agenda_pelatihan.end_date).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</p>
                                            </div>
                                            <div class="button-group">
                                                <a href="#" class="btn btn-success btn-sm btn-custom download-competency-cert-btn" data-idpendaftaran="${certificate.id_pendaftaran}">
                                                    Kompetensi
                                                </a>
                                                <a href="#" class="btn btn-primary btn-sm btn-custom download-attendance-cert-btn" data-idpendaftaran="${certificate.id_pendaftaran}">
                                                    Kehadiran
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                            certificateList.append(card);
                        });

                        // Attach event listener to "Sertifikat Kompetensi" buttons
                        $('.download-competency-cert-btn').on('click', function(e) {
                            e.preventDefault();
                            const idPendaftaran = $(this).data('idpendaftaran');
                            openCertOptions(idPendaftaran, 'kompetensi');
                        });

                        // Attach event listener to "Sertifikat Kehadiran" buttons
                        $('.download-attendance-cert-btn').on('click', function(e) {
                            e.preventDefault();
                            const idPendaftaran = $(this).data('idpendaftaran');
                            openCertOptions(idPendaftaran, 'kehadiran');
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching certificates:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch certificates.',
                    });
                });
        }

        function openCertOptions(idPendaftaran, type) {
            Swal.fire({
                title: 'Pilih Opsi',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Download',
                denyButtonText: 'Lihat',
            }).then((result) => {
                if (result.isConfirmed) {
                    downloadCertificate(idPendaftaran, type);
                } else if (result.isDenied) {
                    viewCertificate(idPendaftaran, type);
                }
            });
        }

        function viewCertificate(idPendaftaran, type) {
            axios.get(`/api/sertifikat/view-${type}/${idPendaftaran}`)
                .then(response => {
                    const fileUrl = response.data.data.file_url;

                    if (!fileUrl) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Certificate not found.',
                        });
                        return;
                    }

                    let content;
                    if (fileUrl.endsWith('.pdf')) {
                        content = `<iframe src="${fileUrl}" class="preview-content w-100" style="height: 80vh;"></iframe>`;
                    } else {
                        content = `<img src="${fileUrl}" class="preview-content img-fluid" style="max-width: 100%; max-height: 80vh; object-fit: contain;" alt="Sertifikat Peserta">`;
                    }

                    $('#previewModal .modal-body').html(content);
                    $('#previewModal').modal('show');
                })
                .catch(error => {
                    console.error('Error viewing certificate:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load certificate preview.',
                    });
                });
        }

        function downloadCertificate(idPendaftaran, type) {
            axios.get(`/api/sertifikat/download-${type}?id_pendaftaran=${idPendaftaran}`, {
                    responseType: 'blob' // Set response type to blob to handle file download
                })
                .then(response => {
                    // Ambil nama file dari header 'Content-Disposition'
                    const contentDisposition = response.headers['content-disposition'];
                    let fileName = 'sertifikat.pdf'; // Default jika nama file tidak ditemukan

                    // Jika Content-Disposition mengandung 'filename', ambil nama file
                    if (contentDisposition && contentDisposition.indexOf('filename=') !== -1) {
                        const fileNameMatch = contentDisposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                        if (fileNameMatch != null && fileNameMatch[1]) {
                            fileName = fileNameMatch[1].replace(/['"]/g, ''); // Hapus tanda kutip di sekitar nama file
                        }
                    }

                    // Buat blob URL dan unduh file
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', fileName); // Gunakan nama file yang diambil dari header
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Sedang terjadi keselahan. Silakan coba lagi nanti',
                    });
                });
        }
    });
</script>
@endsection
