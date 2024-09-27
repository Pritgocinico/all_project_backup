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
        Schema::dropIfExists('leads');
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->text('project_name')->nullable();
            $table->integer('customer_id')->nullable();
            $table->text('lead_no');
            $table->text('reference_name')->nullable();
            $table->text('reference_number')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('email')->nullable();
            $table->text('addressone')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('architecture_name')->nullable();
            $table->text('architecture_number')->nullable();
            $table->text('supervisor_name')->nullable();
            $table->text('supervisor_number')->nullable();
            $table->text('description')->nullable();
            $table->integer('lead_status')->default(0);
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Schema::table('leads', function (Blueprint $table) {
        //     $table->foreign('customer_id')->references('id')->on('customers');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
