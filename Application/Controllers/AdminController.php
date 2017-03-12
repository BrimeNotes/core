<?php

namespace Brime\Controllers;

use Brime\Core\Controller;

use Brime\Core\Http;
use Brime\Models\User;
use Brime\Models\UserManager;

class AdminController extends Controller
{
    private $user;
    private $userManager;

    public function __construct()
    {
        $this->user = new User();
        $this->userManager = new UserManager();
        parent::__construct();
    }

    public function registeruser()
    {

        if (!$this->userManager->isAdmin())
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