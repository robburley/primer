<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBespokeFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bespoke_form_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('icon')->default('fa-question-circle');
            $table->string('class')->nullable();
            $table->string('type')->nullable();
            $table->boolean('has_placeholder')->default(1);
            $table->boolean('has_default')->default(1);
            $table->boolean('has_rules')->default(1);
            $table->boolean('has_options')->default(0);
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
        Schema::dropIfExists('bespoke_form_fields');
    }
}
