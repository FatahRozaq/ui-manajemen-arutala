@extends('layouts.PesertaLayouts')

@section('style')
<link href="{{ asset('assets/css/daftarEvent.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="containerEvent">
    <h2 class="title">Daftar Event</h2>
    
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
        axios.get('api/laman-peserta/daftar-event')
            .then(function(response) {
                const events = response.data.data;
                const eventCards = document.getElementById('event-cards');

                events.forEach(event => {
                    const card = document.createElement('div');
                    card.classList.add('card');
                    card.style.cursor = 'pointer'; // Tambahkan pointer untuk menandakan bisa diklik
                    card.onclick = function() {
                        window.location.href = `/detail-event/${event.id_agenda}`;
                    };

                    const investasi = Array.isArray(event.investasi) ? event.investasi[0] : event.investasi;
                    const priceAfterDiscount = investasi - (investasi * event.diskon / 100);

                    const eventDate = new Date(event.start_date);
                    const formattedDate = eventDate.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });

                    card.innerHTML = `
                        <div class="image-title">
                            <img src="${event.gambar_pelatihan}" alt="${event.nama_pelatihan}" class="event-image">
                            <h3 class="nama-pelatihan">${event.nama_pelatihan}</h3>
                        </div>
                        <div class="harga-date">
                            <p class="price">
                                ${priceAfterDiscount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).replace(/\s+/g, '')}
                                <span class="original-price">${investasi.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).replace(/\s+/g, '')}</span>
                            </p>
                            <p class="date"><i class="bi bi-clock" style="margin-right: 5px"></i>${formattedDate}</p>
                        </div>
                    `;

                    eventCards.appendChild(card);
                });
            })
            .catch(function(error) {
                console.error('Error fetching event data:', error);
            });
    });
</script>
@endsection
