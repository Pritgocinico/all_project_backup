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
        Schema::create('additional_project_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->double('quotation_cost')->nullable();
            $table->double('project_cost')->nullable();
            $table->double('laber_cost')->nullable();
            $table->double('transport_cost')->nullable();
            $table->double('margin_cost')->nullable();
            $table->unsignedBigInteger('material_selection')->nullable();
            $table->unsignedBigInteger('cutting_selection')->nullable();
            $table->timestamp('cutting_date')->nullable();
            $table->unsignedBigInteger('shutter_selection')->nullable();
            $table->unsignedBigInteger('glass_measurement')->nullable();
            $table->timestamp('glass_date')->nullable();
            $table->unsignedBigInteger('glass_receive')->nullable();
            $table->timestamp('glass_receive_date')->nullable();
            $table->unsignedBigInteger('shutter_ready_date')->nullable();
            $table->timestamp('shutter_date')->nullable();
            $table->unsignedBigInteger('material_delivered')->nullable();
            $table->string('delivered_by')->nullable();
            $table->timestamp('deliver_date')->nullable();
            $table->string('driver_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_project_details');
    }
};
