<?php

namespace Brime\Controllers;

use Brime\Core\Controller;
use Brime\Core\Framework\Helper;
use Brime\Core\Framework\Model;
use Brime\Core\Helpers\Http;

use Carbon\Carbon;

class LostController extends Controller
{
    /**
     * @var \Brime\Models\User
     */
    private $user;

    /**
     * @var \Brime\Models\UserProperties
     */
    private $userProperties;

    /**
     * @var \Brime\Core\Helpers\Config
     */
    private $Config;

    /**
     * @var \Brime\Core\Helpers\Mailer
     */
    private $Mailer;

    /**
     * @var \Brime\Core\Helpers\Random
     */
    private $Random;

    /**
     * @var \Brime\Core\Helpers\Request
     */
    private $Request;


    public function __construct(Model $model, Helper $helper)
    {
        $this->user = $model->get('User');
        $this->userProperties = $model->get('UserProperties');

        $this->Config = $helper->get('Config');
        $this->Mailer = $helper->get('Mailer');
        $this->Random = $helper->get('Random');
        $this->Request = $helper->get('Request');

        parent::__construct($model, $helper);
    }

    private function checkPasswordResetToken($token, $userid)
    {
        $splittedToken = explode(':', $this->userProperties->getUserValue($userid, 'lostpassword'));
        if (count($splittedToken) !== 2) {
            $this->userProperties->deleteUserValue($userid, 'lostpassword');
            return false;
        }

        if ($splittedToken[0] < (Carbon::now()->timestamp - 60 * 60 * 12)) {
            $this->userProperties->deleteUserValue($userid, 'lostpassword');
            return false;
        }

        if (!hash_equals($splittedToken[1], $token)) {
            $this->userProperties->deleteUserValue($userid, 'lostpassword');
            return false;
        }
        return true;
    }

    /**
     * Send Reset Password Email
     */
    public function forgotPassword()
    {
        $email = $this->Request->post('email');

        $user = $this->user->getUserIdByEmail($email);

        if (!$user) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "User does not exist"
                ],
                Http::STATUS_BAD_REQUEST
            );
            return;
        }

        $mail = $this->Mailer->setup();

        $token = $this->userProperties->getUserValue($user->userid, 'lostpassword');
        if ($token !== '') {
            $splittedToken = explode(':', $token);
            if ((count($splittedToken)) === 2 && $splittedToken[0] > (Carbon::now()->timestamp - 60 * 5)) {
                $this->View->renderJSON(
                    [
                        "status" => "error",
                        "message" => "The email is not sent because a password reset email was sent recently.",
                    ],
                    Http::STATUS_BAD_REQUEST
                );
                return;
            }
        }

        $token = $this->Random->generateString(21, '0123456789'. 'abcdefghijklmnopqrstuvwxyz'. 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');

        $this->userProperties->setUserValue($user->userid, 'lostpassword', Carbon::now()->timestamp . ':' . $token);

        $mail->setFrom('contact@ujjwalbhardwaj.me', 'Ujjwal Bhardwaj');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Reset Password";
        $mail->Body = '<html>
                        <body>
                            <p>Please click on the link below to change password:</p>
                            <p><a href = "http://www.brime.ml/user/password/change/' . $token . '/' . $user->userid . '">Reset</a></p>
                        </body >
                    </html >';
        $mail->AltBody = 'Click on the link to change password. http://www.brime.ml/user/password/change/' . $token . '/' . $user->userid;
        $mail->send();

        $this->View->renderJSON(
            [
                "status" => "success",
                "message" => "Mail Sent Successfully"
            ],
            Http::STATUS_OK
        );
    }

    /**
     * Check token from URL
     * @param $token
     * @param $userid
     */
    public function changePassword($token, $userid)
    {
        if (!$this->checkPasswordResetToken($token, $userid)) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Invalid Password Reset Token"
                ],
                Http::STATUS_BAD_REQUEST
            );
            return;
        }

        $this->View->renderJSON(
            [
                "status" => "success",
                "message" => "Valid token"
            ],
            Http::STATUS_OK
        );
        return;
    }

    public function setPassword()
    {
        $userid = $this->Request->post('userid');
        $password = $this->Request->post('password');
        $token = $this->Request->post('token');

        if (!$this->checkPasswordResetToken($token, $userid)) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Invalid Password Reset Token"
                ],
                Http::STATUS_BAD_REQUEST
            );
            return;
        }

        if (!$this->user->setPassword($userid, $password)) {
            $this->View->renderJSON(
                [
                    "status" => "error",
                    "message" => "Failed to set password."
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
