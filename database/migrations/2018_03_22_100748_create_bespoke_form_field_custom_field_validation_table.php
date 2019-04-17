<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBespokeFormFieldCustomFieldValidationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bespoke_form_field_custom_field_validation', function (Blueprint $table) {
            $table->integer('bespoke_form_field_id');
            $table->integer('custom_field_validation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bespoke_form_field_custom_field_validation');
    }
}
