<?php

namespace Brime\Controllers;

use Brime\Core\Controller;
use Brime\Core\Framework\Helper;
use Brime\Core\Framework\Model;
use Brime\Core\Helpers\Http;

use \Firebase\JWT\JWT;

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

    /**
     * @var \Brime\Core\Helpers\Config
     */
    private $Config;

    public function __construct(Model $model, Helper $helper)
    {
        $this->user = $model->get('User');
        $this->userManager = $model->get('UserManager');

        $this->Request = $helper->get('Request');
        $this->Config = $helper->get('Config');
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
            return false;
        }

        if (!$this->userManager->checkPassword($uid, $password)) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Incorrect password."
                ],
                Http::STATUS_BAD_REQUEST
            );
            return false;
        }

        $tokenId    = base64_encode(random_bytes(32));
        $issuedAt   = time();
        $notBefore  = $issuedAt + 10;
        $expire     = $notBefore + 7200;
        $serverName = $this->Config->get('SERVER_NAME');

        $data = [
            'iat'  => $issuedAt,
            'jti'  => $tokenId,
            'iss'  => $serverName,
            'nbf'  => $notBefore,
            'exp'  => $expire,
            'data' => [
                'id'   => $uid,
            ]
        ];
        $secretKey = base64_decode($this->Config->get('JWT_SECRET_KEY'));

        $jwt = JWT::encode(
            $data,
            $secretKey,
            $this->Config->get('JWT_ALGORITHM')
        );

        $this->View->renderJSON(
            [
                "status" => "success",
                "message" => "User logged in successfully.",
                "response" => [
                    "jwt" => $jwt
                ]
            ],
            Http::STATUS_OK
        );
        return;
    }

    public function changePassword()
    {
        $uid = $this->Request->post('username');
        $oldPassword = $this->Request->post('old_password');
        $newPassword = $this->Request->post('new_password');

        if (!$this->userManager->checkPassword($uid, $oldPassword)) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Old password does not match the existing password."
                ],
                Http::STATUS_BAD_REQUEST
            );
            return;
        }

        if (!$this->user->setPassword($uid, $newPassword)) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Failed to set new password."
                ],
                Http::STATUS_BAD_REQUEST
            );
            return;
        }

        $this->View->renderJSON(
            [
                "status" => "success",
                "message" => "Password changed successfully."
            ],
            Http::STATUS_OK
        );
        return;
    }
}