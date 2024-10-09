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
        Schema::table('lead_attachments', function (Blueprint $table) {
            if(!Schema::hasColumn('lead_attachments','file_name')) {
                $table->string('file_name')->nullable();
            }
            if(!Schema::hasColumn('lead_attachments','policy_type')) {
                $table->string('policy_type')->nullable();
            }
        });
        Schema::table('existing_policy_copy_leads', function (Blueprint $table) {
            if(!Schema::hasColumn('existing_policy_copy_leads','policy_type')) {
                $table->string('policy_type')->nullable();
            }
        });
        Schema::table('insurance_leads', function (Blueprint $table) {
            if(!Schema::hasColumn('insurance_leads','daily_cash_allowance')) {
                $table->longText('daily_cash_allowance')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','total_stock_in_process')) {
                $table->longText('total_stock_in_process')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_attachments', function (Blueprint $table) {
            if(Schema::hasColumn('lead_attachments','file_name')) {
                $table->dropColumn('file_name');
            }
            if(Schema::hasColumn('lead_attachments','policy_type')) {
                $table->dropColumn('policy_type');
            }
        });
    }
};
