<?php
// app/Models/Meeting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // TAMBAH INI
use App\Models\MeetingParticipant; // TAMBAH INI
use App\Models\MeetingMinute; // TAMBAH INI

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'meeting_date', 'start_time', 'end_time',
        'location', 'agenda', 'status', 'created_by',
        'approval_status', 'approved_at', 'approved_by', 'rejection_reason',
    ];

    // PERBAIKI CASTING INI
    protected $casts = [
        'meeting_date' => 'date',
        'start_time'   => 'datetime', // HAPUS :H:i
        'end_time'     => 'datetime', // HAPUS :H:i
        'approved_at'  => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function participants()
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function participantUsers()
    {
        return $this->belongsToMany(User::class, 'meeting_participants')->withPivot('status', 'response_at');
    }

    public function minutes()
    {
        return $this->hasMany(MeetingMinute::class);
    }
}