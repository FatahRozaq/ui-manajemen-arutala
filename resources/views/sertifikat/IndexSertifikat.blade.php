@extends('layouts.PesertaLayouts')

@section('title')
Arutala | Sertifikat Peserta
@endsection

@section('content')

<div class="pagetitle">
    <h1>My Certificate</h1>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div id="certificate-list"></div>
        </div>
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
            <div class="modal-body">
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div> -->
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
                        certificateList.append('<p>No certificates available.</p>');
                    } else {
                        certificates.forEach(certificate => {
                            const card = `
                                <div class="card mb-3">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-0">${certificate.pendaftaran.agenda_pelatihan.pelatihan.nama_pelatihan}</h5>
                                            <small class="text-muted">Batch ${certificate.pendaftaran.agenda_pelatihan.batch}</small>
                                        </div>
                                        <div class="d-flex flex-column flex-sm-row mt-3 mt-sm-0 gap-2">
                                            <a href="/api/sertifikat/download?id_pendaftaran=${certificate.id_pendaftaran}" class="btn btn-success mb-2 mb-sm-0 d-flex align-items-center">
                                                <i class="fas fa-download"></i> <span class="ms-2">Download</span>
                                            </a>
                                            <a href="#" class="btn btn-primary mt-2 mt-sm-0 d-flex align-items-center view-cert-btn" data-idpendaftaran="${certificate.id_pendaftaran}">
                                                <i class="fas fa-eye"></i> <span class="ms-2">Lihat</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>`;
                            certificateList.append(card);
                        });

                        // Attach event listener to "Lihat" buttons
                        $('.view-cert-btn').on('click', function(e) {
                            e.preventDefault();
                            const idPendaftaran = $(this).data('idpendaftaran');
                            viewCertificate(idPendaftaran);
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

        function viewCertificate(idPendaftaran) {
            axios.get(`/api/sertifikat/view/${idPendaftaran}`)
                .then(response => {
                    const fileUrl = response.data.data.file_url;
                    const namaPeserta = response.data.nama_peserta;

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
                        content = `<iframe src="${fileUrl}" class="w-100" style="height: 500px;"></iframe>`;
                    } else {
                        content = `<img src="${fileUrl}" class="img-fluid" alt="Sertifikat Peserta">`;
                    }

                    $('#previewModal .modal-body').html(`
                        ${content}
                    `);
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
    });
</script>
@endsection
