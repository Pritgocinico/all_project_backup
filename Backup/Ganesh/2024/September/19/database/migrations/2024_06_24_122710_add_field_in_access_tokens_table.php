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
        Schema::table('access_tokens', function (Blueprint $table) {
            if(!Schema::hasColumn('access_tokens', 'type')){
                $table->string('type')->default('user')->comment('user,customer')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('access_tokens', function (Blueprint $table) {
            if(Schema::hasColumn('access_tokens', 'type')){
                $table->dropColumn('type');
            }
        });
    }
};
