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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')->constrained();

            $table->string('title');
            $table->string('descripton')->nullable();

            $table->string('difficulty');

            $table->unsignedTinyInteger('portion')->default(1);

            $table->string('preparation_time')->nullable(); // in minute
            $table->string('baking_time')->nullable(); // in minute

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
        Schema::dropIfExists('posts');
    }
};
