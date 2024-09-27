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
        Schema::table('leads', function (Blueprint $table) {
            if(Schema::hasColumn('leads','name')){
                $table->dropColumn('name');
            }
            if(Schema::hasColumn('leads','kyc_number')){
                $table->dropColumn('kyc_number');
            }
            if(Schema::hasColumn('leads','address')){
                $table->dropColumn('address');
            }
            if(Schema::hasColumn('leads','mobile')){
                $table->dropColumn('mobile');
            }
            if(Schema::hasColumn('leads','email')){
                $table->dropColumn('email');
            }
            if(Schema::hasColumn('leads','insurance_group')){
                $table->dropColumn('insurance_group');
            }
            if(!Schema::hasColumn('leads','policy_type')){
                $table->string('policy_type')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            //
        });
    }
};
