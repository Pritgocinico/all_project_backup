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
            if(!Schema::hasColumn('customers','passport_number')){
                $table->string('passport_number')->nullable();
            }
            if(!Schema::hasColumn('customers','passport_file')){
                $table->string('passport_file')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if(Schema::hasColumn('customers','passport_number')){
                $table->dropColumn('passport_number');
            }
            if(Schema::hasColumn('customers','passport_file')){
                $table->dropColumn('passport_file');
            }
        });
    }
};
