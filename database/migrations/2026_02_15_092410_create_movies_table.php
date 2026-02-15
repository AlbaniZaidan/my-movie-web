<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies_table', function(Blueprint $table)
        {
            // Primary Key
            $table->char('id', 36)->primary(); 

            // Basic Info
            $table->string('title');
            $table->string('year', 20);
            $table->string('rated', 10)->nullable();
            $table->string('released', 30)->nullable();
            $table->string('runtime', 20)->nullable();
            $table->string('genre')->nullable();
            
            // People
            $table->string('director')->nullable();
            $table->text('writer')->nullable();
            $table->text('actors')->nullable();
            
            // Content
            $table->text('plot')->nullable();
            $table->string('language')->nullable();
            $table->string('country')->nullable();
            $table->text('awards')->nullable();
            $table->text('poster')->nullable();
            
            // Ratings & Metadata
            $table->string('metascore', 10)->nullable();
            $table->string('imdbRating', 10)->nullable();
            $table->string('imdbVotes', 30)->nullable();
            $table->string('imdbID', 30)->unique();
            $table->string('type', 20)->nullable();
            $table->string('box_office')->nullable();
            $table->string('production')->nullable();
            
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
        Schema::drop('movies_table');
    }

}