<?php

namespace Brime\Core\Framework;

class Model
{
    private static $models;
    private $list = [
        'Group',
        'Notes',
        'User',
        'UserManager',
        'UserProperties'
    ];
    private $namespace = 'Brime\Models\\';

    public function __construct()
    {
        foreach ($this->list as $model) {
            $model = $this->namespace . $model;
            self::$models[$model] = new $model;
        }
        /*echo '<pre>';
        print_r(self::$models);
        print_r($this->get('xyz'));
        die();*/
    }

    public function get($model)
    {
        if (!in_array($model, $this->list)) {
            return false;
        }

        $model = $this->namespace . $model;
        if (!self::$models[$model]) {
            self::$models[$model] = new $model;
        }
        return self::$models[$model];
    }
}