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
            if (!Schema::hasColumn('leads', 'lead_status')) {
                $table->string('lead_status')->default(1)->comment('1=pending , 2= Assigned ,3= Hold, 4= Complete, 5= Extends, and 6= Cancel');
            }
            if (!Schema::hasColumn('leads', 'assign_date_time')) {
                $table->dateTime('assign_date_time')->nullable();
            }
            if (!Schema::hasColumn('leads', 'hold_date_time')) {
                $table->dateTime('hold_date_time')->nullable();
            }
            if (!Schema::hasColumn('leads', 'complete_date_time')) {
                $table->dateTime('complete_date_time')->nullable();
            }
            if (!Schema::hasColumn('leads', 'extends_date_time')) {
                $table->dateTime('extends_date_time')->nullable();
            }
            if (!Schema::hasColumn('leads', 'cancel_date_time')) {
                $table->dateTime('cancel_date_time')->nullable();
            }
            if (!Schema::hasColumn('leads', 'lead_amount')) {
                $table->unsignedBigInteger('lead_amount')->nullable();
            }
            if (!Schema::hasColumn('leads', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->after('lead_id');
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            }
            if (Schema::hasColumn('leads', 'client_name')) {
                $table->string('client_name')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['lead_status', 'assign_date_time', 'hold_date_time', 'complete_date_time', 'extends_date_time', 'cancel_date_time','lead_amount']);
        });
    }
};
