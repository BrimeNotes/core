<?php

namespace Brime\Controllers;

use Brime\Core\Controller;
use Brime\Core\Http;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->View->renderJSON(array(
            "app_name" => "Brime",
            "authors" => [
                1 => [
                    "name" => "Ujjwal Bhardwaj"
                ]
            ]
        ), Http::STATUS_OK);
    }
}