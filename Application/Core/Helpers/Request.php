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

class Request
{
    public function __construct()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
    }

    private function getMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function isPost()
    {
        if ($this->getMethod() != "POST") {
            return false;
        }
        return true;
    }

    public function isGet()
    {
        if ($this->getMethod() != "GET") {
            return false;
        }
        return true;
    }

    public function post($key, $trim = false)
    {
        if (isset($_POST[$key])) {
            return ($trim) ? trim(strip_tags($_POST[$key])) : $_POST[$key];
        }
        return false;
    }

    public function get($key)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }
        return false;
    }

    function getHeader($key)
    {
        $headers = [];
        if (!function_exists('getallheaders')) {
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
        } else {
            $headers = getallheaders();
        }

        $headerValue = '';
        if ( array_key_exists($key, $headers) ) {
            $headerValue = $headers[$key];
        }
        return $headerValue;
    }
}