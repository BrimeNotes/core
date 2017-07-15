<?php
/**
 * @author Ujjwal Bhardwaj <ujjwalb1996@gmail.com>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

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