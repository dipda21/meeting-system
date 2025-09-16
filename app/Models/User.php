<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'position',
        'department',
        'role',
        'phone_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function createdMeetings()
    {
        return $this->hasMany(Meeting::class, 'created_by');
    }

    public function meetingParticipations()
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function createdMinutes()
    {
        return $this->hasMany(MeetingMinute::class, 'created_by');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Method untuk kompatibilitas dengan kode lama
    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }

    public function setPhoneNumberAttribute($value)
    {
        // Auto-format phone number when saving
        $this->attributes['phone_number'] = $this->formatPhoneNumber($value);
    }

    private function formatPhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        if (substr($phone, 0, 1) === '0') {
            return '62' . substr($phone, 1);
        }
        
        if (substr($phone, 0, 1) === '8') {
            return '62' . $phone;
        }

        return $phone;
    }
}