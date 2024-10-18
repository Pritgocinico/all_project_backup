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
        Schema::table('cust_alternate_numbers', function (Blueprint $table) {
            $table->string('cust_alt_num', 255)
                ->charset('utf8mb4')
                ->collation('utf8mb4_unicode_ci')
                ->nullable() // Allow NULL
                ->default(null) // Set DEFAULT to NULL
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cust_alternate_numbers', function (Blueprint $table) {
            $table->string('cust_alt_num', 255)
                ->charset('utf8mb4')
                ->collation('utf8mb4_unicode_ci')
                ->nullable(false) // Revert nullable to false
                ->default('') // Revert default to an empty string or whatever was before
                ->change();
        });
    }
};
