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

class User
{
    private $database;
    private $userProperties;

    public function __construct()
    {
        $this->database = DatabaseFactory::getFactory()->getConnection();
        $this->userProperties = new UserProperties();
    }

    public function getUserIdByEmail($email)
    {
        $query = $this->database->prepare("SELECT userid FROM user_properties WHERE propertykey = 'email' AND propertyvalue = :email");
        $query->execute([
            ':email' => $email
        ]);

        if ($query->rowCount() == 1) {
            return $query->fetch();
        }
        return false;
    }

    public function create($userId, $password)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = $this->database->prepare("INSERT INTO users (userid, password) VALUES (:userid, :password)");
        $query->execute([
            ':userid' => $userId,
            ':password' => $passwordHash,
        ]);

        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    public function delete($userId)
    {
        $query = $this->database->prepare("DELETE FROM users WHERE  userid = :userid");
        $query->execute([
            ':userid' => $userId,
        ]);
    }

    public function getEMailAddress($userId)
    {
        return $this->userProperties->getUserValue($userId, 'email');
    }

    public function setEMailAddress($userId, $email)
    {
        return $this->userProperties->setUserValue($userId, 'email', $email);
    }

    public function setPassword($userId, $password)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = $this->database->prepare("UPDATE users SET password = :password WHERE userid = :userid");
        $query->execute([
            ':password' => $passwordHash,
            ':userid' => $userId
        ]);

        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }
}