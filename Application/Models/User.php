<?php

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
        $query = $this->database->prepare("UPDATE users SET password = :password WHERE userid = :userid");
        $query->execute([
            ':password' => $password,
            ':userid' => $userId
        ]);

        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }
}