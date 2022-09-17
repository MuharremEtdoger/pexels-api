<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
			$table->integer('photo_id');	
			$table->string('getOriginal');	
			$table->string('getLarge2x');
			$table->string('getLarge');
			$table->string('getMedium');
			$table->string('getSmall');
			$table->string('getPortrait');
			$table->string('getLandscape');
			$table->string('getTiny');
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
        Schema::dropIfExists('images');
    }
}
