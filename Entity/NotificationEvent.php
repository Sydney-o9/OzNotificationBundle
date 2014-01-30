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

namespace Oz\NotificationBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Oz\NotificationBundle\Model\NotificationEventInterface;
use Oz\NotificationBundle\Model\NotificationKeyInterface;

/**
 * A notification event, identified by a notification key (e.g 'newsletter.of.the.week')
 * is triggered by a user (actor) taken on an object managed by doctrine ORM (subject)
 */
abstract class NotificationEvent implements NotificationEventInterface
{

    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var NotificationKeyInterface $notificationKey
     */
    protected $notificationKey;

    /**
     * A temporary subject object variable
     *
     * Used by Doctrine ORM listener to convert it into
     * a FullyQualifiedClassName/identifiers.
     *
     * @var mixed $subject
     */
    protected $subject;

    /**
     * An array of identifiers used to identify the subject object.
     *
     * @var array $subjectIdentifiers
     */
    protected $subjectIdentifiers;

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
     * @param UserInterface $actor
     */
    public function __construct(NotificationKeyInterface $notificationKey, $subject, UserInterface $actor = null)
    {
        $this->createdAt       = new \DateTime;
        $this->setNotificationKey($notificationKey);
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
     * Sets the actor for the event.
     *
     * @param UserInterface $actor
     */
    protected function setActor(UserInterface $actor = null)
    {
        $this->actor = $actor;
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
    public function setNotificationKey(NotificationKeyInterface $notificationKey)
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

    /**
     *
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Returns the subject of the event.
     *
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubjectClass()
    {
        return $this->notificationKey->getSubjectClass();
    }

    /**
     * @param array $subjectIdentifiers
     */
    public function setSubjectIdentifiers(array $subjectIdentifiers = null)
    {
        $this->subjectIdentifiers = $subjectIdentifiers;
    }

    /**
     * @return array
     */
    public function getSubjectIdentifiers()
    {
        return $this->subjectIdentifiers;
    }
}
