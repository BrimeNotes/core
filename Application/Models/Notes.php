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