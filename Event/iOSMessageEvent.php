<?php

namespace Oz\NotificationBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use RMS\PushNotificationsBundle\Message\iOSMessage;
use Oz\NotificationBundle\Model\iOSPushNotificationInterface;

class iOSMessageEvent extends Event
{

    /**
     * @var Oz\NotificationBundle\Model\iOSPushNotificationInterface
     */
    private $iOSPushNotification;

    /**
     * @var RMS\PushNotificationsBundle\Message\iOSMessage
     */
    private $iOSMessage;

    /**
     * @param RMS\PushNotificationsBundle\Message\iOSMessage
     * @param Oz\NotificationBundle\Model\iOSPushNotificationInterface
     */
    public function __construct(iOSPushNotificationInterface $iOSPushNotification, iOSMessage $iOSMessage)
    {
        $this->iOSPushNotification = $iOSPushNotification;
        $this->iOSMessage = $iOSMessage;
    }

    /**
     * @return iOSPushNotificationInterface
     */
    public function getIOSPushNotification()
    {
        return $this->iOSPushNotification;
    }

    /**
     * @return iOSMessage
     */
    public function getIOSMessage()
    {
        return $this->iOSMessage;
    }

    public function setIOSMessage(iOSMessage $iOSMessage)
    {
        $this->iOSMessage = $iOSMessage;
    }

}
