<?php

namespace App\Http\Popos;

class User
{
    public $id;
    public $name;
    public $order;

    public function __construct($id, $name, $order)
    {
        $this->id = $id;
        $this->name = $name;
        $this->order = $order;
    }
}
