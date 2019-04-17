<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateValidationColumnsLeadCampaignsLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_lead', function (Blueprint $table) {
            $table->renameColumn('validated_at', 'confirmed_at');
            $table->renameColumn('validated_by', 'confirmed_by');
            $table->renameColumn('invalidated_at', 'rejected_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
