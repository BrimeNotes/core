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

return [
    'index' => ['Index#index', 'get'],
    'index/hello/{var}' => ['Index#hello', 'get'],

    'user/register' => ['Admin#registerUser', 'post'],
    'user/login' => ['Users#login', 'post'],
    'user/changepassword' => ['Users#changePassword', 'post'],

    'user/password/forgot' => ['Lost#forgotPassword', 'post'],
    'user/password/change/{token}/{userid}' => ['Lost#changePassword', 'get'],
    'user/password/set' => ['Lost#setPassword', 'post'],

    'notes/add' => ['Notes#add', 'post'],
    'notes/{userid}/{noteid}' => ['Notes#get', 'get'],

];