<?php

namespace Brime\Controllers;

use Brime\Core\Controller;

use Brime\Core\Http;
use Brime\Models\User;

class AdminController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
        parent::__construct();
    }

    public function registeruser()
    {

        if (!$this->user->isAdmin())
        {
            $this->View->renderJSON(array(
                "error" => "Unauthorised access"
            ),
            Http::STATUS_UNAUTHORIZED
            );
        }
    }

    public function changeusergroup() {}
    public function deleteuser() {}
    public function changeuserinfo() {}


}