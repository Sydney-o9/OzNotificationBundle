<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney-o9 <https://github.com/Sydney-o9/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Abstract Notification model
 *
 */
abstract class Notification implements NotificationInterface
{

    /**
     * @var integer $id
     */
    protected $id;

    /**
     * Owner of the notification
     *
     * @var \Symfony\Component\Security\Core\User\UserInterface
     */
    protected $user;

    /**
     * The event the notification has been triggered by.
     *
     * @var NotificationEvent
     */
    protected $event;

    /**
     * What the notification is about.
     *
     * @var string
     */
    protected $subject;

    /**
     * When the notification was created.
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * When the notification was sent.
     *
     * @var \DateTime
     */
    protected $sentAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * The type of notification
     *
     * @return string
     */
    abstract public function getType();

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param NotificationEventInterface $event
     */
    public function setEvent(NotificationEventInterface $event)
    {
        $this->event = $event;
    }

    /**
     * @return NotificationEvent|NotificationEventInterface
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return \DateTime
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * Mark the notification as sent.
     */
    public function markSent()
    {
        $this->sentAt = new \DateTime();
    }

}
