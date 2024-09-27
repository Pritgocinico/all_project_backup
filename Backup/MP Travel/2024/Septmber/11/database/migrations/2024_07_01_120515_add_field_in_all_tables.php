<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tables = [
        'accesses',
        'departments',
        'designations',
        'logs',
        'menus',
        'roles',
        'settings',
        'users',
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasColumn($table, 'created_by')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->unsignedBigInteger('created_by')->nullable();
                });
            }
            if (!Schema::hasColumn($table, 'updated_by')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->unsignedBigInteger('updated_by')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('created_by');
                $table->dropColumn('updated_by');
            });
        }
    }
};