<?php

namespace Brime\Controllers;

use Brime\Core\Controller;
use Brime\Core\Framework\Helper;
use Brime\Core\Framework\Model;
use Brime\Core\Helpers\Http;

class IndexController extends Controller
{
    public function __construct(Model $model, Helper $helper)
    {
        parent::__construct($model, $helper);
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

    public function hello($world)
    {
        $this->View->renderJSON(array(
            "app_name" => "Brime",
            "authors" => [
                1 => [
                    "name" => $world
                ]
            ]
        ), Http::STATUS_OK);
    }
}