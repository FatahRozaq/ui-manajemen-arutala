@extends('layouts.AdminLayouts')

@section('style')
<link href="{{ asset('assets/css/detailEvent.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="event-detail-container">
    <div class = "event-detail">
    <div class="event-header">
        
        <h1 class="event-title">{{ $event['namaPelatihan'] }}</h1>
    </div>
    
    <div class="event-body">
        <div class="image-detail">
        <img src="{{ asset('assets/images/' . $event['image']) }}" alt="{{ $event['namaPelatihan'] }}" class="event-image">
        </div>
        <p class="description">{{ $event['deskripsi'] }}</p>
        
        <div class="benefit-materi">
        <div class="section section-benefit">
            <h5 class="event-color-blue">Benefit :</h5>
            <ul>
                @foreach($event['benefit'] as $benefit)
                    <li>{{ $benefit }}</li>
                @endforeach
            </ul>
        </div>
        
        <div class="section section-materi">
            <h5 class="event-color-blue">Materi :</h5>
            <ul>
                @foreach($event['materi'] as $materi)
                    <li>{{ $materi }}</li>
                @endforeach
            </ul>
        </div>
        </div>
        
        <div class="mentor-investasi">
        <div class="section section-mentor">
            <h5 class="event-color-blue">Mentor:</h5>
            <ul>
                @foreach($event['mentor'] as $mentor)
                    <li>{{ $mentor }}</li>
                @endforeach
            </ul>
        </div>
        
        <div class="section section-investasi">
            <h5 class="event-color-blue">Investasi:</h5>
            <p class="price">
                Rp{{ number_format($event['investasi'][0], 0, ',', '.') }}
                <span class="original-price">Rp{{ number_format($event['investasi'][0] * (1 + $event['discount'] / 100), 0, ',', '.') }}</span>
                <span class="discount">{{ $event['discount'] }}% off</span>
            </p>
            <p class="additional-info">{{ $event['investasi'][1] }}</p>
        </div>
        </div>
    </div>
    
    <div class="event-footer">
        <button class="register-button">DAFTAR</button>
    </div>
    </div>
</div>
@endsection
