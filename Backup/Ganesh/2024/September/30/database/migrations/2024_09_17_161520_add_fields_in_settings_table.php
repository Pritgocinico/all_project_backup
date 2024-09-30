<?php

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
        Schema::table('settings', function (Blueprint $table) {
            if(!Schema::hasColumn('settings','wa_message_sent')){
                $table->unsignedBigInteger('wa_message_sent')->default(1);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if(Schema::hasColumn('settings','wa_message_sent')){
                $table->dropColumn('wa_message_sent');
            }
        });
    }
};
