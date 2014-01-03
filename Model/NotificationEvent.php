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
use DateTime;

/**
 * A notification event, identified by a notification key (e.g 'newsletter.of.the.week')
 * is triggered by a user (actor) about a particular action (verb) taken on
 * an object managed by doctrine ORM (subject)
 */
abstract class NotificationEvent implements NotificationEventInterface
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $notificationKey
     */
    protected $notificationKey;

    /**
     * @var mixed $subject
     */
    protected $subject;

    /**
     * @var string $verb
     */
    protected $verb;

    /**
    * @var UserInterface $actor
    */
    protected $actor;

    /**
     * @var \DateTime $createdAt
     */
    protected $createdAt;

    /**
     * @param NotificationKey $notificationKey
     * @param mixed $subject
     * @param string $verb
     * @param UserInterface $actor
     * @param \DateTime $createdAt
     */
    public function __construct($notificationKey, $subject, $verb, UserInterface $actor = null, DateTime $createdAt = null)
    {
        $this->notificationKey = $notificationKey;
        $this->verb            = $verb;
        $this->createdAt       = $createdAt ?: new DateTime;
        $this->setActor($actor);
        $this->setSubject($subject);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the user that caused the event.
     *
     * @return UserInterface
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
    * Sets the actor of this event.
    *
    * @param UserInterface $actor
    */
    abstract protected function setActor(UserInterface $actor = null);

    /**
     * Returns the subject of the event.
     *
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
    * Sets the subject of this event.
    *
    * @param mixed $subject
    */
    abstract protected function setSubject($subject);


    /**
     * Returns the verb describing the event.
     *
     * @return string
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * Returns when the event occurred.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $notificationKey
     */
    public function setNotificationKey($notificationKey)
    {
        $this->notificationKey = $notificationKey;
    }

    /**
     * Returns the event key.
     *
     * @return NotificationKey
     */
    public function getNotificationKey()
    {
        return $this->notificationKey;
    }

}
