// Migration file: database/migrations/2024_xx_xx_create_whatsapp_logs_table.php
/*
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->text('message');
            $table->string('message_id')->nullable();
            $table->enum('status', ['sent', 'failed', 'error']);
            $table->string('delivery_status')->nullable(); // delivered, read, etc
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at');
            $table->timestamps();
            
            $table->index(['phone_number', 'sent_at']);
            $table->index('message_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};

