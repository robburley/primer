<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('running')->default(0);
            $table->integer('status')->default(1);
            $table->string('name');
            $table->string('location')->nullable();
            $table->timestamp('analysed_at')->nullable();
            $table->integer('total')->nullable()->default(0);
            $table->timestamp('fields_mapped_at')->nullable();
            $table->integer('processed_leads')->nullable()->default(0);
            $table->integer('valid_leads')->nullable()->default(0);
            $table->integer('invalid_leads')->nullable()->default(0);
            $table->timestamp('import_started_at')->nullable();
            $table->integer('imported_leads')->nullable()->default(0);
            $table->timestamp('error_at')->nullable();
            $table->text('error_text')->nullable();
            $table->integer('tenant_id');
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
        Schema::dropIfExists('file_uploads');
    }
}
