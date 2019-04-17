<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignLeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_lead', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id');
            $table->integer('lead_id');
            $table->integer('assigned_id');
            $table->timestamp('callback')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->integer('validated_by')->nullable();
            $table->timestamp('invalidated_at')->nullable();
            $table->text('invalid_comment')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('qualified_at')->nullable();
            $table->timestamps();

            $table->index(['campaign_id']);
            $table->index(['campaign_id', 'callback']);
            $table->index(['campaign_id', 'completed_at']);
            $table->index(['campaign_id', 'confirmed_at']);
            $table->index(['campaign_id', 'rejected_at']);
            $table->index(['campaign_id', 'sent_at']);
            $table->index(['campaign_id', 'qualified_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_lead');
    }
}
