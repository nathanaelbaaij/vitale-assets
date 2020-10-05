<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depths', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');

            $table->unsignedInteger('breach_location_id');
            $table->foreign('breach_location_id')->references('id')->on('breach_locations')->onDelete('cascade');

            $table->unsignedInteger('load_level_id');
            $table->foreign('load_level_id')->references('id')->on('load_levels')->onDelete('cascade');

            $table->double('water_depth');
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
        Schema::dropIfExists('depths');
    }
}
