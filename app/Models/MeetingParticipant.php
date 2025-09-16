<?php
// app/Models/MeetingParticipant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'user_id',
        'status',
        'response_at',
        'reminder_sent_at', // Add this field
        'whatsapp_message_id', 
    ];

    protected $casts = [
          'response_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}