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

class NotificationTest extends \PHPUnit_Framework_TestCase
{

    public function testNotification()
    {
        $notification = $this->getNotification();
        $this->assertNotNull($notification->getCreatedAt());
        $this->assertInstanceOf('datetime', $notification->getCreatedAt());

    }

    public function testUser()
    {
        $notification = $this->getNotification();
        $this->assertNull($notification->getUser());

        $user = $this->getUser();
        $notification->setUser($user);
        $this->assertEquals($user, $notification->getUser());

    }

    public function testEvent()
    {
        $notification = $this->getNotification();
        $this->assertNull($notification->getEvent());

        $notificationEvent = $this->getNotificationEvent();
        $notification->setEvent($notificationEvent);
        $this->assertEquals($notificationEvent, $notification->getEvent());

    }

    public function testSubject()
    {
        $notification = $this->getNotification();
        $this->assertNull($notification->getSubject());

        $notification->setSubject('A notification subject');
        $this->assertEquals('A notification subject', $notification->getSubject());

    }

    public function testMarkSent()
    {
        $notification = $this->getNotification();
        $dateTimeNow = new \DateTime();
        $notification->markSent();

        $this->assertInstanceOf('datetime', $notification->getSentAt());
        /** Compare if the two datetime are equal with a delta of 1 second */
        $deltaTimeInSeconds = 1;
        $this->assertEquals(
            $notification->getSentAt()->getTimestamp(),
            $dateTimeNow->getTimestamp(),
            '',
            $deltaTimeInSeconds
        );
    }

    /**
     * @return Notification
     */
    protected function getNotification()
    {
        return $this->getMockForAbstractClass('Oz\NotificationBundle\Model\Notification');
    }

    /**
     * @return User
     */
    protected function getUser()
    {
        return $this->getMockBuilder('Symfony\Component\Security\Core\User\UserInterface')
            ->getMock();
    }

    /**
     * @return NotificationEvent
     */
    protected function getNotificationEvent()
    {
        return $this->getMockBuilder('Oz\NotificationBundle\Model\NotificationEvent')
            ->disableOriginalConstructor(true)
            ->getMockForAbstractClass();
    }
}
