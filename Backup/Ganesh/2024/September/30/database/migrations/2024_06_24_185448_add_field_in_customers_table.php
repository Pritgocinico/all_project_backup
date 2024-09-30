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
            if(!Schema::hasColumn('customers', 'customer_id')) {
                $table->string('customer_id')->unique()->nullable();
            }
            if(!Schema::hasColumn('customers', 'app_user_active')){
                $table->unsignedBigInteger('app_user_active')->nullable()->comment('1=active and 0=inactive');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if(Schema::hasColumn('customers', 'customer_id')) {
                $table->dropColumn('customer_id');
            }
            if(Schema::hasColumn('customers', 'app_user_active')) {
                $table->dropColumn('app_user_active');
            }
        });
    }
};
