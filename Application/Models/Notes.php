<?php

namespace Brime\Models;

use Brime\Core\Helpers\DatabaseFactory;

class Notes
{
    public function addNote($title, $content, $label, $userId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("INSERT INTO notes(id, title, content, label, userid, created_at, updated_at) VALUES (NULL, :title, :content, :label, :userid, NOW(),  NOW())");
        $query->execute([
            ':title' => $title,
            ':content' => $content,
            ':label' => $label,
            ':userid' => $userId
        ]);

        if ($query->rowCount() === 1) {
            return true;
        }

        return false;
    }

    public function editNote() {}
    public function deleteNote() {}

    public function getNotesByUser($userId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT * FROM notes WHERE userid = :userid");
        $query->execute([
           ':userid' => $userId
        ]);

        if ($query->rowCount() !== 0) {
            return $query->fetchAll();
        }
        return '';
    }

    public function getAllNotes()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT * FROM notes");
        $query->execute();

        if ($query->rowCount() !== 0) {
            return $query->fetchAll();
        }
        return '';
    }

    public function getSingleNote($noteId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT * FROM notes WHERE id = :noteid");
        $query->execute(array(
            ':noteid' => $noteId
        ));

        if ($query->rowCount() !== 0) {
            return $query->fetch();
        }
        return '';
    }
}