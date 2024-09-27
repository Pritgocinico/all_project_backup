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
        Schema::table('follow_up_events', function (Blueprint $table) {
            if(!Schema::hasColumn('follow_up_events','event_status')){
                $table->unsignedBigInteger('event_status')->default(1)->comment('1=Pending, 2= Complete,3= Extend,4= Cancel,5= Hold');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_up_events', function (Blueprint $table) {
            $table->dropColumn('event_status');
        });
    }
};
