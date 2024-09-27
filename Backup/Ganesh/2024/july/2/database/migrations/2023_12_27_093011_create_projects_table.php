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
        Schema::dropIfExists('projects');
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->text('project_generated_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->text('project_name')->nullable();
            $table->text('business_type')->nullable();
            $table->text('business_name')->nullable();
            $table->text('gst_number')->nullable();
            $table->text('customer_name')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('architecture_name')->nullable();
            $table->text('architecture_number')->nullable();
            $table->text('supervisor_name')->nullable();
            $table->text('supervisor_number')->nullable();
            $table->text('email')->nullable();
            $table->text('address')->nullable();
            $table->text('cityname')->nullable();
            $table->text('statename')->nullable();
            $table->text('zipcode')->nullable();
            $table->text('description')->nullable();
            $table->date('project_confirm_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('measurement_date')->nullable();
            $table->text('reference_name')->nullable();
            $table->text('reference_phone')->nullable();
            $table->integer('status')->default(0);
            $table->date('transit_date')->nullable();
            $table->string('transit_desc')->nullable();
            $table->date('fitting_date')->nullable();
            $table->string('fitting_desc')->nullable();
            $table->date('fitting_complete_date')->nullable();
            $table->string('fitting_complete_desc')->nullable();
            $table->string('lead_no')->nullable();
            $table->integer('lead_status')->nullable();
            $table->integer('type')->default('0')->nullable();
            $table->integer('step')->default('0')->nullable();
            $table->double('quotation_cost')->default(0)->nullable();
            $table->double('project_cost')->default(0)->nullable();
            $table->integer('material_selection')->default('0')->nullable();
            $table->timestamp('selection_date')->nullable();
            $table->text('reject_note')->nullable();
            $table->integer('reject_reason')->default(0)->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
