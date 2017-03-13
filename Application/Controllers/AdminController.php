<?php

namespace Brime\Controllers;

use Brime\Core\Controller;

use Brime\Core\Http;
use Brime\Core\Request;
use Brime\Models\User;
use Brime\Models\UserManager;

class AdminController extends Controller
{
    private $user;
    private $userManager;

    private $Request;

    public function __construct()
    {
        $this->user = new User();
        $this->userManager = new UserManager();

        $this->Request = new Request();
        parent::__construct();
    }

    public function registeruser()
    {
        if (!$this->Request->isPost()) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Unauthorised access"
                ],
                Http::STATUS_UNAUTHORIZED
            );
            return;
        }

        $admin = $this->Request->post('admin');
        $userId = $this->Request->post('uid');
        $userPassword = $this->Request->post('password');

        if (!$this->userManager->isAdmin($admin))
        {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Unauthorised access"
                ],
                Http::STATUS_UNAUTHORIZED
            );
            return;
        }

        if (!$this->userManager->validateUserId($userId)) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Invalid username"
                ],
                Http::STATUS_BAD_REQUEST
            );
        }

        if ($this->userManager->userExists($userId)) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "User already exists"
                ],
                Http::STATUS_CONFLICT
            );
        }

        if ($this->user->create($userId, $userPassword)) {
            $this->View->renderJSON(
                [
                    "status" => "success",
                    "message" => "User created successfully"
                ],
                Http::STATUS_CREATED
            );
            return;
        }
    }

    public function changeusergroup() {}

    public function deleteuser()
    {
        if (!$this->Request->isPost()) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Unauthorised access"
                ],
                Http::STATUS_UNAUTHORIZED
            );
            return;
        }

        $admin = $this->Request->post('admin');
        $userId = $this->Request->post('userid');

        if (!$this->userManager->isAdmin($admin))
        {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Unauthorised access"
                ],
                Http::STATUS_UNAUTHORIZED
            );
            return;
        }

        if (!$this->userManager->userExists($userId)) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "User does not exist"
                ],
                Http::STATUS_CONFLICT
            );
            return;
        }

        $this->user->delete($userId);
    }

    public function changeuserinfo() {}


}