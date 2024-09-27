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
        Schema::table('businesses', function (Blueprint $table) {
            if (Schema::hasColumn('businesses', 'show_business_name')) {
                $table->string('thumbsup_text')->default('Thumbs Up')->nullable()->change();
            }
            if (Schema::hasColumn('businesses', 'show_business_name')) {
                $table->string('thumbsdown_text')->default('Thumbs Down')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            //
        });
    }
};
