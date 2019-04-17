<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignInvalidReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_invalid_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_pivot_id');
            $table->integer('invalid_reason_id');
            $table->timestamps();

            $table->index(['campaign_pivot_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_pivot_invalid_reasons');
    }
}
