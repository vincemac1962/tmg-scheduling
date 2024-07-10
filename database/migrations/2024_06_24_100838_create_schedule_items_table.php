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
        // ToDo remove description field
        Schema::create('schedule_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('upload_id')->constrained()->onDelete('cascade');
            // advertiser id is nullable or must contain a valid advertiser id
            $table->unsignedBigInteger('advertiser_id')->nullable()->default(null);
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('file');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       /* Schema::table('schedule_items', function (Blueprint $table) {
            $table->dropForeign(['advertiser_id']);
            $table->dropColumn('advertiser_id');
        }); */
        Schema::dropIfExists('schedule_items');
    }
};
