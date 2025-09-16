<?php
// config/whatsapp.php



return [
    'instance_id' => env('WHATSAPP_INSTANCE_ID'),
    'token' => env('WHATSAPP_TOKEN'),
    'base_url' => env('WHATSAPP_BASE_URL', 'https://api.green-api.com'),
    'timeout' => env('WHATSAPP_TIMEOUT', 30),
    
    // Format pesan
    'messages' => [
        'invitation' => "🎯 *UNDANGAN MEETING*\n\n" .
                       "Halo {participant_name},\n\n" .
                       "Anda diundang untuk menghadiri meeting:\n\n" .
                       "📋 *Judul:* {meeting_title}\n" .
                       "📝 *Deskripsi:* {meeting_description}\n" .
                       "📅 *Tanggal:* {meeting_date}\n" .
                       "⏰ *Waktu:* {start_time} - {end_time}\n" .
                       "📍 *Lokasi:* {meeting_location}\n" .
                       "📌 *Agenda:* {meeting_agenda}\n\n" .
                       "Mohon konfirmasi kehadiran Anda dengan membalas:\n" .
                       "• Ketik *HADIR* untuk konfirmasi kehadiran\n" .
                       "• Ketik *TIDAK* jika berhalangan\n\n" .
                       "Terima kasih! 🙏",
        
        'reminder' => "⏰ *REMINDER MEETING*\n\n" .
                     "Halo {participant_name},\n\n" .
                     "Meeting akan dimulai dalam {time_remaining}:\n\n" .
                     "📋 *Judul:* {meeting_title}\n" .
                     "📅 *Tanggal:* {meeting_date}\n" .
                     "⏰ *Waktu:* {start_time} - {end_time}\n" .
                     "📍 *Lokasi:* {meeting_location}\n\n" .
                     "Jangan lupa untuk hadir tepat waktu! 🙏",
        
        'status_update' => "📢 *UPDATE MEETING*\n\n" .
                          "Meeting: *{meeting_title}*\n" .
                          "Status: {status_message}\n\n" .
                          "📅 *Tanggal:* {meeting_date}\n" .
                          "⏰ *Waktu:* {start_time} - {end_time}\n" .
                          "📍 *Lokasi:* {meeting_location}",
        
        'cancellation' => "❌ *MEETING DIBATALKAN*\n\n" .
                         "Meeting: *{meeting_title}*\n" .
                         "📅 *Tanggal:* {meeting_date}\n" .
                         "⏰ *Waktu:* {start_time} - {end_time}\n\n" .
                         "Meeting ini telah dihapus dari jadwal.\n\n" .
                         "Mohon maaf atas ketidaknyamanannya. 🙏",
        
        'confirmation_received' => "✅ Terima kasih atas konfirmasi Anda!\n\n" .
                                  "Status kehadiran untuk meeting *{meeting_title}* telah diperbarui.\n\n" .
                                  "Sampai jumpa di meeting! 👋"
    ],
    
    // Status messages
    'status_messages' => [
        'scheduled' => '📅 Meeting dijadwalkan',
        'ongoing' => '🟢 Meeting sedang berlangsung',
        'completed' => '✅ Meeting telah selesai',
        'cancelled' => '❌ Meeting telah dibatalkan'
    ],
    
    // Reminder settings (dalam menit sebelum meeting)
    'reminders' => [
        'first' => env('WHATSAPP_REMINDER_FIRST', 1440),  // 24 jam sebelum
        'second' => env('WHATSAPP_REMINDER_SECOND', 60),  // 1 jam sebelum
        'third' => env('WHATSAPP_REMINDER_THIRD', 15)     // 15 menit sebelum
    ]

    
];

