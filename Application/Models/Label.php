<?php

namespace Brime\Models;

use Brime\Core\Helpers\DatabaseFactory;

class Label
{
    private $database;

    public function __construct()
    {
        $this->database = DatabaseFactory::getFactory()->getConnection();
    }

    public function get()
    {
        $query = $this->database->prepare("SELECT * FROM labels");
        $query->execute();

        if ($query->rowCount() != 0) {
            return $query->fetch();
        }
        return false;
    }

    public function create($label)
    {
        $query = $this->database->prepare("INSERT INTO labels (name) VALUES (:label)");
        $query->execute([
            ':label' => $label,
        ]);

        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    public function delete($label)
    {
        $query = $this->database->prepare("DELETE FROM labels WHERE  name = :label");
        $query->execute([
            ':label' => $label,
        ]);
    }

    public function doesLabelExist($label) {
        $query = $this->database->prepare("SELECT * FROM labels WHERE name = :label");
        $query->execute([
            ':label' => $label
        ]);

        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }
}