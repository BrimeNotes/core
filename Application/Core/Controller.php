<?php

namespace Brime\Core;

use Brime\Core\Framework\Helper;
use Brime\Core\Framework\Model;

class Controller
{
    public $View;

    public function __construct(Model $model, Helper $helper)
    {
        $this->View = new View();
    }
}