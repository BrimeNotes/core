<?php

namespace Brime\Core\Framework;

class Route
{
    public $route;
    private $now;
    private $paraString;
    public function __construct()
    {
        if (!$this->route) {

            $route_file = '../Application/routes.php';

            if (file_exists($route_file)) {
                $this->route = require $route_file;
            }
        }
    }

    public function exists($url)
    {
        if (isset($this->route[$url])) {
            $this->now = $this->route[$url];
            return $this->route[$url];
        } else {
           foreach ($this->route as $uri => $route) {
               if (strpos($uri, '{') !== false) {
                   $position = strpos($uri, '{');
                    if (substr($url, 0, $position) == substr($uri, 0, $position)) {
                        $this->paraString = substr($url, $position);
                        $this->now = $route;
                        return $uri;
                    }
               }
           }
        }
        return false;
    }

    public function getController() {
        $controller = explode('#', $this->now[0])[0];
        return $controller;
    }

    public function getMethod() {
        $method = explode('#', $this->now[0])[1];
        return $method;
    }

    public function getParams() {
        return explode('/', $this->paraString);
    }

    public function getRequestMethod() {
        return strtoupper($this->now[1]);
    }


}