@extends('layouts.PesertaLayouts')

@section('style')
<link href="{{ asset('assets/css/detailEvent.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="event-detail-container">
    <div class="event-detail">
        <div class="event-header">
            <h1 id="event-title" class="event-title"></h1>
        </div>
        
        <div class="event-body">
            <div class="image-detail">
                <img id="event-image" src="" alt="" class="event-image">
            </div>
            <p id="event-description" class="description"></p>
            
            <div class="benefit-materi">
                <div class="section section-benefit">
                    <h5 class="event-color-blue">Benefit :</h5>
                    <ul id="benefit-list"></ul>
                </div>
                
                <div class="section section-materi">
                    <h5 class="event-color-blue">Materi :</h5>
                    <ul id="materi-list"></ul>
                </div>
            </div>
            
            <div class="mentor-investasi">
                <div class="section section-mentor">
                    <h5 class="event-color-blue">Mentor:</h5>
                    <ul id="mentor-list"></ul>
                </div>
                
                <div class="section section-investasi">
                    <h5 class="event-color-blue">Investasi:</h5>
                    <p id="price" class="price"></p>
                    <p id="additional-info" class="additional-info"></p>
                </div>
            </div>
        </div>
        
        <div class="event-footer">
            <button class="register-button" onclick="daftar()">DAFTAR</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil id event dari URL
        const eventId = window.location.pathname.split('/').pop();
        
        // Lakukan fetch data event menggunakan Axios
        axios.get(`/api/laman-peserta/event-detail/${eventId}`)
            .then(function(response) {
                const event = response.data.data;

                // Log data untuk debugging
                console.log('Event data:', event);

                // Update konten halaman dengan data event
                document.getElementById('event-title').textContent = event.namaPelatihan;
                document.getElementById('event-image').src = event.gambar_pelatihan;
                document.getElementById('event-image').alt = event.namaPelatihan;
                document.getElementById('event-description').textContent = event.deskripsi;

                // Update benefit list
                const benefitList = document.getElementById('benefit-list');
                if (event.benefit) {
                    event.benefit.forEach(function(benefit) {
                        const li = document.createElement('li');
                        li.textContent = benefit;
                        benefitList.appendChild(li);
                    });
                }

                // Update materi list
                const materiList = document.getElementById('materi-list');
                if (event.materi) {
                    event.materi.forEach(function(materi) {
                        const li = document.createElement('li');
                        li.textContent = materi;
                        materiList.appendChild(li);
                    });
                }

                // Update mentor list
                // Update mentor list
                const mentorList = document.getElementById('mentor-list');
                if (event.mentor && event.mentor.length > 0) {
                    event.mentor.forEach(function(mentor) {
                        const li = document.createElement('li');
                        li.textContent = `${mentor.nama_mentor} - ${mentor.aktivitas}`;
                        mentorList.appendChild(li);
                    });
                } else {
                    const noMentor = document.createElement('li');
                    noMentor.textContent = 'Tidak ada mentor';
                    mentorList.appendChild(noMentor);
                }


                // Update investasi
                if (event.investasi) {
                    const formattedInvestasi = `Rp${Number(event.investasi).toLocaleString('id-ID')}`;
                    const formattedOriginalPrice = `Rp${Number(event.investasi * (1 + event.discount / 100)).toLocaleString('id-ID')}`;
                    
                    document.getElementById('price').innerHTML = `
                        ${formattedInvestasi}
                        <span class="original-price">${formattedOriginalPrice}</span>
                        <span class="discount">${event.discount}% off</span>
                    `;
                }

                // Update additional investasi info
                if (event.investasi_info) {
                    const additionalInfo = event.investasi_info.map(info => `<p>${info}</p>`).join('');
                    document.getElementById('additional-info').innerHTML = additionalInfo;
                }

            })
            .catch(function(error) {
                console.error('Error fetching event detail:', error);
            });
    });

    function daftar() {
        const eventId = window.location.pathname.split('/').pop();
        if (eventId) {
            window.location.href = `/peserta/pendaftaran?idAgenda=${eventId}`;
        } else {
            alert('ID Agenda tidak ditemukan. Silakan coba lagi.');
        }
    }
</script>
@endsection
