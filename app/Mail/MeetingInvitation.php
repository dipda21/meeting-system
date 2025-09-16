<?php
// app/Mail/MeetingInvitation.php

namespace App\Mail;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MeetingInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $meeting;
    public $participant;

    public function __construct(Meeting $meeting, User $participant)
    {
        $this->meeting = $meeting;
        $this->participant = $participant;
    }

    public function build()
    {
        return $this->subject('Meeting Invitation: ' . $this->meeting->title)
                    ->view('emails.meeting-invitation');
    }
}