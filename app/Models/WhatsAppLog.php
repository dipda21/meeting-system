<?php
// app/Models/WhatsAppLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'message',
        'message_id',
        'status',
        'delivery_status',
        'error_message',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    /**
     * Scope for successful messages
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for failed messages
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('status', ['failed', 'error']);
    }

    /**
     * Scope for delivered messages
     */
    public function scopeDelivered($query)
    {
        return $query->where('delivery_status', 'delivered');
    }
}

