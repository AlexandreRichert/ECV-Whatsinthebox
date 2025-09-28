<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\Genre;
use App\Models\Actor;
use App\Models\Episode;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ShowController extends Controller
{

    public function show($id)
    {
        $show = Show::with(['genres', 'actors', 'seasons', 'episodes'])->findOrFail($id);
        $posterPath = null;
        if ($show->image) {
            $localPath = 'poster/shows/' . $show->id . '.jpg';
            if (Storage::disk('public')->exists($localPath)) {
                $posterPath = '/storage/' . $localPath;
            } else {
                $posterPath = 'https://image.tmdb.org/t/p/w500' . $show->image;
            }
        }
        $mainActors = $show->actors->take(5);
        $seasons = $show->seasons;
        $episodes = $show->episodes;
        $percentageSeen = $this->percentageSeen($show);
        return view('shows.show', [
            'show' => $show,
            'posterPath' => $posterPath,
            'mainActors' => $mainActors,
            'seasons' => $seasons,
            'episodes' => $episodes,
            'percentageSeen' => $percentageSeen,
        ]);
    }

    public function storeShow(Request $request)
    {
        try {
            $show_id = $request->input('show_id');
            if ($request->has('show_id') && $request->input('show_id') > 0) {
                $show_data = ApiController::getCurlData("/tv/" . $show_id . "?language=fr-FR");
                $credits_data = ApiController::getCurlData("/tv/" . $show_id . "/credits?language=fr-FR");

                // Récupération du nombre de saisons
                $numberOfSeasons = $show_data->number_of_seasons ?? 0;
                $seasons = [];
                for ($i = 1; $i <= $numberOfSeasons; $i++) {
                    $seasonData = ApiController::getCurlData("/tv/" . $show_id . "/season/" . $i . "?language=fr-FR");
                    if ($seasonData) {
                        $seasons[] = $seasonData;
                    }
                }

                $show = new Show();
                $show->name = $show_data->name;
                $show->image = !empty($show_data->poster_path) ? $show_data->poster_path : null;
                $show->description = $show_data->overview ?? null;
                $show->tmdb_id = $show_data->id ?? null;
                $show->vote_average = $show_data->vote_average ?? null;
                $show->first_air_date = $show_data->first_air_date ?? null;


                if (!Show::where('tmdb_id', $show->tmdb_id)->exists()) {
                    $show->save();
                    // Enregistre l'image APRES avoir l'id
                    if (isset($show_data->poster_path)) {
                        $path = 'poster/shows/' . $show->id . '.jpg';
                        $response = Http::get('https://image.tmdb.org/t/p/w500' . $show_data->poster_path);
                        Storage::disk('public')->put($path, $response->body());
                    }
                    // Ajout des saisons dans la table seasons
                    foreach ($seasons as $seasonData) {
                        $season = Season::create([
                            'name' => $seasonData->name ?? ($seasonData->season_number ? 'Saison ' . $seasonData->season_number : 'Saison inconnue'),
                            'show_id' => $show->id,
                            'season_number' => $seasonData->season_number ?? null,
                        ]);

                        if (isset($seasonData->episodes) && is_array($seasonData->episodes)) {
                            foreach ($seasonData->episodes as $episodeData) {
                                try {
                                    Episode::create([
                                        'name' => $episodeData->name ?? ($episodeData->episode_number ? 'Épisode ' . $episodeData->episode_number : 'Épisode inconnu'),
                                        'season_id' => $season->id,
                                        'tmdb_id' => $episodeData->id ?? null,
                                        'description' => $episodeData->overview ?? null,
                                        'season_number' => $episodeData->season_number ?? null,
                                        'episode_number' => $episodeData->episode_number ?? null,
                                        'image' => $episodeData->still_path ?? null,
                                        'vote_average' => $episodeData->vote_average ?? null,
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('episode_create_error', ['error' => $e->getMessage()]);
                                }
                            }
                        }
                    }
                } else {
                    return Redirect::back()->with('alert', 'La série est déjà enregistrée dans votre liste.');
                }
                if (isset($show_data->genres) && is_array($show_data->genres)) {
                    $genreIds = [];
                    foreach ($show_data->genres as $tmdb_genre) {
                        $genre = Genre::firstOrCreate(
                            ['id_genre_tmdb' => $tmdb_genre->id],
                            ['name' => $tmdb_genre->name]
                        );
                        $genreIds[] = $genre->id;
                    }
                    $show->genres()->attach($genreIds);
                }

                if (isset($credits_data->cast) && is_array($credits_data->cast)) {
                    $actorIds = [];
                    foreach (array_slice($credits_data->cast, 0, 5) as $actor) {
                        $actorModel = Actor::firstOrCreate(
                            ['tmdb_id' => $actor->id],
                            ['name' => $actor->name, 'photo' => $actor->profile_path]
                        );
                        $actorIds[] = $actorModel->id;
                    }
                    $show->actors()->attach($actorIds);
                }

                return Redirect::back()->with('status', 'Série ajoutée avec succès !');
            }
        } catch (\Exception $e) {
            return Redirect::back()->with('error', "Erreur lors de l'enregistrement de la série : " . $e->getMessage());
        }
    }

    public function updateEpisodeSeen(Request $request, Episode $episode)
    {
        $episode->seen = $request->input('seen') ? 1 : 0;
        $episode->save();

        $show = $episode->season->show;
        $percentage = $this->percentageSeen($show);

        return response()->json([
            'success' => true,
            'seen' => $episode->seen,
            'percentage' => $percentage,
        ]);
    }

    public function percentageSeen(Show $show)
    {
        $totalEpisodes = $show->episodes()->count();
        if ($totalEpisodes === 0) {
            return 0;
        }
        $seenEpisodes = $show->episodes()->where('seen', 1)->count();
        return ($seenEpisodes / $totalEpisodes) * 100;
    }

    public function deleteShow(Request $request)
    {
        if ($request->has('id_show')) {
            $show = Show::find($request->input('id_show'));
            if ($show) {
                $show->genres()->detach();
                $show->delete();
            }
        }
        return Redirect::route('home')->with('status', 'Série supprimée avec succès !');
    }
}
