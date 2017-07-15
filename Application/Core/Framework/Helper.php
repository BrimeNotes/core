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

namespace Brime\Core\Framework;

class Helper
{
    private static $helpers;
    private $list = [
        'Config',
        'DatabaseFactory',
        'Http',
        'Redirect',
        'Request'
    ];
    private $namespace = 'Brime\Core\Helpers\\';

    public function __construct()
    {
        foreach ($this->list as $helper) {
            $helper = $this->namespace . $helper;
            self::$helpers[$helper] = new $helper;
        }
        /*echo '<pre>';
        print_r(self::$helpers);
        print_r($this->get('xyz'));
        die();*/
    }

    public function get($helper)
    {
        if (!in_array($helper, $this->list)) {
            return false;
        }

        $helper = $this->namespace . $helper;
        if (!self::$helpers[$helper]) {
            self::$helpers[$helper] = new $helper;
        }
        return self::$helpers[$helper];
    }
}