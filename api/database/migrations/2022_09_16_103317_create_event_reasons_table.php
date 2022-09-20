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
        Schema::create('event_reasons', function (Blueprint $table) {
            $table->id();
            $table->integer('division_id');
            $table->integer('l2l_id');
            $table->string('code');
            $table->string('description');
            $table->integer('belongs_to')->nullable();
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
        Schema::dropIfExists('event_reasons');
    }
};
