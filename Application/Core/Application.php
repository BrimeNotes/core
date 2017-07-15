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

namespace Brime\Core;

use Brime\Core\Framework\Helper;
use Brime\Core\Framework\Model;
use Brime\Core\Framework\Route;

class Application
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var Helper
     */
    private $helper;

    protected $controller;
    protected $method;
    protected $params = array();
    private $controllerName;
    protected $route;

    /**
     * @var \Brime\Core\Helpers\Config
     */
    private $Config;

    public function __construct()
    {

        $this->helper = new Helper();
        $this->model = new Model();
        $this->route = new Route();
        $this->Config = $this->helper->get('Config');

        $this->parseURL();

        if (!$this->controllerName) {
            $this->controllerName = $this->Config->get('DEFAULT_CONTROLLER');
        }

        if (!$this->method OR (strlen($this->method) == 0)) {
            $this->method = $this->Config->get('DEFAULT_ACTION');
        }

        $this->controllerName = ucwords($this->controllerName) . 'Controller';

        if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
            http_response_code(200);
            return;
        }

        if ($this->route->getRequestMethod() != $_SERVER["REQUEST_METHOD"]) {
            require $this->Config->get('PATH_VIEW') . '403.php';
            return;
        }

        if (file_exists($this->Config->get('PATH_CONTROLLER') . $this->controllerName . '.php')) {
            $this->instantiateController();
            if (method_exists($this->controller, $this->method)) {
                if (!empty($this->params)) {
                    call_user_func_array(array($this->controller, $this->method), $this->params);
                } else {
                    $this->controller->{$this->method}();
                }
            } else {
                require $this->Config->get('PATH_VIEW') . '404.php';
            }
        } else {
            require $this->Config->get('PATH_VIEW') . '404.php';
        }
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            if ($this->route->exists($_GET['url'])) {
                $this->controllerName = $this->route->getController();
                $this->method = $this->route->getMethod();
                $this->params = $this->route->getParams();
            } else {
                require $this->Config->get('PATH_VIEW') . '404.php';
            }
        }
    }

    private function instantiateController()
    {
        require $this->Config->get('PATH_CONTROLLER') . $this->controllerName . '.php';
        $class = 'Brime\Controllers\\' . $this->controllerName;
        $this->controller = new $class($this->model, $this->helper);
    }
}