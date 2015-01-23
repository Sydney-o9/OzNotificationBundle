<?php

namespace Oz\NotificationBundle\NotificationFactory;

use Oz\NotificationBundle\Exception\NoSubjectFoundException;

class iOSPushNotificationFactory extends NotificationFactory
{
    /**
     * Device Manager
     *
     * @var \Oz\NotificationBundle\ModelManager\DeviceManagerInterface
     */
    protected $deviceManager;


    /**
     * @param \Oz\NotificationBundle\ModelManager\DeviceManagerInterface $deviceManager
     */
    public function setDeviceManager($deviceManager)
    {
        $this->deviceManager = $deviceManager;
    }

    /**
     * @return \Oz\NotificationBundle\ModelManager\DeviceManagerInterface
     */
    public function getDeviceManager()
    {
        return $this->deviceManager;
    }

    /**
     * {@inheritDoc}
     */
    public function createFromUser($event, $user){

        $notifications = array();

        $mobileDevices = $this->deviceManager
            ->findMobileDevicesForUser($user);

        if ( empty($mobileDevices)) {
            return;
        }

        foreach ($mobileDevices as $mobileDevice)
        {
            $notification = $this->build($this->class);
            $notification->setEvent($event);
            $notification->setUser($user);

            $notification->setDeviceToken($mobileDevice->getDeviceToken());

            $template = $this->renderer->render($notification);
            $notification->setSubject($template['subject']);
            $notification->setMessage($template['message']);

            array_push($notifications, $notification);
        }

        return $notifications;
    }

}
