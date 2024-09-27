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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('timezone')->nullable();
            $table->string('logo')->nullable();
            $table->text('business_name')->nullable();
            $table->string('shortname')->nullable();
            $table->string('social_media')->nullable();
            $table->text('facebook_url')->nullable();
            $table->text('twitter_url')->nullable();
            $table->text('instagram_url')->nullable();
            $table->text('visitor_message')->nullable();
            $table->text('visitor_title')->nullable();
            $table->integer('threshold')->nullable();
            $table->text('public_review_message')->nullable();
            $table->text('public_review_thankyou_message')->nullable();
            $table->text('prompt_message')->nullable();
            $table->text('private_feedback')->nullable();
            $table->text('form_type')->nullable();
            $table->text('private_feedback_thankyou')->nullable();
            $table->text('thumbsup_text')->nullable();
            $table->text('thumbsdown_text')->nullable();
            $table->string('contact_info')->nullable();
            $table->string('show_business_name')->nullable();
            $table->string('brand_color')->nullable();
            $table->text('place_id')->nullable();
            $table->string('api_key')->nullable();
            $table->integer('status')->nullable();
            $table->integer('active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
