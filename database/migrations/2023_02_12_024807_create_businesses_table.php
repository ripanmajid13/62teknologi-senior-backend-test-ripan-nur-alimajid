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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('alias', 100);
            $table->string('name', 100);
            $table->text('image_url')->nullable();
            $table->boolean('is_closed')->nullable();
            $table->text('url')->nullable();
            $table->integer('review_count')->nullable();
            $table->double('rating', 8, 2)->nullable();
            $table->integer('price')->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('display_phone', 100)->nullable();
            $table->double('distance', 8, 2)->nullable();
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
        Schema::dropIfExists('businesses');
    }
};
