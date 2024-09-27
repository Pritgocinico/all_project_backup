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
        Schema::table('claim_attachments', function (Blueprint $table) {
            if(!Schema::hasColumn('claim_attachments','file_name')) {
                $table->string('file_name')->nullable();
            }
        });
        Schema::table('covernote_attachments', function (Blueprint $table) {
            if(!Schema::hasColumn('covernote_attachments','file_name')) {
                $table->string('file_name')->nullable();
            }
        });
        Schema::table('endorsement_attachments', function (Blueprint $table) {
            if(!Schema::hasColumn('endorsement_attachments','file_name')) {
                $table->string('file_name')->nullable();
            }
        });
        Schema::table('policy_attachments', function (Blueprint $table) {
            if(!Schema::hasColumn('policy_attachments','file_name')) {
                $table->string('file_name')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claim_attachments', function (Blueprint $table) {
            $table->dropColumn('file_name');
        });
        Schema::table('covernote_attachments', function (Blueprint $table) {
            $table->dropColumn('file_name');
        });
        Schema::table('endorsement_attachments', function (Blueprint $table) {
            $table->dropColumn('file_name');
        });
        Schema::table('policy_attachments', function (Blueprint $table) {
            $table->dropColumn('file_name');
        });
    }
};
