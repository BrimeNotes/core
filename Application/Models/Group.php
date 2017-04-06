<?php

namespace Brime\Models;

use Brime\Core\Helpers\DatabaseFactory;

class Group
{
    private $database;

    public function __construct()
    {
        $this->database = DatabaseFactory::getFactory()->getConnection();
    }

    public function getGroup($groupId) {}

    public function deleteGroup($groupId)
    {
        $query = $this->database->prepare("DELETE FROM groups WHERE groupid = :groupid");
        $query->execute(array(
            ':groupid' => $groupId
        ));
        return true;
    }

    public function addGroup($groupId)
    {
        $query = $this->database->prepare("INSERT INTO groups(groupid) VALUES(:groupid)");
        $query->execute(array(
            ':groupid' => $groupId
        ));

        if($query->rowCount() != 1) {
            return false;
        }
        return true;
    }

    public function addUserToGroup($userId, $groupId)
    {
        $query = $this->database->prepare("INSERT INTO group_users(groupid, userid) VALUES(:groupid, :userid)");
        $query->execute(array(
            ':groupid' => $groupId,
            ':userid' => $userId
        ));

        if($query->rowCount() != 1) {
            return false;
        }
        return true;
    }

    public function removeUserFromGroup($userId, $groupId)
    {
        $query = $this->database->prepare("DELETE FROM group_users WHERE groupid = :groupid AND userid = :userid");
        $query->execute(array(
            ':groupid' => $groupId,
            ':userid' => $userId
        ));
    }

    public function getAllGroups()
    {
        $query = $this->database->prepare("SELECT groupid FROM groups");
        $query->execute();

        if ($query->rowCount() == 0) {
            return '';
        }
        return $query->fetchAll();
    }
    public function getAllGroupUsers($groupId)
    {
        $query = $this->database->prepare("SELECT userid FROM group_users WHERE groupid = :groupid");
        $query->execute(array(
            ':groupid' => $groupId
        ));

        if ($query->rowCount() == 0) {
            return '';
        }
        return $query->fetchAll();
    }

    public function deleteAllGroupUsers($groupId)
    {
        $query = $this->database->prepare("DELETE FROM group_users WHERE groupid = :groupid");
        $query->execute(array(
            ':groupid' => $groupId,
        ));
    }
}