<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieDetailsSeeder extends Seeder
{
    public function row_id() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function run()
    {
        $apiKey = 'ea3e6d9d'; // Use your OMDb API Key
        
        // Get all imdbIDs from your first table (movies_list)
        $movies = DB::table('movies_list')->get();

        foreach ($movies as $movie) {
            $this->command->info("Fetching full details for: {$movie->title}");

            // Note the use of 'i=' instead of 's=' for ID lookup
            $url = "http://www.omdbapi.com/?i={$movie->imdbID}&apikey={$apiKey}&plot=full";
            
            $response = @file_get_contents($url);
            if ($response === false) continue;

            $details = json_decode($response, true);

            if (isset($details['Response']) && $details['Response'] === 'True') {
                
                // Avoid duplicates in movies_table
                $exists = DB::table('movies_table')->where('imdbID', $details['imdbID'])->exists();

                if (!$exists) {
                    DB::table('movies_table')->insert([
                        'id'          => $this->row_id(),
                        'title'       => $details['Title'],
                        'year'        => $details['Year'],
                        'rated'       => $details['Rated'],
                        'released'    => $details['Released'],
                        'runtime'     => $details['Runtime'],
                        'genre'       => $details['Genre'],
                        'director'    => $details['Director'],
                        'writer'      => $details['Writer'],
                        'actors'      => $details['Actors'],
                        'plot'        => $details['Plot'],
                        'language'    => $details['Language'],
                        'country'     => $details['Country'],
                        'awards'      => $details['Awards'],
                        'poster'      => $details['Poster'],
                        'metascore'   => $details['Metascore'],
                        'imdbRating'  => $details['imdbRating'],
                        'imdbVotes'   => $details['imdbVotes'],
                        'imdbID'      => $details['imdbID'],
                        'type'        => $details['Type'],
                        'box_office'  => isset($details['BoxOffice']) ? $details['BoxOffice'] : null,
                        'production'  => isset($details['Production']) ? $details['Production'] : null,
                        'created_at'  => date('Y-m-d H:i:s'),
                        'updated_at'  => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            // Be gentle with the API rate limits
            usleep(200000); 
        }
    }
}