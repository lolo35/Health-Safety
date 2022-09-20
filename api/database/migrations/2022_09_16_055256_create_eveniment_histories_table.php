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
        Schema::create('eveniment_histories', function (Blueprint $table) {
            $table->id();
            $table->integer("division_id");
            $table->integer('dispatch_number');
            $table->integer('dispatch_id');
            $table->string('type');
            $table->string('description');
            $table->string('reason')->nullable();
            $table->string('line');
            $table->string('area');
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
        Schema::dropIfExists('eveniment_histories');
    }
};
