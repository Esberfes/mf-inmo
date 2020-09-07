<?php

namespace App\Http\Popos;

class UserSession
{
    public $id;
    public $ip;
    public $date;
    public $url;

    public function __construct($id, $ip, $date)
    {
        $this->id = $id;
        $this->ip = $ip;
        $this->date = $date;
    }
}
