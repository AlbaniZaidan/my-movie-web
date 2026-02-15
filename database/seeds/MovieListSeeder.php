<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieListSeeder extends Seeder
{
    /**
     * Helper to generate UUIDs for PHP 5.6
     */
    public function row_id() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function run()
    {
        $apiKey = 'ea3e6d9d';
        
        // The list of popular franchises you mentioned
        $franchises = [
            'Star Wars', 
            'Star Trek', 
            'Avengers', 
            'Batman', 
            'Superman', 
            'Iron Man', 
            'Captain America'
        ];

        foreach ($franchises as $searchQuery) {
            $this->command->info("Fetching movies for: $searchQuery...");

            $url = "http://www.omdbapi.com/?s=" . urlencode($searchQuery) . "&apikey=" . $apiKey . "&type=movie";
            
            // Fetch data
            $response = @file_get_contents($url);
            
            if ($response === false) {
                $this->command->error("Could not connect to OMDb for $searchQuery.");
                continue;
            }

            $data = json_decode($response, true);

            if (isset($data['Search'])) {
                foreach ($data['Search'] as $movie) {
                    // Check for duplicates before inserting
                    $exists = DB::table('movies_list')->where('imdbID', $movie['imdbID'])->exists();

                    if (!$exists) {
                        DB::table('movies_list')->insert([
                            'id'         => $this->row_id(),
                            'title'      => $movie['Title'],
                            'year'       => $movie['Year'],
                            'imdbID'     => $movie['imdbID'],
                            'type'       => $movie['Type'],
                            'poster'     => $movie['Poster'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
                $this->command->comment("Added " . count($data['Search']) . " movies for $searchQuery.");
            } else {
                $this->command->warn("No results found for $searchQuery.");
            }

            // Optional: sleep for a second to be nice to the API
            sleep(1);
        }
    }
}