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

namespace Brime\Controllers;

use Brime\Core\Controller;
use Brime\Core\Framework\Helper;
use Brime\Core\Framework\Model;
use Brime\Core\Helpers\Http;

class IndexController extends Controller
{
    public function __construct(Model $model, Helper $helper)
    {
        parent::__construct($model, $helper);
    }

    public function index()
    {
        $this->View->renderJSON(array(
            "app_name" => "Brime",
            "authors" => [
                1 => [
                    "name" => "Ujjwal Bhardwaj"
                ]
            ]
        ), Http::STATUS_OK);
    }

    public function hello($world)
    {
        $this->View->renderJSON(array(
            "app_name" => "Brime",
            "authors" => [
                1 => [
                    "name" => $world
                ]
            ]
        ), Http::STATUS_OK);
    }
}