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
        Schema::table('menus', function (Blueprint $table) {
            if(Schema::hasColumn('menus', 'parent_id')){
                $table->unsignedBigInteger('parent_id')->default(0)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            if(Schema::hasColumn('menus', 'parent_id')){
                $table->int('parent_id')->default(1)->change();
            }
        });
    }
};
