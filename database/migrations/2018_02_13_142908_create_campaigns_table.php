<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('endpoint_type')->nullable();
            $table->string('endpoint_location')->nullable();
            $table->string('lead_order')->default('first');
            $table->boolean('validate_leads')->default(1);
            $table->integer('primary_name_field_id')->nullable();
            $table->integer('primary_telephone_field_id')->nullable();
            $table->integer('primary_email_field_id')->nullable();
            $table->integer('tenant_id');
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
}
