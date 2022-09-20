<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autoliv_plants', function (Blueprint $table) {
            $table->id();
            $table->string("abbreviation");
            $table->string('l2l_url');
            $table->string('l2l_auth');
            $table->string('l2l_site');
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
        Schema::dropIfExists('autoliv_plants');
    }
};
