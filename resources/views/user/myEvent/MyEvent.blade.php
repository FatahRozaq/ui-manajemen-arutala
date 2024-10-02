@extends('layouts.PesertaLayouts')

@section('style')
<link href="{{ asset('assets/css/myEvent.css') }}" rel="stylesheet">
@endsection

@section('title')
Arutala | My Event
@endsection

@section('content')

<h4 class="title">My Event</h4>

<div class="event-list" id="event-list">
    <!-- Data event akan dimuat di sini oleh JavaScript -->
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function() {
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

                // Buat tombol status pembayaran
                let paymentButton = '';
                if (event.status_pembayaran === 'Unpaid' || event.status_pembayaran === 'Process') {
                    // Jika unpaid atau process, buat tombol berwarna merah dengan link
                    paymentButton = `
                        <button 
                            class="btn btn-bayar btn-danger" 
                            onclick="window.open('${event.link_mayar}', '_blank')">
                            ${event.status_pembayaran === 'Unpaid' ? 'Bayar' : 'Proses'}
                        </button>`;
                } else if (event.status_pembayaran === 'Paid') {
                    // Jika paid, buat tombol hijau tanpa aksi
                    paymentButton = `
                        <button class="btn btn-bayar btn-success" disabled>
                            Paid
                        </button>`;
                } else {
                    // Jika tidak ada status pembayaran yang valid
                    paymentButton = `<span>Status Pembayaran Tidak Diketahui</span>`;
                }

                eventCard.innerHTML = `
                    <div class="event-info">
                        <img 
                            src="${event.gambar_pelatihan ? event.gambar_pelatihan : '/assets/images/default-pelatihan.jpg'}"
                            alt="${event.nama_pelatihan}" 
                            class="event-image"
                            onerror="this.onerror=null; this.src='/assets/images/default-pelatihan.jpg';"
                        >
                        <div class="event-more-info">
                        <h4>${event.nama_pelatihan}</h4>
                        <h5>  Batch ${event.batch} </h5>
                        <p><i class="fas fa-calendar-alt"></i> ${new Date(event.start_date).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })} - ${new Date(event.end_date).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</p>

                    
                    <div class="event-status-payment" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="event-status ${event.status_pelatihan ? event.status_pelatihan.toLowerCase().replace(/ /g, '-') : ''}">
                            ${event.status_pelatihan ? event.status_pelatihan : 'Status not available'}
                        </div>
                        <div class="event-payment">
                            <strong></strong> ${paymentButton}
                        </div>
                    </div>
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
