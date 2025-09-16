
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
                <small class="text-muted">Welcome, {{ auth()->user()->name }}, {{ auth()->user()->department }}</small>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $totalMeetings }}</h4>
                                    <p class="mb-0">Total Meetings</p>
                                </div>
                                <i class="fas fa-calendar-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $todayMeetings }}</h4>
                                    <p class="mb-0">Today's Meetings</p>
                                </div>
                                <i class="fas fa-calendar-day fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $upcomingMeetings }}</h4>
                                    <p class="mb-0">Upcoming Meetings</p>
                                </div>
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $totalUsers }}</h4>
                                    <p class="mb-0">Total Users</p>
                                </div>
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Meetings -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-history"></i> Recent Meetings</h5>
                        </div>
                        <div class="card-body">
                            @if($recentMeetings->count() > 0)
                                @foreach($recentMeetings as $meeting)
                                    <div class="border-bottom pb-2 mb-2">
                                        <h6>{{ $meeting->title }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> {{ $meeting->meeting_date->format('d M Y') }}
                                            <i class="fas fa-clock ms-2"></i> {{ $meeting->start_time->format('H:i') }}
                                            <span class="badge bg-{{ $meeting->status == 'completed' ? 'success' : 'primary' }} ms-2">
                                                {{ ucfirst($meeting->status) }}
                                            </span>
                                        </small>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">No recent meetings found.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Today's Schedule -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-calendar-day"></i> Today's Schedule</h5>
                        </div>
                        <div class="card-body">
                            @if($todaySchedule->count() > 0)
                                @foreach($todaySchedule as $meeting)
                                    <div class="border-bottom pb-2 mb-2">
                                        <h6>{{ $meeting->title }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $meeting->start_time->format('H:i') }} - {{ $meeting->end_time->format('H:i') }}
                                            <i class="fas fa-map-marker-alt ms-2"></i> {{ $meeting->location }}
                                        </small>
                                        <br>
                                        <small class="text-info">
                                            {{ $meeting->participants->count() }} participants
                                        </small>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">No meetings scheduled for today.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection