@extends('layouts.PesertaLayouts')

@section('style')
<link href="{{ asset('assets/css/myEvent.css') }}" rel="stylesheet">
@endsection

@section('content')

<h3 class="title">My Event</h3>

<div class="event-list">
    @foreach($events as $event)
    <div class="event-card">
        <div class="event-info">
            <h4>{{ $event['namaPelatihan'] }}</h4>
            <p><i class="fas fa-calendar-alt"></i> {{ $event['startDate'] }}</p>
        </div>
        @if(isset($event['status']))
        <div class="event-status {{ strtolower(str_replace(' ', '-', $event['status'])) }}">
            {{ $event['status'] }}
        </div>
        @else
        <div class="event-status">
            Status not available
        </div>
        @endif
    </div>
    @endforeach
</div>

@endsection
