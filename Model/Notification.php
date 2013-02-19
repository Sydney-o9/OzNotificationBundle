<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Notification implements NotificationInterface
{

    /**
     * @var integer $id
     */
    protected $id;

    /**
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

    /**
     * The message sent to the user.
     *
     * @var string
     */
    protected $message;

    /**
     *
     * @var string
     */
    protected $subject;



    protected $recipientName;


    protected $recipientData;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    abstract public function getType();


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setEvent(NotificationEventInterface $event)
    {
        $this->event = $event;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setRecipientData($recipientData)
    {
        $this->recipientData = $recipientData;
    }

    public function getRecipientData()
    {
        return $this->recipientData;
    }

    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
    }

    public function getRecipientName()
    {
        return $this->recipientName;
    }

    public function getSentAt()
    {
        return $this->sentAt;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function markSent()
    {
        $this->sentAt = new \DateTime();
    }


}