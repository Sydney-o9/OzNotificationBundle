<?php

namespace Oz\NotificationBundle\Entity;

interface SMSNotificationInterface
{

    /**
     * @param string $recipientName
     */
    public function setRecipientName($recipientName);

    /**
     * @return string
     */
    public function getRecipientName();

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber);

    /**
     * @return string
     */
    public function getPhoneNumber();

    /**
     * @param string $message
     */
    public function setMessage($message);

    /**
     * Returns the message body of the notification.
     * @return string
     */
    public function getMessage();

    public function getType();

}
