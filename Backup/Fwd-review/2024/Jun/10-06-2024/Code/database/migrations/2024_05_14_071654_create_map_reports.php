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
        if (!Schema::hasTable('map_reports')) {
            Schema::create('map_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->text('business_id')->nullable();
                $table->string('place_id')->nullable();
                $table->string('report_type')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('name')->nullable();
                $table->json('details')->nullable();
                $table->enum('export_pdf', ['0', '1'])->default(0)->comment('0=no export, 1=export');
                $table->enum('export_csv', ['0', '1'])->default(0)->comment('0=no export, 1=export');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_reports');
    }
};
