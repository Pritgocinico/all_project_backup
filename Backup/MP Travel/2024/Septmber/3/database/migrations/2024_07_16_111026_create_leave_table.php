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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('leave_type')->nullable();
            $table->date('leave_from')->nullable();
            $table->date('leave_to')->nullable();
            $table->longText('reason')->nullable();
            $table->unsignedBigInteger('status')->default(1)->comment('1=Active and 0= In active');
            $table->unsignedBigInteger('leave_status')->default(0)->comment('0=Pending and 1=Approved and 2=Rejected');
            $table->unsignedBigInteger('total_leave_day')->nullable();
            $table->longText('reject_reason')->nullable();
            $table->string('attachment')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
