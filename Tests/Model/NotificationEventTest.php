<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Sydney-o9 <https://github.com/Sydney-o9/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Tests\Model;

use Oz\NotificationBundle\Model\Notification;

class NotificationKeyTest extends \PHPUnit_Framework_TestCase
{

    public function testNotification()
    {
        $notification = $this->getNotification();
        $this->assertNotNull($notification->getCreatedAt());
        $this->assertInstanceOf('datetime', $notification->getCreatedAt());

    }

    /**
     * @return User
     */
    protected function getActor()
    {
        return $this->getMockBuilder('Symfony\Component\Security\Core\User\UserInterface')
            ->getMock();
    }

    /**
     * @return NotificationKey
     */
    protected function getNotificationKey()
    {
        return $this->getMockBuilder('Oz\NotificationBundle\Model\NotificationKey')
            ->getMockForAbstractClass();
    }
}
