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
        Schema::table('insurance_leads', function (Blueprint $table) {
            if(!Schema::hasColumn('insurance_leads','other_marine_policy')){
                $table->longText('other_marine_policy')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','hyphenation')){
                $table->longText('hyphenation')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','commodity_description')){
                $table->longText('commodity_description')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','transit_mode')){
                $table->longText('transit_mode')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','voyage_type')){
                $table->longText('voyage_type')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','voyage_detail')){
                $table->longText('voyage_detail')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','packaging')){
                $table->longText('packaging')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','per_bottom_limit')){
                $table->longText('per_bottom_limit')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','per_location_limit')){
                $table->longText('per_location_limit')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','vehicle_type')){
                $table->longText('vehicle_type')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurance_leads', function (Blueprint $table) {
            //
        });
    }
};
