<?php

namespace Brime\Controllers;

use Brime\Core\Controller;
use Brime\Core\Framework\Helper;
use Brime\Core\Framework\Model;
use Brime\Core\Helpers\Http;

class UsersController extends Controller
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

    public function get($id='') {}

    public function login()
    {
        $uid = $this->Request->post('uid');
        $password = $this->Request->post('password');

        if (!$this->userManager->userExists($uid)) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "User does not exist."
                ],
                Http::STATUS_BAD_REQUEST
            );
            return;
        }

        if (!$this->userManager->checkPassword($uid, $password)) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Incorrect password."
                ],
                Http::STATUS_BAD_REQUEST
            );
            return;
        }

        $this->View->renderJSON(
            [
                "status" => "success",
                "message" => "User logged in successfully."
            ],
            Http::STATUS_OK
        );
        return;
    }

    public function changePassword()
    {

    }
}