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

namespace Brime\Core\Helpers;

class DatabaseFactory
{
    private static $factory;
    private $database;
    private $Config;

    public function __construct()
    {
        $this->Config = new Config();
    }

    public static function getFactory()
    {
        if (!self::$factory) {
            self::$factory = new DatabaseFactory();
        }
        return self::$factory;
    }

    public function getConnection()
    {
        if (!$this->database) {
            try {
                $options = array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING);
                $this->database = new \PDO(
                    $this->Config->get('DB_TYPE') . ':host=' . $this->Config->get('DB_HOST') . ';dbname=' .
                    $this->Config->get('DB_NAME') . ';port=' . $this->Config->get('DB_PORT') . ';charset=' . $this->Config->get('DB_CHARSET'),
                    $this->Config->get('DB_USER'), $this->Config->get('DB_PASS'), $options
                );
                $this->database->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            } catch (\PDOException $e) {
                echo 'Database connection can not be established. Please try again later.' . '<br>';
                echo 'Error code: ' . $e->getCode();
                exit;
            }
        }
        return $this->database;
    }
}