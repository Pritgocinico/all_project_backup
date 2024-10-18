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
        Schema::table('certificates', function (Blueprint $table) {
            if(!Schema::hasColumn('certificates','manager_signature_file')){
                $table->string('manager_signature_file')->nullable();
            }
            if(!Schema::hasColumn('certificates','director_signature_file')){
                $table->string('director_signature_file')->nullable();
            }
            if(!Schema::hasColumn('certificates','month_name')){
                $table->string('month_name')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            if(Schema::hasColumn('certificates','manager_signature_file')){
                $table->dropColumn('manager_signature_file');
            }
            if(Schema::hasColumn('certificates','director_signature_file')){
                $table->dropColumn('director_signature_file');
            }
            if(Schema::hasColumn('certificates','month_name')){
                $table->dropColumn('month_name');
            }
        });
    }
};
