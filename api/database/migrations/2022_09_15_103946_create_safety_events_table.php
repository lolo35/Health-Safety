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
        Schema::create('safety_events', function (Blueprint $table) {
            $table->id();
            $table->integer('division_id');
            $table->integer('l2l_id');
            $table->string('code');
            $table->string('description');
            $table->boolean('has_reasons')->default(false);
            $table->string('btn_color')->nullable()->default('bg-red-500');
            $table->boolean('remains_open')->default(true);
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
        Schema::dropIfExists('safety_events');
    }
};
