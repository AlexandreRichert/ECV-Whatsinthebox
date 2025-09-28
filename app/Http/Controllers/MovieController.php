<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
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
            $localPath = 'poster/movies/' . $movie->id . '.jpg';
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

    public function getPopularMovieData()
    {
        return ApiController::getCurlData("/movie/popular?language=fr-FR&page=1");
    }

    public function getPopular()
    {
        $movies_data = $this->getPopularMovieData();
        $popular_show = ApiController::getCurlData("/tv/popular?language=fr-FR&page=1");
        return view(
            'movies.popular',
            [
                'movies_datas' => $movies_data,
                'popular_show' => $popular_show,
            ]
        );
    }

    public function getTopRated100()
    {
        $all_movies = [];
        for ($page = 1; $page <= 5; $page++) {
            $data = ApiController::getCurlData("/movie/top_rated?language=fr-FR&page=$page");
            if (isset($data->results)) {
                $all_movies = array_merge($all_movies, $data->results);
            }
        }
        usort($all_movies, function ($a, $b) {
            return $b->vote_average <=> $a->vote_average;
        });
        foreach ($all_movies as $i => $movie) {
            $movie->rank = $i + 1;
        }
        $topShows = (new ApiController())->getTopRatedShows100();
        return view('movies.top', ['movies_datas' => $all_movies, 'shows_datas' => $topShows]);
    }

    public function getDatabaseMovies()
    {
        return Movie::all();
    }

    public function home(Request $request)
    {

        $genres = Genre::withCount(['movies', 'shows'])->get();
        $genre_id = $request->input('genre_id');

        $movies_query = Movie::with('genres')->orderBy('seen', 'asc');
        $shows_query = Show::with('genres')->orderBy('seen', 'asc');
        if ($genre_id) {
            $movies_query->whereHas('genres', function ($q) use ($genre_id) {
                $q->where('genres.id', $genre_id);
            });
            $shows_query->whereHas('genres', function ($q) use ($genre_id) {
                $q->where('genres.id', $genre_id);
            });
        }
        $movies = $movies_query->get()->map(function ($movie) {
            $movie->type = 'movie';
            return $movie;
        });
        $shows = $shows_query->get()->map(function ($show) {
            $show->type = 'show';
            return $show;
        });

        return view('welcome', [
            'movies_database' => $movies,
            'shows_database' => $shows,
            'genres' => $genres,
            'genre_id' => $genre_id,
        ]);
    }

    public function storeMovie(Request $request)
    {
        $movie_id = $request->input('movie_id');
        if ($request->has('movie_id') && $request->input('movie_id') > 0) {
            $movie_data = ApiController::getCurlData("/movie/" . $movie_id . "?language=fr-FR");
            $credits_data = ApiController::getCurlData("/movie/" . $movie_id . "/credits?language=fr-FR");

            $movie = new Movie();
            $movie->name = $movie_data->title;
            $movie->image = $movie_data->poster_path;
            $movie->description = $movie_data->overview ?? null;
            $movie->tmdb_id = $movie_data->id ?? null;
            $movie->release_date = $movie_data->release_date ?? null;
            $movie->vote_average = $movie_data->vote_average ?? null;

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

            //5 premiers acters
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
                $path = 'poster/movies/' . $movie->id . '.jpg';
                $response = Http::get('https://image.tmdb.org/t/p/w500' . $movie_data->poster_path);
                Storage::disk('public')->put($path, $response->body());
            }
            return Redirect::back()->with('status', 'Film ajouté avec succès !');
        }
    }

    public function searchMovie(Request $request)
    {
        $query = $request->input('search');
        $movies_data = ApiController::getCurlData("/search/multi?query=" . urlencode($query) . "&include_adult=false&language=fr-FR&page=1");

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

    public function setMovieSeen(Request $request, $id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['success' => false, 'message' => 'Film introuvable'], 404);
        }
        $movie->seen = $request->input('seen') ? 1 : 0;
        $movie->save();
        return response()->json(['success' => true, 'seen' => $movie->seen]);
    }

    public function deleteMovie(Request $request)
    {
        if ($request->has('id_movie')) {
            $movie = Movie::find($request->input('id_movie'));
            if ($movie) {
                $movie->actors()->detach();
                $movie->genres()->detach();
                $movie->delete();
            }
        }
        return Redirect::route('home')->with('status', 'Film supprimé avec succès !');
    }
}
