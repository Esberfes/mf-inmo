<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Dropdown extends Component
{

    public $name;

    public $linkList;

    public $linkCreate;

    /***
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $linkList, $linkCreate)
    {
        $this->name = $name;

        $this->linkList = $linkList;
        $this->linkCreate = $linkCreate;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dropdown');
    }
}
