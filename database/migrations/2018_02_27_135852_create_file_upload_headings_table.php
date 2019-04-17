<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileUploadHeadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_upload_headings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_upload_id');
            $table->string('name');
            $table->string('slug');
            $table->string('array_key');
            $table->integer('custom_field_id')->nullable();
            $table->integer('default_field_id')->nullable();
            $table->timestamps();

            $table->index(['file_upload_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_upload_headings');
    }
}
