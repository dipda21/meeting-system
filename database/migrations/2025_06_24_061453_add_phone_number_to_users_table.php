<?php
// database/migrations/xxxx_xx_xx_add_phone_number_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email');
            $table->boolean('whatsapp_notifications')->default(true)->after('phone_number');
            $table->timestamp('last_whatsapp_sent_at')->nullable()->after('whatsapp_notifications');
            
            // Add index for phone number lookups
            $table->index('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['phone_number']);
            $table->dropColumn(['phone_number', 'whatsapp_notifications', 'last_whatsapp_sent_at']);
        });
    }
};