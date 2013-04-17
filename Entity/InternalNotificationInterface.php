<?php

namespace merk\NotificationBundle\Entity;

interface InternalNotificationInterface
{

    /**
     * Set if the notification has been read by the owner
     * @param Bool $isRead
     * @return void
     */
    public function setIsRead($isRead);

    /**
     * Returns if the notification has been read by the owner
     * @return Bool
     */
    public function isRead();


    /**
     * Returns the message body of the notification.
     * @return string
     */
    public function getMessage();

    /**
     * Sets the message
     * @param string $message
     */
    public function setMessage($message);


    public function getType();

}
