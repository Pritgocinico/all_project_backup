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
        Schema::table('remarks', function (Blueprint $table) {
            if (!Schema::hasColumn('remarks', 'next_call_date_time')) {
                $table->dateTime('next_call_date_time')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remarks', function (Blueprint $table) {
            if (Schema::hasColumn('remarks', 'next_call_date_time')) {
                $table->dropColumn('next_call_date_time');
            }
        });
    }
};
