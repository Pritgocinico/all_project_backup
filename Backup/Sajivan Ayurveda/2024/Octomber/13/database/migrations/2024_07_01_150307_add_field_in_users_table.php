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
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn('users','aadhar_card')) {
                $table->string('aadhar_card')->nullable();
            }
            if(!Schema::hasColumn('users','pan_card')) {
                $table->string('pan_card')->nullable();
            }
            if(!Schema::hasColumn('users','user_agreement')) {
                $table->string('user_agreement')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('aadhar_card');
            $table->dropColumn('pan_card');
            $table->dropColumn('user_agreement');
        });
    }
};
