<?php

namespace Oz\NotificationBundle\Model;

/**
 * iOSPushNotification interface
 */
interface iOSPushNotificationInterface
{
    /**
     * @param string $deviceToken
     */
    public function setDeviceToken($deviceToken);

    /**
     * @return string
     */
    public function getDeviceToken();

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
