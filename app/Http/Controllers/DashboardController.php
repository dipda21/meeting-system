<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingMinute;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMeetings = Meeting::count();
        $todayMeetings = Meeting::whereDate('meeting_date', Carbon::today())->count();
        $upcomingMeetings = Meeting::where('meeting_date', '>', Carbon::today())->count();
        $totalUsers = User::count();
        
        $recentMeetings = Meeting::with('creator')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $todaySchedule = Meeting::with('participants.user')
            ->whereDate('meeting_date', Carbon::today())
            ->orderBy('start_time')
            ->get();

        return view('dashboard', compact(
            'totalMeetings',
            'todayMeetings', 
            'upcomingMeetings',
            'totalUsers',
            'recentMeetings',
            'todaySchedule'
        ));
    }
}