<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Show;
use App\Models\Genre;
use App\Models\Actor;
use App\Models\Director;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function show($id)
    {
        $movie = Movie::with(['genres', 'actors', 'director'])->findOrFail($id);
        $posterPath = null;
        if ($movie->image) {
            $localPath = 'poster/' . $movie->id . '.jpg';
            if (Storage::disk('public')->exists($localPath)) {
                $posterPath = '/storage/' . $localPath;
            } else {
                $posterPath = 'https://image.tmdb.org/t/p/w500' . $movie->image;
            }
        }
        $mainActors = $movie->actors->take(5);
        return view('movies.show', [
            'movie' => $movie,
            'posterPath' => $posterPath,
            'mainActors' => $mainActors,
        ]);
    }
    public function getPopularData()
    {
        return $this->getCurlData("/movie/popular?language=fr-FR&page=1");
    }

    public function getPopular()
    {
        $movies_data = $this->getPopularData();
        return view(
            'movies.popular',
            [
                'movies_datas' => $movies_data,
            ]
        );
    }

    private function getTopRatedData()
    {
        return $this->getCurlData("/movie/top_rated?language=fr-FR&page=1");
    }

    public function getTopRated100()
    {
        $all_movies = [];
        for ($page = 1; $page <= 5; $page++) {
            $data = $this->getCurlData("/movie/top_rated?language=fr-FR&page=$page");
            if (isset($data->results)) {
                $all_movies = array_merge($all_movies, $data->results);
            }
        }
        // Tri par vote_average décroissant
        usort($all_movies, function ($a, $b) {
            return $b->vote_average <=> $a->vote_average;
        });
        // Ajout du classement
        foreach ($all_movies as $i => $movie) {
            $movie->rank = $i + 1;
        }
        return view('movies.top', ['movies_datas' => $all_movies]);
    }

    public function getDatabaseMovies()
    {
        return Movie::all();
    }

    public function home(Request $request)
    {

        $genres = Genre::withCount('movies')->get();

        $genre_id = $request->input('genre_id');
        $movies_database = Movie::with('genres');
        if ($genre_id) {
            $movies_database->whereHas('genres', function ($q) use ($genre_id) {
                $q->where('genres.id', $genre_id);
            });
        }
        $movies_database = $movies_database->get();

        return view('welcome', [
            'movies_database' => $movies_database,
            'genres' => $genres,
            'genre_id' => $genre_id,
        ]);
    }

    public function storeMovie(Request $request)
    {
        $movie_id = $request->input('movie_id');
        if ($request->has('movie_id') && $request->input('movie_id') > 0) {
            $movie_data = $this->getCurlData("/movie/" . $movie_id . "?language=fr-FR");
            $credits_data = $this->getCurlData("/movie/" . $movie_id . "/credits?language=fr-FR");

            $movie = new Movie();
            $movie->name = $movie_data->title;
            $movie->image = $movie_data->poster_path;
            $movie->description = $movie_data->overview ?? null;
            $movie->tmdb_id = $movie_data->id ?? null;

            // Réalisateur
            $director = null;
            if (isset($credits_data->crew) && is_array($credits_data->crew)) {
                foreach ($credits_data->crew as $crew) {
                    if (isset($crew->job) && $crew->job === 'Director') {
                        $director = Director::firstOrCreate(
                            ['tmdb_id' => $crew->id],
                            ['name' => $crew->name, 'photo' => $crew->profile_path]
                        );
                        $movie->director_id = $director->id;
                        break;
                    }
                }
            }

            if (!Movie::where('tmdb_id', $movie->tmdb_id)->exists()) {
                $movie->save();
            } else {
                return Redirect::back()->with('alert', 'Le film est déjà enregistré dans votre liste.');
            }

            // Genres
            if (isset($movie_data->genres) && is_array($movie_data->genres)) {
                $genreIds = [];
                foreach ($movie_data->genres as $tmdb_genre) {
                    $genre = Genre::firstOrCreate(
                        ['id_genre_tmdb' => $tmdb_genre->id],
                        ['name' => $tmdb_genre->name]
                    );
                    $genreIds[] = $genre->id;
                }
                $movie->genres()->attach($genreIds);
            }

            // Ajout des 5 premiers acters
            if (isset($credits_data->cast) && is_array($credits_data->cast)) {
                $actorIds = [];
                foreach (array_slice($credits_data->cast, 0, 5) as $actor) {
                    $actorModel = Actor::firstOrCreate(
                        ['tmdb_id' => $actor->id],
                        ['name' => $actor->name, 'photo' => $actor->profile_path]
                    );
                    $actorIds[] = $actorModel->id;
                }
                $movie->actors()->attach($actorIds);
            }

            if (isset($movie_data->poster_path)) {
                $path = 'poster/' . $movie->id . '.jpg';
                $response = Http::get('https://image.tmdb.org/t/p/w500' . $movie_data->poster_path);
                Storage::disk('public')->put($path, $response->body());
            }
            return Redirect::back()->with('status', 'Film ajouté avec succès !');
        }
    }

    public function storeShow(Request $request)
    {
        try {
            $show_id = $request->input('show_id');
            if ($request->has('show_id') && $request->input('show_id') > 0) {
                $show_data = $this->getCurlData("/tv/" . $show_id . "?language=fr-FR");
                // $credits_data = $this->getCurlData("/tv/" . $show_id . "/credits?language=fr-FR");

                $show = new Show();
                $show->name = $show_data->name;
                $show->image = $show_data->poster_path;
                $show->description = $show_data->overview ?? null;
                $show->tmdb_id = $show_data->id ?? null;

                if (isset($show_data->poster_path)) {
                    $path = 'poster/' . $show->id . '.jpg';
                    $response = Http::get('https://image.tmdb.org/t/p/w500' . $show_data->poster_path);
                    Storage::disk('public')->put($path, $response->body());
                }

                if (!Show::where('tmdb_id', $show->tmdb_id)->exists()) {
                    $show->save();
                } else {
                    return Redirect::back()->with('alert', 'La série est déjà enregistrée dans votre liste.');
                }

                return Redirect::back()->with('status', 'Série ajoutée avec succès !');
            }
        } catch (\Exception $e) {
            return Redirect::back()->with('error', "Erreur lors de l'enregistrement de la série : " . $e->getMessage());
        }
    }

    public function searchMovie(Request $request)
    {
        $query = $request->input('search');
        $movies_data = $this->getCurlData("/search/multi?query=" . urlencode($query) . "&include_adult=false&language=fr-FR&page=1");

        //Vérification si c'est une série ou un film
        if (isset($movies_data->results) && is_array($movies_data->results)) {
            // Filtrer pour ignorer les résultats de type 'person'
            $movies_data->results = array_filter($movies_data->results, function ($result) {
                return isset($result->media_type) && $result->media_type !== 'person';
            });

            foreach ($movies_data->results as $result) {
                if ($result->media_type === 'movie') {
                    $result->display_name = $result->title ?? 'Film inconnu';
                } elseif ($result->media_type === 'tv') {
                    $result->display_name = $result->name ?? 'Série inconnue';
                } else {
                    $result->display_name = $result->name ?? 'Résultat inconnu';
                }
            }
        }
        return view('movies.search', [
            'movies_data' => $movies_data,
        ]);
    }

    public function setMovieSeen(Request $request)
    {
        if ($request->has('id_movie')) {
            $movie = Movie::find($request->input('id_movie'));
            $movie->seen = 1;
            $movie->save();
        }
        return back();
    }


    public function getCurlData($url)
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3" . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI2MGRhNGY2MTg4MDQwMmU0ZjkxM2VkYzI4YWNjZTU1MyIsIm5iZiI6MTc1ODAwODAzNS4xMjUsInN1YiI6IjY4YzkxMmUzMmZlM2Q3YmMwZTVkNGI1YyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ._hOj3F8P4Su8pOddba2z622SGr7meR_PoPfsYx0Y6gA",
                "accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }
}
