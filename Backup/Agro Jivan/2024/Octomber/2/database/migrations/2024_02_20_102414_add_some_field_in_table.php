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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->double('amount',8,2)->nullable();
            $table->enum('order_status',[1,2,3,4,5,6])->default(1)->comment('1= Pending, 2=Confirmed,3= On Delivery,4=Cancel, 5= Returned and 6 = Completed');
            $table->string('product_type')->nullable();
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->double('amount',8,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table', function (Blueprint $table) {
            //
        });
    }
};
