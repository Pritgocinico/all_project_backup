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
            if(!Schema::hasColumn('leads','investment_field')){
                $table->string('investment_field')->nullable();
            }
            if(!Schema::hasColumn('leads','investment_code')){
                $table->string('investment_code')->nullable();
            }
            if(!Schema::hasColumn('leads','investment_remark')){
                $table->longText('investment_remark')->nullable();
            }
            if(!Schema::hasColumn('leads','old_policy_copy_attachment')){
                $table->longText('old_policy_copy_attachment')->nullable();
            }
            if(!Schema::hasColumn('leads','rc_copy_attachment')){
                $table->longText('rc_copy_attachment')->nullable();
            }
            if(!Schema::hasColumn('leads','previous_copy_attachment')){
                $table->longText('previous_copy_attachment')->nullable();
            }
            if(!Schema::hasColumn('leads','policy_no')){
                $table->string('policy_no')->after('id')->nullable();
            }
            if(!Schema::hasColumn('leads','name')){
                $table->string('name')->after('policy_no')->nullable();
            }
            if(!Schema::hasColumn('leads','kyc_number')){
                $table->string('kyc_number')->after('name')->nullable();
            }
            if(!Schema::hasColumn('leads','address')){
                $table->longText('address')->after('kyc_number')->nullable();
            }
            if(!Schema::hasColumn('leads','mobile')){
                $table->unsignedBigInteger('mobile')->after('address')->nullable();
            }
            if(!Schema::hasColumn('leads','email')){
                $table->unsignedBigInteger('email')->after('mobile')->nullable();
            }
            if(!Schema::hasColumn('leads','gst_certificate')){
                $table->longText('gst_certificate')->after('email')->nullable();
            }
            if(!Schema::hasColumn('leads','sum_insurance')){
                $table->longText('sum_insurance')->after('gst_certificate')->nullable();
            }
            if(!Schema::hasColumn('leads','description')){
                $table->longText('description')->after('sum_insurance')->nullable();
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
