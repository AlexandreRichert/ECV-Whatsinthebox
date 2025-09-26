<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public static function getCurlData($url)
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

    public function getTopRatedShows100()
    {
        $all_shows = [];
        for ($page = 1; $page <= 5; $page++) {
            $shows_data = self::getCurlData("/tv/top_rated?language=fr-FR&page=" . $page);
            if ($shows_data && isset($shows_data->results)) {
                $all_shows = array_merge($all_shows, $shows_data->results);
            }
        }
        usort($all_shows, function ($a, $b) {
            return $b->vote_average <=> $a->vote_average;
        });
        foreach ($all_shows as $i => $show) {
            $show->rank = $i + 1;
        }
        return $all_shows;
    }
}
