<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id');
            $table->integer('user_id');
            $table->boolean('supervisor')->default(0);
            $table->boolean('create_new_lead')->default(1);
            $table->boolean('update_lead')->default(1);

            $table->index(['campaign_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_user');
    }
}
