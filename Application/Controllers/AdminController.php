<?php

namespace Brime\Controllers;

use Brime\Core\Controller;

use Brime\Core\Framework\Helper;
use Brime\Core\Framework\Model;
use Brime\Core\Helpers\Http;

class AdminController extends Controller
{
    /**
     * @var \Brime\Models\User
     */
    private $user;

    /**
     * @var \Brime\Models\UserManager
     */
    private $userManager;

    /**
     * @var \Brime\Core\Helpers\Request
     */
    private $Request;

    public function __construct(Model $model, Helper $helper)
    {
        $this->user = $model->get('User');
        $this->userManager = $model->get('UserManager');

        $this->Request = $helper->get('Request');
        parent::__construct($model, $helper);
    }

    public function registerUser()
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

    public function changeUserGroup() {}

    public function deleteUser()
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

        if (!$this->userManager->isAdmin($admin)) {
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

    public function changeUserInfo() {}


}