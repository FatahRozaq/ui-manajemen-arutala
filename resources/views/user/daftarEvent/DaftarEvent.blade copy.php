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

    <div class="cards">
        @foreach($events as $event)
        <div class="card">
            <div class="image-title">
                <img src="{{ asset('assets/images/' . $event['gambar_pelatihan']) }}" alt="{{ $event['nama_pelatihan'] }}" class="event-image">
                <h3 class="nama-pelatihan">{{ $event['nama_pelatihan'] }}</h3>
            </div>
            <div class="harga-date">
                @php
                $investasi = is_array($event['investasi']) ? $event['investasi'][0] : $event['investasi'];
                @endphp
                <p class="price">
                    Rp{{ number_format($investasi - ($investasi * $event['diskon'] / 100), 0, ',', '.') }}
                    <span class="original-price">Rp{{ number_format($investasi, 0, ',', '.') }}</span>
                </p>
                <p class="date"><i class="bi bi-clock" style="margin-right: 5px"></i>{{ $event['start_date'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection