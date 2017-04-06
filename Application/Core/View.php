<?php

namespace Brime\Core;

use Brime\Core\Helpers\Config;

class View
{
    private $Config;

    public function __construct()
    {
        $this->Config = new Config();
    }

    public function renderJSON($data, $code)
    {
        http_response_code($code);
        header("Content-Type: application/json");
        echo json_encode($data);
    }
}