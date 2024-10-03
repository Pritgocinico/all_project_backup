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
            if(!Schema::hasColumn('customers','customer_department')){
                $table->string('customer_department')->nullable();
            }
            if(!Schema::hasColumn('customers','gst_certificate')){
                $table->string('gst_certificate')->nullable();
            }
            if(!Schema::hasColumn('customers','pan_card_file')){
                $table->string('pan_card_file')->nullable();
            }
            if(!Schema::hasColumn('customers','aadhar_card_file')){
                $table->string('aadhar_card_file')->nullable();
            }
            if(Schema::hasColumn('customers','aadhaar_number')){
                $table->renameColumn('aadhaar_number','aadhar_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if(Schema::hasColumn('customers','customer_department')){
                $table->dropColumn('customer_department');
            }
            if(Schema::hasColumn('customers','gst_certificate')){
                $table->dropColumn('gst_certificate');
            }
            if(Schema::hasColumn('customers','pan_card_file')){
                $table->dropColumn('pan_card_file');
            }
            if(Schema::hasColumn('customers','aadhar_card_file')){
                $table->dropColumn('aadhar_card_file');
            }
            if(Schema::hasColumn('customers','aadhar_number')){
                $table->renameColumn('aadhar_number','aadhaar_number');
            }
        });
    }
};
