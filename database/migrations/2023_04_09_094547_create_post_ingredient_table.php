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
        Schema::create('post_ingredient', function (Blueprint $table) {
            $table->id();

            $table->foreignId('post_id')->constrained();
            $table->foreignId('ingredient_id')->nullable()->constrained();

            $table->string('amount')->nullable();
            $table->string('unit')->nullable();
            $table->unsignedInteger('gram')->nullable();

            $table->string('ingredient_name')->nullable();

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
        Schema::dropIfExists('post_ingredient');
    }
};