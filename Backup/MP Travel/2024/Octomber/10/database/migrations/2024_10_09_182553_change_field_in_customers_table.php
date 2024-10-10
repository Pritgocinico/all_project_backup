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
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'aadhar_card_file')) {
                $table->longText('aadhar_card_file')->nullable()->change();
            }
            if (Schema::hasColumn('customers', 'passport_file')) {
                $table->longText('passport_file')->nullable()->change();
            }
            if (Schema::hasColumn('customers', 'pan_card_file')) {
                $table->longText('pan_card_file')->nullable()->change();
            }
            if (Schema::hasColumn('customers', 'gst_certificate')) {
                $table->longText('gst_certificate')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
