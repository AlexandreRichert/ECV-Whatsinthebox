<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Select extends Component
{
    public $options;
    public $selected;
    public $id;
    public $onChange;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($options = [], $selected = null, $id = null, $onChange = null)
    {
        $this->options = $options;
        $this->selected = $selected;
        $this->id = $id;
        $this->onChange = $onChange;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.select');
    }
}
