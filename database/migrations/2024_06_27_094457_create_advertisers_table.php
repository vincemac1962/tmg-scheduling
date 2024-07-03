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
        Schema::create('advertisers', function (Blueprint $table) {
            $table->id();
            $table->string('contract')->unique();
            $table->string('business_name');
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('url')->nullable();
            $table->string('social')->nullable();
            $table->string('banner')->nullable();
            $table->string('button')->nullable();
            $table->string('mp4')->nullable();
            $table->integer('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisers');
    }
};
