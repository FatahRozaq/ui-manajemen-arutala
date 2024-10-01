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
        height: auto;
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

</style>
@endsection

@section('content')
<div class="containerEvent">
    <h4 class="title">Daftar Produk</h4>

    <div id="produk-list" class="produk-list"></div> <!-- Tempat untuk menampilkan produk -->

    <div id="loading" class="loading">Loading...</div> <!-- Loading indikator -->
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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
                        <div class="card">
                            <img src="${produk.gambar_pelatihan}" alt="${produk.nama_pelatihan}">
                            
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
</script>
@endsection
