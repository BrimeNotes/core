<?php

namespace Brime\Models;

use Brime\Core\DatabaseFactory;

class UserProperties
{
    private $database;

    public function __construct()
    {
        $this->database = DatabaseFactory::getFactory()->getConnection();
    }

    public function getUserValue($userId, $key, $default='')
    {
        $query = $this->database->prepare("SELECT propertyvalue FROM user_properties WHERE userid = :userid AND propertykey = :propertykey");
        $query->execute(array(
            ':userid' => $userId,
            ':propertykey' => $key
        ));

        if ($query->rowCount() != 1) {
            return $default;
        }
        return $query->fetch();
    }

    public function setUserValue($userId, $key, $value)
    {
        $query = $this->database->prepare("INSERT INTO user_properties(userid, propertykey, propertyvalue) 
                                           VALUES(:userid, :propertykey, :propertyvalue)");
        $query->execute(array(
            ':userid' => $userId,
            ':propertykey' => $key,
            ':propertyvalue' => $value
        ));

        if ($query->rowCount() != 1) {
            return false;
        }
        return true;
    }

    public function deleteUserValue($userId, $key)
    {
        if (!$this->userKeyExists($userId, $key)) {
            return false;
        }

        $query = $this->database->prepare("DELETE FROM user_properties WHERE userid = :userid AND propertykey = :propertykey");
        $query->execute(array(
            ':userid' => $userId,
            ':propertykey' => $key,
        ));
        return true;
    }

    public function getUserKeys($userId)
    {
        $query = $this->database->prepare("SELECT propertyvalue FROM user_properties WHERE userid = :userid");
        $query->execute(array(
            ':userid' => $userId,
        ));

        if ($query->rowCount() == 0) {
            return '';
        }
        return $query->fetchAll();
    }

    public function deleteAllUserValues($userId)
    {
        $query = $this->database->prepare("DELETE FROM user_properties WHERE userid = :userid");
        $query->execute(array(
            ':userid' => $userId,
        ));
        return true;
    }

    public function userKeyExists($userId, $key)
    {
        if ($this->getUserValue($userId, $key, null) === null) {
            return false;
        }
        return true;
    }
}