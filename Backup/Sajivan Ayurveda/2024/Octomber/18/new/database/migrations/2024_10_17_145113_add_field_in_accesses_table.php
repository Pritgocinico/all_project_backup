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
        Schema::table('accesses', function (Blueprint $table) {
            if (!Schema::hasColumn('accesses', 'disable')) {
                $table->tinyInteger('disable')->default(0)->comment('1=active and 0= inactive');
            }
            if (!Schema::hasColumn('accesses', 'view')) {
                $table->tinyInteger('view')->default(0)->comment('1=active and 0= inactive');
            }
            if (!Schema::hasColumn('accesses', 'add')) {
                $table->tinyInteger('add')->default(0)->comment('1=active and 0= inactive');
            }
            if (!Schema::hasColumn('accesses', 'edit')) {
                $table->tinyInteger('edit')->default(0)->comment('1=active and 0= inactive');
            }
            if (!Schema::hasColumn('accesses', 'delete')) {
                $table->tinyInteger('delete')->default(0)->comment('1=active and 0= inactive');
            }
            if (!Schema::hasColumn('accesses', 'export')) {
                $table->tinyInteger('export')->default(0)->comment('1=active and 0= inactive');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accesses', function (Blueprint $table) {
            //
        });
    }
};
