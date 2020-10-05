<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCascadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cascades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asset_id_from')->unsigned();
            $table->foreign('asset_id_from')->references('id')->on('assets');
            $table->integer('asset_id_to')->unsigned();
            $table->foreign('asset_id_to')->references('id')->on('assets');
            $table->integer('consequence_id')->unsigned();
            $table->foreign('consequence_id')->references('id')->on('consequences')->onDelete('cascade');
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
        Schema::dropIfExists('cascades');
    }
}
