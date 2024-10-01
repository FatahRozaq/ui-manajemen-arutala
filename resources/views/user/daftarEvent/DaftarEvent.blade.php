@extends('layouts.PesertaLayouts')

@section('style')
<link href="{{ asset('assets/css/daftarEvent.css') }}" rel="stylesheet">
@endsection

@section('title')
Arutala | Daftar Event
@endsection

@section('content')
<div class="containerEvent">
    <h4 class="title">Daftar Event</h4>
    
    @if(session('error'))
        <p class="alert alert-danger">{{ session('error') }}</p>
    @endif

    <div id="event-cards" class="cards">
        <!-- Event cards will be populated here by JavaScript -->
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ambil token dari localStorage, pastikan konsisten dengan nama token yang digunakan
    const token = localStorage.getItem('auth_token');  // Menggunakan 'auth_token' sesuai contoh kedua

    // Pastikan token tidak null atau undefined
    if (token) {
        // Kirim permintaan dengan token di header Authorization
        axios.get('api/laman-peserta/daftar-event', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(function(response) {
            const events = response.data.data;
            const eventCards = document.getElementById('event-cards');

            events.forEach(event => {
                const card = document.createElement('div');
                card.classList.add('card');
                card.style.cursor = 'pointer';

                const investasi = Array.isArray(event.investasi) ? event.investasi[0] : event.investasi;
                let priceHtml = investasi.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).replace(/\s+/g, '');

                if (event.diskon && event.diskon > 0) {
                    const priceAfterDiscount = investasi - (investasi * event.diskon / 100);
                    priceHtml = `
                        ${priceAfterDiscount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).replace(/\s+/g, '')}
                        <span class="original-price">${investasi.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).replace(/\s+/g, '')}</span>
                    `;
                }

                const eventDate = new Date(event.start_date);
                const formattedDate = eventDate.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });

                const isDisabled = event.is_registered ? 'disabled' : '';

                card.innerHTML = `
                    <div class="image-title">
                        <img 
                            src="${event.gambar_pelatihan ? event.gambar_pelatihan : '/assets/images/default-pelatihan.jpg'}"
                            alt="${event.nama_pelatihan}" 
                            class="event-image"
                            onerror="this.onerror=null; this.src='/assets/images/default-pelatihan.jpg';"
                        >
                        <h4 class="nama-pelatihan">${event.nama_pelatihan}</h4>
                    </div>
                    <div class="harga-date">
                        <p class="price">
                            ${priceHtml}
                        </p>
                        <p class="date">Mulai<i class="bi bi-clock" style="margin-right: 5px; margin-left:5px"></i>${formattedDate}</p>
                    </div>
                    <div class="tombol-detail-daftar">
                        <button class="tombol-detail" onclick="window.location.href = '/event/${event.id_agenda}'">Detail</button>
                        <button class="tombol-daftar" ${isDisabled} onclick="daftar(${event.id_agenda})">Daftar</button>
                    </div>
                `;

                eventCards.appendChild(card);
            });
        })
        .catch(function(error) {
            console.error('Error fetching event data:', error);
        });
    } else {
        console.error('Token JWT tidak ditemukan. Pengguna harus login terlebih dahulu.');
    }
});

// Fungsi daftar dengan parameter idAgenda
function daftar(idAgenda) {
    if (idAgenda) {
        window.location.href = `/peserta/pendaftaran?idAgenda=${idAgenda}`;
    } else {
        alert('ID Agenda tidak ditemukan. Silakan coba lagi.');
    }
}
</script>


@endsection
