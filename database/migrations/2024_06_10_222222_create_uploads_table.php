<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->integer('advertiser_id')->nullable();
            $table->string('resource_type');
            $table->string('resource_filename');
            $table->string('resource_path');
            $table->string('file_path')->nullable();
            $table->boolean('is_uploaded')->default(false);
            $table->integer('uploaded_by')->nullable();
            $table->dateTime('uploaded_at')->nullable();
            $table->mediumText('notes')->nullable();
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
        Schema::dropIfExists('uploads');
    }
}
