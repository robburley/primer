<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvalidReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invalid_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id');
            $table->string('title');
            $table->text('description');
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('invalid_reasons');
    }
}
