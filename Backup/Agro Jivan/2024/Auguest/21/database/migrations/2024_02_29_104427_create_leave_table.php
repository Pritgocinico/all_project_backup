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
        Schema::create('leave', function (Blueprint $table) {
            $table->id();
            $table->string('leave_type')->nullable();
            $table->date('leave_from')->nullable();
            $table->date('leave_to')->nullable();
            $table->longText('reason')->nullable();
            $table->enum('status',['1','0'])->default(1)->comment('1= Active and 0=Inactive');
            $table->enum('leave_status',['1','2','3'])->default(1)->comment('1= Pending ,2=Approve and 3=Reject');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->enum('leave_feature',['1','0'])->nullable()->comment('1= full day and 0= half day');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave');
    }
};
