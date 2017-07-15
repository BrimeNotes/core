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

namespace Tests\Controllers;

use Brime\Controllers\UsersController;
use Brime\Core\Framework\Helper;
use Brime\Core\Helpers\Request;
use PHPUnit\Framework\TestCase;
use Brime\Core\Helpers\php;


class UsersControllerTest extends TestCase
{
    /**
     * @var \Brime\Core\Helpers\Request | \PHPUnit_Framework_MockObject_MockObject
     */
    private $Request;
    /**
     * @var UsersController | \PHPUnit_Framework_MockObject_MockObject
     */
    private $usersController;
    /**
     * @var \Brime\Models\User | \PHPUnit_Framework_MockObject_MockObject
     */
    private $user;
    /**
     * @var \Brime\Models\UserManager | \PHPUnit_Framework_MockObject_MockObject
     */
    private $userManager;

    /**
     * @var \Brime\Core\Framework\Helper | \PHPUnit_Framework_MockObject_MockObject
     */
    private $helper;

    /**
     * @var \Brime\Core\Framework\Model | \PHPUnit_Framework_MockObject_MockObject
     */
    private $model;

    private $View;

    public function setUp() {
        $this->helper = $this->createMock('\\Brime\\Core\\Framework\\Helper');
        $this->model = $this->createMock('\\Brime\\Core\\Framework\\Model');

        $this->Request = $this->createMock('\\Brime\\Core\\Helpers\\Request');

        $this->user = $this->createMock('Brime\\Models\\User');
        $this->userManager = $this->createMock('Brime\\Models\\UserManager');

        $this->helper->expects($this->once())
            ->method('get')
            ->with('Request')
            ->will($this->returnValue($this->Request));

        $this->model->expects($this->any())
            ->method('get')
            ->withConsecutive(['User'], ['UserManager'])
            ->willReturnOnConsecutiveCalls($this->user, $this->userManager);

        $this->usersController = new UsersController(
            $this->model,
            $this->helper
        );
        parent::setUp();
    }

    public function testLoginWithInvalidCredentials() {
        $username = 'legalusername';
        $password = 'incorrectpassword';

        $this->Request
            ->expects($this->any())
            ->method('post')
            ->withConsecutive(['uid', false], ['password', false])
            ->willReturnOnConsecutiveCalls($username, $password);

        $this->userManager
            ->expects($this->once())
            ->method('userExists')
            ->with($username)
            ->willReturn(false);


        $this->assertEquals(false, $this->usersController->login());
    }
}