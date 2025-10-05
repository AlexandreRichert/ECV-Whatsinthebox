<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Moviecard extends Component
{
    public $movie;
    public $showAddButton;
    public $showSeenCheckbox;
    public $rank;
    public $releaseYear;
    public $percentageAverageNote;
    /**
     * Create a new component instance.
     */
    public function __construct($movie, $showAddButton = true, $showSeenCheckbox = true, $rank = null)
    {
        $this->movie = $movie;
        $this->showAddButton = $showAddButton;
        $this->showSeenCheckbox = $showSeenCheckbox;
        $this->rank = $rank;
        $this->releaseYear = $this->releaseYear();
        $this->percentageAverageNote = $this->percentageAverageNote();
    }


    /**
     * Retourne le pourcentage de vote arrondi.
     *
     * @return int
     */
    public function percentageAverageNote()
    {
        return round(($this->movie->vote_average ?? 0) * 10) . '%';
    }

    /**
     * Retourne l'année de sortie du film.
     * 
     * @return int
     */
    public function releaseYear()
    {
        return $this->movie->release_date ? date('Y', strtotime($this->movie->release_date)) : 'N/A';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.moviecard');
    }
}
