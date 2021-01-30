<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_urls', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("job_site_id");
            $table->foreign('job_site_id')->references('id')->on('job_sites');
            $table->unsignedBigInteger("job_cat_id");
            $table->foreign('job_cat_id')->references('id')->on('job_categories');

            $table->string("job_name")->nullable();
            $table->string("job_url")->unique();

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
        Schema::dropIfExists('job_urls');
    }
}
