<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoritesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('favorites_table', function(Blueprint $table)
		{
			$table->char('id', 36)->primary(); 
			$table->char('user_id', 36);
			$table->char('movie_id', 36);
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('movie_id')->references('imdbID')->on('movies_table')->onDelete('cascade');
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
		Schema::drop('favorites_table');
	}

}
