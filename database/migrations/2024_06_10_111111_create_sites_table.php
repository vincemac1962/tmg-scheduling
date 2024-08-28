<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->integer('site_group_id')->nullable();
            $table->string('site_ref');
            $table->string('site_name');
            $table->string('site_address')->nullable();
            $table->string('site_postcode')->nullable();
            $table->string('site_country')->nullable();
            $table->string('site_contact')->nullable();
            $table->string('site_email')->nullable();
            $table->boolean('site_active')->default(true);
            $table->string('site_notes')->nullable();
            $table->dateTime('site_last_contact')->nullable();
            $table->dateTime('site_last_updated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
}
