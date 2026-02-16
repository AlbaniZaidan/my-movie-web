<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorites extends Model {

	protected $table = 'favorites_table';

	protected $fillable = ['movie_id', 'user_id'];

}
