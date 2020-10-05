<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Providers\Breach_location;

class CreateBreachLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breach_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string("code");
            $table->string("name");
            $table->string("longname");
            $table->float("xcoord", 15, 8);
            $table->float("ycoord", 15, 8);
            $table->integer("dykering");
            $table->integer("vnk2");
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
        Schema::dropIfExists('breach_locations');
    }
}
