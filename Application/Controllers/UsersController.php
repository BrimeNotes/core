<?php

namespace Brime\Controllers;

use Brime\Core\Controller;
use Brime\Core\Framework\Helper;
use Brime\Core\Framework\Model;

class UsersController extends Controller
{
    /**
     * @var \Brime\Models\User
     */
    private $user;

    public function __construct(Model $model, Helper $helper)
    {
        $this->user = $model->get('User');

        parent::__construct($model, $helper);
    }

    public function get($id='') {}

    public function login()
    {

    }

    public function changePassword()
    {

    }
}