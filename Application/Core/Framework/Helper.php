<?php

namespace Brime\Core\Framework;

class Helper
{
    private static $helpers;
    private $list = [
        'Config',
        'DatabaseFactory',
        'Http',
        'Redirect',
        'Request'
    ];
    private $namespace = 'Brime\Core\Helpers\\';

    public function __construct()
    {
        foreach ($this->list as $helper) {
            $helper = $this->namespace . $helper;
            self::$helpers[$helper] = new $helper;
        }
        /*echo '<pre>';
        print_r(self::$helpers);
        print_r($this->get('xyz'));
        die();*/
    }

    public function get($helper)
    {
        if (!in_array($helper, $this->list)) {
            return false;
        }

        $helper = $this->namespace . $helper;
        if (!self::$helpers[$helper]) {
            self::$helpers[$helper] = new $helper;
        }
        return self::$helpers[$helper];
    }
}