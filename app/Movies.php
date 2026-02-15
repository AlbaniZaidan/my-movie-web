<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Movies extends Model {

	protected $table = 'movies_table';

	protected $fillable = [
		'id', 'title', 'year', 'rated', 'released', 'runtime', 'genre',
		'director', 'writer', 'actors', 'plot', 'language', 'country',
		'awards', 'poster', 'metascore', 'imdbRating', 'imdbVotes',
		'imdbID', 'type', 'box_office', 'production'
	];

}
