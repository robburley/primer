<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectedCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selected_custom_fields', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('selected_custom_field_group_id');
            $table->integer('custom_field_id');
            $table->boolean('show_to_lead_generator');
            $table->boolean('show_to_customers');
            $table->boolean('required_for_completion');
            $table->integer('order');

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
        Schema::dropIfExists('selected_custom_fields');
    }
}
