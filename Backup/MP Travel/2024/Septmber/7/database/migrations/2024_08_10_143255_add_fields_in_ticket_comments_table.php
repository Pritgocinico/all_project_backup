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
        Schema::table('ticket_comments', function (Blueprint $table) {
            if(!Schema::hasColumn('ticket_comments','file_ext')){
                $table->string('file_ext')->nullable();
            }
        });
        Schema::table('salary_slip_details', function (Blueprint $table) {
            if(!Schema::hasColumn('salary_slip_details','salary_slip_id')){
                $table->string('salary_slip_id')->after('id')->nullable();

            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_comments', function (Blueprint $table) {
            $table->dropColumn('file_ext');
        });
    }
};
