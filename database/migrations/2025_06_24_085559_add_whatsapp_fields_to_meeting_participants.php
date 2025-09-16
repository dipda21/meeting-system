<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWhatsappFieldsToMeetingParticipants extends Migration
{
    public function up()
    {
        Schema::table('meeting_participants', function (Blueprint $table) {
            $table->timestamp('reminder_sent_at')->nullable()->after('response_at');
            $table->string('whatsapp_message_id')->nullable()->after('reminder_sent_at');
        });
    }

    public function down()
    {
        Schema::table('meeting_participants', function (Blueprint $table) {
            $table->dropColumn(['reminder_sent_at', 'whatsapp_message_id']);
        });
    }
}