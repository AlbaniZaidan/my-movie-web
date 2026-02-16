<?php namespace App\Http\Controllers;

use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Movies;
use App\Favorites;
use Illuminate\Http\Request;
use stdClass; // Standard Class for creating temporary movie objects


class MoviesController extends Controller {

    protected $apiKey = 'ea3e6d9d';

    public function index(Request $request)
    {
        $search = $request->input('search');

        $favorites = [];
        $favoritesMovies = collect();

        if (Auth::check()) {
            $favorites = Favorites::where('user_id', Auth::id())
                            ->lists('movie_id');

            if (!empty($favorites)) {
                $favoritesMovies = Movies::whereIn('imdbID', $favorites)->get()->keyBy('imdbID');
            }
        }


        $movies = Movies::all();
        return view('movies.index', compact('movies', 'favoritesMovies'));
    }

    /**
     * Show Details (Lazy Load: Check DB -> Fetch API -> Save -> Show)
     */
public function movieDetails($id) {
        
        $movie = Movies::where('imdbID', $id)->first();

        if (!$movie) {
            $url = "http://www.omdbapi.com/?apikey={$this->apiKey}&i={$id}";
            $response = @file_get_contents($url);
            $details = json_decode($response, true);

            if (isset($details['Response']) && $details['Response'] === 'True') {
                
                $movie = new Movies();
                $movie->id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
                $movie->imdbID = $details['imdbID'];
                $movie->title = $details['Title'];
                $movie->year = $details['Year'];
                $movie->rated = $details['Rated'];
                $movie->released = $details['Released'];
                $movie->runtime = $details['Runtime'];
                $movie->genre = $details['Genre'];
                $movie->director = $details['Director'];
                $movie->writer = $details['Writer'];
                $movie->actors = $details['Actors'];
                $movie->plot = $details['Plot'];
                $movie->language = $details['Language'];
                $movie->country = $details['Country'];
                $movie->awards = $details['Awards'];
                $movie->poster = $details['Poster'];
                $movie->metascore = $details['Metascore'];
                $movie->imdbRating = $details['imdbRating'];
                $movie->imdbVotes = $details['imdbVotes'];
                $movie->type = $details['Type'];
                
                // Handle optional fields
                $movie->box_office = isset($details['BoxOffice']) ? $details['BoxOffice'] : 'N/A';
                $movie->production = isset($details['Production']) ? $details['Production'] : 'N/A';

                $movie->save();
            } else {
                abort(404);
            }
        }

        
        $isFavorited = false; 
        
        if (Auth::check()) {
            $isFavorited = Favorites::where('user_id', Auth::id())
                                    ->where('movie_id', $movie->imdbID)
                                    ->exists();
        }

        return view('movies.details', compact('movie', 'isFavorited'));
    }

	public function liveSearch(Request $request)
	{
		$search = $request->input('query');

		if (empty($search)) {
			return response()->json([]);
		}

		// Fetch just the list from API
		$url = "http://www.omdbapi.com/?apikey={$this->apiKey}&s=" . urlencode($search) . "&type=movie";
		$response = @file_get_contents($url);
		$data = json_decode($response, true);

		// Return just the 'Search' array as JSON
		return response()->json(isset($data['Search']) ? $data['Search'] : []);
	}

    public function addToFavorites(Request $request)
    {
        $id = $request->input('movie_id');
        $exists = Favorites::where('movie_id', $id)->where('user_id', Auth::id())->exists();
            
        if (!$exists) {
            $favorite = new Favorites();
            $favorite->movie_id = $id;
            $favorite->user_id = Auth::id();
            $favorite->save();
        }

        return response()->json(['message' => 'Added to favorites']);
    }

    public function removeFromFavorites($id)
    {
        Favorites::where('movie_id', $id)->where('user_id', auth()->id())->delete();
        return response()->json(['message' => 'Removed from favorites']);
    }
}