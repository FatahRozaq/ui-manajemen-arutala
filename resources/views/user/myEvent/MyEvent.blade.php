@extends('layouts.PesertaLayouts')

@section('style')
<link href="{{ asset('assets/css/myEvent.css') }}" rel="stylesheet">
@endsection

@section('content')

<h3 class="title">My Event</h3>

<div class="event-list" id="event-list">
    <!-- Data event akan dimuat di sini oleh JavaScript -->
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gantilah '1' dengan ID peserta yang sebenarnya
        // const idPeserta = ; // Sesuaikan dengan ID peserta yang ingin Anda gunakan

        // Lakukan fetch data event menggunakan Axios
        axios.get(`/api/my-events`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            }
        }).then(function(response) {
                const events = response.data.data;
                const eventList = document.getElementById('event-list');

                // Bersihkan konten event-list sebelum menambahkan data baru
                eventList.innerHTML = '';

                // Perulangan untuk menambahkan data event ke dalam halaman
                events.forEach(event => {
                    const eventCard = document.createElement('div');
                    eventCard.classList.add('event-card');

                    eventCard.innerHTML = `
                        <div class="event-info">
                            <h4>${event.nama_pelatihan}</h4>
                            <p><i class="fas fa-calendar-alt"></i> ${new Date(event.start_date).toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })} - ${new Date(event.end_date).toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })}</p>
                        </div>
                        <div class="event-status-payment">
                            <div class="event-status ${event.status_pelatihan ? event.status_pelatihan.toLowerCase().replace(/ /g, '-') : ''}">
                                ${event.status_pelatihan ? event.status_pelatihan : 'Status not available'}
                            </div>
                            <div class="event-payment">
                                <strong>Pembayaran :</strong> <span class="${event.status_pembayaran ? event.status_pembayaran.toLowerCase().replace(/ /g, '-') : ''}">${event.status_pembayaran ? event.status_pembayaran : 'Tidak ada status pembayaran'}</span>
                            </div>
                        </div>
                    `;
                    
                    eventList.appendChild(eventCard);
                });
            })
            .catch(function(error) {
                console.error('Error fetching events:', error);
                const eventList = document.getElementById('event-list');
                eventList.innerHTML = '<p class="error">Gagal memuat data event. Silakan coba lagi nanti.</p>';
            });
    });
</script>
@endsection
