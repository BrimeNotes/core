<?php

namespace Brime\Core;

class Controller
{
    public $View;
    protected $Redirect;
    protected $Config;

    public function __construct()
    {
        $this->Redirect = new Redirect();
        $this->Config = new Config();
        $this->View = new View();
    }
}