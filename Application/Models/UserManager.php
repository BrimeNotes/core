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

namespace Brime\Models;

use Brime\Core\Helpers\DatabaseFactory;

class UserManager
{
    private $database;

    public function __construct()
    {
        $this->database = DatabaseFactory::getFactory()->getConnection();
    }

    public function validateUserId($userId)
    {
        if (empty($userId)) {
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9]{2,64}$/', $userId)) {
            return false;
        }
        return true;
    }

    public function validateUserEmail($email)
    {
        if (empty($email)) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public function checkPassword($userId, $password)
    {
        $query = $this->database->prepare("SELECT userid, password FROM users WHERE userid = :userid ");
        $query->execute(array(
            ':userid' => $userId
        ));

        if ($query->rowCount() === 1) {
            $result = $query->fetch();

            if (password_verify($password, $result->password)) {
                return true;
            }
        }
        return false;
    }

    public function userExists($userId)
    {
        $query = $this->database->prepare("SELECT userid FROM users WHERE userid = :userid");
        $query->execute(array(
            ':userid' => $userId
        ));

        if ($query->rowCount() !== 1) {
            return false;
        }
        return true;
    }

    public function isAdmin($userId)
    {
        $query = $this->database->prepare("SELECT userid FROM group_users WHERE groupid = 'admin' AND userid = :userid");
        $query->execute(array(
            ':userid' => $userId
        ));

        if ($query->rowCount() !== 1) {
            return false;
        }
        return true;
    }
}