{{-- resources/views/meetings/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2><i class="fas fa-info-circle"></i> Meeting Details</h2>
    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>Title:</strong> {{ $meeting->title }}</li>
        <li class="list-group-item"><strong>Date:</strong> {{ $meeting->meeting_date }}</li>
        <li class="list-group-item"><strong>Time:</strong> {{ $meeting->start_time }} - {{ $meeting->end_time }}</li>
        <li class="list-group-item"><strong>Location:</strong> {{ $meeting->location }}</li>
        <li class="list-group-item"><strong>Agenda:</strong> {{ $meeting->agenda }}</li>
        <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($meeting->status) }}</li>
        <li class="list-group-item">
            <strong>Participants:</strong>
            <ul>
                @foreach($meeting->participants as $p)
                    <li>{{ $p->user->name }} ({{ $p->status }})</li>
                @endforeach
            </ul>
        </li>
    </ul>
    <a href="{{ route('meetings.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>
@endsection
