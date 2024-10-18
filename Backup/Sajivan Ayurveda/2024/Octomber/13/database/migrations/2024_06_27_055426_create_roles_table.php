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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->tinyInteger('active')->default('1')->comment('1=active and 0= inactive');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('users',function(Blueprint $table){
            if(!Schema::hasColumn('users','role_id')){
                $table->unsignedBigInteger('role_id')->nullable();
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::table('users',function(Blueprint $table){
            if(Schema::hasColumn('users','role_id')){
                $table->dropColumn('role_id');
            }
        });
    }
};
