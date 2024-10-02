@extends('layouts.PesertaLayouts')

@section('style')
<link href="{{ asset('assets/css/detailEvent.css') }}" rel="stylesheet">
@endsection

@section('title')
Arutala | Detail Event
@endsection

@section('content')
<style>
    .breadcrumb {
      background-color: transparent;
      padding-left: 0;
      padding-bottom: 0;
    }

    .breadcrumb-item {
        font-size: 12px;
    }
  </style>
  
  <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/daftar-event">Daftar Event</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Event</li>
      </ol>
  </nav>
<div class="event-detail-container row">
    <div class="event-detail col-lg-8 row">
        <div class="event-header">
            <h1 id="event-title" class="event-title"></h1>
        </div>
        
        <div class="event-body row">
            <div class="col-lg-5">
            <div class="image-detail">
                <img id="event-image" src="" alt="" class="event-image">
            </div>
        </div>

        <div class="col-lg-7">
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
                
                {{-- <div class="section section-investasi">
                    <h5 class="event-color-blue">Investasi:</h5>
                    <p id="price" class="price"></p>
                    <p id="additional-info" class="additional-info"></p>
                </div> --}}
            </div>
            </div>
        </div>
        
        {{-- <div class="event-footer">
            <button class="register-button" onclick="daftar()">Daftar</button>
        </div> --}}
    </div>
    <div class="col-lg-3 card">
        <div class="image-title">
            {{-- <img 
                src="${event.gambar_pelatihan ? event.gambar_pelatihan : '/assets/images/default-pelatihan.jpg'}"
                alt="${event.nama_pelatihan}" 
                class="event-image"
                onerror="this.onerror=null; this.src='/assets/images/default-pelatihan.jpg';"
            > --}}
            
        </div>
        <div class="card-info">
        <h4 class="nama-pelatihan" id="event-title-2"></h4>
        <div class="harga-date">
            <p class="price">
                <p id="price" class="price"></p>
                <p id="additional-info" class="additional-info"></p>
            </p>
            <p id="event-date"></p> <!-- Tambahkan ini untuk menampilkan tanggal -->
        </div>
        
        <div class="section section-benefit">
            <h5 class="event-color-blue">Benefit :</h5>
            <ul id="benefit-list-2"></ul>
        </div>
        <div class="event-footer">
            <button class="register-button" onclick="daftar()">Daftar</button>
        </div>
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
    axios.get(`/api/laman-peserta/event-detail/${eventId}`, {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('auth_token')}` // Tambahkan token JWT
        }
    })
    .then(function(response) {
        const event = response.data.data;

        // Update konten halaman dengan data event
        document.getElementById('event-title').textContent = event.namaPelatihan;
        document.getElementById('event-title-2').textContent = event.namaPelatihan;
        const eventDate = document.getElementById('event-date');
        const startDate = new Date(event.start_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const endDate = new Date(event.end_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        eventDate.textContent = `Mulai ${startDate} - ${endDate}`;

        const eventImage = document.getElementById('event-image');
        eventImage.src = event.image ? event.image : '/assets/images/default-pelatihan-gambar.jpg';
        eventImage.alt = event.namaPelatihan;
        eventImage.onerror = function() {
            this.onerror = null;
            this.src = '/assets/images/default-pelatihan-gambar.jpg';
        };

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

        const benefitList2 = document.getElementById('benefit-list-2');
        if (event.benefit) {
            event.benefit.forEach(function(benefit) {
                const li = document.createElement('li');
                li.textContent = benefit;
                benefitList2.appendChild(li);
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
            let priceHtml = `Rp${Number(event.investasi).toLocaleString('id-ID')}`;

            if (event.discount) {
                const discountedInvestasi = event.investasi * (1 - event.discount / 100);
                const formattedDiscountedInvestasi = `Rp${Number(discountedInvestasi).toLocaleString('id-ID')}`;
                const formattedOriginalPrice = `Rp${Number(event.investasi).toLocaleString('id-ID')}`;
                
                priceHtml = `
                    ${formattedDiscountedInvestasi}
                    <span class="original-price">${formattedOriginalPrice}</span>
                    <span class="discount">${event.discount}% off</span>
                `;
            }

            document.getElementById('price').innerHTML = priceHtml;
        }

        const additionalInfoList = document.getElementById('additional-info');
        if (event.investasi_info && event.investasi_info.length > 0) {
            event.investasi_info.forEach(function(info) {
                const li = document.createElement('li');
                li.textContent = info;
                additionalInfoList.appendChild(li);
            });
        } else {
            // Jika tidak ada informasi tambahan, hapus elemen dari DOM
            if (additionalInfoList) {
                additionalInfoList.remove();
            }
        }

        // Atur tombol daftar
        const registerButton = document.querySelector('.register-button');
        if (event.is_registered) {
            registerButton.disabled = true;
            registerButton.classList.add('disabled'); // Tambahkan kelas 'disabled' agar tombol berubah warna
            registerButton.textContent = 'Daftar';
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
