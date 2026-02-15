<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesList extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies_list', function(Blueprint $table)
        {
            // Use char(36) for UUIDs as it is more performant than string/varchar
            $table->char('id', 36)->primary(); 
            $table->string('title');
            $table->string('year', 10);
            $table->string('imdbID')->unique();
            $table->string('type', 50);
            $table->text('poster');
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
        Schema::drop('movies_list');
    }

}