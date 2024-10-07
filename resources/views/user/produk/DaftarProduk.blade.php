@extends('layouts.PesertaLayouts')

@section('style')
<link href="{{ asset('assets/css/daftarEvent.css') }}" rel="stylesheet">

<style>
    /* .containerEvent {
        display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
    } */

    .produk-list {
        display: flex;
  flex-wrap: wrap;
  gap: 20px;
  /* justify-content: center; */
    }

    .card {
        display: flex;
        flex-direction: column;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 290px;
        padding: 3px;
        border: solid 1px rgb(215, 215, 215);
        cursor: pointer;
        transition: all 0.3s ease; /* Menambahkan transisi halus */
    }

    .card img {
        width: 100%;
        height: 400px;
        border-radius: 10px;
        object-fit: cover;
        border-bottom: solid 1px rgb(215, 215, 215);
    }

    .card-body {
        padding: 15px;
        text-align: center;
    }

    .card-body h5 {
        margin: 10px 0;
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }

    .card-body p {
        font-size: 14px;
        color: #777;
    }

    .card:hover {
        transform: translateY(-5px); /* Efek hover agar card naik sedikit */
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15); /* Meningkatkan shadow saat hover */
    }

    .loading {
        font-size: 18px;
        text-align: center;
        margin-top: 20px;
    }

    /* Style untuk modal */

    .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.8) !important; /* Darker transparent background */
}

/* Custom style for the Bootstrap modal close button */
.modal .btn-close {
    background-color: transparent; /* Remove the default background */
    border: none; /* Remove border */
    color: white; /* Change icon color to white */
    font-size: 2rem; /* Increase the size of the icon */
    font-weight: bold; /* Make the icon thicker */
    opacity: 1; /* Ensure the icon is fully opaque */
}

.modal .btn-close:hover,
.modal .btn-close:focus {
    color: #ddd; /* Slightly lighter color on hover/focus for visual feedback */
    text-decoration: none;
    outline: none;
    box-shadow: none;
}




</style>
@endsection

@section('content')

<!-- Bootstrap Modal -->
<div id="imageModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content" style="background-color: transparent; border: none;">
            <div class="card-modal position-relative">
                <button type="button" class="close btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>

                <img class="modal-img w-90" id="modalImage" alt="Modal Image" style="max-width: 90%; max-height: auto;">
            </div>
        </div>
    </div>
</div>

<div class="containerEvent">
    <h4 class="title">Daftar Produk</h4>

    <div id="produk-list" class="produk-list"></div> <!-- Tempat untuk menampilkan produk -->

    <div id="loading" class="loading"></div> <!-- Loading indikator -->
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Panggil API menggunakan Axios untuk mendapatkan data produk
    axios.get('/api/produk')
        .then(function(response) {
            // Hapus loading indicator
            document.getElementById('loading').style.display = 'none';

            // Ambil data dari response
            var produkList = response.data.data;
            var produkContainer = document.getElementById('produk-list');

            // Jika tidak ada produk, tampilkan pesan
            if (produkList.length === 0) {
                produkContainer.innerHTML = '<p>Tidak ada produk yang tersedia.</p>';
                return;
            }

            // Loop melalui produk dan buat card untuk setiap produk
            produkList.forEach(function(produk) {
                var card = `
                    <div class="card" onclick="showModal('${produk.poster_agenda}')">
                        <img
                            src="${produk.poster_agenda ? produk.poster_agenda : '/assets/images/default-pelatihan.jpg'}"
                            alt="${produk.nama_pelatihan}"
                            class="event-image"
                            onerror="this.onerror=null; this.src='/assets/images/default-pelatihan.jpg';"
                        >
                    </div>
                `;
                produkContainer.innerHTML += card;
            });
        })
        .catch(function(error) {
            // Hapus loading indicator
            document.getElementById('loading').style.display = 'none';

            // Tampilkan error di konsol atau tampilkan pesan error ke pengguna
            console.error('Error fetching produk:', error);
            document.getElementById('produk-list').innerHTML = '<p>Gagal memuat produk.</p>';
        });
});

// Fungsi untuk menampilkan modal dengan gambar yang lebih besar
// Fungsi untuk menampilkan modal dengan gambar yang lebih besar
// Function to show the modal with the image
function showModal(imageSrc) {
    var modalImg = document.getElementById("modalImage");
    modalImg.src = imageSrc;

    // Show the Bootstrap modal
    var bootstrapModal = new bootstrap.Modal(document.getElementById('imageModal'));
    bootstrapModal.show();

    // Handle image error fallback
    modalImg.onerror = function () {
        modalImg.src = '/assets/images/default-pelatihan.jpg';
    };
}



</script>
@endsection
