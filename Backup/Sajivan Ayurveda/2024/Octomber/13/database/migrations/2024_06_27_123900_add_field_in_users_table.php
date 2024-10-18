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
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn('users', 'original_password')) {
                $table->string('original_password')->nullable();
            }
            if(!Schema::hasColumn('users', 'phone_number')) {
                $table->unsignedBigInteger('phone_number')->nullable();
            }
            if(!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable();
            }
            if(!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable();
            }
            if(!Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable();
            }
            if(!Schema::hasColumn('users', 'address')) {
                $table->longText('address')->nullable();
            }
            if(!Schema::hasColumn('users', 'zip_code')) {
                $table->string('zip_code')->nullable();
            }
            if(!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('original_password');
            $table->dropColumn('phone_number');
            $table->dropColumn('state');
            $table->dropColumn('city');
            $table->dropColumn('address');
            $table->dropColumn('zip_code');
            $table->dropColumn('deleted_at');
        });
    }
};
